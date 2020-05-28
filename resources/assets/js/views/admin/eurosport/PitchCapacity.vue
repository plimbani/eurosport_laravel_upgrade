<template>
    <div class="tab-content">
        <div class="card">
            <div class="card-block">
                <div class="row align-items-center">
                  <div class="col-md-3 align-self-center">
                      <h6 class="mb-0 fieldset-title"><strong>{{$lang.pitch_capacity}}</strong></h6>
                  </div>

                    <div class="col-md-9">
                        <form class="form-inline justify-content-end pitch-capacity-form">
                            <div class="form-group">
                                <label><strong>Filter by:</strong></label>
                            </div>
                            <div class="form-group">
                                <select class="form-control m-w-130"
                                    v-model="selectedVenue" name="selected_venue" id="selected_venue"
                                    @change="getPitchSearchData()">
                                    <option value="">All venues</option>
                                    <option :value="venuesOption.id"
                                    v-for="venuesOption in venuesOptions">
                                      {{venuesOption.name}}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control"
                               v-on:keyup="getPitchSearchData" v-model="pitchDataSearch"
                               placeholder="Search for a pitch">
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" @click="addPitch()"><small><i class="fas fa-plus"></i></small>&nbsp;{{$lang.pitch_add}}</button>
                            </div>
                        </form>
                    </div>


                    <!-- <div class="col-md-2">
                        <select class="form-control"
                            v-model="selectedVenue" name="selected_venue" id="selected_venue"
                            @change="getPitchSearchData()">
                            <option value="">All venues</option>
                            <option :value="venuesOption.id"
                            v-for="venuesOption in venuesOptions">
                              {{venuesOption.name}}
                            </option>
                        </select>
                    </div> -->
                  <!-- <div class="col-md-2">
                        <input type="text" class="form-control"
                               v-on:keyup="getPitchSearchData" v-model="pitchDataSearch"
                               placeholder="Search for a pitch">
                    </div>
                    <div class="col-md-2">
                        <div class="row justify-content-end">

                          <div class="col-md-10">
                                <button type="button" class="btn btn-primary btn-block" @click="addPitch()"><small><i class="fas fa-plus"></i></small>&nbsp;{{$lang.pitch_add}}</button>
                          </div>
                        </div>
                    </div> -->
                </div>

                <addPitchDetail  v-if="pitchId=='' && dispPitch==true" ></addPitchDetail>
                <editPitchDetail :pitchAction="pitchAction" v-if="pitchId!='' && dispPitch==true" > </editPitchDetail>
                <delete-modal :deleteConfirmMsg="deleteConfirmMsg" @confirmed="deleteConfirmed()"></delete-modal>

                <div class="row mt-4">
                    <div class="result col-md-12">
                         <table class="table table-hover table-bordered mb-0 pitch_capacity_table" v-if="dragPitches">
                            <thead>
                                <tr>
                                    <th class="text-center">{{$lang.pitch_modal_details_name}}</th>
                                    <th class="text-center">{{$lang.pitch_capacity_location}}</th>
                                    <th class="text-center">{{$lang.pitch_modal_details_size}}</th>
                                    <th class="text-center">{{$lang.pitch_modal_details_type}}</th>
                                    <th class="text-center">{{$lang.pitch_modal_availability_stage}}</th>
                                    <th class="text-center">{{$lang.pitch_modal_action}}</th>
                                    <th class="text-center" v-if="!searchDisplayData">{{$lang.pitch_modal_order}} </th>
                                </tr>
                            </thead>
                            <draggable v-model="dragPitches" :element="'tbody'" @change="updatePitchOrder()" :options="{handle: '.drag-handle'}">
                                    <tr v-for="(pitch,index) in dragPitches">
                                        <td class="text-left">{{pitch.pitch_number}}</td>
                                        <td class="text-left">{{ pitch.venue.name }}</td>
                                        <td class="text-left">{{pitch.size}}</td>
                                        <td class="text-left" style="text-transform: capitalize;">{{pitch.type}}</td>
                                        <td>
                                            <p v-for="pitchStage in pitch.pitch_av_text">
                                            {{pitchStage}}</p>
                                            <!--<p>Day 2: 10am-1pm, 3pm-5pm</p>
                                            <p>Day 3: 10am-2pm</p>-->
                                        </td>
                                        <td class="text-center">
                                            <span class="align-middle">
                                                <a class="text-primary" href="javascript:void(0)" @click="editPitch(pitch.id, 'edit')" title="Edit pitch"><i class="fas fa-pencil"></i></a>
                                            </span>
                                            <span class="align-middle">
                                                 <a href="#" @click="generatePitchMatchReport(pitch.id)" title="Pitch match schedule" class="text-primary mx-1" style="font-size:1.1em"><i class="fas fa-download"></i></a>
                                            </span>
                                            <span class="align-middle">
                                                 <a href="javascript:void(0)" @click="editPitch(pitch.id, 'duplicate')" title="Copy pitch" class="text-primary mx-1" style="font-size:1.1em"><i class="fas fa-copy"></i></a>
                                            </span>
                                            <span class="align-middle">
                                                 <a href="javascript:void(0)" data-confirm-msg="Are you sure you would like to delete this pitch record?" data- data-toggle="modal" data-target="#delete_modal" @click="deletePitch(pitch.id)"><i class="fas fa-trash text-danger"></i></a>
                                            </span>
                                        </td>
                                        <td v-if="!searchDisplayData" class="text-center drag-handle">
                                            <span class="align-middle text-primary draggable-handle">
                                                <i class="fas fa-arrow-up" v-if="index > 0 && index < dragPitches.length"></i>
                                                <i class="fas fa-arrow-down" v-if="index >= 0 && index < dragPitches.length - 1"></i>
                                            </span>
                                        </td>
                                    </tr>
                            </draggable>
                        </table>
                        <div v-else>
                            <p class="text-muted">No pitch found.</p>
                        </div>
                    </div>
                </div>
                <div class="row my-3">
                  <div class="col-3 align-self-center">
                      <h6 class="mb-0 fieldset-title"><strong>{{$lang.pitch_summary}}</strong></h6>
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
                                    <td class="text-left">{{ pitchSize }}</td>
                                    <td class="text-left">{{ pitchSizeDetail.availableTime }}</td>
                                    <td class="text-left">{{ pitchSizeDetail.timeRequired }}</td>
                                    <td class="text-left" :class="[pitchSizeDetail.balanceSign == '-' ? 'red' : 'text-success']">{{ pitchSizeDetail.balance }}</td>
                                </tr>
                                <tr>
                                    <td class="text-left"><strong>{{ $lang.totals }}</strong></td>
                                    <td class="text-left">{{ pitchSizeWiseSummaryTotal.totalAvailableTime }}</td>
                                    <td class="text-left">{{ pitchSizeWiseSummaryTotal.totalTimeRequired }}</td>
                                    <td class="text-left" :class="[pitchSizeWiseSummaryTotal.totalBalanceSign == '-' ? 'red' : 'text-success']">{{ pitchSizeWiseSummaryTotal.totalBalance }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div v-for="(locationDetail, locationId) in locationSizeWiseSummaryArray">
                    <div class="row my-3">
                      <div class="col-3 align-self-center">
                          <h6 class="mb-0 fieldset-title"><strong>Venue - {{ locationDetail.name }}</strong></h6>
                      </div>
                    </div>
                    <div class="row">
                        <div class="result col-md-12">
                            <table class="table table-hover table-bordered mb-0 pitch_size_summary">
                                <thead>
                                    <tr>
                                        <th class="text-center">Pitch</th>
                                        <th class="text-center">{{$lang.pitch_available_time}}</th>
                                        <th class="text-center">Total time used</th>
                                        <th class="text-center">{{$lang.pitch_balance}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(pitchSizeDetail, pitchSize) in locationDetail.sizes">
                                        <td class="text-left">{{ pitchSize }}</td>
                                        <td class="text-left">{{ pitchSizeDetail.availableTime }}</td>
                                        <td class="text-left">{{ pitchSizeDetail.timeUsed }}</td>
                                        <td class="text-left" :class="[pitchSizeDetail.balanceSign == '-' ? 'red' : 'text-success']">{{ pitchSizeDetail.balance }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left"><strong>{{ $lang.totals }}</strong></td>
                                        <td class="text-left">{{ locationWiseSummaryTotal[locationId].totalAvailableTime }}</td>
                                        <td class="text-left">{{ locationWiseSummaryTotal[locationId].totalTimeUsed }}</td>
                                        <td class="text-left" :class="[locationWiseSummaryTotal[locationId].totalBalanceSign == '-' ? 'red' : 'text-success']">{{ locationWiseSummaryTotal[locationId].totalBalance }}</td>
                                    </tr>
                                </tbody>
                            </table>
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
import draggable from 'vuedraggable';
    export default {
        components: { draggable },
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
                'locationWiseSummaryData': {
                    'allLocations': [],
                    'allPitchSizes': [],
                    'allPitches': [],
                    'totalAvailableTimeLocationWise': {},
                    'totalTimeUsedLocationWise': {},
                },
                'locationSizeWiseSummaryArray': {},
                'locationWiseSummaryTotal': {},
                'dragPitches':this.$store.state.Pitch.pitches,
                pitchDataSearch: '',
                selectedVenue: '',
                venuesOptions:[],
                searchDisplayData: false,
                pitchAction: '',
            }
        },

        created: function() {
            this.$root.$on('displayPitch', this.displayPitch);
            this.$root.$on('pitchrefresh', this.getAllPitches);
            this.$root.$on('getPitchSizeWiseSummary', this.getPitchSizeWiseSummary);
            this.$root.$on('getLocationWiseSummary', this.getLocationWiseSummary);
            this.getPitchSizeWiseSummary();
            this.getLocationWiseSummary();
            this.displayTournamentCompetationList();
        },
        beforeCreate: function() {
            // Remove custom event listener
            this.$root.$off('displayPitch');
            this.$root.$off('pitchrefresh');
            this.$root.$off('getPitchSizeWiseSummary');
            this.$root.$off('getLocationWiseSummary');
        },
        components: {
            editPitchDetail,addPitchDetail,DeleteModal,draggable
        },
        computed: {
            pitchId: function(){
                return _.cloneDeep(this.$store.getters.curPitchId)
            },
            pitchData: function() {
                return this.$store.state.Pitch.pitchData
            },
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
            Plugin.initPlugins(['Select2','TimePickers','MultiSelect','DatePicker', 'addstage'])
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
            this.getVenuesDropDownData();

        },
        methods: {
            displayPitch(value) {
              this.dispPitch = false
            },
            getAllPitches() {
                let vm = this;
                this.$store.dispatch('SetPitches',this.tournamentId).then((pitches) => {
                    vm.dragPitches = pitches;
                });
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
                       vm.getLocationWiseSummary();
                       vm.$root.$emit('')
                       vm.resetSearchFilter();
                  });
                },1000)

            },
            editPitch(pitchId, pitchAction) {
                this.pitchAction = pitchAction;
                this.dispPitch = true;
                this.$store.dispatch('SetPitchId',pitchId);
                // this.pitchId = pitchId
                    // this1.$store.dispatch('PitchData',pitchId)
                let this1 = this
                setTimeout(function(){
                    this1.$store.dispatch('PitchData',pitchId)
                    this1.getAllPitches()
                    this1.resetSearchFilter();
                },1000)

            },
            duplicatePitch(pitchId) {
                $('#duplicatePitch').modal('show')
            },
            removePitch(pitchId) {
                let vm = this;
                // this.$store.dispatch('removePitch',pitchId)
                // toastr['warning']('All schedules with this pitch will be removerd', 'Warning');
                return axios.post('/api/pitch/delete/'+pitchId).then(response =>  {
                    //this.getAllPitches()
                   $("#delete_modal").modal("hide");
                    toastr.success('Pitch successfully deleted.', 'Delete Pitch', {timeOut: 5000});
                    // toastr['success']('Pitch Successfully removed', 'Success');
                    vm.getAllPitches();
                    vm.getPitchSizeWiseSummary();
                    vm.getLocationWiseSummary();
                    vm.resetSearchFilter();
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
            generatePitchMatchReport(pitchId) {
               // var win = window.open("/api/pitch/reportCard/" + pitchId);
               // win.focus();
               var pitchPrintWindow = window.open('', '_parent');
                Tournament.getSignedUrlForPitchMatchReport(pitchId).then(
                    (response) => {
                       pitchPrintWindow.location = response.data;
                    },
                    (error) => {

                    }
                )
            },
            getLocationWiseSummary() {
                if (!isNaN(this.tournamentId)) {
                    let vm = this;
                    vm.locationWiseSummaryData = this.defaultLocationWiseSummaryData();
                    vm.locationSizeWiseSummaryArray = {};

                    Pitch.getLocationWiseSummary(this.tournamentId).then (
                      (response) => {
                        vm.locationWiseSummaryData = response.data;
                        let allLocations = response.data.allLocations;
                        let allPitchSizes = response.data.allPitchSizes;
                        let locationSizeWiseSummaryArray = {};
                        let locationWiseSummaryTotal = {};
                        for(let i=0; i<allLocations.length; i++) {
                            let totalAvailableTime = 0;
                            let totalTimeUsed = 0;
                            let totalBalance = 0;
                            let location = allLocations[i];
                            let locationId = allLocations[i].id;
                            locationSizeWiseSummaryArray[locationId] = {};
                            locationSizeWiseSummaryArray[locationId]['name'] = location.name;
                            locationSizeWiseSummaryArray[locationId]['sizes'] = {};
                            locationWiseSummaryTotal[locationId] = this.defaultLocationWiseSummaryTotal();
                            for(let j=0; j<allPitchSizes.length; j++) {
                                let locationSizeDetail = {};
                                let size = allPitchSizes[j];

                                let availableTime = vm.getAvailableTimeOfLocationSize(locationId, size);
                                let timeUsed = vm.getRequiredTimeForLocationSize(locationId, size);
                                let balance = vm.getLocationBalanceSize(locationId, size);

                                totalAvailableTime += availableTime;
                                totalTimeUsed += timeUsed;
                                totalBalance += balance;

                                let minutes = balance % 60;
                                let hours = (balance - minutes) / 60;

                                if(minutes<0){
                                    minutes = parseInt(0 - minutes)
                                }
                                if(hours<0){
                                    hours = parseInt(0 - hours)
                                }

                                if(availableTime > 0 || timeUsed > 0) {
                                    locationSizeDetail.availableTime = ( ((availableTime - (availableTime % 60)) / 60) + ' hrs ' + (availableTime % 60) + ' mins');
                                    locationSizeDetail.timeUsed = ( ((timeUsed - (timeUsed % 60)) / 60) + ' hrs ' + (timeUsed % 60) + ' mins');
                                    locationSizeDetail.balance = (balance < 0 ? '-' : '') + ( hours + ' hrs ' + minutes + ' mins' );
                                    locationSizeDetail.balanceSign = balance < 0 ? '-' : '+';

                                    locationSizeWiseSummaryArray[locationId]['sizes'][size] = locationSizeDetail;
                                }
                            }
                            let minutes = totalBalance % 60;
                            let hours = (totalBalance - minutes) / 60;

                            if(minutes<0){
                                minutes = parseInt(0 - minutes)
                            }
                            if(hours<0){
                                hours = parseInt(0 - hours)
                            }

                            locationWiseSummaryTotal[locationId].totalAvailableTime = ( ((totalAvailableTime - (totalAvailableTime % 60)) / 60) + ' hrs ' + (totalAvailableTime % 60) + ' mins');
                            locationWiseSummaryTotal[locationId].totalTimeUsed = ( ((totalTimeUsed - (totalTimeUsed % 60)) / 60) + ' hrs ' + (totalTimeUsed % 60) + ' mins');
                            locationWiseSummaryTotal[locationId].totalBalance = (totalBalance < 0 ? '-' : '') + ( hours + ' hrs ' + minutes + ' mins' );
                            locationWiseSummaryTotal[locationId].totalBalanceSign = totalBalance < 0 ? '-' : '+';
                        }
                        vm.locationSizeWiseSummaryArray = locationSizeWiseSummaryArray;
                        vm.locationWiseSummaryTotal = locationWiseSummaryTotal;
                      },
                      (error) => {
                      }
                    )
                } else {
                  this.TournamentId = 0;
                }
            },
            defaultLocationWiseSummaryData() {
                return {
                    'allLocations': [],
                    'allPitches': [],
                    'totalAvailableTimeLocationWise': {},
                    'totalTimeUsedLocationWise': {},
                }
            },
            defaultLocationWiseSummaryTotal() {
                return {
                    'totalAvailableTime': 0,
                    'totalTimeRequired': 0,
                    'totalBalance': 0,
                    'totalBalanceSign': '+',
                };
            },
            getAvailableTimeOfLocationSize(locationId, size) {
                let totalAvailableTimeLocationWise = this.locationWiseSummaryData.totalAvailableTimeLocationWise;
                let timeInMinutes = 0;
                if(totalAvailableTimeLocationWise.hasOwnProperty(locationId)) {
                    if (totalAvailableTimeLocationWise[locationId][size]) {
                        timeInMinutes = parseInt(totalAvailableTimeLocationWise[locationId][size]);
                    }
                }
                return timeInMinutes;
            },
            getRequiredTimeForLocationSize(locationId, size) {
                let totalTimeUsedLocationWise = this.locationWiseSummaryData.totalTimeUsedLocationWise;
                let timeInMinutes = 0;
                if(totalTimeUsedLocationWise.hasOwnProperty(locationId)) {
                    if (totalTimeUsedLocationWise[locationId][size]) {
                        timeInMinutes = parseInt(totalTimeUsedLocationWise[locationId][size]);
                    }
                }
                return timeInMinutes;
            },
            getLocationBalanceSize(locationId, size) {
                let totalAvailableTimeLocationWise = this.locationWiseSummaryData.totalAvailableTimeLocationWise;
                let totalTimeUsedLocationWise = this.locationWiseSummaryData.totalTimeUsedLocationWise;
                let totalAvailableTime = 0;
                let totalTimeRequired = 0;

                if(totalAvailableTimeLocationWise.hasOwnProperty(locationId)) {
                    if (totalAvailableTimeLocationWise[locationId][size]) {
                        totalAvailableTime = parseInt(totalAvailableTimeLocationWise[locationId][size]);
                    }
                }
                if(totalTimeUsedLocationWise.hasOwnProperty(locationId)) {
                    if (totalTimeUsedLocationWise[locationId][size]) {
                        totalTimeRequired = parseInt(totalTimeUsedLocationWise[locationId][size]);
                    }
                }
                return (totalAvailableTime - totalTimeRequired);
            },
            updatePitchOrder() {
                let vm = this;
                var pitchIds = _.map(this.dragPitches, 'id');
                return axios.post('/api/pitch/updatePitchOrder', pitchIds).then(response =>  {
                    toastr.success('The order of the pitches has been updated', 'Pitch Order', {timeOut: 5000});
                    vm.getAllPitches();
                }).catch(error => {
                    if (error.response.status == 401) {
                        toastr['error']('Invalid Credentials', 'Error');
                    } else {
                        toastr.error('Pitches order not successfully updated', 'Pitch Order', {timeOut: 5000});
                    }
                });
            },
            getPitchSearchData(){
                let tournamentData = {'tournament_id': this.tournamentId, 'pitchDataSearch': this.pitchDataSearch,
                    'selectedVenue': this.selectedVenue}
                let vm = this;
                Pitch.getPicthSearchRecord(tournamentData).then (
                    (response) => {
                    this.dragPitches = [];
                    this.searchDisplayData = false;
                    if(this.selectedVenue != '' || this.pitchDataSearch != '') {
                        this.searchDisplayData = true;
                    }
                    this.dragPitches = response.data.pitches;
                    _.forEach(this.dragPitches , function(pitch, index) {
                        let i = 1;
                        let stageTime = {}
                        
                        _.forEach(pitch.pitch_availability, function(pitchAvailable) {
                            
                            if(pitchAvailable.break_enable == '0' || pitchAvailable.break_enable == '1'  ) {

                                let stageStr = "Day " + pitchAvailable.stage_no +" : "+pitchAvailable.stage_start_time+'-';

                                _.forEach(pitchAvailable.pitch_breaks, function(pitchBreaks) {
                                    stageStr = stageStr +pitchBreaks.break_start+', '+pitchBreaks.break_end+'-';
                                });

                                stageStr = stageStr + pitchAvailable.stage_end_time;
                
                                stageTime[pitch.id+"_"+i]  = stageStr;

                                i++;
                                
                            }
                            vm.dragPitches[index].pitch_av_text = stageTime; 
                        });

                    });
                });
            },

            getVenuesDropDownData() {
                let tournamentData = {'tournament_id': this.tournamentId}
                Pitch.getVenuesDropDownData(tournamentData).then (
                      (response) => {
                        this.venuesOptions = response.data.venues;
                });
            },
            resetSearchFilter() {
                this.selectedVenue = '';
                this.pitchDataSearch = '';
                this.searchDisplayData = false;
            }
        }
    }
</script>
