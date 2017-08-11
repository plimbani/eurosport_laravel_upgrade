<template>
    <div class="modal fade" id="delete_tournament_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">{{$lang.tournament_delete}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body text-left">
                    <p>
                        Hit "Confirm" to delete your tournament.
                    </p>
                    <p>
                        <strong>Warning: </strong>all tournament details will be lost.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{$lang.tournament_cancel_button}}</button>
                    <button type="submit" class="btn btn-primary">{{$lang.tournament_confirm_button}}</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</template>
<script type="text/babel">
    export default  {
      
    }
</script>