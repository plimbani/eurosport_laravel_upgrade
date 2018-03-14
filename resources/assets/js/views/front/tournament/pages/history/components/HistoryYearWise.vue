<template>
  <div>
    <div v-if="allHistoryYears.length > 0">
      <span class="text-uppercase">{{ $t('tournament.tournament_year') }}</span><span> {{ this.currentYear.year }}</span>
      <ul>        
        <li v-for="allHistoryYear in allHistoryYears">
          <a href="javascript:" @click="historyYearChanged(allHistoryYear.id)">{{ allHistoryYear.year }}</a>
        </li>
      </ul>
      <div class="container">
        <div class="row">
          <div class="col-sm-3" v-for="ageCategoryTeam in ageCategoryTeams.age_categories">
            <h3>{{ ageCategoryTeam.name }}</h3>
            <ul class="js-list">
              <li class="team-item" v-for="team in ageCategoryTeam.teams">
                {{ team.name }} <span :class="'flag-icon flag-icon-'+team.country.country_flag"></span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <span v-else>{{ $t('no_information_available') }}</span>
  </div>
</template>

<script type="text/babel">
  import Tournament from '../../../../../../api/frontend/tournament.js';
  import _ from 'lodash';

  export default {
    data() {
      return {
        allHistoryYears: allHistoryYears,
        currentYear: '',
        currentYearId: null,
        ageCategoryTeams: ''
      }
    },
    mounted() {
      if (allHistoryYears.length > 0) {
        this.currentYear = _.head(allHistoryYears)
        this.currentYearId = this.currentYear.id;
        this.getHistoryAgeCategories(this.currentYearId);        
      }
    },
    methods: {
      historyYearChanged(id) {
        this.currentYear = _.find(allHistoryYears, function(historyYear) {
          if(id == historyYear.id) {
            return historyYear;
          }
        });
        this.getHistoryAgeCategories(id);
        initJsList();
      },
      getHistoryAgeCategories(id) {
        let vm = this;
        let ageCategoryTeam = _.find(this.allHistoryYears, (historyYears) => {
          if (id == historyYears.id) {
            return historyYears.age_categories;
          }
        });
        vm.ageCategoryTeams = ageCategoryTeam
      }      
    },
  };
</script>
