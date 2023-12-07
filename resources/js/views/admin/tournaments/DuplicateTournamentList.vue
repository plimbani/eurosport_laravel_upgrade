<template>
    <div>
        <div class="d-flex align-items-center justify-content-end">
        </div>
        <div class="tab-content">
            <div class="card">
                <div class="card-block">
                    <div class="row d-flex flex-row align-items-center mb-3 ">
                      <div class="col-md-5">                            
                      </div>
                      <div class="col-md-7">
                        <div class="row align-items-center">
                          <div class="col-12">
                            <div class="row justify-content-end">
                              <div class="col-md-5">
                                <input type="text" class="form-control"
                                      v-on:keyup="searchTournamentData" v-model="tournamentListSearch"
                                      placeholder="Search tournament name">
                              </div>
                              <div class="col-md-2">
                                <button type="button" class="btn btn-primary w-100" @click='clear()'>{{$lang.duplicate_tournament_copy_clear_button}}</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row d-flex flex-row align-items-center">
                        <div class="col-md-12">
                            <table class="table add-category-table">
                                <thead>
                                    <tr>
                                        <th>{{$lang.duplicate_tournamet_tournament_name}}</th>
                                        <th>{{$lang.duplicate_tournamet_tournament_start_date}}</th>
                                        <th>{{$lang.duplicate_tournamet_tournament_end_date}}</th>
                                        <th>{{$lang.duplicate_tournamet_maximum_teams}}</th>
                                        <th style="text-align: center;">{{$lang.duplicate_tournamet_action}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <tr class="" v-for="tournament in paginated('tournamentpagination')">
                                    <td>{{ tournament.name }}</td>
                                    <td>{{ tournament.start_date }}</td>
                                    <td>{{ tournament.end_date }}</td>
                                    <td>{{ tournament.maximum_teams }}</td>
                                    <td align="center">
                                      <a class="text-primary" href="javascript:void(0)" @click="duplicateTournament(tournament.id, tournament.name)">
                                        <i class="fas fa-copy"></i>
                                      </a>
                                    </td>
                                  </tr>
                                </tbody>
                            </table>
                            <paginate name="tournamentpagination"
                             :list="tournamentList.tournamentData" ref="paginator" :per="no_of_records"  class="paginate-list">
                            </paginate>
                            <div class="row d-flex flex-row align-items-center">
                              <div class="col page-dropdown">
                                <select class="form-control ls-select2" name="no_of_records" v-model="no_of_records">
                                  <option v-for="recordCount in recordCounts" v-bind:value="recordCount">
                                      {{ recordCount }}
                                  </option>
                                </select>
                              </div>
                              <div class="col">
                                <span v-if="$refs.paginator">
                                  Viewing {{ $refs.paginator.pageItemsCount }} results
                                </span>
                              </div>
                              <div class="col-md-6">
                                <paginate-links for="tournamentpagination"
                                  :show-step-links="true" :async="true" :limit="2" class="mb-0">
                                </paginate-links>
                              </div>
                            </div>
                        </div>
                        <div v-if="tournamentList.tournamentDataCount == 0" class="col-md-12">
                            <h6 class="block text-center">No record found</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <DuplicateTournamentModal :copyTournamentId="copyTournamentId" :copyTournamentName="copyTournamentName"></DuplicateTournamentModal>
    </div>
</template>
<script type="text/babel">
    import Tournament from '../../../api/tournament.js'
    import DuplicateTournamentModal from '../../admin/tournaments/DuplicateTournamentModal.vue'
    import VuePaginate from 'vue-paginate'
    export default {
      data() {
          return {
              copyTournamentId: '',
              copyTournamentName: '',
              tournamentListSearch: '',
              paginate: ['tournamentpagination'],
              no_of_records: 20,
              recordCounts: [5,10,20,50,100],
          }
      },
      props: ['tournamentList'],
      components: {
          DuplicateTournamentModal
      },
      mounted() {
      },
      created: function() {
         this.$root.$on('displayTournamentList', this.displayTournamentList);
      },
      beforeCreate: function() {
        this.$root.$off('displayTournamentList');
      },
      methods: {
        clear() {
          this.tournamentListSearch = ''
          this.$root.$emit('clearSearch')
        },
        searchTournamentData() {
          this.$root.$emit('setSearch', this.tournamentListSearch);
        },
        searchTypeData() {
          this.searchTournamentData();
        },
        duplicateTournament(id, name) {
          this.copyTournamentId = id;
          this.copyTournamentName = name;
          $('#duplicateTournament').modal('show')
        },
        displayTournamentList() {
          this.searchTournamentData();
        }
      }
    }
</script>
