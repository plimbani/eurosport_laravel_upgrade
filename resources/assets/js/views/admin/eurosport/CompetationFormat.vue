<template>
	<div class="tab-content">
		<div class="card">
      <div class="card-block">
          <h6 class="mt4"><strong>{{$lang.competation_age_categories}}</strong></h6>
          <competationFormatList></competationFormatList>
      </div>
		</div>
        <!--<div class="row">
            <div class="col-md-12">
                <div class="pull-left">
                    <button class="btn btn-primary" @click="backward()"><i class="fa fa-angle-double-left" aria-hidden="true" ></i>{{$lang.competation_button_back}}</button>
                </div>
                <div class="pull-right">
                  <button class="btn btn-primary" @click="next()"> <i class="fa fa-angle-double-right" aria-hidden="true" ></i>{{$lang.competation_button_next}}</button>
                </div>
            </div>
        </div>-->
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
      let currentNavigationData = {activeTab:'competation_format', currentPage: 'Competition Format'}
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
