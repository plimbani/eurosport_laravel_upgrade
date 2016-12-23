var PageLimit = 5;
// Metronic.init();


$(document).on('ajaxStart', function (xhr) {
    // setHeader(xhr);
});
$(document).on('ajaxComplete', function (xhr, status){
    // 
});


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

function ajaxCall(url, data, method, dataType, successHandlerFunction, processDataFlag, contentTypeFlag)
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

        // $("#"+formId).find(".select2-chosen").html("");
        // $("#"+formId).find("div.thumbnail").html("");
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

// $('body').on('click', '.portlet > .portlet-title > .tools > a.reload', function(e) {
//     window.location.reload();
// });

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