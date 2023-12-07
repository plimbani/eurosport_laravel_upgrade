<template>
    <select class="form-control ls-select2" v-model="selected" v-on:change="updateStatus">
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
        created: function() {
            this.$root.$on('cancelStatusChange', this.cancelStatusChange);
        },
        beforeCreate: function() {
            this.$root.$off('cancelStatusChange');
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
            },
            cancelStatusChange() {
                this.selected = this.tournamentStatus;
            },
        }
    }
</script>
