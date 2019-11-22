<template>
    <div class="js-draggable-events">
        <div v-if="match != 'unavailable'" class="unscheduled-match-content draggable-event dashbox p-2 text-center hoverable" :style="{background: fixtureBackgroundColor, color: fixtureTextColor}">
            <div>{{match.displayMatchName}}</div>
            <div>{{match.fullGame}}</div>
            <div>({{match.matchTime}} min)</div>
            <div class="unscheduled-match-content-strip" :style="{background: match.competationColorCode != null ? match.competationColorCode : '#FFFFFF'}"></div>
        </div>
        <div class="dark_grey_bg card p-2 m-0 text-center" v-else>
            <div>Unavailable 60 mins</div>
            <div>{{match.fullGame}}</div>
            <div>(60 min)</div>
        </div>
    </div>
</template>

<script type="text/babel">
import moment from 'moment'
export default {
    props: ['match', 'fixtureBackgroundColor', 'fixtureTextColor'],
    data() {
      return {
            'tournamentFilter': this.$store.state.Tournament.tournamentFiler 
      }  
    },
    mounted() {
        this.initEvents();
    },
    methods: {
        initEvents() {
            // store data so the calendar knows to render an event upon drop
            $(this.$el).data('event', {
                id: this.match=='unavailable' ? 'block_' + new Date().getTime() : this.match.id,

                title: this.match.displayMatchName ? this.match.displayMatchName : '', // use the element's text as the event title
                refereeId: this.match=='unavailable'?-2:'0', // use the element's text as the event title
                refereeText: 'R', // use the element's text as the event title
                color: this.match == 'unavailable' ? 'grey' : this.fixtureBackgroundColor,
                textColor: this.match == 'unavailable' ? '#FFFFFF' : this.fixtureTextColor,
                borderColor: this.match == 'unavailable' ? 'grey' : this.fixtureBackgroundColor,
                stick: true, // maintain when user navigates (see docs on the renderEvent method),
                duration: this.match.matchTime ? moment.duration(this.match.matchTime, 'minutes') : moment.duration(60, 'minutes'),
                matchId: this.match.matchId,
                matchAgeGroupId: this.match.ageGroupId,
                matchCompetitionId: this.match.competitionId,
                matchVenueId: this.match.venueId,
                forceEventDuration: true,
                fixtureStripColor: this.match == 'unavailable' ? 'grey' : (this.match.competationColorCode != null ? this.match.competationColorCode : '#FFFFFF'),
                displayMatchName: this.match.displayMatchName,
                categoryAgeColor: this.match.categoryAgeColor,
                categoryAgeFontColor: this.match.categoryAgeFontColor,
                competitionColorCode: this.match.competationColorCode,
                matchRefereeId: this.match == 'unavailable' ? -2 : '0',
                scheduleLastUpdateDateTime: this.match.scheduleLastUpdateDateTime ? this.match.scheduleLastUpdateDateTime : null,
            });

            // make the event draggable using jQuery UI
            $(this.$el).draggable({
                zIndex: 999,
                revert: true,      // will cause the event to go back to its
                revertDuration:0  //  original position after the drag
            });
        }
    }
};

</script>
