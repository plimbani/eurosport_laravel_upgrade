<template class="template-container">
    <div class="modal fade p-0" id="enlarge_pitch_planner_modal" tabindex="-1" role="dialog" aria-labelledby="enlarge_pitch_planner_modal" aria-hidden="true" data-keyboard="false">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pitch planner</h5>
                <button type="button" class="close" aria-label="Close" @click="showPitchPlanner()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <router-view></router-view>
            </div>  
        </div>
    </div>
</template>

<script type="text/babel">

    export default {
        mounted() {
            $('#enlarge_pitch_planner_modal').modal('show')
            this.$store.dispatch('SetPitchPlannerInEnlargeMode')
        },
        data() {
            return {

            }
        },
        components : {
        },
        methods: {
            showPitchPlanner() {
                this.$store.dispatch('ResetPitchPlannerFromEnlargeMode');
                $('#enlarge_pitch_planner_modal').modal('hide');
                this.$router.push({name: 'pitch_planner'});
            }
        },
    }
</script>