<template>
  <div class="modal fade bg-modal-color displayGraphicImage" id="displayGraphicImage" tabindex="-1" role="dialog" aria-labelledby="displaygraphicLabel">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="displaygraphicLabel">Match Schedule {{ templateName ? '– Template ' + templateName : '' }}</h5>
            <div class="d-flex align-items-center">
              <button type="button" class="btn btn-primary mr-4" @click="generateMatchSchedulePrint()">Print</button>
              <button type="button" class="close js-close-btn" @click="closeViewGraphicImage()">
                <span>×</span>
              </button>
            </div>
        </div>
        <div class="modal-body">
          <form name="ageCategoryName">
            
              <div v-html="graphicHtml"></div>
           
          </form>
        </div>
       </div>
    </div>
  </div>
</template>
<script type="text/babel">
    import Tournament from '../api/tournament.js';
    export default {
      props: ['sectionGraphicImage', 'categoryId', 'tournamentTemplateId', 'tournamentId', 'tournamentFormat', 'competitionType', 'numberOfTeams'],
      data() {
        return {
          templateName: '',
          graphicHtml: '',
        }
      },
      created: function() {
        this.$root.$on('getTemplateGraphic', this.getTemplateGraphic);
        this.$root.$on('getTemplateGraphicOfLeague', this.getTemplateGraphicOfLeague);
      },
      beforeCreate: function() {
        this.$root.$off('getTemplateGraphic');
        this.$root.$off('getTemplateGraphicOfLeague');
      },
      mounted() {
        var sectionGraphicImage = this.sectionGraphicImage;
        $('#displayGraphicImage').on('hidden.bs.modal', function () {
          if(sectionGraphicImage === 'AgeCategoryModal') {
            $('body').addClass('modal-open');
          }
        });
      },
      methods: {
        closeViewGraphicImage() {
          $('#displayGraphicImage').modal('hide');
        },
        getTemplateGraphic(ageCategoryId, templateId) {
          $("body .js-loader").removeClass('d-none');
          let templateData = {'ageCategoryId': ageCategoryId, 'templateId': templateId};
          Tournament.getTemplateGraphic(templateData).then(
            (response)=> {
              this.templateName = response.data.data.templateName;
              this.graphicHtml = response.data.data.graphicHtml;
              $("body .js-loader").addClass('d-none');
            },
            (error) => {
              alert('Error in getting category competitionassss')
            }
          );
        },
        getTemplateGraphicOfLeague(numberOfTeams) {
          $("body .js-loader").removeClass('d-none');
          let templateData = {'number_of_teams': numberOfTeams};
          Tournament.getTemplateGraphicOfLeague(templateData).then(
            (response)=> {
              this.templateName = '';
              this.graphicHtml = response.data.data.graphicHtml;
              $("body .js-loader").addClass('d-none');
            },
            (error) => {
              alert('Error in getting category competitions')
            }
          );
        },
        generateMatchSchedulePrint() {
          let templateData = {'ageCategoryId': this.categoryId, 'templateId': this.tournamentTemplateId, tournamentId: this.tournamentId, 'tournamentFormat': this.tournamentFormat, 'competitionType': this.competitionType, 'numberOfTeams': this.numberOfTeams};
          let matchSchedulePrintWindow = window.open('', '_blank');
          Tournament.getSignedUrlForMatchSchedulePrint(templateData).then(
            (response) => {
              matchSchedulePrintWindow.location = response.data;
            },
            (error) => {

            }
          )
        }
      }
    }
</script>
