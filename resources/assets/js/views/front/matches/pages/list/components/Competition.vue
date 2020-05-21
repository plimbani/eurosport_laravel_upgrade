<template>
  <div>
    <button v-if="fromView == 'Matches'" @click="showMatchListView()" class="btn btn-primary">
        <i aria-hidden="true" class="fas fa-angle-double-left"></i> {{ $t('matches.back_to_match_list') }}
    </button>
    <button v-if="fromView == 'Categories'" @click="showCompetitionListView()" class="btn btn-primary">
        <i aria-hidden="true" class="fas fa-angle-double-left"></i> {{ $t('matches.back_to_competition_list') }}
    </button>
    <button v-if="fromView == 'Teams'" @click="showTeamListView()" class="btn btn-primary">
        <i aria-hidden="true" class="fas fa-angle-double-left"></i> Back to team list
    </button>
    <div>
        <div class="row align-items-center my-4">
          <div class="col-10 col-sm-6 col-md-4 col-lg-3 col-xl-3">
            <label class="d-block mb-0 select2_override" for="match_overview">
              <select v-on:change="onCompetitionChange" id="competition-overview" class="border-0 form-control" name="competition-options">
                  <optgroup :label="key" v-for="(round, key) in dropdownDrawName.round_robin">
                    <option v-bind:value="group.id" :label="group.display_name" :rel="group.actual_competition_type" v-for="group in round">{{group.display_name}}</option>
                  </optgroup>

                  <optgroup :label="index" class="division" v-for="(division, index) in dropdownDrawName.divisions">
                    <option class="rounds" disabled="true" :rel="roundIndex" :label="roundIndex" v-for="(divRound, roundIndex) in division">
                    <option v-bind:value="divGroup.id" class="placingMatches" :label="divGroup.display_name" :rel="divGroup.actual_competition_type" v-for="divGroup in divRound">&nbsp;&nbsp;&nbsp;&nbsp;{{ divGroup.display_name }}</option>
                    </option>
                  </optgroup>
              </select>
            </label>
          </div>
        </div>
        <div v-if="competitionDetail.type != 'Elimination'">
          <h6 class="mt-3 font-weight-bold">{{ competitionDetail.name }} {{ $t('matches.results_grid') }}</h6>
        </div>
        <span v-if="matchesGrid.length == 0 && competitionDetail.type != 'Elimination'">{{ $t('matches.no_information_available') }}</span>
    </div>

    <div class="table-responsive">
      <table class="table" v-if="matchesGrid.length > 0 && competitionDetail.type != 'Elimination'">
        <thead class="no-border">
          <tr>
            <th></th>
            <th scope="col" v-for="(match, index) in matchesGrid">
              <div class="matchteam-details">                
                <span v-if="match.TeamCountryFlag" :class="'matchteam-flag flag-icon flag-icon-' + match.TeamCountryFlag"></span>
                <div class="matchteam-dress" v-if="match.ShortsColor && match.ShirtColor">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><polygon class="cls-1" v-bind:fill="match.ShortsColor" points="13.79 39.72 13.79 61.04 30.26 61.04 32.2 55.22 34.14 61.04 50.61 61.04 50.61 39.72 13.79 39.72"/></g><path class="cls-2" v-bind:fill="match.ShirtColor" d="M62.83,11.44,50.61,1H38A6.29,6.29,0,0,1,32.2,4.84,6.29,6.29,0,0,1,26.39,1H13.79L1.57,11.44a1.65,1.65,0,0,0-.09,2.41L8,20.34l5.81-3.87V39.72H50.61V16.47l5.81,3.87,6.5-6.49A1.65,1.65,0,0,0,62.83,11.44Z"/></svg>
                </div>
                <span class="matchteam-name">{{ match.TeamName }}</span>
              </div>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(match, index) in matchesGrid">
            <td>
              <div class="matchteam-details">                
                <span v-if="match.TeamCountryFlag" :class="'matchteam-flag flag-icon flag-icon-' + match.TeamCountryFlag"></span>
                <div class="matchteam-dress" v-if="match.ShortsColor && match.ShirtColor">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><polygon class="cls-1" v-bind:fill="match.ShortsColor" points="13.79 39.72 13.79 61.04 30.26 61.04 32.2 55.22 34.14 61.04 50.61 61.04 50.61 39.72 13.79 39.72"/></g><path class="cls-2" v-bind:fill="match.ShirtColor" d="M62.83,11.44,50.61,1H38A6.29,6.29,0,0,1,32.2,4.84,6.29,6.29,0,0,1,26.39,1H13.79L1.57,11.44a1.65,1.65,0,0,0-.09,2.41L8,20.34l5.81-3.87V39.72H50.61V16.47l5.81,3.87,6.5-6.49A1.65,1.65,0,0,0,62.83,11.44Z"/></svg>
                </div>
                <span class="matchteam-name">{{ match.TeamName }}</span>
              </div>
            </td>
            <td v-for="(teamMatch, ind2) in match.matches" :class="[teamMatch == 'Y' ? 'bg-light-grey' : '', '']">
              <div v-if="teamMatch.score == null && teamMatch != 'Y' && teamMatch != 'X' ">{{ teamMatch.date | formatDate }}</div>
              <div v-else>{{ teamMatch.score }}</div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div>
      <h6 class="mt-3 font-weight-bold" v-if="competitionDetail.type != 'Elimination'">
        {{ competitionDetail.name }} standings
      </h6>
      <teamStanding :currentCompetitionId="currentCompetitionId" :competitionType="competitionDetail.type" v-if="currentCompetitionId != 0">
      </teamStanding>
      <div v-if="currentCompetitionId == 0 && competitionDetail.type != 'Elimination'">{{ $t('matches.no_information_available') }}
      </div>
    </div>

    <h6 class="mt-3 font-weight-bold" v-if="matches.length > 0 && isDivExist == 0 && isKnockoutPlacingMatches === false">{{ competitionDetail.name }} matches</h6>
    <matches :matches="matches" :competitionDetail="currentCompetition" :currentView="currentView" :fromView="'Competition'" :isDivExist="isDivExist" :isDivOrKnockoutExistData="isDivOrKnockoutExistData" :categoryId="categoryId" :isKnockoutPlacingMatches="isKnockoutPlacingMatches"></matches>
  </div>
</template>

<script type="text/babel">
  import Matches from './Matches.vue';
  import TeamStanding from './TeamStanding.vue';
  import MatchList from '../../../../../../api/frontend/matchlist.js';

  export default {
    props: ['matches', 'competitionDetail', 'currentView', 'fromView', 'categoryId'],
    data() {
      return {
        currentCompetition: {},
        competitionList: [],
        competitionRound: 'Round Robin',
        currentCompetitionId: 0,
        matchesGrid: [],
        isDivExist: false,
        isKnockoutPlacingMatches: false,
        isDivOrKnockoutExistData: [],
        dropdownDrawName:[],
      };
    },
    created() {
    },
    mounted() {

      this.currentCompetitionId = this.competitionDetail.id;
      this.getCompetitions();
      this.generateDrawTable();
    },
    filters: {
      formatDate: function(date) {
        if (date != null) {
          return moment(date).format("Do MMM YYYY HH:mm");
        } else {
          return "";
        }
      },
    },
    watch: {
      matches: {
        handler: function (val, oldVal) {
          var getFirstMatch = _.head(this.matches);
          if ( typeof(getFirstMatch) != 'undefined' && getFirstMatch.isDivExist == 1 )
          {
            this.isDivExist = getFirstMatch.isDivExist;
            this.isKnockoutPlacingMatches = getFirstMatch.isKnockoutPlacingMatches;
            this.isDivExistData = _.groupBy(this.matches, 'competation_round_no');
          }
          else
          {
            this.isDivExist = 0;
            this.isKnockoutPlacingMatches = false;
            this.isDivExistData = [];
          }
        },
        deep: true,
      },
    },
    computed: {
      updateDivExistData:function(){
        var getFirstMatch = _.head(this.matches);
        if ( typeof(getFirstMatch) != 'undefined' && (getFirstMatch.isDivExist == 1 || getFirstMatch.isKnockoutPlacingMatches === true) )
        {
          this.isDivExist = getFirstMatch.isDivExist;
          this.isKnockoutPlacingMatches = getFirstMatch.isKnockoutPlacingMatches;
          this.isDivOrKnockoutExistData = _.groupBy(this.matches, 'competation_round_no');
        }
        else
        {
          this.isDivExist = 0;
          this.isKnockoutPlacingMatches = false;
          this.isDivOrKnockoutExistData = [];
        }
      }
    },
    components: {
      Matches,
      TeamStanding,
    },
    methods: {
      getCompetitions() {
        var vm = this;
        var currentCompetition;
        var competitionRound;
        let currentAgeCategoryId =  this.categoryId;
        MatchList.getAllDraws(tournamentData.id,currentAgeCategoryId).then(
          (response)=> {
            if(response.data.status_code == 200) {
              vm.competitionList = response.data.data.mainData;
              vm.dropdownDrawName = response.data.data.ageCategoryData;
              vm.competitionList.map(function(competition, key) {
                if(competition.actual_competition_type == 'Elimination') {
                  competition.name = _.replace(competition.name, '-Group', '');
                  return competition;
                }
              });

              currentCompetition = _.find(response.data.data.mainData, function(o) { return o.id == vm.currentCompetitionId; });

              vm.currentCompetition = currentCompetition;
              vm.competitionRound = currentCompetition.competation_type;

              this.$nextTick(() => {
                $('#competition-overview optgroup .rounds').each(function() {
                  var insideOptions = $(this).html();
                  $(this).html('');
                  $(insideOptions).insertAfter($(this));

                  $(this).html($(this).attr('rel'));
                });

                $("#competition-overview").val(vm.currentCompetitionId);
                $("#competition-overview").select2({
                  templateResult: function (data, container) {
                    if (data.element) {
                      $(container).addClass($(data.element).attr("class"));
                    }
                    return data.text;
                  }
                }) 
                .on('change', function () {
                  let curreId = $(this).val();
                  vm.competitionList.map(function(value, key) {
                    if(value.id == curreId) {
                      vm.currentCompetition = value;
                      vm.currentCompetitionId = curreId;
                    }
                  });
                  vm.onCompetitionChange();
                });
              });
            }
          },
          (error) => {
            console.log('Error in getting draws')
          }
        );
      },
      generateDrawTable() {
        if(this.currentCompetitionId != undefined) {
          let tournamentId = tournamentData.id;
          let data = {'tournamentId': tournamentId, 'competationId': this.currentCompetitionId};
          MatchList.getDrawTable(data).then(
          (response)=> {
            if(response.data.status_code == 200){
              this.matchesGrid = response.data.data;
            }
            if(response.data.status_code == 300){
              this.matchesGrid = [];
            }
            // this.teamStatus = false;
            // let vm = this;
            // setTimeout(function(){
            //   vm.teamStatus = true;
            // }, 500);
          },
          (error)=> {}
          )
        }
      },
      refreshStanding() {
        $("body .js-loader").removeClass('d-none');
        let competitionId = 0;
        if(this.currentCompetitionId !== undefined){
          competitionId = this.currentCompetitionId;
        }
        let data = {'tournamentId': tournamentData.id, 'competitionId': competitionId};
        MatchList.refreshStanding(data).then(
          (response)=> {
            if(response.data.status_code == 200){
              $("body .js-loader").addClass('d-none');
            }
          },
        )
      },
      onCompetitionChange() {

        var competitionId = this.currentCompetition.id;
        var competitionName = this.currentCompetition.name;
        var competitionType = this.currentCompetition.competation_type;

        if(this.fromView == 'Matches') {
          this.$root.$emit('showCompetitionData', competitionId, competitionName, competitionType);
        } else if(this.fromView == 'Categories') {
          this.$root.$emit('showCompetitionViewFromCategory', competitionId, competitionName, competitionType);
        } else if(this.fromView == 'Teams') {
          this.$root.$emit('showCompetitionViewFromTeam', competitionId, competitionName, competitionType);
        }
        this.currentCompetitionId = competitionId;

        this.generateDrawTable();
        this.$root.$emit('setStandingData', competitionId);
      },
      showMatchListView() {
        this.$root.$off('setStandingData');
        this.$root.$emit('showMatchesList');
      },
      showCompetitionListView() {
        this.$root.$emit('showCategoryGroups', this.categoryId);
      },
      showTeamListView() {
        this.$root.$emit('showTeamsList');
      },
    }
  };
</script>
