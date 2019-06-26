<template>
    <div class="container-fluid">
   		<EnterTournamentAccessCode v-if="getCurrentLayout === 'commercialisation'"></EnterTournamentAccessCode>
   		<home v-if="getCurrentLayout === 'tmp'"></home>
    </div>
</template>
<script type="text/babel">
import EnterTournamentAccessCode from '../tournament/EnterTournamentAccessCode.vue'
import Home from '../front/Home.vue'

export default {
    data() {
	    return {
	      
	    }
    },
    components : {
        EnterTournamentAccessCode,Home
    },
    computed: {
	    getCurrentLayout() {
	        return this.$store.state.Configuration.currentLayout;
	    }
	} 

}
</script>