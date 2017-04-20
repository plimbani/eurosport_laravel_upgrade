<template>
    <div class="tab-content">
        <div class="card">
            <div class="card-block">
                <h6 class=""><strong>{{$lang.pitch_capacity}}</strong></h6>
                <div class="row">
                    <div class="col-md-1 pitch-capaciry"    v-for="pitch in pitches">
                        <p><strong>{{pitch.pitch_number}}</strong></p>
                        <img src="/assets/img/pitch.png">
                        <p>
                            <span><a href="javascript:void(0)" @click="editPitch(pitch.id)">Edit</a></span>
                            <span><a href="javascript:void(0)" data-confirm-msg="Are you sure you would like to delete this pitch record?" data- data-toggle="modal" data-target="#delete_modal" @click="deletePitch(pitch.id)">Remove</a></span>
                        </p>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="button" class="btn btn-primary" @click="addPitch()"><i class="fa fa-plus" ></i>{{$lang.pitch_add}}</button>
                </div>
                <addPitchDetail v-if="pitchId==''" ></addPitchDetail>
                <editPitchDetail v-if="pitchId!=''" ></editPitchDetail>
                <delete-modal :deleteConfirmMsg="deleteConfirmMsg" @confirmed="deleteConfirmed()"></delete-modal>
                
                <div class="row mt-4">
                    <div class="result col-md-12">
                        <div class="dashbox">
                            <p>
                                <label class="col-md-3"><strong>{{$lang.pitch_totaL_time}}</strong></label>
                                <label class="col-md-5">{{((tournamentTime - (tournamentTime % 60)) / 60)+ ' hrs ' + (tournamentTime % 60) + ' mins '}}</label>
                            </p>
                            <p>
                                <label class="col-md-3"><strong>{{$lang.pitch_total_capacity}}</strong></label>
                                <label class="col-md-5">{{((pitchCapacity - (pitchCapacity % 60)) / 60)+ ' hrs ' + (pitchCapacity % 60) + ' mins '}}</label>
                            </p>
                            <p>
                                <label class="col-md-3"><strong>{{$lang.pitch_balance}}</strong></label>
                                <label :class="[pitchAvailableBalance[0]<0? 'red': 'text-success','col-md-5' ]">{{pitchAvailableBalance[0]+ ' hrs ' + pitchAvailableBalance[1] + ' mins '}}</label>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script type="text/babel">
import editPitchDetail from '../../../views/admin/eurosport/editPitchDetail.vue'
import addPitchDetail from '../../../views/admin/eurosport/addPitchDetail.vue'
import DeleteModal from '../../../components/DeleteModal.vue'
    export default {
        data() {
            return {
                'tournamentId': this.$store.state.Tournament.tournamentId,
                'tournamentDays': this.$store.state.Tournament.tournamentDay,
                'stage_date':[],
                'tournamentStartDate': this.$store.state.Tournament.tournamentStartDate,
                'tournamentEndDate': this.$store.state.Tournament.tournamentEndDate,
                'removeStage': [],
                'disableDate': [],
                'stage_capacity' : [],
                'availableDate': [],
                'deleteConfirmMsg': 'All schedules with this pitch will be removerd. Are you sure you would like to delete this pitch record?',
                'deletePitchId': ''

                }
        },
        components: {
            editPitchDetail,addPitchDetail,DeleteModal
        },
        computed: {
            tournamentTime: function() {
                return this.$store.state.Tournament.currentTotalTime
            },
            pitchId: function(){
                return _.cloneDeep(this.$store.getters.curPitchId)
            },
            pitches: function() {
                return this.$store.state.Pitch.pitches
            },
            pitchData: function() {
                return this.$store.state.Pitch.pitchData
            },
            pitchCapacity: function() {
                return this.$store.state.Pitch.pitchCapacity
            },
            pitchAvailableBalance : function() {
                let pitchavailableBalance = []
                let tournamentAvailableTime =  this.tournamentTime
                let pitchCapacityTime =this.pitchCapacity
                let availableTime = pitchCapacityTime - tournamentAvailableTime
                var minutes = availableTime % 60;
                var hours = (availableTime - minutes) / 60;
                if(minutes<0){
                    minutes = parseInt(0- minutes)
                }
                pitchavailableBalance.push (hours,minutes)
                return pitchavailableBalance
            }
        },
        mounted(){
            this.getAllPitches()
            let tournamentId = this.$store.state.Tournament.tournamentId
            if(tournamentId == null || tournamentId == '' || tournamentId == undefined) {
              toastr['error']('Please Select Tournament', 'Error');
              this.$router.push({name: 'welcome'});
            } else {
                // First Set Menu and ActiveTab
              let currentNavigationData = {activeTab:'pitch_capacity', currentPage: 'Pitch Capacity'}
                this.$store.dispatch('setActiveTab', currentNavigationData)
            }
            Plugin.initPlugins(['Select2','BootstrapSelect','TimePickers','MultiSelect','DatePicker','SwitchToggles', 'addstage'])
            // this.stage_capacity1 ='5.30';
            // this.stage_capacity1 ='5.30';
            // this.stage_capacity1 ='5.30';
            // this.$store.dispatch('SetPitches',this.tournamentId);
            let capacity={}
            let sDate = []
            var startDate = new Date(this.tournamentStartDate)
            var obj ={}
            $('.ls-datepicker').datepicker('setStartDate', this.tournamentStartDate);
            $('.ls-datepicker').datepicker('setEndDate', this.tournamentEndDate);
            for(let i=1;i<=this.tournamentDays;i++){
                capacity['day'+i]= '0.00'
                $('.datestage'+i).datepicker('setDate', startDate)
                this.disableDate.push( $('.datestage'+i).val());
                startDate.setDate(new Date(this.tournamentStartDate).getDate() + i)
                obj['date'+i] = $('.datestage'+i).val();
            }
            let disableDate = this.disableDate;
            this.stage_date.push(obj)
            $('.ls-datepicker').datepicker('setDatesDisabled', this.disableDate);
            this.stage_capacity.push(capacity)
            $('#frmPitchAvailable').on("change",'.ls-timepicker',function(){
               // this.stageCapacityCalc(1)
               // console.log($(this)[0].class)
               let stage = $(this)[0].id;
               // console.log(stage_id)
               stage = stage.replace('stage_start_time','')
               stage = stage.replace('stage_break_start','')
               stage = stage.replace('stage_continue_time','')
               stage = stage.replace('stage_end_time','')
               if( $('#stage_start_time'+stage).val() == '' || $('#stage_end_time'+stage).val() == '' || $('#stage_break_start'+stage).val() == '' || $('#stage_continue_time'+stage).val() == ''  ) {
                $('#stage_capacity_span'+stage).text('0.00 hrs');
                $('#stage_capacity'+stage).val('0.00');
               }else {
                 var stageTimeStart = new Date($('#stage_start_date'+stage).val() + " "+ $('#stage_start_time'+stage).val());
                var stageTimeEnd = new Date($('#stage_start_date'+stage).val() + " " + $('#stage_end_time'+stage).val());
                var stageBreakStart = new Date($('#stage_start_date'+stage).val() + " " + $('#stage_break_start'+stage).val());
                var stageBreakEnd = new Date($('#stage_start_date'+stage).val() + " " + $('#stage_continue_time'+stage).val());
                    var diff1 = (stageBreakStart - stageTimeStart) / 60000; //dividing by seconds and milliseconds
                    var diff2 = (stageTimeEnd - stageBreakEnd) / 60000; //dividing by seconds and milliseconds
                    var diff = diff1 + diff2
                    var minutes = diff % 60;
                    var hours = (diff - minutes) / 60;
                    var time_val = hours+ '.' +minutes
                    var time = hours+ ':' +minutes +' hrs'
                $('#stage_capacity'+stage).val(time_val);
                $('#stage_capacity_min'+stage).val(diff);
                $('#stage_capacity_span'+stage).text(time);
               }
            })
            // $(".ls-datepicker").on("change", function(e) {
            //         console.log('msg')
            // });
            var that = this
            $('.ls-datepicker').datepicker().on('changeDate',function(){
                var stage = this.id
               stage = stage.replace("stage_start_date", "");
               if (stage.search('stage_end_date') != -1 || stage.search('stage_continue_date') != -1 ) {
               return false
               }
               if($.inArray( parseInt(stage), that.removeStage ) !== -1  ){
                    return false
                }else{
                var index =  that.disableDate.indexOf($('#stage_end_date'+stage).val());
                if (index > -1) {
                    // let stage = disableDate[index];
                    that.disableDate.splice(index, 1);
                    that.availableDate.push($('#stage_end_date'+stage).val())
                    that.availableDate.splice(that.availableDate.indexOf($('#'+this.id).val()),1)
                    // that.disableDate = disableDate
                    $('.ls-datepicker').datepicker('setDatesDisabled', that.disableDate);
                    // disableDate
                }
                that.disableDate.push( $('#'+this.id).val());
                $('.datestage'+stage).val($('#'+this.id).val())
                }
            });
            // $('.ls-datepicker').datepicker('setDatesDisabled', this.disableDate);
            // $('.sdate').datepicker('setDatesDisabled', this.disableDate);
            let this3 = this 
            $("#exampleModal").on('hidden.bs.modal', function () {
                $('#frmPitchDetail')[0].reset()
                $('#frmPitchAvailable')[0].reset()
                this3.getAllPitches()
            });
            
        },
        methods: {

            getAllPitches() {
                this.$store.dispatch('SetPitches',this.tournamentId);
                this.$store.dispatch('SetVenues',this.tournamentId);
            },
            deletePitch (pitchId) {
                this.deletePitchId = pitchId
            },
            stageRemove (day) {
                this.removeStage.push(day)
                var index = this.disableDate.indexOf($('#stage_start_date'+day).val());
                if (index > -1) {
                    this.disableDate.splice(index, 1);
                    this.availableDate.push($('#stage_start_date'+day).val())
                    $('.ls-datepicker').datepicker('setDatesDisabled', this.disableDate);
                    $('.datestage'+day).datepicker('clearDates')
                }
            },
            displayDay (day) {
                if($.inArray( day,this.removeStage) != -1 ) {
                    return false
                }else {
                    return true
                }
            },
            setStageCapacity(stage) {
                let stage_start_date = $('#stage_start_date'+stage).val();
                let stage_start_time = $('#stage_start_time'+stage).val();
                let stage_end_date = $('#stage_end_date'+stage).val();
                let stage_end_time = $('#stage_end_time'+stage).val();
                var timeStart = new Date(stage_start_date + stage_start_time);
                var timeEnd = new Date(stage_end_date + stage_end_time);
                if(timeStart && timeEnd) {
                    var diff = (timeEnd - timeStart) / 60000; //dividing by seconds and milliseconds
                    var minutes = diff % 60;
                    var hours = (diff - minutes) / 60;
                   this.stage_capacity['day'+stage] = hours+ ':' +minutes
                }
            },

            setDatepicker(tStartDate,tEndDate,disableDate,availableDate,stage) {
                    // let availableDate = this.availableDate
                    let that =this
                   if(availableDate.length > 0) {
                        let availDate = availableDate[0];
                        that.disableDate.push(availableDate[0])
                        var index = availableDate.indexOf(availableDate[0]);
                        availableDate.splice(index, 1);
                        that.availableDate = availableDate
                         setTimeout(function() {
                        $('.datestage'+stage).datepicker();
                        $('.datestage'+stage).datepicker('setStartDate', tStartDate)
                        $('.datestage'+stage).datepicker('setEndDate', tEndDate)
                        $('.datestage'+stage).datepicker('setEndDate', tEndDate)
                        $('.datestage'+stage).datepicker('setDatesDisabled', disableDate);
                         $('.datestage'+stage).datepicker('setDate', availDate)
                        $('#stage_start_time'+stage).timepicker()
                        $('#stage_break_start'+stage).timepicker()
                        $('#stage_continue_time'+stage).timepicker()
                        $('#stage_end_time'+stage).timepicker()
                        },1000)
                    }
            },
            addStage () {
                let removeStageArr = this.removeStage
                let stageno = Math.min.apply( Math, removeStageArr)
                var index = removeStageArr.indexOf(Math.min.apply( Math, removeStageArr ));
                if (index > -1) {
                    let stage = removeStageArr[index];
                    removeStageArr.splice(index, 1);
                    this.removeStage = removeStageArr
                     var that = this
                     that.setDatepicker(that.tournamentStartDate,that.tournamentEndDate,that.disableDate,that.availableDate,stage);
                }
            },

            addPitch() {
                this.$store.dispatch('SetPitchId',0);
                 
                setTimeout(function(){
                    $('#addPitchModal').modal('show')
                },500)
            },
            editPitch(pitchId) {
                this.$store.dispatch('SetPitchId',pitchId);
                // this.pitchId = pitchId
                    // this1.$store.dispatch('PitchData',pitchId)
                let this1 = this
                setTimeout(function(){
                    this1.$store.dispatch('PitchData',pitchId)
                },1000)
                
            },
            removePitch(pitchId) {
                // this.$store.dispatch('removePitch',pitchId)
                // toastr['warning']('All schedules with this pitch will be removerd', 'Warning');
                return axios.post('/api/pitch/delete/'+pitchId).then(response =>  {
                    this.getAllPitches()
                   $("#delete_modal").modal("hide");
                    toastr.success('Pitch Successfully removed', 'Delete User', {timeOut: 5000});
                    // toastr['success']('Pitch Successfully removed', 'Success');
                    this.getAllPitches()
                    }).catch(error => {
                    if (error.response.status == 401) {
                        toastr['error']('Invalid Credentials', 'Error');
                    } else {
                        // Something happened in setting up the request that triggered an Error
                        console.log('Error', error.message);
                    }
                });
            },
            deleteConfirmed() {
                this.removePitch(this.deletePitchId)
                // axios.post(this.deleteAction).then((response) => {
                //     $("#delete_modal").modal("hide");
                //     toastr.success('User has been deleted succesfully.', 'Delete User', {timeOut: 5000});
                //     this.updateUserList();
                // });
            }
        }
    }
</script>
