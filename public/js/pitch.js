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
              alert('h');

            },
            methods: {
                savePitchDetail: function(form) {
                	console.log(form);
                	return false;
                    var validateRules = {
                        "pitch_name": {
                            required: true
                        }
                    }

                    var messages = {
                        "pitch_name": {
                            required: "This field is required"
                        }    
                    }

                    checkValidation( "frmPitchDetails", validateRules, messages );
                    
                    if($('#frmPitchDetails').validate().form()) {
                        var m_data = new FormData($("#frmPitchDetails")[0]);



                        ajaxCall("/pitches/store", m_data, 'POST', 'json', pitchUpdateSuccess, false, false);
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

function pitchUpdateSuccess(pitch, status, xhr){
	console.log('msg');
    if(pitch.status === true) {
        showMsg("success", 'Success', 'Pitch has been saved successfully');
        window.location = "/templates";
    } else {
       console.log('test');
    }
}

});
