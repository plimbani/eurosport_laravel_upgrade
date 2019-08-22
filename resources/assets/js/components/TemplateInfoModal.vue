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
                <p v-show="templateData.length != 0">{{ $lang.template_modal_message }}</p>
                <div v-for="(template, key) in templateData">   
                    <span class="font-weight-bold">{{ key }}</span>
                    (<span><span class="py-2">{{ displayGroupName(template) }}</span></span>)
                </div>
                <span v-show="templateData.length == 0">{{ $lang.no_template_in_use_message }}</span>
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
    import _ from 'lodash';

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
        beforeCreate: function() {
            this.$root.$off('getTemplateDetail');
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
            },
            displayGroupName(template){
                return _.map(template, 'group_name').join(', ');
            }            
        }
    }
</script>