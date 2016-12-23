$("#tbl_avail").on(  "click", '.available', function () {
   $(this).addClass("allocate");
   $(this).removeClass("available");
  $(this).find('span').text('allocate');
});
$( '#tbl_avail' ).on( "click",'.allocate', function() {
	$(this).find('span').text('available');
   $(this).addClass("available");
   $(this).removeClass("allocate");
});


$(document).ready(function(){

    function getPitchesData() {
        vuePitch = new Vue({
            el: "#pitchSet",
            data: {
                pitchData: [],
                pitchCount: 0,
                sortKey: '',
                sortOrder: 1,
                sortby: 'id',
                sorttype: 'desc',
                searchdata: ''
            },
            ready: function() {
                this.getPitches();
            },
            methods: {
                getPitches: function(page, sortby, sorttype, searchdata) {
                    if(typeof(sortby) == "undefined"){
                        sortby = this.sortby;
                        sorttype = this.sorttype;
                    }
                    var data = "sortby="+sortby + "&sorttype=" + sorttype; 

                    if(typeof(searchdata) != "undefined") {
                        data += searchdata;    
                    }

                    data += setPaginationAmount();

                    if(typeof(page) == "undefined"){
                        ajaxCall("templates/get_data", data, 'POST', 'json', pitchDataSuccess);
                    } else {
                        ajaxCall("templates/get_data?page="+page, data, 'POST', 'json', pitchDataSuccess);
                    }
                },
                savePitchDetail: function() {

                    var validateRules = {
                        "name": {
                            required: true
                        },
                        "subject": {
                            required: true
                        },
                        "content_editor": {
                            cke_required: true
                        }
                    }

                    var messages = {
                        "name": {
                            required: "This field is required"
                        }    
                    }

                    checkValidation( "frmTemplateForm", validateRules, messages );
                    
                    if($('#frmTemplateForm').validate().form()) {
                        var m_data = new FormData($("#frmTemplateForm")[0]);

                        content = CKEDITOR.instances.content_editor.getData();
                        m_data.append('content', content);

                        if(typeof(this.templateID) != "undefined" && this.templateID != 0) {
                            m_data.append('template_id', this.templateID);
                        }

                        ajaxCall("/template/store", m_data, 'POST', 'json', templateUpdateSuccess, false, false);
                    }

                },
                sortBy: function (key) {
                    this.sortOrder = this.sortOrder * -1;
                    this.$set('sortOrder', this.sortOrder);
                    this.$set('sortby', key);
                    this.$set('sortKey', key);
                    var stype = this.sortOrder == 1 ? 'asc':'desc';
                    this.$set('sorttype', stype);
                    this.getTemplates(this.currPage, key, stype, this.searchdata);
                },
                changesStatus: function (key) {
                    // console.log(this.sortby, this.sorttype, this.searchdata);
                    // return false;
                     vuePitch.$set('modal_title', 'Confirmation');
                        vuePitch.$set('modal_msg', 'Do you really want to delete this template?');
                        vuePitch.$set('modal_id', 'active');
                        vuePitch.$set('modal_template_id', key);

                    $("#updateStatus").modal('show');
                    
                },
                updateStateStatus: function(templateid) {
                    
                    ajaxCall('template/delete/'+templateid, '', 'POST', 'json', pitchDataSuccess);
                    showMsg("success", 'Success', 'Template has been deleted successfully');
                     $("#updateStatus").modal('hide');
                    this.getTemplates(this.currPage,this.sortby, this.sorttype, this.searchdata);
                }
            }
        });
    }
    getPitchesData();
    datePickerInit();
});
