<template>
	<thead class="no-border">
    <tr>
      <th scope="col">{{ $t('matches.date_and_time') }}</th>
      <th scope="col">{{ $t('matches.categories') }}</th>
      <th scope="col">{{ $t('matches.match_codes') }}</th>
      <th scope="col">{{ $t('matches.team') }}</th>
      <th scope="col">{{ $t('matches.team') }}</th>
      <th scope="col">{{ $t('matches.score') }}</th>
      <th scope="col" v-if="showPlacingForMatch">{{ $t('matches.placing') }}</th>
      <th scope="col">{{ $t('matches.location') }}</th>
    </tr>
  </thead>
</template>
<script>
export default {
  props: ['showPlacingForMatch'],
  components: {
  },
}
</script>