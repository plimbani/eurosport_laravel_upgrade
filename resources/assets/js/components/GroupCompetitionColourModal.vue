<template>
    <div class="modal fade bg-modal-color refdel" id="group_competition_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ $lang.pitch_planner_group_colours_title }}</h5>
            <div class="d-flex align-items-center">
              <button type="button" class="close" @click="closeModal()">
                <span aria-hidden="true">×</span>
              </button>
            </div>
          </div>
          <form method="post" class="js-group-competition-colour-modal-form">
            <div class="modal-body" id="pitch_model_body">
                
                <div class="form-group row" v-for="(competition, key) in competitions">
                  <div class="col-sm-3 form-control-label">{{ competition.name }}*</div>
                  <div class="col-sm-6">
                    <input type="text" class="js-colorpicker" :name="`competitionsColor[${key}]`" v-model="competitionsColorData[competition.id]" @input="competitionsColorData[competition.id]" :class="{'form-control demo minicolors-input' : true }" :data-competition-id="competition.id" />
                  </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger"  @click="closeModal()">{{$lang.manual_ranking_cancel}}</button>
                <input type="submit" class="btn btn-primary" v-bind:value="$lang.manual_ranking_save" />
            </div>
          </form>
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
            competitionsColorData: {},
          }
        },
        created: function() {
          // Plugin.initPlugins(['colorPicker']);
        },
        mounted() {
          this.$root.$on('getCategoryCompetitions', this.getCategoryCompetitions);
          this.initiazeGroupCompetitionValidation();
          // this.$validator.updateDictionary(this.errorMessages);
        },
        methods: {
            getCategoryCompetitions() {
              let vm = this;
              let filterValue = this.$store.state.Tournament.tournamentFiler.filterValue
              let data = {'ageGroupId': filterValue.id, 'competationType': 'Round Robin', 'competationRoundNo': 'Round 1' }
              this.competitionsColorData = {};
              this.competitions = [];

              Tournament.getCategoryCompetitions(data).then(
                  (response)=> {
                    if(response.data.status_code == 200) {
                      this.competitions = response.data.competitions;
                      _.map(response.data.competitions, function(o) {
                        vm.competitionsColorData[o.id] = o.color_code ? o.color_code : '';
                      });
                      setTimeout(function(){
                        $('.js-colorpicker').minicolors({
                          animationSpeed: 50,
                          animationEasing: 'swing',
                          format : 'hex',
                          theme: 'bootstrap',
                          position: 'bottom right',
                          change : function() {
                            let inputObj = $(this);
                            let competitionId = inputObj.data('competition-id');
                            // vm.$set(vm.competitionsColorData, competitionId, inputObj.val());
                            vm.competitionsColorData[competitionId] = inputObj.val();
                            return;
                          }
                        });
                        $('[name*="competitionsColor"]').each(function () {
                          $(this).rules('add', {
                              required: true,
                              pattern: /^#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/i,
                              messages: {
                                required: "This field is required.",
                                pattern: "This field is invalid."
                              }
                          });
                        });
                      }, 500);
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
            initiazeGroupCompetitionValidation() {
              let vm = this;
              $('.js-group-competition-colour-modal-form').validate({
                  ignore: [],
                  debug: false,
                  focusInvalid: false,
                  messages: {
                  },
                  rules: {
                  },
                  errorPlacement: function (error, element) { // render error placement for each input type     
                    element.parent().parent().append(error);
                  },
                  submitHandler: function (form) {
                    Tournament.saveCategoryCompetitionColor(vm.competitionsColorData).then(
                          (response)=> {
                            if(response.data.status_code == 200) {                    
                              vm.competitionsColorData = {};
                              vm.$root.$emit('resetPitchesOnCategoryColorSave','','');
                              vm.closeModal();
                            }
                          },
                          (error) => {
                            alert('Error in getting category competitions')
                          }
                        )
                  }
              });
            },
        }
    }
</script>
