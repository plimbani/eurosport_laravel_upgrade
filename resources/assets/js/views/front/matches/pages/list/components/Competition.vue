<template>
  <div>
    <a @click="setCurrentTabView(currentTabView)" href="javascript:void(0)">
      <i aria-hidden="true" class="fa fa-angle-double-left"></i>Back to match list
    </a>
    <div>
      <select
        v-on:change="onChangeDrawDetails"
        v-model="DrawName">
          <!-- <option value="">Select</option> -->
          <option
          v-for="option in drawList"
          v-bind:value="option"
          >{{option.name}}
          </option>
        </select>
    </div>
  </div>
</template>

<script type="text/babel">
  import Matches from './Matches.vue';
  import MatchList from '../../../../../../api/matchlist.js';

  export default {
    props: ['matches', 'competitionDetail'],
    data() {
      return {
        competitionName: '',
        competitionList: [],
        competitionRound: 'Round Robin',
      };
    },
    created() {

    },
    mounted() {
      this.getCompetitions();
    },
    filters: {

    },
    computed: {

    },
    components: {

    },
    methods: {
      getCompetitions() {
        var cm = this;
        MatchList.getAllDraws(tournamentData.id).then(
        (response)=> {
          if(response.data.status_code == 200) {
            vm.competitionList = response.data.data;
            vm.competitionList.map(function(competition, key) {
              if(competition.actual_competition_type == 'Elimination') {
                competition.name = _.replace(competition.name, '-Group', '');
                return competition;
              }
            })

            var uniqueArray = response.data.data.filter(function(item, pos) {
              if(item['id'] == currDId)
              {
               drawname1 = item
               round = item['competation_type']
              }
            }, {});
            this.competitionName = drawname1;
            this.competitionRound = round;
            this.refreshStanding();
          }
        },
        (error) => {
          alert('Error in Getting Draws')
        }
      );
      },
    }
  };
</script>
