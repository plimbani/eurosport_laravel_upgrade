<template>
  <div class="row justify-content-end">
    <div class="col-sm-12">
      <h6 class="font-weight-bold">{{$lang.teams_filter}}</h6>
    </div>
    <div class="col-sm-12">
      <div class="row">
        <div class="col-md-10">
          <div class="row align-items-center">
            <div class="col-md-8">
              <form  class="form-inline summary-matches-filter">
                <div class="form-group" v-if="section!='scheduleResult'">
                  <label class="radio-inline control-label">
                    <div class="checkbox">
                      <div class="c-input">
                          <input type="radio" id="age_category" name="filter" value="age_category"
                          @click="getDropDownData('age_category')" class="euro-radio mr-2">
                          <label for="age_category">{{$lang.tournament_filter_age_category}}</label>
                      </div>
                    </div>
                  </label>
                </div>
                <div class="form-group" v-if="section =='scheduleResult'">
                  <label class="radio-inline control-label">
                      <div class="checkbox">
                        <div class="c-input">
                          <input type="radio" id="competation_group" name="filter" value="competation_group"
                          @click="getDropDownData('competation_group')" class="euro-radio mr-2">
                          <label for="competation_group">{{$lang.tournament_filter_age_category_match}}</label>
                        </div>
                      </div>
                  </label>
                </div>
                <div class="form-group" v-if="section=='pitchPlanner' || section=='scheduleResult'">
                  <label class="radio-inline control-label">
                      <div class="checkbox">
                        <div class="c-input">
                          <input type="radio" id="location" name="filter" value="location"
                             @click="getDropDownData('location')" class="euro-radio mr-2">
                          <label for="location">{{$lang.teams_location}}</label>
                        </div>
                      </div>
                  </label>
                </div>
                <div class="form-group" v-if="section == 'scheduleResult' || section =='teams'">
                  <label class="radio-inline control-label">
                      <div class="checkbox">
                        <div class="c-input">
                          <input type="radio" id="team" name="filter" value="team"
                            @click="getDropDownData('team')" class="euro-radio mr-2">
                          <label for="team">{{$lang.teams_team}}</label>
                        </div>
                      </div>
                  </label>
                </div>
                <div class="form-group" v-if="section=='teams'">
                  <label class="radio-inline control-label">
                      <div class="checkbox">
                        <div class="c-input">
                          <input type="radio" id="country" name="filter" value="country" @click="getDropDownData('country')" class="euro-radio mr-2">
                          <label for="country">{{$lang.teams_country}}</label>
                        </div>
                      </div>                      
                  </label>
                </div>
              </form>
            </div>
            <div class="col-md-4">
              <select :class="'form-control  ls-select2 '+filterKey" v-if="filterKey == 'competation_group'">
                <option value="" v-if="filterKey != 'age_category'">Select</option>
                <option   
                v-for="option in options" v-bind:data-val="setOption(option)"  v-bind:id="option.id" v-bind:value="setOption(option)" :class="option.class" >  {{ option.name }}</option>
              </select>
              <select  class="form-control ls-select2" v-model="dropDown" @change="setFilterValue()" v-else>
                <option value="" v-if="filterKey != 'age_category'">Select</option>
                <option  :value="option.id" v-for="option in options"   v-bind:value="option" >{{option.name}}</option>
              </select>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group mr-0">
            <label class="control-label w-100 mr-0">
              <a href="javascript:void(0)" class="btn btn-secondary w-100" @click="clearFilter()">{{$lang.teams_clear}}</a>
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Tournament from '../api/tournament.js'
export default {
  data() {
    return {
      dropDownData: [],
      dropDown: '',
      options:[],
      selectMsg: 'Select a Team',
      filterKey: 'team',
      filterValue: ''
    }
  },
  computed: {
    activePath() {
      return this.$store.state.activePath
    }
  },
  props:['section'],
  created() {
    this.$root.$on('getMatchesByFilter', this.getMatchesByFilter);
  },
  beforeCreate: function() {
    // Remove custom event listener
    this.$root.$off('getMatchesByFilter');
  },
  mounted() {
    // By Default Called with Team
      this.getDropDownData('competation_group')
      $('#competation_group').prop("checked",true)
  },
  methods: {
    setOption(opt) {
      return JSON.stringify(opt);
    },
    clearFilter(){
      this.dropDown = ''
      this.setFilterValue()
      $('.competation_group').select2().val(null).trigger("change");
      $('#age_category').trigger('click')
      if (this.section == 'scheduleResult' ){
        this.getDropDownData('competation_group');
      } else {
        $('#competation_group').prop("checked",true);
        this.getDropDownData('age_category');
      }
      
    },
    holdingName(group) {
      // return group.name;
      let grpName =group.name.split("-");
      grpName = grpName.splice(2,grpName.length);
      grpName =grpName.join('-');
      
      return grpName;
    },
    setFilterValue() {
      // return false;

      this.filterValue = this.dropDown
      window.filterValue = this.dropDown;
      let tournamentFilter = {'filterKey': this.filterKey, 'filterValue':this.filterValue, 'filterDependentKey': '', 'filterDependentValue': ''}
      this.$store.dispatch('setTournamentFilter', tournamentFilter);
      if(this.activePath == 'teams_groups'){
        this.$root.$emit('getTeamsByTournamentFilter',this.filterKey,this.filterValue);
      }else if(this.activePath == 'pitch_planner'){
        this.$root.$emit('getPitchesByTournamentFilter',this.filterKey,this.filterValue);
      } else {
        this.$root.$emit('getMatchByTournamentFilter',this.filterKey,this.filterValue);
      }
    },
    setFilterForAgeAndGroup() {
      var matchFilterKey = 'competation_group';
      this.filterValue = this.dropDown;
      
      // return false
      var filterCompGroup = {'id' :this.filterValue};
      var tournamentFilter = {'filterKey': this.filterKey, 'filterValue':this.filterValue, 'filterDependentKey': '', 'filterDependentValue': ''}
      this.$store.dispatch('setTournamentFilter', tournamentFilter);
      if(this.dropDown.class == 'age'){
        matchFilterKey = 'competation_group_age';
      }
     
      this.$root.$emit('getMatchByTournamentFilter',matchFilterKey,this.filterValue);
    },
    getDropDownData(tourament_key) {
      $('.competation_group').select2('destroy');
      this.dropDown = ''
      let tournamentId = this.$store.state.Tournament.tournamentId
      // Here Call method to get Tournament Data for key
      this.filterKey = tourament_key;
      window.filterKey = tourament_key;
      let tournamentData = {'tournamentId':tournamentId,
      'keyData':tourament_key,'type':this.section,'cat':'age'}
      Tournament.getDropDownData(tournamentData).then(
        (response) => {
          // here we fill the options
          switch(tourament_key){
            case 'country':
              this.selectMsg = 'Select'
              break
            case 'team':
              this.selectMsg = 'Select'
              break
            case 'age_category':
              this.selectMsg = 'Select'
              break
            case 'location':
              this.selectMsg = 'Select'
              break
          }
          this.options = response.data.data
          let newOption = [];
          if(tourament_key == 'competation_group'){
            $('.competation_group').select2().val(null).trigger("change");
      
            _.map(response.data.data, function(opt){
              newOption.push({"id":opt.id,"name": opt.name,"class":"age","data":opt.id});
              _.map(opt.competition, function(comp){
                 let grpName =comp.name.split("-");
                      grpName = grpName.splice(grpName.length-2,2);
                      grpName =grpName.join('-');
        
                newOption.push({"id":comp.id, "name": grpName, "class":"group", "data":comp});
              });

            });
            $('.competation_group').select2({
                minimumResultsForSearch: Infinity,
            });
            var vm =this;
            $('.competation_group').on("select2:select", function (e) {
              var selVal = $(this).val();
              vm.dropDown = selVal != '' ? JSON.parse(selVal) : '';
              vm.setFilterForAgeAndGroup();
            });
            this.options =  newOption;
          }
          
          if(tourament_key == 'age_category'){
            this.dropDown = ""
          }

          this.setFilterValue();
        },
        (error) => {
        }
      )
    },
    getMatchesByFilter() {
      this.$root.$emit('getMatchByTournamentFilter',this.filterKey,this.filterValue);
    },
  }
}
</script>