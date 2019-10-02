<template class="template-container">
    <div class="enlarge-div" v-show="showEnlargedPitchPlanner">
        <div class="enlarge-page-title">
            <h5 class="modal-title" id="exampleModalLabel">Match planner</h5>
            <button type="button" class="close" aria-label="Close" @click="showPitchPlanner()">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <router-view v-bind="routerProps" @hideEnlargedPitchPlannerStatus="hideEnlargedPitchPlannerStatus"></router-view>
    </div>
</template>

<script type="text/babel">
    export default {
        mounted() {
            this.$store.dispatch('SetPitchPlannerInEnlargeMode')
            this.showEnlargedPitchPlanner = true;
        },
        computed: {
            routerProps() {
                return {showEnlargedPitchPlanner: this.showEnlargedPitchPlanner};
            },
        },
        created() {
        },
        data() {
            return {
                showEnlargedPitchPlanner: true
            }
        },
        components : {
        },
        methods: {
            showPitchPlanner() {
                this.$router.push({name: 'pitch_planner'});
            },
            hideEnlargedPitchPlannerStatus() {
                this.showEnlargedPitchPlanner = false;
                this.$store.dispatch('ResetPitchPlannerFromEnlargeMode');
            },
        },
    }
</script>