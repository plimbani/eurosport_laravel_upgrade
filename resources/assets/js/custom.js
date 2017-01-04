import Vue from 'vue';

var PageLimit = 5;
Metronic.init();
if ($.cookie('sidebar_closed') == undefined){
    $.cookie('sidebar_closed', '1');
}

$(document).on('ajaxStart', function (xhr) {
    // setHeader(xhr);
});
$(document).on('ajaxComplete', function (xhr, status){
    // 
});

setInterval(function() {
    $('#timer').html(moment().format('HH:mm:ss'));
    $('#date').html(moment().format('ddd D MMMM YYYY'));
}, 1000);

// $(document).ready(function(){
//   $('#logout').on('click',function(){
//     window.location = 'logout';
//   });
// });

$.ajaxSetup({
    beforeSend: setHeader1
});

function setHeader1(xhr) {   
    xhr.setRequestHeader('X-CSRF-Token', $('meta[name=csrf-token]').attr('content'));
}

var auth_token = '';
var geturl = '';

function ajaxCall(url, data, method, dataType, successHandlerFunction,
 processDataFlag, contentTypeFlag)
{
    if(typeof(processDataFlag) == 'undefined'){
      processDataFlag = true;
    }
     
    if(typeof(contentTypeFlag) == 'undefined'){
      contentTypeFlag = 'application/x-www-form-urlencoded';
    }

    Metronic.startPageLoading();
    
    geturl = $.ajax({
        url: url,
        data: data,
        processData: processDataFlag,
        contentType: contentTypeFlag,
        type: method,
        dataType: dataType,
        cache: false,
        success: successHandlerFunction,
        complete: function() {
            Metronic.stopPageLoading();
        }
    });
}

const vueHeader = new Vue({
    el: "#page-header",    
    data() {
        return {}
    },
    methods: {
        openUpdateAdminModal(user_id) {
            $("#clearBtn").hide();
            this.vueHeader.selectedUser = user_id;
            vueHeader.isAdmin = 1;
            var affiliate_id = $("#affiliates").val();
            var data = "user_id="+user_id+"&affiliate_id="+affiliate_id;
            ajaxCall("/user/getUserData", data, 'POST', 'json', adminSuccess);
        },
        openChangePasswordModal(user_id) {
            vueHeader.selectedUser = user_id;
            $("#change_password_modal").modal("show");
            setTimeout(function() {
                $(".modal-backdrop.fade.in").remove();
            }, 300);
        },
        clearForm(formid) {
            clearFormData(formid);
        },
        saveUser() { 
            var validateRules = {
                "email": {
                    required: true,
                    email: true
                },
                "first_name": {
                    required: true
                },
                "last_name": {
                    required: true
                },
                "admin": {
                    required: true
                }
            }
            var messages = {
                "email": {
                    required: "This field is required"
                },
                "first_name": {
                    required: "This field is required"
                },
                "last_name": {
                    required: "This field is required"
                },
                "admin": {
                    required: "This field is required"
                }                
            }

            checkValidation( "frmAdminSaveData", validateRules, messages );
            if($('#frmAdminSaveData').validate().form()) {
                var m_data = new FormData($("#frmAdminSaveData")[0]);

                if($("#gwt-uid-25").is(":checked")) {
                    m_data.append('admin', 0);
                } else {
                    m_data.append('admin', 1);
                }

                var affiliate_id = $("#affiliates").val();
                m_data.append('affiliate_id', affiliate_id);

                if(vueHeader.isAdmin == 1)
                    m_data.append('admin_profile_update', 1);
                else 
                    m_data.append('admin_profile_update', 0);
                
                m_data.append('admin_id', vueHeader.selectedUser);
                ajaxCall("/user/update", m_data, 'POST', 'json', adminUpdateSuccess, false, false);
            }
        },
        changePassword() {
            $.validator.addMethod('checkConfirmPassword', function (value, element, param) {
                return $("#password").val() == $("#password_confirmation").val();
            });

            var validateRules = {
                "password": {
                    required: true
                },
                "new_password": {
                    required: true
                },
                "password_confirmation": {
                    required: true,
                    checkConfirmPassword: true
                }
            }

            var messages = {
                "password_confirmation": {
                    checkConfirmPassword: "New password and confirm new password do not match."
                }
            }

            checkValidation( "frmChangePassword", validateRules, messages );
            if($('#frmChangePassword').validate().form()) {
                var m_data = new FormData($("#frmChangePassword")[0]);
                m_data.append('admin_id', vueHeader.selectedUser);
                ajaxCall("/user/change_password", m_data, 'POST', 'json', adminChangePasswordSuccess, false, false);
            }

            // ajaxCall("user/getUserData", data, 'POST', 'json', userSuccess);
        }
        // gotoUsers: function() {
        //     window.location = "/users";
        // }
    }
});

function adminUpdateSuccess(usersList, status, xhr){
    $("#admin_modal").modal('hide');
    if(usersList.success === true) {
        showMsg("success", 'Success', 'User details saved successfully');
        if(typeof(usersList.adminName) != "undefined")
            $(".dropdown-user span.username").html(usersList.adminName);
    } else {
        showMsg("error", 'Error', 'User details could not be saved successfully');
    }
}

function adminChangePasswordSuccess(password, status, xhr){
    $("#change_password_modal").modal('hide');
    if(password.success === true) {
        showMsg("success", 'Success', 'Password has been changed successfully');
    } else {
        if(typeof(password.currPassword) != "undefined") 
            showMsg("error", 'Error', '  Current password does not match, Please provide valid current password to update your new password.');
        else
            showMsg("error", 'Error', 'Password could not be changed successfully');
    }
}

function adminSuccess(userData, status, xhr){
    vueHeader.$set('userData', userData['data'][0]);
    if(userData.success === true) {
        if( userData['data'][0].admin == 0 ) {
            $("#gwt-uid-25").attr('checked', 'checked');
        } else {
            $("#gwt-uid-26").attr('checked', 'checked');
        }
        $(".adminModal").show();
        $("#submitAdmin .v-button-caption").html('Save user');
        $("#removeUser").show();
        $("#admin_modal").modal('show');
        setTimeout(function() {
            $(".modal-backdrop.fade.in").remove();
        }, 300);
    } else {
        alert("error");
    }
}


function checkValidation( formId, validateRules, messages ) {
    // for more info visit the official plugin documentation:
    // http://docs.jquery.com/Plugins/Validation
    formId = $( "#" + formId );
    var error1 = $('.alert-danger', formId);
    var success1 = $('.alert-success', formId);
    
    formId.validate ( {
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",
        rules: validateRules,

        messages: messages,

        errorPlacement: function (error, element) { // render error placement for each input type
            if (element.parent(".input-group").size() > 0) {
                error.insertAfter(element.parent(".input-group"));
            } else if (element.attr("data-error-container")) { 
                error.appendTo(element.attr("data-error-container"));
            } else if (element.parents('.radio-list').size() > 0) { 
                error.appendTo(element.parents('.radio-list').attr("data-error-container"));
            } else if (element.parents('.radio-inline').size() > 0) { 
                error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
            } else if (element.parents('.checkbox-list').size() > 0) {
                error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
            } else if (element.parents('.checkbox-inline').size() > 0) { 
                error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
            } 
            else if ($(element[0]).hasClass('ckeditor')) {
                element.parent().append(error);
            } else {
                error.insertAfter(element); // for other inputs, just perform default behavior
            }
        },

        invalidHandler: function (event, validator) { //display error alert on form submit              
            success1.hide();
            error1.show();
            App.scrollTo(error1, -200);
        },

        highlight: function (element) { // hightlight error inputs
            $(element)
                .closest('.form-group').addClass('has-error'); // set error class to the control group
        },

        unhighlight: function (element) { // revert the change done by hightlight
            $(element)
                .closest('.form-group').removeClass('has-error'); // set error class to the control group
        },

        success: function (label) {
            label
                .closest('.form-group').removeClass('has-error'); // set success class to the control group
        },

        submitHandler: function (form) {
            success1.show();
            error1.hide();
        }
    } );
}

function clearFormData(formId) {
    setTimeout(function(){
        $("#"+formId).find("input").val('');
        $("#"+formId).find("textarea").val('');
        $("#"+formId+" .form-body").find('.has-error').removeClass('has-error');
        $("#"+formId+" .help-block-error").remove();

        if($("#"+formId).find(".select2-allow-clear").length) {
            $("#"+formId+" .select2-allow-clear").each(function(){
                $(this).select2("val", "");
            });
        }
        
        if($("#"+formId).find(".select2-multiple").length) {
            $("#"+formId+" .select2-multiple").each(function(){
                $(this).select2("val", "");
            });
        }
    }, 20);
}

function showMsg(type, subject, msg) {
    toastr.options.closeButton = true;
    if(type == "info")
        toastr.info(msg)
    else if(type == "error" || type == "warning") {
        toastr.error(msg, subject)
    } else if(type == "success") {
        toastr.success(msg, subject)
    }
} 

// define
var paginationComponent = Vue.extend({
  template: '<div class="dataTables_length pull-left"><select id="pagination_length" name="pagination_length" aria-controls="pagination_length" class="form-control input-xsmall input-inline"><option value="5">5</option><option value="10">10</option><option value="15">15</option><option value="20">20</option><option value="-1">All</option></select></div><div class="dataTables_info pull-left" id="pagination_record_msg"></div>'
})

// register
Vue.component('pagination_component', paginationComponent);

function setPaginationRecords(start, records, totalcount) {
    if(records > totalcount) {
        $("#pagination_record_msg").html("Showing "+ start +" to "+ totalcount +" of "+ totalcount +" entries");
    } else {
        $("#pagination_record_msg").html("Showing "+ start +" to "+ records +" of "+ totalcount +" entries");
    } 
}

function setPaginationAmount() {
    var set_pagination = '';

    if(typeof($.cookie('pagination_length')) == "undefined"){
        set_pagination += "&pagination_length=10";
    }
    else{
        if($.cookie('pagination_length') == -1){
            set_pagination += "&pagination=false";
        }
        else{
            set_pagination += "&pagination_length="+$.cookie('pagination_length');
        }
    }
    return set_pagination;
}

function initPaginationRecord() {
    setTimeout(function(){
        if(typeof($.cookie('pagination_length')) != "undefined"){
            $("#pagination_length").val($.cookie('pagination_length'));
        } else {
            $.cookie('pagination_length', PageLimit);
        }
    });
}

setTimeout(function(){
    $(".caption").click(function(){
        $('#customCollapse').trigger('click');
    }); 
}, 50);

function datePickerInit() {
    if (jQuery().datepicker) {
        setTimeout(function(){
            $('.date-picker').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "right",
                autoclose: true
            });
        }, 20);
    }
}

function _scrollTo(field) {
    $(window).scrollTop($('#'+field).offset().top-100);
}

function setDefaultData(vueID) {
    vueID.currPage = 1;
    vueID.sortby = 'id';
    vueID.sorttype = 'desc';
    vueID.searchdata = '';
}

// set sidebar active class
$(".page-sidebar-menu li").on("click", function() {
    $(".page-sidebar-menu li").removeClass('active');
    localStorage.setItem("page_name", $(this).attr('data-page'));
});

$(".btnLogout").on("click", function(){
    localStorage.setItem("page_name", "");
});

$(document).ready(function() {
    if(typeof(localStorage.getItem("page_name")) == "undefined" || localStorage.getItem("page_name") == "") {
        $(".page-sidebar-menu li.home").addClass("active");
    } else {
        $(".page-sidebar-menu li."+localStorage.getItem("page_name")).addClass("active");
    }
});