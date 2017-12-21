<template>
    <div class="tab-content">
        <div class="card">
            <div class="card-block">
                <div class="row">
                  <div class="col-3 align-self-center">
                      <h6 class="mb-0"><strong>{{$lang.pitch_capacity}}</strong></h6>
                  </div>
                  <div class="col-9 align-self-center">
                    <button type="button" class="btn btn-primary pull-right" @click="addPitch()"><small><i class="jv-icon jv-plus"></i></small>&nbsp;{{$lang.pitch_add}}</button>
                  </div>
                </div>

                <addPitchDetail  v-if="pitchId=='' && dispPitch==true" ></addPitchDetail>
                <editPitchDetail v-if="pitchId!='' && dispPitch==true" > </editPitchDetail>
                <delete-modal :deleteConfirmMsg="deleteConfirmMsg" @confirmed="deleteConfirmed()"></delete-modal>

                <div class="row mt-4">
                    <div class="result col-md-12">
                         <table class="table table-hover table-bordered mb-0 pitch_capacity_table" v-if="pitches">
                            <thead>
                                <tr>
                                    <th class="text-center">{{$lang.pitch_modal_details_name}}</th>
                                    <th class="text-center">{{$lang.pitch_modal_details_size}}</th>
                                    <th class="text-center">{{$lang.pitch_modal_details_type}}</th>
                                    <th class="text-center">{{$lang.pitch_modal_availability_stage}}</th>
                                    <th class="text-center">{{$lang.pitch_modal_action}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="pitch in pitches">
                                    <td class="text-center">{{pitch.pitch_number}}</td>
                                    <td class="text-center">{{pitch.size}}</td>
                                    <td class="text-center" style="text-transform: capitalize;">{{pitch.type}}</td>
                                    <td>
                                        <p v-for="pitchStage in pitch.pitch_av_text">
                                        {{pitchStage}}</p>
                                        <!--<p>Day 2: 10am-1pm, 3pm-5pm</p>
                                        <p>Day 3: 10am-2pm</p>-->
                                    </td>
                                    <td class="text-center">
                                        <span class="align-middle">
                                            <a class="text-primary" href="javascript:void(0)" @click="editPitch(pitch.id)"><i class="jv-icon jv-edit"></i></a>

                                        </span>
                                        <span class="align-middle">
                                             <a href="javascript:void(0)" data-confirm-msg="Are you sure you would like to delete this pitch record?" data- data-toggle="modal" data-target="#delete_modal" @click="deletePitch(pitch.id)"><i class="jv-icon jv-dustbin"></i></a>
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-else>
                            <p class="text-muted">No pitch found.</p>
                        </div>
                    </div>
                </div>
                <div class="row my-3">
                  <div class="col-3 align-self-center">
                      <h6 class="mb-0 text-muted"><strong>{{$lang.pitch_summary}}</strong></h6>
                  </div>
                </div>
                <div class="row">
                    <div class="result col-md-12">
                        <table class="table table-hover table-bordered mb-0 pitch_size_summary" v-if="pitchSizeWiseSummaryArray">
                            <thead>
                                <tr>
                                    <th class="text-center">{{$lang.pitch_size}}</th>
                                    <th class="text-center">{{$lang.pitch_available_time}}</th>
                                    <th class="text-center">{{$lang.pitch_totaL_time}}</th>
                                    <th class="text-center">{{$lang.pitch_balance}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(pitchSizeDetail, pitchSize) in pitchSizeWiseSummaryArray">
                                    <td class="text-center">{{ pitchSize }}</td>
                                    <td class="text-center">{{ pitchSizeDetail.availableTime }}</td>
                                    <td class="text-center">{{ pitchSizeDetail.timeRequired }}</td>
                                    <td class="text-center" :class="[pitchSizeDetail.balanceSign == '-' ? 'red' : 'text-success']">{{ pitchSizeDetail.balance }}</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><strong>{{ $lang.totals }}</strong></td>
                                    <td class="text-center">{{ pitchSizeWiseSummaryTotal.totalAvailableTime }}</td>
                                    <td class="text-center">{{ pitchSizeWiseSummaryTotal.totalTimeRequired }}</td>
                                    <td class="text-center" :class="[pitchSizeWiseSummaryTotal.totalBalanceSign == '-' ? 'red' : 'text-success']">{{ pitchSizeWiseSummaryTotal.totalBalance }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row my-3">
                  <div class="col-3 align-self-center">
                      <h6 class="mb-0 text-muted"><strong>{{$lang.pitch_totals}}</strong></h6>
                  </div>
                </div>
                <div class="row">
                    <div class="result col-md-12">
                        <div class="dashbox mb-2">
                            <p class="row">
                                <label class="col-md-3"><strong>{{$lang.pitch_totaL_time}}</strong></label>
                                <label class="col-md-5">{{((tournamentTime - (tournamentTime % 60)) / 60)+ ' hrs ' + (tournamentTime % 60) + ' mins '}}</label>
                            </p>
                            <p class="row">
                                <label class="col-md-3"><strong>{{$lang.pitch_total_capacity}}</strong></label>
                                <label class="col-md-5">{{((pitchCapacity - (pitchCapacity % 60)) / 60)+ ' hrs ' + (pitchCapacity % 60) + ' mins '}}</label>
                            </p>
                            <p class="row mb-0">
                                <label class="col-md-3 m-0"><strong>{{$lang.pitch_balance}}</strong></label>
                                <label :class="[parseInt(pitchCapacity-tournamentTime)<0? 'red': 'text-success','col-md-5 m-0' ]">{{ pitchAvailableBalance[2] + '' +pitchAvailableBalance[0]+ ' hrs ' + pitchAvailableBalance[1] + ' mins '}}</label>
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
import Pitch from '../../../api/pitch'
import Tournament from '../../../api/tournament.js'

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
                'deleteConfirmMsg': 'Are you sure you would like to delete this pitch? All matches on this pitch will be un-scheduled.',
                'deletePitchId': '',
                'dispPitch': false,
                'pitchSizeWiseSummaryData': {
                    'allPitchSizes': [],
                    'totalAvailableTimePitchSizeWise': {},
                    'totalTimeRequiredPitchSizeWise': {},
                },
                'pitchSizeWiseSummaryArray': {},
                'pitchSizeWiseSummaryTotal': {
                    'totalAvailableTime': 0,
                    'totalTimeRequired': 0,
                    'totalBalance': 0,
                    'totalBalanceSign': '+',
                },
            }
        },

        created: function() {
            this.$root.$on('displayPitch', this.displayPitch);
            this.$root.$on('pitchrefresh', this.getAllPitches);
            this.$root.$on('getPitchSizeWiseSummary', this.getPitchSizeWiseSummary);
            this.getPitchSizeWiseSummary();
            this.displayTournamentCompetationList();
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
                if(hours<0){
                    hours = parseInt(0- hours)
                }
                let pitchSign= ''
                if(this.tournamentTime > this.pitchCapacity){
                  pitchSign = '-'
                }
                pitchavailableBalance.push (hours,minutes,pitchSign)
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

        },
        methods: {
            displayPitch(value) {
              this.dispPitch = false
            },
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
                this.dispPitch = true;
                this.$store.dispatch('SetPitchId',0);
                let vm = this
                setTimeout(function(){
                    $('#addPitchModal').modal('show')
                    $("#addPitchModal").on('hidden.bs.modal', function () {
                       vm.getAllPitches()
                       vm.getPitchSizeWiseSummary();
                       vm.$root.$emit('')
                  });
                },1000)

            },
            editPitch(pitchId) {
                this.dispPitch = true;
                this.$store.dispatch('SetPitchId',pitchId);
                // this.pitchId = pitchId
                    // this1.$store.dispatch('PitchData',pitchId)
                let this1 = this
                setTimeout(function(){
                    this1.$store.dispatch('PitchData',pitchId)
                    this1.getAllPitches()
                },1000)

            },
            removePitch(pitchId) {
                let vm = this;
                // this.$store.dispatch('removePitch',pitchId)
                // toastr['warning']('All schedules with this pitch will be removerd', 'Warning');
                return axios.post('/api/pitch/delete/'+pitchId).then(response =>  {
                    this.getAllPitches()
                   $("#delete_modal").modal("hide");
                    toastr.success('Pitch successfully deleted.', 'Delete Pitch', {timeOut: 5000});
                    // toastr['success']('Pitch Successfully removed', 'Success');
                    vm.getAllPitches();
                    vm.getPitchSizeWiseSummary();
                    }).catch(error => {
                    if (error.response.status == 401) {
                        toastr['error']('Invalid Credentials', 'Error');
                    } else {
                        // Something happened in setting up the request that triggered an Error
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
            },
            getPitchSizeWiseSummary() {
                if (!isNaN(this.tournamentId)) {
                    let vm = this;
                    vm.pitchSizeWiseSummaryData = this.defaultPitchSizeWiseSummaryData();
                    vm.pitchSizeWiseSummaryArray = {};
                    vm.pitchSizeWiseSummaryTotal = this.defaultPitchSizeWiseSummaryTotal();

                    Pitch.getPitchSizeWiseSummary(this.tournamentId).then (
                      (response) => {
                        vm.pitchSizeWiseSummaryData = response.data;
                        let allPitchSizes = response.data.allPitchSizes;
                        let totalAvailableTime = 0;
                        let totalTimeRequired = 0;
                        let totalBalance = 0;

                        for(let i=0; i<allPitchSizes.length; i++) {
                            let pitchSize = allPitchSizes[i];
                            let pitchSizeDetail = {};
                            let availableTime = vm.getAvailableTimeOfPitchSize(pitchSize);
                            let timeRequired = vm.getRequiredTimeForPitchSize(pitchSize);
                            let balance = vm.getPitchSizeBalance(pitchSize);

                            totalAvailableTime += availableTime;
                            totalTimeRequired += timeRequired;
                            totalBalance += balance;

                            let minutes = balance % 60;
                            let hours = (balance - minutes) / 60;

                            if(minutes<0){
                                minutes = parseInt(0 - minutes)
                            }
                            if(hours<0){
                                hours = parseInt(0 - hours)
                            }

                            pitchSizeDetail.availableTime = ( ((availableTime - (availableTime % 60)) / 60) + ' hrs ' + (availableTime % 60) + ' mins');
                            pitchSizeDetail.timeRequired = ( ((timeRequired - (timeRequired % 60)) / 60) + ' hrs ' + (timeRequired % 60) + ' mins');
                            pitchSizeDetail.balance = (balance < 0 ? '-' : '') + ( hours + ' hrs ' + minutes + ' mins' );
                            pitchSizeDetail.balanceSign = balance < 0 ? '-' : '+';

                            vm.pitchSizeWiseSummaryArray[pitchSize] = pitchSizeDetail;
                        }

                        let minutes = totalBalance % 60;
                        let hours = (totalBalance - minutes) / 60;

                        if(minutes<0){
                            minutes = parseInt(0 - minutes)
                        }
                        if(hours<0){
                            hours = parseInt(0 - hours)
                        }

                        vm.pitchSizeWiseSummaryTotal.totalAvailableTime = ( ((totalAvailableTime - (totalAvailableTime % 60)) / 60) + ' hrs ' + (totalAvailableTime % 60) + ' mins');
                        vm.pitchSizeWiseSummaryTotal.totalTimeRequired = ( ((totalTimeRequired - (totalTimeRequired % 60)) / 60) + ' hrs ' + (totalTimeRequired % 60) + ' mins');
                        vm.pitchSizeWiseSummaryTotal.totalBalance = (totalBalance < 0 ? '-' : '') + ( hours + ' hrs ' + minutes + ' mins' );
                        vm.pitchSizeWiseSummaryTotal.totalBalanceSign = totalBalance < 0 ? '-' : '+';
                      },
                      (error) => {
                      }
                    )
                } else {
                  this.TournamentId = 0;
                }
            },
            defaultPitchSizeWiseSummaryData() {
                return {
                    'allPitchSizes': [],
                    'totalAvailableTimePitchSizeWise': {},
                    'totalTimeRequiredPitchSizeWise': {},
                }
            },
            defaultPitchSizeWiseSummaryTotal() {
                return {
                    'totalAvailableTime': 0,
                    'totalTimeRequired': 0,
                    'totalBalance': 0,
                    'totalBalanceSign': '+',
                };
            },
            getAvailableTimeOfPitchSize(pitchSize) {
                let totalAvailableTimePitchSizeWise = this.pitchSizeWiseSummaryData.totalAvailableTimePitchSizeWise;
                let timeInMinutes = 0;
                if(totalAvailableTimePitchSizeWise.hasOwnProperty(pitchSize)) {
                    timeInMinutes = parseInt(totalAvailableTimePitchSizeWise[pitchSize]);
                }
                return timeInMinutes;
            },
            getRequiredTimeForPitchSize(pitchSize) {
                let totalTimeRequiredPitchSizeWise = this.pitchSizeWiseSummaryData.totalTimeRequiredPitchSizeWise;
                let timeInMinutes = 0;
                if(totalTimeRequiredPitchSizeWise.hasOwnProperty(pitchSize)) {
                    timeInMinutes = parseInt(totalTimeRequiredPitchSizeWise[pitchSize]);
                }
                return timeInMinutes;
            },
            getPitchSizeBalance(pitchSize) {
                let totalAvailableTimePitchSizeWise = this.pitchSizeWiseSummaryData.totalAvailableTimePitchSizeWise;
                let totalTimeRequiredPitchSizeWise = this.pitchSizeWiseSummaryData.totalTimeRequiredPitchSizeWise;
                let totalAvailableTime = 0;
                let totalTimeRequired = 0;

                if(totalAvailableTimePitchSizeWise.hasOwnProperty(pitchSize)) {
                    totalAvailableTime = parseInt(totalAvailableTimePitchSizeWise[pitchSize]);
                }
                if(totalTimeRequiredPitchSizeWise.hasOwnProperty(pitchSize)) {
                    totalTimeRequired = parseInt(totalTimeRequiredPitchSizeWise[pitchSize]);
                }

                return (totalAvailableTime - totalTimeRequired);
            },
            displayTournamentCompetationList () {
              $("body .js-loader").removeClass('d-none');
                this.TournamentId = parseInt(this.$store.state.Tournament.tournamentId)
                // Only called if valid tournament id is Present
                if (!isNaN(this.TournamentId)) {
                    // here we add data for
                    let TournamentData = {'tournament_id': this.TournamentId}
                    Tournament.getCompetationFormat(TournamentData).then(
                    (response) => {
                      $("body .js-loader").addClass('d-none');
                        let time_sum= 0;
                        response.data.data.reduce(function (a,b) {
                            time_sum += b['total_time']
                        },0);
                        this.$store.dispatch('SetTournamentTotalTime', time_sum);
                    },
                    (error) => {
                    }
                    )
                } else {
                  this.TournamentId = 0;
                }
            },
        }
    }
</script>
