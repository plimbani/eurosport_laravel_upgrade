<template>
	<thead class="no-border">
    <tr>
      <th scope="col">Date and time</th>
      <th scope="col">Categories</th>
      <th scope="col">Match codes</th>
      <th scope="col">Team</th>
      <th scope="col">Team</th>
      <th scope="col">Score</th>
      <th scope="col" v-if="showPlacingForMatch">Placing</th>
      <th scope="col">Location</th>
    </tr>
  </thead>
</template>
<script>
export default {
  props: ['isHideLocation','isUserDataExist','getCurrentScheduleView','showPlacingForMatch'],
  components: {
  },
}
</script>