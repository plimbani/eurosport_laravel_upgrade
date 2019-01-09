<template>
    <select class="form-control ls-select2 col-sm-10 offset-sm-1 mr-0 ml-0" v-model="selected" v-on:change="updateStatus">
        <option v-for="option in options" v-bind:value="option">
            {{option}}
        </option>
    </select>
</template>
<script type="text/babel">
    export default  {
        props: ['tournamentStatus'],
        data() {
            return {
                selected:'',
                options: ['Preview','Published','Unpublished']
            }
        },
        mounted () {
          this.selected = this.tournamentStatus
        },
        watch: {
          tournamentStatus: function (newValue) {
            this.selected = newValue
          }
        },
        methods: {
            updateStatus(value) {

                if ( this.selected == 'Published')
                {
                   $('#publish_modal').modal('show');
                }

                if ( this.selected == 'Unpublished')
                {
                   $('#unpublish_modal').modal('show');
                }

                if ( this.selected == 'Preview')
                {
                   $('#preview_modal').modal('show');
                }
            }
        }
    }
</script>
