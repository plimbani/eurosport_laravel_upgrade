<template>
  <div>
    <div v-if="allHistoryYears.length > 0">
      <span class="text-uppercase">{{ $t('tournament.tournament_year') }}</span><span> {{ currentYear ? currentYear.year : null }}</span>
      <ul>
        <li v-for="allHistoryYear in allHistoryYears">
          <a href="javascript:" @click="historyYearChanged(allHistoryYear.id)">{{ allHistoryYear.year }}</a>
        </li>
      </ul>
      <div class="container" v-if="currentYear">
        <div class="row">
          <div class="col-sm-3 js-age-category" v-for="ageCategory in currentYear.age_categories">
            <h3>{{ ageCategory.name }}</h3>
            <ul class="js-list">
              <li class="team-item" v-for="team in ageCategory.teams">
                {{ team.name }} <span :class="'flag-icon flag-icon-' + team.country.country_flag"></span>
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
        currentYear: null,
        currentYearId: null,
      }
    },
    mounted() {
      if (allHistoryYears.length > 0) {
        this.currentYear = _.cloneDeep(_.head(allHistoryYears));
        this.currentYearId = this.currentYear.id;
        this.getHistoryAgeCategories(this.currentYearId);
      }
    },
    methods: {
      historyYearChanged(id) {
        this.getHistoryAgeCategories(id);
      },
      getHistoryAgeCategories(id) {
        let historyYear = _.find(this.allHistoryYears, (historyYear) => {
          if (id == historyYear.id) {
            return historyYear;
          }
        });
        this.currentYear = _.cloneDeep(historyYear);
        this.$nextTick()
        .then(() => {
          initializeList();
        });
      }
    },
  };
</script>
