<template>
	<div class="tab-content">
		<div class="card">
      <div class="card-block">
          <h6 class="mt4 fieldset-title"><strong>{{$lang.competation_age_categories}}</strong></h6>
          <competationFormatList></competationFormatList>
      </div>
		</div>
    <div class="row">
      <div class="col-md-12">
        <div class="pull-right">
            <button class="btn btn-primary" :class="{'is-disabled': this.$store.state.Tournament.competationList.length == 0 }" @click="next()">{{$lang.tournament_button_next}}&nbsp;&nbsp;&nbsp;<i class="fas fa-angle-double-right" aria-hidden="true"></i></button>
        </div>
      </div>
    </div>
	</div>
</template>

<script type="text/babel">
import CompetationFormatList from '../../../components/CompetationFormatList.vue'
export default {
  components: {
      CompetationFormatList
  },
  data() {
    return {
      type : 'edit'
    }
  },
  mounted() {

    // Here if tournament Id is Not Set Redirect to Login page
    let tournamentId = this.$store.state.Tournament.tournamentId
    this.$store.dispatch('SetPitches',tournamentId);
    this.$store.dispatch('SetVenues',tournamentId);
    if(tournamentId == null || tournamentId == '' || tournamentId == undefined) {
      toastr['error']('Please Select Tournament', 'Error');
      this.$router.push({name: 'welcome'});
    } else {
      // Means Set Here
      let currentNavigationData = {activeTab:'competition_format', currentPage: 'Competition Formats'}
      this.$store.dispatch('setActiveTab', currentNavigationData)
      this.$store.dispatch('SetPitches',tournamentId);
      this.$store.dispatch('SetVenues',tournamentId);
    }
  },
  methods: {
    next() {
      let currentNavigationData = {activeTab:'pitch_capacity', currentPage: 'Pitch Capacity'}
      this.$store.dispatch('setActiveTab', currentNavigationData)
      this.$router.push({name:'pitch_capacity'})
    },
  }
}
</script>
