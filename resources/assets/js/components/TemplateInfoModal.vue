<template>
    <div class="modal fade bg-modal-color refdel" id="template_info_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog template-info-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="templateInfoModalLabel">{{$lang.template_modal_header}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body modal-fixed-height">
                <div class="row">
                    <div class="col-6 col-md-6">
                        <h5>{{ $lang.template_modal_tournaments_listing }}</h5>
                    </div>
                    <div class="col-6 col-md-6">
                        <h5>{{ $lang.template_modal_age_categories_listing }}</h5>
                    </div>
                </div>
                <div class="row" v-for="template in templateData">
                    <div v-for="templateDetail in template">
                        <div class="col-6 col-md-6">                        
                            <ul class="list-unstyled">
                                <li class="font-weight-bold py-2">{{ templateDetail }}</li>
                            </ul>
                        </div>
                        <div class="col-6 col-md-6">
                            <ul class="list-unstyled">
                                <li class="font-weight-bold py-2">{{ templateDetail }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger"  @click="closeModal()">{{$lang.manual_ranking_cancel}}</button>
            </div>            
        </div>
      </div>
    </div>
</template>
<script type="text/babel">
    import Template from '../api/template.js'

    export default  {
        data() {
            return {
                templateData: []
            }
        },
        props: ['templateDetail'],
        created() {
            this.$root.$on('getTemplateDetail', this.getTemplateDetail);
        },
        mounted() {
        },
        methods: {
            closeModal() {
                $('#template_info_modal').modal('hide');
                return false;
            },
            getTemplateDetail() {
                Template.getTemplateDetail(this.templateDetail).then(
                    (response)=> {
                        this.templateData = response.data.data
                    },
                    (error)=> {

                    }
                )
            }
        }
    }
</script>