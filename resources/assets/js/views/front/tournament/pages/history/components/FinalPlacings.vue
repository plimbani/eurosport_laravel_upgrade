<template>
  <div>
    <h2 class="text-center mb-4">{{ $t('tournament.current_placings') }}</h2>
    <div class="table-responsive" v-if="competitionList.length > 0">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">{{ $t('tournament.category_name') }}</th>
            <th scope="col">{{ $t('tournament.age_category') }}</th>
            <th scope="col">{{ $t('tournament.placings') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="competition in competitionList">
            <td>{{ competition.group_name }}</td>
            <td>{{ competition.category_age }}</td>
            <td>
              <a @click="getAgeCategoryDetail(competition.id)" href="#" data-toggle="modal" data-target="#final_placing_modal">
                <u>{{ $t('tournament.view_placings') }}</u>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="d-flex my-4"></div>
    <!-- View final placings modal -->
    <div class="modal" id="final_placing_modal" tabindex="-1" role="dialog" aria-labelledby="final_placing_modal" style="display: none;" aria-hidden="true" data-animation="false">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title font-weight-bold">{{ $t('tournament.final_placings') }}</h5>
             <button type="button" class="close" data-dismiss="modal" :aria-label="$t('tournament.close')">
             <span aria-hidden="true">×</span>
             </button>
          </div>
          <div class="modal-body modal-fixed-height">
              <div class="form-group row mb-0" v-for="position in positionData">
                <div class="col-sm-3 form-control-label border-0">
                  <h6 class="font-weight-bold">{{ $t('tournament.placing') }} {{ position.pos }}</h6>
                </div>
                <div class="col-sm-9 form-control-label"> 
                  <h6>{{ position.team_name }}</h6>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="closeModal()">{{ $t('tournament.cancel') }}</button>
          </div>
         </div>
      </div>
    </div>
  </div>
</template>

<script type="text/babel">
  import Tournament from '../../../../../../api/frontend/tournament.js';

  export default {
    data() {
      return {
        competitionList: competitionList,
        positionData: [],
      }
    },
    mounted() {
    },
    methods: {
      getAgeCategoryDetail(ageCategoryId) {
        let ageCategoryData = {'ageCategoryId': ageCategoryId};
        this.positionData = [];
        Tournament.getPlacingsData(ageCategoryData).then(
          (response) => {
            this.positionData = response.data.data
          },
          (error) => {
          }
        )
      },
      closeModal() {
        $('#final_placing_modal').modal('hide');
        return false;
      },
    },
  };
</script>
