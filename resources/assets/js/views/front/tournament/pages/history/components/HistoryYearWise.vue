<template>
  <div>
    <div v-if="allHistoryYears.length > 0">
      <div class="mx-auto text-center">
        <span class="text-uppercase h6">{{ $t('tournament.tournament_year') }}</span>
        <label class="custom_select round custom_select-2 d-inline-block" for="history_year">
          <select id="history_year" v-model="currentYearId" @change="historyYearChanged()">
              <option :value="allHistoryYear.id" v-for="allHistoryYear in allHistoryYears">
                  {{ allHistoryYear.year }}
              </option>
          </select>
        </label>
      </div>

      <div class="row tournament-list" v-if="currentYear">
        <div class="col-sm-3 mb-5 js-age-category" v-for="ageCategory in currentYear.age_categories">
          <h3 class="mb-0 text-primary font-weight-bold">{{ ageCategory.name }}</h3>
          <hr class="hr mt-0 mb-0 bg-primary">
          <div class="js-list-parent-div">
            <ul class="js-list list-unstyled">
              <li class="team-item d-flex justify-content-between" v-for="team in ageCategory.teams">
                {{ team.name }} ({{ team.country.country_code }}) <span :class="'flag-icon flag-icon-' + team.country.country_flag"></span>
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
      historyYearChanged() {
        this.getHistoryAgeCategories(this.currentYearId);
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
