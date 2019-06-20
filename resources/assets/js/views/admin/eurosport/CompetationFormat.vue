<template>
	<div class="tab-content">
		<div class="card">
      <div class="card-block">
          <h6 class="mt4 fieldset-title"><strong>{{$lang.competation_age_categories}}</strong></h6>
          <competationFormatList></competationFormatList>
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

      if ($(document).height() > $(window).height()) {
         $('.site-footer').removeClass('sticky');
      } else {
         $('.site-footer').addClass('sticky');
      }

  },
  methods: {


  }
}
</script>
