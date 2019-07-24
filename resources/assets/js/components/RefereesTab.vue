<template>
  <div class="tab-content planner_list_content">
    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="col-12 mb-3">
            <a href="javascript:void(0)" @click="downloadRefereeSample()" class="text-primary"><u>Click here</u></a> to download the latest version of the referee upload spreadsheet.
          </div>
          <div class="col-12">
            <button type="button" data-toggle="modal" data-target="#refreesModal" id="add_referee" class="btn btn-primary mb-3 w-100" disabled="disabled">{{$lang.pitch_planner_referee}}</button>
          </div>
          <div class="col-12">
            <button type="button" data-toggle="modal" data-target="#uploadRefereesModal" class="btn btn-primary mb-3 w-100">{{$lang.pitch_planner_upload_referees}}</button>
          </div>
        </div>
        <div v-if="refereeStatus" v-for="referee in referees">
          <div>
            <draggable-referee :referee="referee" :competationList="competationListData" :isMatchScheduleInEdit="isMatchScheduleInEdit"></draggable-referee>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script type="text/babel">
  import DraggableReferee from './DraggableReferee';
  import Tournament from '../api/tournament.js';
  import _ from 'lodash';

  export default {
    props: ['competationList', 'isMatchScheduleInEdit'],
    data() {
    return {
      'tournamentId': this.$store.state.Tournament.tournamentId,
       refereeStatus: true,
       competationListData:{}
      }
    },
    components: {
      DraggableReferee
    },
    computed:{
      referees() {
        return this.$store.state.Tournament.referees
      },
    },
    created:function() {
      this.$root.$on('getAllReferee', this.getAllreferees);
    },
    mounted() {
      this.$store.dispatch('getAllReferee',this.tournamentId);
      $("#addReferee").on('hidden.bs.modal', function () {
          $('#frmAddReferee')[0].reset()
      });

      //this.displayTournamentCompetationList()
      $("#referee-list").mCustomScrollbar({
         'autoHideScrollbar':true
      });

      this.getCompetationList();
    },
    methods: {
      getAllreferees() {
        this.referees = this.$store.state.Tournament.referees
      },
      addReferee(){
        $('#addReferee').modal('show')
      },
      getCompetationList() {
        let vm = this;
        _.forEach(vm.competationList, function(value) {
          if(value.id && value.category_age) 
            vm.competationListData[value.id] = value.category_age;
        });
        return vm.competationListData;
      },
      downloadRefereeSample() {
        Tournament.getSignedUrlForRefereeSampleDownload().then(
          (response) => {
            window.location.href = response.data;         
          },
          (error) => {
        });
      },
    }
  }
</script>
