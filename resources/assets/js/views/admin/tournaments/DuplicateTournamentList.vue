<template>
    <div>
        <div class="d-flex align-items-center justify-content-end">
        </div>
        <div class="tab-content">
            <div class="card">
                <div class="card-block">
                    <div class="row d-flex flex-row align-items-center">
                        <div class="col-md-12">
                            <table class="table add-category-table">
                                <thead>
                                    <tr>
                                        <th>{{$lang.duplicate_tournamet_tournament_name}}</th>
                                        <th>{{$lang.duplicate_tournamet_tournament_start_date}}</th>
                                        <th>{{$lang.duplicate_tournamet_tournament_end_date}}</th>
                                        <th>{{$lang.duplicate_tournamet_maximum_teams}}</th>
                                        <th>{{$lang.duplicate_tournamet_action}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <tr class="" v-for="option in options">
                                    <td>{{ option.name }}</td>
                                    <td>{{ option.start_date }}</td>
                                    <td>{{ option.end_date }}</td>
                                    <td>{{ option.maximum_teams }}</td>
                                    <td>  
                                      <a class="text-primary" href="javascript:void(0)" @click="duplicateTournamanet(option.id)">
                                        <i class="fas fa-copy"></i>
                                      </a>
                                    </td>
                                  </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <DuplicateTournamentModal></DuplicateTournamentModal>
    </div>
</template>
<script type="text/babel">
    import Tournament from '../../../api/tournament.js'
    import DuplicateTournamentModal from '../../admin/tournaments/DuplicateTournamentModal.vue'
    export default {
        data() {
            return {
                options: []
            }
        },
        components: {
            DuplicateTournamentModal
        },
        mounted() {
          Tournament.getAllTournaments().then(
            (response) => {
              this.options = response.data.data
            },
            (error) => {
            }
          )
        },
        methods: {
          duplicateTournamanet(id) {
            console.log(id);
            $('#duplicateTournament').modal('show')
             
          },
        }
    }
</script>
