<template>
    <div class="modal fade bg-modal-color refdel" id="unscheduled_fixtures_confirmation_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog delete-modal" role="document">
        <div class="modal-content">
            <form method="delete" class="js-delete-modal-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">{{$lang.user_management_confirmation}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body js-delete-confirmation-msg">{{$lang.unschedule_age_categories_modal_confirmation_message}}</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-58"  @click="hideModal()">No</button>
                    <button type="submit" class="btn btn-primary" @click.prevent="confirmDelete()">Yes</button>
                </div>
                <input name="_method" value="DELETE" type="hidden" />
            </form>
        </div>
      </div>
    </div>
</template>
<script>
    export default  {

        props: {
            unscheduleFixture: String
        },
        methods: {
            confirmDelete() {
                $('#unscheduled_fixtures_confirmation_modal').modal('hide');
                this.$emit('confirmed');
            },
            hideModal() {
                $('#unscheduled_fixtures_confirmation_modal').modal('hide');
                return false
            }
        }
    }
</script>
