<template>
    <div class="raferee_details js-referee-draggable-block" data-toggle="popover" data-animation="false" data-placement="left" :data-popover-content="'#i'+referee.id">
        <div class="raferee_list">
            <div v-bind:id="'i'+referee.id" style="display:none;">
                <div class="popover-body">
                    <div>
                        <b>Age categories</b><br/>
                        <span v-show="referee.age_group_id !== null">
                            {{ referee.age_group_id | formatAgeCategoryName(competationList) }}
                        </span>
                        <span class="text-muted" v-show="referee.age_group_id === null">
                            No age categories assigned
                        </span>
                    </div>
                    <div v-if="referee.comments">
                        <br/><b>Availability</b><br/>
                        <span>{{ referee.comments }}</span>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between my-2">
                <div>
                    {{referee.last_name}}, {{referee.first_name}}
                </div>
                <div>
                    <a href="#" @click="generateRefereeReport()" title="Referee report card" class="text-primary" style="font-size:1.1em"><i class="fas fa-print"></i></a>&nbsp;&nbsp;
                    <a href="#" @click="editReferee()" title="Edit referee" class="text-primary mr-2"><i class="fas fa-pencil"></i></a>     
                </div>
            </div>  
        </div> 
    </div>
</template>

<script type="text/babel">
import Tournament from '../api/tournament.js'
export default {
    props: ['referee', 'competationList', 'isMatchScheduleInEdit'],
    filters: {

        formatAgeCategoryName: function(ageGroupId,competationList) {
            let ageGroupString = '';
            if(ageGroupId !== null) {
                let ageGroupArray = ageGroupId.split(',');
                _.forEach(ageGroupArray, function(key,value) {
                    ageGroupString += ageGroupString == '' ? competationList[key] : ', '+competationList[key];
                });
            }
            return ageGroupString;   
        }
    },
    mounted() {
        this.initEvents();  
        $("[data-toggle=popover]").popover({
            html : true,
            trigger: 'hover',
            content: function() {
                var content = $(this).attr("data-popover-content");
                return $(content).children(".popover-body").html();
            },
            title: function() {
                var title = $(this).attr("data-popover-content");
                return $(title).children(".popover-heading").html();
            }
        });
    },
    watch: {
        isMatchScheduleInEdit: function (val) {
            if(val === true) {
                $(this.$el).draggable('disable');
            } else {
                $(this.$el).draggable('enable');
            }
        },
    },
    methods: {
        initEvents() {
            $(this.$el).data('event', {
                id: this.referee.id,

                title: 'Referee added', // use the element's text as the event title
                refereeId: -3, // use the element's text as the event title
                refereeText: this.referee.first_name+' '+this.referee.last_name, // use the element's text as the event title
                color: 'white',
                stick: true, // maintain when user navigates (see docs on the renderEvent method),
                duration: moment.duration(15, 'minutes'),
                matchId: this.referee.id,
                matchAgeGroupId: '',
                forceEventDuration: true,
                overlap: true
                
            });
            // make the event draggable using jQuery UI
            $(this.$el).draggable({
                zIndex: 999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0,  //  original position after the drag
                containment: ".fc-time-grid-event",
            });
        },

        generateRefereeReport() {
            let refereeId = this.referee.id
            // var win = window.open("/api/match/reportCard/" + refereeId, '_blank');
            // win.focus();
            var refereePrintWindow = window.open('', '_blank');
            Tournament.getSignedUrlForRefereeReport(refereeId).then(
                (response) => {
                    refereePrintWindow.location = response.data;
                },
                (error) => {

                }  
            )
        },

        editReferee() { 
            this.$root.$emit('editReferee', this.referee.id)
        },
    }    
};
    
</script>