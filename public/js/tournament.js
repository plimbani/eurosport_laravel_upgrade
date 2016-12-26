$(document).ready(function(){

    function gettournamentList() {
        vueTournamentAdd = new Vue({
            el: "#tournamentadd",
            data: {
                tournamentList: [],
                addEditDonation: false,
                selectedUser: 0,
                sortKey: '',
                sortOrder: 1,
                sortby: 'id',
                sorttype: 'desc',
                addressDrp: [],
                addressOrig: [],
                delAddressDrp: [],
                delAddressOrig: [],
                setAddr: '',
                setDelAddr: '',
                tournamentdata: [],
                tournamentdataold:[],
                updateTournamentId: 0,
                status: 'add',
                chngImage: false,
                tournamentLogsCount:''
            },
            ready: function() {
                var path = window.location.pathname;
                var id = path.substring(path.lastIndexOf('/') + 1);
                if(id != 'add') {
                    $(".active.pagewise-breadcrumb").html('Edit');
                    this.updateTournamentId = id;
                    this.fetchTournamentData(id);
                    this.status = 'edit';
                }
            },
            methods: {
                fetchTournamentData: function(tournamentid) {
                    //var data = "tournamentid="+tournamentid; 
                    //ajaxCall("/tournament/getTournamentData", data, 'POST', 'json', userDataSuccess);
                },
                saveTournament: function() {

                   /* $.validator.addMethod('checkDesiredUrlStr', function (value, element, param) {
                        if(/^[\w\d]+-?[\w\d]*$/.test(value)) {
                            return true;
                        } else {
                            return false;
                        }                        
                    });
                    $.validator.addMethod('checkCustomPhone', function (value, element, param) {
                        if(/^[0-9+ ]+$/.test(value)) {
                            return true;
                        } else {
                            return false;
                        }
                    });*/

                    var validateRules = {
                        
                        "first_name": {
                            required: true
                        },
                        "last_name": {
                            required: true
                        },
                        "contact_email": {
                            required: true,
                            email: true
                        },                        
                        "phone": {
                            required: true,
                            checkCustomPhone: true
                        },
                        "name": {
                            required: true
                            
                        },
                        "type": {
                            required: true
                        },
                        "sport": {
                            required: true
                        },
                        "proficiency": {
                            required: true
                        },
                        "desired_url": {
                            required: true,
                            checkDesiredUrlStr: true
                        },
                        "super_affiliate": {
                            required: true
                        }   
                    }

                    var messages = {
                        "contact_email": {
                            required: "This field is required"
                        },
                        "first_name": {
                            required: "This field is required"
                        },
                        "last_name": {
                            required: "This field is required"
                        },
                        "desired_url": {
                            checkDesiredUrlStr: "Url can contain only alphabetic characters and one dash"
                        },
                        "phone": {
                            checkCustomPhone: "Please enter valid Phone Number"
                        }
                        
                    }

                    checkValidation( "frmTournamentSaveData", validateRules, messages );
                    
                    if($('#frmTournamentSaveData').validate().form()) {
                         var ajaxresponse = false;
                         var response_error = false;
                            $('#club_name-error').html('');
                            $('#desired_url-error').html('');
                            if($('div').find('#club_name-error')){
                               $('#club_name-error').parentsUntil('div .form-group').parent().removeClass('has-error');  
                            }
                            if($('div').find('#desired_url-error')){
                               $('#desired_url-error').parentsUntil('div .form-group').parent().removeClass('has-error');  
                            }

                         /*var data1 = "clubname=" + $('#club_name').val() + "&id="+this.updateClubId + "&desired_url=" + $('#desired_url').val();
                          ajaxCall("/club/checkClubName",data1, 'GET', 'json', 
                            function(response) {
                               
                            ajaxresponse = true;
                               if(response.rstatus == 'false'){
                               
                                response_error= true;
                                if(response.state == 'club'){
                                
                                    $('#club_name-error').html('Clubname is already registered.');
                                    $('#club_name-error').parentsUntil('div .form-group').parent().addClass('has-error');

                                }if(response.state == 'url'){
                                   
                                    $('#desired_url-error').html('DesiredURL is already registered.');
                                    $('#desired_url-error').parentsUntil('div .form-group').parent().addClass('has-error');
                                }
                                $('html, body').animate({
                                    scrollTop: $('.has-error').offset().top - 50
                                }, 1000);
                                 return false;

                               }
                               
                        });*/
                          
                          
                          
                        var m_data = new FormData($("#frmTournamentSaveData")[0]);
                       
                        if(this.updateTournamentId != 0); {
                            m_data.append('clubid', this.updateTournamentId);
                        }
                        ajaxresponse = true;

                        // return false;
                        setTimeout(function(){
                           
                            if(ajaxresponse == true && response_error == false){

                                ajaxCall("/tournament/store", m_data, 'POST', 'json', clubUpdateSuccess, false, false);
                            }
                        
                        },2000);
                    } 
                        if($('#collapse_1').find('span.help-block').length > 0)
                            $('#collapse_1 .portlet-body').css('display', 'block');
                        if($('#collapse_2').find('span.help-block').length > 0)
                            $('#collapse_2 .portlet-body').css('display', 'block');
                        if($('#collapse_3').find('span.help-block').length > 0)
                            $('#collapse_3 .portlet-body').css('display', 'block');
                        if($('#collapse_4').find('span.help-block').length > 0)
                            $('#collapse_4 .portlet-body').css('display', 'block');
                   

                },
                cancelBtn: function() {
                    clearFormData('frmTournamentSaveData');
                },
                 moment: function () {
                    return moment();
                  },
                date: function (date) {
                  return moment(date).format('HH:mm:ss d MMM YYYY');
                },
                currency: function(data) {
                   return currency == 'GBP'? "€" : "£" ;  
                },
                setAddress: function(addr) {
                    // alert(addr);
                    // alert(vueClubsAdd.addressOrig[addr]);
                    

                    var getData = vueTournamentsAdd.addressOrig[addr];
                    var getDataList = getData.split(',');
                    $("#p_address1").val($.trim(getDataList[1])+ ' '+ $.trim(getDataList[2]));
                    $("#p_address2").val('');
                    $("#p_town").val($.trim(getDataList[3]));
                    $("#p_county").val('');
                    // $("#p_postcode").val($.trim(vueClubsAdd.setAddr).toUpperCase());
                    $("#p_postcode").val($.trim(getDataList[0]));

                     $("#addressdrp").select2({
                        data: null
                    });
                    $(".addressdrp").addClass("hide");
                    $("#address").val('');

                    
                },
                setDelAddress: function(addr) {
                    // alert(addr);
                    // alert(vueClubsAdd.addressOrig[addr]);
                    var getData = vueTournamentsAdd.delAddressOrig[addr];
                    var getDataList = getData.split(',');
                    $("#c_address1").val($.trim(getDataList[1])+ ' '+ $.trim(getDataList[2]));
                    $("#c_address2").val($.trim(''));
                    $("#c_town").val($.trim(getDataList[3]));
                    $("#c_county").val('');
                    $("#c_postcode").val($.trim(getDataList[0]));
                    $("#addressdrp").select2({
                        data: null
                    });
                    $(".deladdressdrp").addClass("hide");
                    $("#delivery_address").val('');
                    
                },
                setTournamentName: function() {
                    var clubname = $("#club_name").val();
                    clubname = clubname.replace(/[\s-_*!@#$%^&*()+=]*/g, '');
                    $("#desired_url").val(clubname.toLowerCase());
                },
                goBack: function() {
                    window.location = "/club";
                },
                reloadData: function(divid) {
                    clearFormData(divid);
                    // $("#" + divid + " .select2-allow-clear").each(function(){
                    //   $(this).select2("val", "");
                    // });
                    $("#" + divid + " input:checkbox").each(function(){
                      $(this).removeAttr('checked');
                    });

                    $("#" + divid + " #sitecolor").removeAttr('style');

                    

                    $("#" + divid + " .removeImage").trigger("click");
                }

            }
        });
    }
    gettournamentList();
    datePickerInit();

    setTimeout(function() {
        $("#collapse_1 .caption").click(function(){
            $('#customCollapse1').trigger('click');
        });
        $("#collapse_2 .caption").click(function(){
            $('#customCollapse2').trigger('click');
        });
        $("#collapse_3 .caption").click(function(){
            $('#customCollapse3').trigger('click');
        });
        $("#collapse_4 .caption").click(function(){
            $('#customCollapse4').trigger('click');
        });
    }, 100);
    
});

function addressSuccess(data, status, xhr) {
    if(data['success'] == true){
        $(".addressdrp").removeClass("hide");
        
        $("#addressdrp").select2("destroy");
        $('#addressdrp').find('option').remove().append('<option value=""></option>').val('');
            
        vueTournamentsAdd.addressDrp = [];
        vueTournamentsAdd.addressDrp[0] = " ";
        for(var i=0; i<data['Addresses'].length; i++) {
            var test = data['Addresses'][i][0].match(/[^,]+,[^,]+/g);
            
            var trimmedStr = test[0].replace(/,\s*$/, "");
            
            vueTournamentsAdd.addressDrp[i+1] = test;
            vueTournamentsAdd.addressOrig[test] = data['Addresses'][i][0];
        }
        // vueClubsAdd.addressOrig = data['location'];
        $("#addressdrp").select2({
            data: vueTournamentsAdd.addressDrp
        });
        $("#addressdrp").select2("open");
    }
   
}

function delAddressSuccess(data, status, xhr) {
    if(data['success'] == true){
        $(".deladdressdrp").removeClass("hide");
       $("#deladdressdrp").select2("destroy");
        $('#deladdressdrp').find('option').remove().append('<option value=""></option>').val('');
        
        for(var i=0; i<data['Addresses'].length; i++) {
            var test = data['Addresses'][i][0].match(/[^,]+,[^,]+/g);
            var trimmedStr = test[0].replace(/,\s*$/, "");
            vueTournamentsAdd.delAddressDrp[i] = trimmedStr;
            vueTournamentsAdd.delAddressOrig[trimmedStr] = data['Addresses'][i][0];
        }
        $("#deladdressdrp").select2({
            data: vueTournamentsAdd.delAddressDrp
        });
        $("#deladdressdrp").select2("open");
    }
}

$("#addressdrp").on("change", function(){
    vueTournamentsAdd.setAddress($(this).val());   
});

$("#deladdressdrp").on("change", function(){
    vueTournamentsAdd.setDelAddress($(this).val());   
});

$(".colorlist div").on("click", function(){
    $("#color_code").val($(this).attr('data-id').toUpperCase()).trigger('change');
    $("#sitecolor").css('background-color', $(this).css('background-color'));
    $('#selectcolor').slideToggle();
    

});

function clubUpdateSuccess(clubData, status, xhr){
   
    if(clubData.status === true) {
        showMsg("success", 'Success', 'Club data has been saved successfully');
        window.setTimeout(function(){
            window.location.href = "/club";
        }, 2500);
    } else {
        $.each(clubData.errors, function(i, error){
            $("#"+i).css('border', '1px solid red');
            
            if(i == "desired_url") {
                showMsg("error", 'Error', "Club already exists");
            }
            else {
                showMsg("error", 'Error', error);
            }
            _scrollTo(i);
        });
        // $('html,body').animate({scrollTop: 0});
    }
}

function userDataSuccess(clubData, status, xhr) {
   // var asd = clubData.clubdata;
   console.log(clubData);
    if(clubData.success === true) {
       
        vueTournamentsAdd.$set('clubdata', clubData.clubdata);
        vueTournamentsAdd.$set('clubdataold', clubData.clubdata);
        if(clubData.clubdata['logsInfo'].length>0)
        vueTournamentsAdd.$set('clubLogsCount', 'expand');

      

    // var newarr = $( '#frmClubSaveData' ).serialize() ;
     // setTimeout(function(){

     //  }, 1000);
        setTimeout(function(){
            if(clubData.clubdata['vista_dispatched'] != "" && clubData.clubdata['vista_dispatched'] != null) {
                $("#vista_dispatched").val(convertDate(clubData.clubdata['vista_dispatched'], "-"));
            }
            if(clubData.clubdata['pos_dispatched'] != "" && clubData.clubdata['pos_dispatched'] != null) {
                $("#pos_dispatched").val(convertDate(clubData.clubdata['pos_dispatched'], "-"));
            }
            if(clubData.clubdata['vista_ordered'] != "" && clubData.clubdata['vista_ordered'] != null) {
                $("#vista_ordered").val(convertDate(clubData.clubdata['vista_ordered'], "-"));
            }
            $("#type").select2("val", clubData.clubdata['type']);
            $("#sport").select2("val", clubData.clubdata['sport']);
            $("#proficiency").select2("val", clubData.clubdata['proficiency']);
            $("#website_type").select2("val", clubData.clubdata['website_type']);
            $("#super_affiliate").select2("val", clubData.clubdata['super_affiliate']);
            
            if(clubData.clubdata['posters'] == 1)
                $("#posters").attr('checked', true);
            if(clubData.clubdata['beermats'] == 1)
                $("#beermats").attr('checked', true);
            if(clubData.clubdata['businesscards'] == 1)
                $("#business_cards").attr('checked', true);
            if(clubData.clubdata['hide_from_view'] == 1)
                $("#hide_view").attr('checked', true);
            if(clubData.clubdata['test_club'] == 1)
                $("#test_club").attr('checked', true);
             window.frmData = $( '#frmTournamentSaveData' ).serialize() ;
setTimeout(function(){ 
   
    if(vueTournamentsAdd.status == 'edit'){
    
        $('#submitTournament').prop('disabled', true);
         $( ":input" ).on('keyup',function(){
        chkFrmUpdate();
        });
        $( ":input" ).on('change',function(){
            chkFrmUpdate();
            
         });

        $("select, input[type=file]").on('change',function(){
            // var asd = vueClubsAdd.clubdata;
        chkFrmUpdate();
        });
         $('#file_img').on('change',function(){
            vueTournamentsAdd.chngImage = true;

        chkFrmUpdate();
           
         });

     function chkFrmUpdate(){
            // console.log('hi');
            var updatedData = $( '#frmTournamentSaveData' ).serialize() ;
           
           if(frmData != updatedData || vueTournamentsAdd.chngImage == true)
            {
                $('#submitTournament').prop('disabled', false);
            } else{
                $('#submitTournament').prop('disabled', true); 
            }
        }
    
    }
 }, 2000);

        }, 100);

    } else {
        window.location = "/club";
    }
}

function convertDate(dateObj, splitBy){
    dateObj1 = dateObj.split(" ");
    dateObj = dateObj1[0].split(splitBy);
    dateObj = dateObj[2] + "-" + dateObj[1] + "-" + dateObj[0];
    return dateObj;
}
