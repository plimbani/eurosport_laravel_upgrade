<template class="template-container">
    <div class="enlarge-div" v-show="showPrintPitchPlanner">
        <div class="enlarge-page-title">
            <h5 class="modal-title" id="exampleModalLabel">Pitch planner</h5>
            <button type="button" class="close" aria-label="Close" @click="showPitchPlanner()">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <router-view></router-view>
    </div>
</template>

<script type="text/babel">
    export default {
        mounted() {
            this.$store.dispatch('SetPrintPitchPlanner')
            this.showPrintPitchPlanner = true;
        },
        created() {
        },
        data() {
            return {
                showPrintPitchPlanner: true
            }
        },
        components : {
        },
        methods: {
            showPitchPlanner() {
                this.showPrintPitchPlanner = false;
                this.$store.dispatch('ResetPrintPitchPlanner');
                this.$router.push({name: 'pitch_planner'});
            }
        },
    }
</script>