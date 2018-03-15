<template>
  <div>
    <div>
      <table class="table table-hover table-bordered" v-if="competitionList.length > 0">
        <thead>
          <tr>
            <th class="text-center">{{ $t('tournament.category_name') }}</th>
            <th class="text-center">{{ $t('tournament.age_category') }}</th>
            <th class="text-center">{{ $t('tournament.placings') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="competition in competitionList">
            <td>{{ competition.group_name }}</td>
            <td class="text-center">{{ competition.category_age }}</td>
            <td class="text-center">
              <a @click="getAgeCategoryDetail(competition.id)" class="text-primary" href="#" data-toggle="modal" data-target="#final_placing_modal">
                <u>{{ $t('tournament.view_placings') }}</u>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
      <span v-else>{{ $t('no_information_available') }}</span>
    </div>
    <!-- View final placings modal -->
    <div class="modal" id="final_placing_modal" tabindex="-1" role="dialog" aria-labelledby="final_placing_modal" style="display: none;" aria-hidden="true" data-animation="false">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title">{{ $t('tournament.final_placings') }}</h5>
             <button type="button" class="close" data-dismiss="modal" :aria-label="$t('tournament.close')">
             <span aria-hidden="true">×</span>
             </button>
          </div>
          <div class="modal-body modal-fixed-height">
              <div class="form-group row mb-0" v-for="position in positionData">
                <div class="col-sm-3 form-control-label font-weight-bold border">{{ $t('tournament.placing') }} {{ position.pos }}</div>
                <div class="col-sm-9 form-control-label"> {{ position.team_name }}</div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" @click="closeModal()">{{ $t('tournament.cancel') }}</button>
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
