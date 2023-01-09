<template>
    <div class="modal bg-modal-color refdel" id="unschedule_fixtures_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ $lang.unschedule_age_categories_modal_title }}</h5>
            <div class="d-flex align-items-center">
              <button type="button" class="close" @click="closeModal()">
                <span aria-hidden="true">×</span>
              </button>
            </div>
          </div>
          <form method="post" class="js-automatic-pitch-planning-modal-form" id="automatic_pitch_planning">
            <div class="modal-body m-h-214" id="pitch_model_body">

                <div class="form-group row">
                    <div class="col-sm-6" v-for="item in ageCategories">
                        <div class="checkbox">
                            <div class="c-input">
                                <input type="checkbox" v-bind:id="`age-category-${item.id}`" class="euro-checkbox" v-bind:value="item.id" v-model="selectedAgeCategories" />
                                <label v-bind:for="`age-category-${item.id}`">{{item.group_name}} ({{item.category_age}})</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row" v-if="selectAgeCategoryError">
                    <div class="col-sm-12">
                        <p class="text-danger mb-0">Please select at least one age category.</p>
                    </div>
                </div>
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" @click="closeModal()">{{$lang.unschedule_age_categories_modal_cancel}}</button>
              <button type="button" class="btn btn-primary" @click="confirmUnschedule()">{{$lang.unschedule_age_categories_modal_confirm}}</button>
            </div>
          </form>
        </div>
      </div>

      <UnscheduleFixturesConfirmationModal @confirmed="unscheduleFixtures()"></UnscheduleFixturesConfirmationModal>
    </div>
</template>

<script>
import _ from 'lodash';
import Tournament from '../api/tournament.js'
import UnscheduleFixturesConfirmationModal from './UnscheduleFixturesConfirmationModal.vue'

    export default  {
        props: [],
        components: { UnscheduleFixturesConfirmationModal },
        data() {
          return {
            selectedAgeCategories: [],
            ageCategories: [],
            selectAgeCategoryError: false,
          }
        },
        created: function() {
          this.getAgeCategories();
        },
        mounted() {
          
        },
        computed: {
          
        },
        methods: {
            getAgeCategories() {
              let TournamentData = {'tournament_id': this.$store.state.Tournament.tournamentId}
              Tournament.getCompetationFormat(TournamentData).then(
              (response) => {
                this.ageCategories = response.data.data
              },
              (error) => {
              })
            },
            
            confirmUnschedule() {
                this.selectAgeCategoryError = false;
                if(this.selectedAgeCategories.length === 0) {
                  this.selectAgeCategoryError = true;
                  return false;
                }
                
                $('#unscheduled_fixtures_confirmation_modal').modal('show');
            },

            unscheduleFixtures() {
                
              let vm = this;
              $("body .js-loader").removeClass('d-none');

              var data = {
                tournament_id: this.$store.state.Tournament.tournamentId,
                age_categories: this.selectedAgeCategories
              };

              Tournament.unscheduleFixturesByAgeCategory(data).then(
              (response) => {
                  if(response.data.status_code == '200') {
                      $("body .js-loader").addClass('d-none');
                      $('#unschedule_fixtures_modal').modal('hide')
                      toastr.success('Fixtures are unscheduled successfully', 'Fixtures Unscheduled', {timeOut: 5000});
                      
                      vm.$emit('confirmed');
                  }
              })

            },
            
            closeModal() {
              this.selectedAgeCategory = '';
              this.resetForm();
              $('#unschedule_fixtures_modal').modal('hide');
              return false
            },
            
            resetForm() {
              this.selectedAgeCategories = [];
              this.selectAgeCategoryError = false;
              //this.clearErrorMsgs();
              $('.js-available-time-error-message').hide();
            },
        }
    }
</script>