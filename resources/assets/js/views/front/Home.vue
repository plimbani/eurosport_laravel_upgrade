<template>
    <section class="section section-hero-area">
         <a href="" @click.prevent="AllTournament" v-if="currentScheduleView != 'allPublishedTournaments'">Home</a><br>
        <component :is="currentScheduleView"></component>
        <!--<allPublishedTournaments>
        </allPublishedTournaments>-->
        
    </section>
</template>
<script type="text/babel">
import Tournament from '../../api/tournament.js'
import AllPublishedTournaments from '../../components/AllPublishedTournaments.vue'
import ScheduleResults from '../../components/ScheduleResults.vue'

export default {
  data() {
    return {
      'currentScheduleView' : ''
    }
  },
  created: function() {
       this.$root.$on('changeTourComp', this.setTournamentData); 
  },
  mounted() {
    this.reset()
  },
  components: {
    AllPublishedTournaments,ScheduleResults
  },
  methods: {
    setTournamentData() {
      // Here we have to change it to Schedule Results
      this.$store.dispatch('setCurrentScheduleView','scheduleResults')
      this.currentScheduleView = this.$store.state.currentScheduleView  
    },
    AllTournament() {
      this.reset()
    },
    reset() {
     this.$store.dispatch('setCurrentScheduleView','allPublishedTournaments')
     this.currentScheduleView = this.$store.state.currentScheduleView
    }
  }
    
}
</script>