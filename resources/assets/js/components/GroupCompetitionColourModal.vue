<template>
    <div class="modal fade bg-modal-color refdel" id="group_competition_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <div class="d-flex align-items-center">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal()">
                <span aria-hidden="true">×</span>
              </button>
            </div>
          </div>
          <div class="modal-body" id="pitch_model_body">
          <form method="post" class="js-group-competition-colour-modal-form">
            
            <div class="form-group row" :class="{'has-error': errors.has('competition_color') }" v-for="(competition, key) in competitions">
              <div class="col-sm-3 form-control-label">{{ competition.name }}*</div>
              <div class="col-sm-6">
                <div class="input-group js-colorpicker">              
                  <input type="text" :name="`competition_color${key}`" v-model="competitionsColorData[competition.id]" v-validate="'required'" :class="{'is-danger': errors.has('competition_color'), 'form-control' : true }" />
                  <span class="input-group-addon"><i></i></span>
                </div>
                <span class="help is-danger" v-show="errors.has(`competition_color${key}`)">{{$lang.manual_ranking_team_required}}</span>
              </div>
            </div>
            
          </form>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger"  @click="closeModal()">{{$lang.manual_ranking_cancel}}</button>
              <button type="button" class="btn btn-primary" @click.prevent="saveCompetitionColour()">{{$lang.manual_ranking_save}}</button>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
import Tournament from '../api/tournament.js'
import _ from 'lodash';
    export default  {
        props: [],
        data() {
          return {
            competitions: [],
            competitionsColorData: {}
          }
        },
        created: function() {
          // Plugin.initPlugins(['colorPicker']);
        },
        mounted() {
          this.$root.$on('getCategoryCompetitions', this.getCategoryCompetitions);
        },
        methods: {
            getCategoryCompetitions() {
              let vm = this;
              let filterValue = this.$store.state.Tournament.tournamentFiler.filterValue
              let data = {'ageGroupId': filterValue.id, 'competationType': 'Round Robin', 'competationRoundNo': 'Round 1' }

              Tournament.getCategoryCompetitions(data).then(
                  (response)=> {
                    if(response.data.status_code == 200) {
                      this.competitions = response.data.competitions;
                      _.map(response.data.competitions, function(o) {
                        vm.competitionsColorData[o.id] = o.color_code ? o.color_code : '';
                      });
                      setTimeout(function(){
                        Plugin.initPlugins(['colorPicker']);
                      }, 1000);
                    }
                  },
                  (error) => {
                    alert('Error in getting category competitions')
                  }
                )
            },
            closeModal() {
                $('#group_competition_modal').modal('hide')
                return false
            },
            saveCompetitionColour() {
              // let vm = this;
              this.$validator.validateAll().then(() => {
                Tournament.saveCategoryCompetitionColor(this.competitionsColorData).then(
                    (response)=> {
                      if(response.data.status_code == 200) {
                        
                      }
                    },
                    (error) => {
                      alert('Error in getting category competitions')
                    }
                  )
              }).catch(() => {});
            },
        }
    }
</script>
