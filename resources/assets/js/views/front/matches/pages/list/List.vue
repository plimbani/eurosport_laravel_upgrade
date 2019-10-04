<template>
  <div>
    {{ updateDivExistData }}
    <div>
        <div class="row align-items-end custom_radio_btn matches-filter" v-if="currentView == 'Matches'">
            <div class="col-xl-6">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-6 col-lg-5 col-xl-12">
                        <label class="label-of-input font-weight-bold mb-0">{{ $t('matches.match_overview') }}:</label>
                    </div>
                    <div class="col-sm-12 col-md-5 col-lg-5 col-xl-12">
                        <label class="custom_select_box d-block mb-0" for="match_overview">
                            <select id="match_overview" class="border-0" v-on:change="onMatchDateChange()" v-model="matchDate">
                                <option value=""> {{ $t('matches.all_dates') }}</option>
                                <option v-for="date in tournamentDates" v-bind:value="date">{{ date | formatDate }}</option>
                            </select>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-6 col-lg-5 col-xl-12">
                        <label class="label-of-input font-weight-bold mb-0">{{ $t('matches.score') }}:</label>
                    </div>
                    <div class="col-sm-12 col-md-5 col-lg-5 col-xl-12">
                        <label class="custom_select_box d-block mb-0" for="match_score">
                            <select id="match_score"  class="border-0" v-on:change="onChangeAllMatchScore" v-model="matchScoreFilter">
                              <option value="all">All matches</option>
                              <option value="played">Scored</option>
                              <option value="to_be_played">Not scored</option>
                            </select>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 mt-4 mt-xl-0">
                <div class="row">
                  <div class="col-12">
                    <label class="label-of-input font-weight-bold mb-0">{{ $t('matches.filter_by') }}:</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-8 col-lg-7 mb-3 mb-md-0">
                    <div class="row align-items-center h-100">
                      <div class="col-md-4">
                        <div class="radio d-inline-block pl-0">
                            <input type="radio" v-model="filterBy" id="filter_category_and_competition" value="category_and_competition" name="match_filter" @change="getFilterOptions()">
                            <label for="filter_category_and_competition" class="d-inline-block mb-0">{{ $t('matches.category') }}</label>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="radio d-inline-block pl-0">
                            <input type="radio" v-model="filterBy" id="filter_location" value="location" name="match_filter" @change="getFilterOptions()">
                            <label for="filter_location" class="d-inline-block mb-0">{{ $t('matches.location') }}</label>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="radio d-inline-block pl-0">
                            <input type="radio" v-model="filterBy" id="filter_team" value="team" name="match_filter" @change="getFilterOptions()">
                            <label for="filter_team" class="d-inline-block mb-0">{{ $t('matches.team') }}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-lg-3 select2_override" v-show="filterBy == 'category_and_competition'">
                    <select class="form-control js-category-and-competition category-and-competition-filter">
                        <option value="">{{ $t('matches.select') }}</option>
                        <option v-for="option in filterOptions" v-bind:data-val="setFilterOption(option)" v-bind:id="option.id" v-bind:value="setFilterOption(option)" :class="option.class">{{ option.name }}</option>
                    </select>
                  </div>
                  <div class="col-md-4 col-lg-3 select2_override"  v-show="filterBy != 'category_and_competition'">
                    <label class="custom_select_box d-block mb-0" for="location_team_filter">
                      <select id="location_team_filter" class="border-0" v-model="selectedOption" @change="setFilterOptions()">
                        <option value="">{{ $t('matches.select') }}</option>
                        <option :value="option.id" v-for="option in filterOptions" v-bind:value="option">{{ option.name }}</option>
                      </select>
                    </label>
                  </div>
                  <div class="col-lg-2 mt-4 mt-lg-0">
                    <button type="button" class="btn btn-primary btn-block h-100" @click="clearFilter()">{{ $t('matches.clear') }}</button>
                  </div>
                </div>
            </div>
        </div>
        <div class="text-center view-full-information" v-if="showGroupInfo" v-html="$t('matches.view_match_info_message', {'competitionName': selectedOption.data.name})" v-on:click.capture="showCompetitionDetailPage()"></div>
    </div>
    <component :is="currentView" :matches="matches" :competitionDetail="competitionDetail" :currentView="currentView" :fromView="'Matches'" :categoryId="currentCategoryId" :isDivExist="isDivExist" :isDivOrKnockoutExistData="isDivOrKnockoutExistData" :isKnockoutPlacingMatches="isKnockoutPlacingMatches"></component>
  </div>
</template>

<script type="text/babel">
  import MatchList from '../../../../../api/frontend/matchlist.js';
  import Matches from './components/Matches.vue';
  import Competition from './components/Competition.vue';

  export default {
    data() {
      return {
        matches: [],
        matchDate: '',
        currentView: 'Matches',
        tournamentData: tournamentData,
        tournamentDates: [],
        filterOptions: [],
        selectedOption: '',
        competitionDetail: {
          id: '',
          name: '',
          type: '',
        },
        filterBy: 'category_and_competition',
        currentCategoryId: '',
        matchScoreFilter: 'all',
        isDivExist: false,
        isKnockoutPlacingMatches: false,
        isDivOrKnockoutExistData: [],
        dropdownDrawName:[],
      };
    },
    filters: {
      formatDate: function(date) {
        var date = moment(date,'DD/MM/YYYY');
        return moment(date).format("DD MMM YYYY");
      },
    },
    mounted() {
      this.tournamentDates = this.getTournamentDates(tournamentData.start_date, tournamentData.end_date);
      this.getFilterOptions();
    },
    created() {
      this.$root.$on('showCompetitionData', this.showCompetitionData);
      this.$root.$on('showMatchesList', this.showMatchesList);
    },
    computed: {
      showGroupInfo() {
        return (this.currentView == 'Matches' && this.filterBy == 'category_and_competition' && this.selectedOption != '' && this.selectedOption.class == 'competition');
      },
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
          this.isKnockoutPlacingMatches === false;
          this.isDivOrKnockoutExistData = [];
        }
      }
    },
    components: {
      Matches,
      Competition,
    },
    methods: {
      getTournamentDates(startDate, endDate) {
        var dateArray = [];

        var startDate = new Date(moment(startDate, 'DD/MM/YYYY'))
        var endDate = new Date(moment(endDate, 'DD/MM/YYYY'))

        while (startDate <= endDate) {
          dateArray.push( moment(startDate).format('DD/MM/YYYY') )
          startDate = moment(startDate).add(1, 'days');
        }
        return dateArray;
      },
      onMatchDateChange() {
        this.setFilterOptions();
      },
      setFilterOption(option) {
        return JSON.stringify(option);
      },
      clearFilter() {
        this.selectedOption = '';
        $('.js-category-and-competition').select2().val(null).trigger("change");
        this.filterBy = 'category_and_competition';
        this.getFilterOptions();
      },
      getFilterOptions() {
        if ($('.js-category-and-competition').data('select2')) {
          $('.js-category-and-competition').select2('destroy');
        }
        let data = { 'tournamentId': tournamentData.id, 'filterBy': this.filterBy };
        this.selectedOption = '';
        this.matches = [];
        MatchList.getFilterDropDownData(data).then(
          (response) => {
            this.filterOptions = response.data.options;
            if(this.filterBy == 'category_and_competition') {
              $('.js-category-and-competition').select2().val(null).trigger("change");
              var options = [];
              _.map(response.data.options, function(option) {
                options.push({ "id": option.id, "name": option.name, "class": "category", "data": option.id });
                _.map(option.competition, function(competition) {
                  let groupName = competition.name.split("-");
                  groupName = groupName.splice(groupName.length-2, 2);
                  groupName = groupName.join('-');
                  options.push({ "id": competition.id, "name": groupName, "class":"competition", "data":competition });
                });
              });
              $('.js-category-and-competition').select2({
                minimumResultsForSearch: Infinity,
              });
              var vm =this;
              $('.js-category-and-competition').on("select2:select", function (e) {
                var selectedVal = $(this).val();
                vm.selectedOption = selectedVal != '' ? JSON.parse(selectedVal) : '';
                vm.setFilterOptions();
              });
              this.filterOptions = options;
            }
            this.setFilterOptions();
          },
          (error) => {
          }
        );
      },
      setFilterOptions() {
        var matchFilterKey = this.filterBy;

        // var tournamentFilter = {'filterKey': this.filterBy, 'filterValue': this.selectedOption}
        // this.$store.dispatch('setTournamentFilter', tournamentFilter);

        if(this.filterBy === 'category_and_competition') {
          if(this.selectedOption == '') {
            matchFilterKey = '';
          } else if(this.selectedOption.class == 'category'){
            matchFilterKey = 'competation_group_age';
          } else {
            matchFilterKey = 'competation_group';
          }
        }

        this.getAllMatches(matchFilterKey, this.selectedOption);
      },
      getAllMatches(filterKey, filterValue) {
        $("body .js-loader").removeClass('d-none');
        var tournamentId = tournamentData.id;
        var data = {};

        data.tournamentId = tournamentId;
        data.fiterEnable = true;
        this.matchDate != '' ? data.tournamentDate = this.matchDate : null;
        this.matchScoreFilter != '' ? data.matchScoreFilter = this.matchScoreFilter : null;

        if(filterKey != '' && filterValue != '') {
          data.filterKey = filterKey;
          data.filterValue = filterValue.id;
        }

        let vm = this;
        MatchList.getFixtures(data).then(
          (response)=> {
            $("body .js-loader").addClass('d-none');
            vm.matches = response.data.data;
            vm.matches = _.orderBy(vm.matches, ['match_datetime'], ['asc']);
            vm.$root.$emit('setMatchesForMatchList', _.cloneDeep(response.data.data));
          },
          (error) => {
          }
        )
      },
      showCompetitionData(id, competitionName, competitionType) {
        this.getCompetitionDetails(id, competitionName, competitionType);
      },
      getCompetitionDetails(competitionId, competitionName, competitionType) {
        var tournamentId = tournamentData.id;
        var data = {'tournamentId': tournamentId, 'competitionId': competitionId};
        var vm = this;

        this.competitionDetail.name = competitionName;
        this.competitionDetail.id = competitionId;
        this.competitionDetail.type = competitionType;

        MatchList.getFixtures(data).then(
          (response)=> {
            if(response.data.status_code == 200) {
              vm.matches = response.data.data;
              vm.$root.$emit('setMatchesForMatchList', _.cloneDeep(response.data.data));
              vm.currentView = 'Competition';
            }
          },
          (error) => {
          }
        )
      },
      showMatchesList() {
        this.currentView = 'Matches';
        this.currentCategoryId = '';
        this.getFilterOptions();
      },
      showCompetitionDetailPage() {
        var currentSelectedCompetition = this.selectedOption.data;
        this.currentCategoryId = currentSelectedCompetition.tournament_competation_template_id;
        this.showCompetitionData(currentSelectedCompetition.id, currentSelectedCompetition.name, currentSelectedCompetition.actual_competition_type);
      },
      onChangeAllMatchScore(){
        this.setFilterOptions();
      },
    },
  };
</script>
