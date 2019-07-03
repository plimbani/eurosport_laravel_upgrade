<template>
    <div class="container-fluid">
   		<EnterTournamentAccessCode v-if="currentLayout === 'commercialisation'">
   		</EnterTournamentAccessCode>
   		<Home v-if="currentLayout === 'tmp'"></Home>
    </div>
</template>
<script type="text/babel">
import EnterTournamentAccessCode from '../tournament/EnterTournamentAccessCode.vue'
import Home from '../front/Home.vue'
import Tournament from '../../api/tournament.js';

export default {
    data() {
	    return {
	      currentLayout: ''
	    }
    },
    components : {
        EnterTournamentAccessCode,Home
    },
    mounted() {
    		this.getCurrentLayout();
    },
	methods: {
		getCurrentLayout() {
        	Tournament.getCurrentLayout().then(
              (response)=> {
                this.currentLayout = response.data;
              },
              (error)=> {
              }
            );
        },
	} 

}
</script>