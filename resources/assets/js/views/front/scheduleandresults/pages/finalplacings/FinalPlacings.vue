<template>
  <div>
    <div class="table-responsive">
      <table class="table table-hover table-bordered">
        <thead class="no-border">
          <tr>
            <th scope="col">Category name</th>
            <th scope="col">Age category</th>
            <th scope="col">Placings</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="competition in competitionList">
            <td>{{ competition.group_name }}</td>
            <td>{{ competition.category_age }}</td>
            <td>
              <a @click="getAgeCategoryDetail(competition.id)" href="#" data-toggle="modal" data-target="#final_placing_modal">
                <u>View placings</u>
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
             <h5 class="modal-title font-weight-bold">Final placings</h5>
             <button type="button" class="close" data-dismiss="modal">
             <span aria-hidden="true">×</span>
             </button>
          </div>
          <div class="modal-body modal-fixed-height">
              <div class="form-group row mb-0" v-for="position in positionData">
                <div class="col-sm-3 form-control-label border-0">
                  <h6 class="font-weight-bold">Placing {{ position.pos }}</h6>
                </div>
                <div class="col-sm-9 form-control-label">                   
                  <h6 class="d-flex">
                    <span :class="'flag-icon flag-icon-' + position.team_flag"></span>
                    <span class="ml-1">{{ position.team_name }}</span>
                  </h6>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="closeModal()">Cancel</button>
          </div>
         </div>
      </div>
    </div>
  </div>
</template>

<script type="text/babel">
  import Tournament from '../../../../../api/frontend/tournament.js';

  export default {
    data() {
      return {
        positionData: [],
        competitionList: [],
      }
    },
    props: ['tournamentData'],
    mounted() {
      this.getAllCategoriesData();
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
      getAllCategoriesData() {
        let data = {'tournament_id': this.tournamentData.id};
        Tournament.getAllCategoriesData(data).then(
        (response)=> {
          this.competitionList = response.data.data;
        },
        (error)=> {}
        )
      },
      closeModal() {
        $('#final_placing_modal').modal('hide');
        return false;
      },
    },
  };
</script>
