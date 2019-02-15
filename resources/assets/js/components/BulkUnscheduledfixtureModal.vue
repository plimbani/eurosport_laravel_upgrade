<template>
    <div class="modal fade bg-modal-color refdel" id="bulk_unscheduled_fixtures" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog delete-modal" role="document">
        <div class="modal-content">
            <form method="delete" class="js-delete-modal-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">{{$lang.user_management_confirmation}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body js-delete-confirmation-msg">{{ unscheduleFixture }}</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"  @click="hideModal()">{{$lang.user_management_cancel}}</button>
                    <button type="submit" class="btn btn-primary" @click.prevent="confirmDelete()">{{$lang.user_management_save}}</button>
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
                this.$emit('confirmed');
            },
            hideModal() {
                $('#bulk_unscheduled_fixtures').modal('hide')
                $("#unschedule_fixtures").html('Unschedule fixture').removeClass('btn btn-success');
                $("#unschedule_fixtures").addClass('btn btn-primary btn-md btn-secondary');
                $(".checkbox").addClass('d-none');
                $("#cancle_unscheduling_fixtures").hide();
                $(".match-unschedule-checkbox" ).prop( "checked", false);
                return false
            }
        }
    }
</script>
