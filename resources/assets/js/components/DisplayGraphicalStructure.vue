<template>
  <div class="modal fade bg-modal-color" id="displaygraphic" tabindex="-1" role="dialog" aria-labelledby="displaygraphicLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
           <h5 class="modal-title" id="displaygraphicLabel">{{$lang.competation_modal_age_category}} {{viewGraphicImageName}}</h5>
           <button type="button" class="close js-close-btn" @click="hideCurrentModal()" aria-label="Close">
           <span>×</span>
           </button>
        </div>
        <div class="modal-body">
          <form name="ageCategoryName">
            <div class="row">
              <div class="w-100 text-center">
                <img class="text-center" :src="viewGraphicImagePath">
              </div>
            </div>
          </form>
        </div>
       </div>
    </div>
  </div>
</template>
<script type="text/babel">
   export default {
    props: ['templateImage','templateName','viewGraphicImagePath','viewGraphicImageName'],
    methods:
    {
      hideCurrentModal()
      {
        $('#displaygraphic').modal('hide');
        
      }
    }
 }
</script>
