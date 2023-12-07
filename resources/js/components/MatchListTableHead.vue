<template>
	<thead>
      <th>{{$lang.summary_schedule_date_time}}</th>
      <th>{{$lang.summary_schedule_matches_categories}}</th>
      <th v-if="isHideLocation !=  false">{{$lang.summary_schedule_matches_location}}</th>
      <th>Match codes</th>
      <th class="text-center">{{$lang.summary_schedule_matches_team}}</th>
      <th class="text-center">{{$lang.summary_schedule_matches_team}}</th>
      <th class="text-center" style="min-width:100px">{{$lang.summary_schedule_matches_score}}</th>
      <th class="text-center" v-if="showPlacingForMatch">{{$lang.summary_schedule_matches_placing}}</th>
      <th class="text-center" v-if="isUserDataExist && getCurrentScheduleView != 'teamDetails'">Details</th>
    </thead>
</template>
<script>
export default {
  props: ['isHideLocation','isUserDataExist','getCurrentScheduleView','showPlacingForMatch'],
  components: {
  },
}
</script>