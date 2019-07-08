<template>
    <div class="modal fade" id="publish_modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{$lang.summary_button_popup_publish_text}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body text-left">
                    <p>
                        {{$lang.summary_button_publish_text}}
                    </p>
                    <div v-if="isPublishedPreviewOnce === 0">
                        <div>When set to "Preview" or "Published" existing followers on the app of the original tournament will also be a follower of this new duplicated tournament.</div>
                        <div class="c-input mt-3">
                            <input id="switch_default_tournament_in_publish" type="checkbox" class="euro-checkbox" v-model="switch_default_tournament" :true-value="1" :false-value="0" />
                            <label for="switch_default_tournament_in_publish">Please check the box if you would also like to switch the default tournament of users from the original tournament to this duplicated one.</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{$lang.summary_tab_popup_publish_cancel_button}}</button>
                    <button type="submit" class="btn btn-primary"
                    @click.prevent="updateStatus">{{$lang.summary_tab_popup_publish_confirm_button}}</button>
                </div>
        </div>
      </div>
    </div>
</template>
<script type="text/babel">
    export default  {
        data() {
            return {
                switch_default_tournament: 0,
            }
        },
        props: ['isPublishedPreviewOnce'],
        methods: {
            updateStatus() {
                // here we set status to Publish For Tournament
               // $('#publish_modal').attr('data-dismiss','modal')
                this.$root.$emit('StatusUpdate','Published', this.switch_default_tournament);
            }
        }
    }
</script>
