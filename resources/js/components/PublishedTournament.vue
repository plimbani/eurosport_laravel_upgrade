<template>
  <div class="container text-sm-center">
        <h1>EuroSport Tournaments</h1>
        <div v-for="tournament in tournaments">
          <a href="" @click.prevent="selectTournament(tournament)">{{tournament.name}}</a>
        </div>
  </div>
</template>
<script type="text/babel">
	export default {

	}
</script>