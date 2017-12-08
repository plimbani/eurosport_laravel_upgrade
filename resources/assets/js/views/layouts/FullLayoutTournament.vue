<template class="template-container">
    <div class="modal fade p-0" id="enlarge_pitch_planner_modal" tabindex="-1" role="dialog" aria-labelledby="enlarge_pitch_planner_modal" aria-hidden="true">
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
            let vm = this;
            $('#enlarge_pitch_planner_modal').modal('show')
            // $("#enlarge_pitch_planner_modal").one('hidden.bs.modal', function (event) {
            //     event.stopPropagation();
            //     vm.$router.push({name: 'pitch_planner'})
            // });
        },
        data() {
            return {

            }
        },
        components : {
        },
        method: {
            showPitchPlanner() {
                vm.$router.push({name: 'pitch_planner'});
            }
        },
    }
</script>