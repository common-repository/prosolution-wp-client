(function($) {
    'use strict';

    $(document).ready(function() {
        //Initiate Color Picker
        $('.wp-color-picker-field').wpColorPicker();

        // Switches option sections
        $('.group').hide();
        var activetab = '';
        if (typeof(localStorage) != 'undefined') {
            //get
            activetab = localStorage.getItem("prosolwpclientloactivetab");
        }

        //if url has section id as hash then set it as active or override the current local storage value
        if (window.location.hash) {
            if ($(window.location.hash).hasClass('group')) {
                activetab = window.location.hash;
                if (typeof(localStorage) != 'undefined') {
                    localStorage.setItem("prosolwpclientloactivetab", activetab);
                }
            }

        }


        if (activetab != '' && $(activetab).length && $(activetab).hasClass('group')) {
            $(activetab).fadeIn();
        } else {
            $('.group:first').fadeIn();
        }

        $('.group .collapsed').each(function() {
            $(this).find('input:checked').parent().parent().parent().nextAll().each(
                function() {
                    if ($(this).hasClass('last')) {
                        $(this).removeClass('hidden');
                        return false;
                    }
                    $(this).filter('.hidden').removeClass('hidden');
                });
        });

        if (activetab != '' && $(activetab + '-tab').length) {
            $(activetab + '-tab').addClass('nav-tab-active');
        } else {
            $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
        }

        $('.nav-tab-wrapper a').click(function(evt) {
            $('.nav-tab-wrapper a').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active').blur();
            var clicked_group = $(this).attr('href');
            if (typeof(localStorage) != 'undefined') {
                //set
                localStorage.setItem("prosolwpclientloactivetab", $(this).attr('href'));
            }
            $('.group').hide();
            $(clicked_group).fadeIn();
            evt.preventDefault();
        });


        $('.wpsa-browse').on('click', function(event) {
            event.preventDefault();

            var self = $(this);

            // Create the media frame.
            var file_frame = wp.media.frames.file_frame = wp.media({
                title: self.data('uploader_title'),
                button: {
                    text: self.data('uploader_button_text')
                },
                multiple: false
            });

            file_frame.on('select', function() {
                var attachment = file_frame.state().get('selection').first().toJSON();

                self.prev('.wpsa-url').val(attachment.url);
            });

            // Finally, open the modal
            file_frame.open();
        });

        // 1.7.8, upload logo in designtemplate
        $('input#prosolwpclient_designtemplate\\[' + selsite + 'deslogo\\], .wpul-browse').on('click', function(event) {
            event.preventDefault();

            var self = $(this);

            // Create the media frame.
            var file_frame = wp.media.frames.file_frame = wp.media({
                title: self.data('uploader_title'),
                button: {
                    text: self.data('uploader_button_text')
                },
                multiple: false
            });

            file_frame.on('select', function() {
                var attachment = file_frame.state().get('selection').first().toJSON();
                self.next('#prosolwpclient_designtemplate\\[' + selsite + 'deslogo\\], .wpul-url').html(attachment.filename);
                self.nextAll().slice(2, 3).attr("src", attachment.url).load();
                $('#prosolwpclient_designtemplate\\[' + selsite + 'deslogofile\\] ').val(attachment.url);
                $('#prosolwpclient_designtemplate\\[' + selsite + 'deslogoname\\] ').val(attachment.filename);

                //self.nextAll().slice(3,4).attr("value",attachment.url);
            });

            // Finally, open the modal
            file_frame.open();
        });

        //add chooser
        $(".prosolwpclient-chosen-select").chosen();

        // jquery validate settings api form
        $.validator.setDefaults({ ignore: ":hidden:not(select)" }) //for all select
        $.extend($.validator.messages, {
            required: prosolwpclient_setting.required,
            remote: prosolwpclient_setting.remote,
            email: prosolwpclient_setting.email,
            url: prosolwpclient_setting.url,
            date: prosolwpclient_setting.date,
            dateISO: prosolwpclient_setting.dateISO,
            number: prosolwpclient_setting.number,
            digits: prosolwpclient_setting.digits,
            creditcard: prosolwpclient_setting.creditcard,
            equalTo: prosolwpclient_setting.equalTo,
            extension: prosolwpclient_setting.extension,
            maxlength: $.validator.format(prosolwpclient_setting.maxlength),
            minlength: $.validator.format(prosolwpclient_setting.minlength),
            rangelength: $.validator.format(prosolwpclient_setting.rangelength),
            range: $.validator.format(prosolwpclient_setting.range),
            max: $.validator.format(prosolwpclient_setting.max),
            min: $.validator.format(prosolwpclient_setting.min),
        });

        $(".prosolwpclient-chosen-select").chosen().on('change', function() {

            var ID = $(this).attr("id");
            //var $select_id_clean = ('#' + ID).replace(/-/g, '_');
            var $select_id_clean = ID.replace(/[^\w]/g, '_');



            if (!$(this).valid()) {
                $('#' + $select_id_clean + "_chosen a").addClass("input-validation-error");
            } else {
                $('#' + $select_id_clean + "_chosen a").removeClass("input-validation-error");
            }
        });

        var $prosolsettingapiform = $('.prosolsettingapiform');
        $('.prosolsettingapiform').each(function(index, element) {
            var formvalidator = $(element).validate({
                errorPlacement: function(error, element) {
                    error.appendTo(element.parents('td'));
                },
                errorElement: 'p'
            });
        });


        var defselsite = '';
        var newselsite = '';
        var getcookie = document.cookie;
        var cookie_arr = getcookie.split(";");
        cookie_arr.forEach(function(item) {
            var item_arr = item.split("=");
            if (item_arr[0].trim() == 'selsite') defselsite = item_arr[1];
            if (item_arr[0].trim() == 'removesite') newselsite = item_arr[1];
        });

        //assign default site after save changes 'remove site'
        if (newselsite != '') {
            if (newselsite.length) {
                newselsite = newselsite.split(",");
                newselsite.forEach(function(item) {
                    if (defselsite >= item) defselsite--;
                });
            } else {
                if (defselsite >= newselsite) defselsite--;
            }
            document.cookie = "selsite=" + defselsite;
        }

        if (defselsite == '') {
            $('select#selsite option\[value=0\]').attr('selected', 'selected');
            defselsite = 0;
            document.cookie = "selsite=" + defselsite;
        } else {
            $('select#selsite option\[value=' + defselsite + '\]').attr('selected', 'selected');
        }

        var selectval = $('#selsite option:selected').val();
        $('#selsite').on('change', function() {
            selectval = $('#selsite option:selected').val();
            //sessionStorage.setItem("selsite", selectval);
        });

        $('#submitselsite').on('click', function(e) {
            selectval = $('#selsite option:selected').val();
            document.cookie = "selsite=" + selectval;
        });

        var idAddSites = '#prosolwpclient_additionalsite';
        var totalsites = $(idAddSites + '\\[valids\\]').val();
        //update total sites tab additional site 
        $('#addsites').on('click', function(e) {
            e.preventDefault();
            totalsites++;
            $('#prosolwpclient_additionalsite\\[valids\\]').val(totalsites);
            var contsite = "<tr class='dumsites" + totalsites + "'><th scope='row'><label>site" + totalsites + " name</label></th><td>";
            contsite += "<input class='regular-text' style='background-color:#eaeaea' disabled>&nbsp;Url id&nbsp;</span><input class='addsite" + totalsites + "' style='background-color:#eaeaea' disabled>  ";
            contsite += "&nbsp;&nbsp;<a id='remsites' class='button button-default' target='_blank' data-urlid=" + totalsites + ">" + $('span.removesite').html() + "</a></td></tr>";
            $('#prosolwpclient_additionalsite tbody').append(contsite);
            $('tr.valids:visible').appendTo('#prosolwpclient_additionalsite tbody');
            $('a#remsites').removeClass('disabled');
            //alert(',totalsite='+totalsites);
        });

        if (!$('#prosolwpclient_additionalsite tbody').hasClass('dumsites') && totalsites == 0) $('#remsites').addClass("disabled");
        var remsite_arr = new Array();

        //flag to adjust new index after save changes 'remove site'
        $('#prosolwpclient_additionalsite\\[chkremove\\]').val(0);

        document.cookie = 'removesite' + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
        $(idAddSites).on('click', 'a#remsites', function(e) {
            e.preventDefault();
            var selurlid = $(this).data("urlid");
            if ($('#prosolwpclient_additionalsite tbody tr').hasClass('dumsites' + selurlid)) { //for dummies
                $('.dumsites' + selurlid).remove();
                totalsites--;
                $('#prosolwpclient_additionalsite\\[valids\\]').val(totalsites);
            } else {
                if (defselsite != selurlid) {
                    if (typeof remsite_arr != "undefined" && remsite_arr != null && remsite_arr.length != null && remsite_arr.length > 0) {
                        if ($.inArray(selurlid, remsite_arr) === -1) remsite_arr.push(selurlid);
                    } else {
                        remsite_arr.push(selurlid);
                    }

                    $('.addsite' + selurlid).remove();
                    totalsites--;
                    $('#prosolwpclient_additionalsite\\[valids\\]').val(totalsites);
                } else {
                    alert($('span.alertsite').html());
                }
            }
            if (typeof remsite_arr != "undefined" && remsite_arr != null && remsite_arr.length != null && remsite_arr.length > 0) {
                document.cookie = 'removesite=' + remsite_arr.join(',');
            }
            $('#prosolwpclient_additionalsite\\[chkremove\\]').val(1);
        });

        //hide hidden label 		
        var selsite = '';
        if (selectval != 0) {
            for (var x = 1; x <= totalsites; x++) {
                if (x != selectval) {
                    $('[class^=site' + x + ']').hide();
                }
            }
            //hide master field when site selected
            //tab api config
            $('.api_url').hide();
            $('.api_user').hide();
            $('.api_pass').hide();
            $('div#prosolwpclient_api_config tbody').append($('.btn_url_check')); //move position to last n-child, all site using master button 'url check'
            //tab frontend
            $('.frontend_pageid').hide();
            $('.default_nation').hide();
            $('.default_office').hide();
            $('.enable_recruitment').hide();
            $('.client_list').hide();
            //tab language
            $('.default_language').hide();
            //tab application form			
            $('.one_pager').hide();
            $('.step_label').hide();
            $('.personaldata').hide();
            $('.education').hide();
            $('.workexperience').hide();
            $('.expertise').hide();
            $('.sidedishes').hide();
            $('.others').hide();
            $('.personaldata_act').hide();
            $('.education_act').hide();
            $('.workexperience_act').hide();
            $('.expertise_act').hide();
            $('.sidedishes_act').hide();
            $('.others_act').hide();
            //tab privacy poliy
            $('.policy1').hide();
            $('.policy2').hide();
            $('.policy3').hide();
            $('.policy4').hide();
            $('.policy5').hide();
            $('.policy6').hide();
            //tab design template
            $('.destemplate').hide();
            $('.desfont').hide();
            $('.dessortby').hide();
            $('.desmaincolor').hide();
            $('.deslogo').hide();
            $('.desperpage').hide();
            $('.dessearchfrontend').hide();
            $('.dessearchheading').hide();
            $('.dessearchjobtitle').hide();
            $('.dessearchplace').hide();
            $('.dessearchsearchbtn').hide();
            $('.dessearchjobidbtn').hide();
            $('.desresultfrontend').hide();
            $('.desbtnfrontendback').hide();
            $('.desdetailsfrontend').hide();
            $('.desbtndetailsback').hide();
            $('.desbtndetailsapplywhatsapp').hide();
            $('.desbtndetailsapply').hide();
            $('.ApplyFrom').hide();
            $('.desbtnappformtodetails').hide();
            $('.desbtnappformtosearch').hide();
            $('.desbtnappformtohome').hide();
            $('.desbtnappformnext').hide();
            $('.desbtnappformback').hide();

            selsite = 'site' + selectval + '_';
        } else { $('[class^=site]').hide(); }


        var idclientls = '#prosolwpclient_frontend\\[' + selsite + 'client_list\\]';
        var cl_currentval = $(idclientls).val();
        $(idclientls).on('change', function() {
            var listcl, strval = '';
            var ic, isinvalid = 0;
            listcl = $(this).val();
            listcl = listcl.split(",");
            if (listcl.length > 5) {
                $(this).val(cl_currentval);
                alert($('.cllist_warningtext').html());
                isinvalid = 1;
            }

            for (ic = 0; ic < listcl.length; ic++) {
                var chch = parseInt(listcl[ic]);
                if ((isNaN(chch) || listcl[ic].indexOf('.') > 0) && $(this).val() != '') {
                    $(this).val(cl_currentval);
                    alert($('.cllista_warningtext').html());
                    isinvalid = 1;
                }
            }

            if (isinvalid == 0) {
                cl_currentval = listcl;
                $(this).val(listcl);

            }
        });

        if ($('input#wpuf-prosolwpclient_frontend\\[' + selsite + 'enable_recruitment\\]').is(':checked')) {
            $('.' + selsite + 'client_list').show();
            $('#prosolwpclient_designtemplate-tab').show();
            $('#prosolwpclient_frontend\\[enable_recruitment\\]').val('on');
            $('#wpuf-prosolwpclient_frontend\\[enable_recruitment\\]').val('on');
        } else {
            $('.' + selsite + 'client_list').hide();
            $('#prosolwpclient_designtemplate-tab').hide();
            $('#prosolwpclient_frontend\\[enable_recruitment\\]').val('off');
            $('#wpuf-prosolwpclient_frontend\\[enable_recruitment\\]').val('off');
        }

        $('input#wpuf-prosolwpclient_frontend\\[' + selsite + 'enable_recruitment\\]').on('change', function() {
            if ($(this).is(':checked')) {
                $('.' + selsite + 'client_list').show();
                $('#prosolwpclient_designtemplate-tab').show();
                $('#prosolwpclient_frontend\\[enable_recruitment\\]').val('on');
                $('#wpuf-prosolwpclient_frontend\\[enable_recruitment\\]').val('on');
            } else {
                $('.' + selsite + 'client_list').hide();
                $('#prosolwpclient_designtemplate-tab').hide();
                $('#prosolwpclient_frontend\\[enable_recruitment\\]').val('off');
                $('#wpuf-prosolwpclient_frontend\\[enable_recruitment\\]').val('off');
                //set destemplate to 'no template'
                $('#prosolwpclient_designtemplate\\[' + selsite + 'destemplate\\] option[value=0]').removeAttr('selected');
                $('#prosolwpclient_designtemplate\\[' + selsite + 'destemplate\\] option[value=1]').attr('selected', 'selected');
                var gettext = $('#prosolwpclient_designtemplate\\[' + selsite + 'destemplate\\] option[value=1]').text();

                if (selsite == '') {
                    var tabsite = '_';
                } else {
                    var tabsite = selsite;
                }
                $('#prosolwpclient_designtemplate' + tabsite + 'destemplate__chosen .chosen-single span').text(gettext);
                $('#prosolwpclient_designtemplate\\[' + selsite + 'destemplate\\]').trigger('change');
            }
        });

        var idAppForm = '#prosolwpclient_applicationform';
        var list_app_form = [selsite + "personaldata", selsite + "education", selsite + "workexperience", selsite + "expertise", selsite + "sidedishes", selsite + "others"];
        var fields_section = {};
        fields_section[selsite + "personaldata"] = ['title', 'federal', 'phone', 'mobile', 'email', 'nationality', 'marital', 'gender', 'diverse', 'expectedsalary', 'countrybirth', 'availfrom', 'notes'];
        fields_section[selsite + "education"] = ['group', 'training', 'beginning', 'end', 'postcode', 'country', 'federal', 'level', 'foact', 'business', 'description'];
        fields_section[selsite + "workexperience"] = ['job', 'beginning', 'end', 'description', 'gendesc', 'company', 'postcode', 'country', 'federal', 'experience', 'contract', 'employment'];
        fields_section[selsite + "others"] = ['source', 'apply', 'message'];
        if ($('input#wpuf-prosolwpclient_frontend\\[' + selsite + 'enable_recruitment\\]').is(':checked')) {
            fields_section[selsite + "personaldata"] = ['title', 'federal', 'phone', 'mobile', 'email', 'nationality', 'marital', 'gender', 'diverse', 'expectedsalary', 'countrybirth', 'availfrom', 'notes', 'Title_profileText1', 'Title_profileText2', 'Title_profileText3', 'Title_profileText4', 'Title_profileText5', 'Title_profileText6', 'Title_profileText7', 'Title_profileText8', 'Title_profileText9', 'Title_profileText10', 'Title_profileOption1', 'Title_profileOption2', 'Title_profileOption3', 'Title_profileOption4', 'max_distance', 'empgroup_ID', 'tagid'];
        }

        //hide Step name fields if one pager selected
        if ($(idAppForm + '\\[' + selsite + 'one_pager\\]').val() == 0) {
            $('tr.' + selsite + 'step_label').hide();
        } else {
            $('tr.' + selsite + 'step_label').show();
        }
        $(idAppForm + '\\[' + selsite + 'one_pager\\]').on('change', function() {
            if ($(this).val() == 0) {
                $('tr.' + selsite + 'step_label').hide();
            } else {
                $('tr.' + selsite + 'step_label').show();
            }
        });

        list_app_form.forEach(function(item) {
            $(idAppForm + '\\[' + item + '_view\\]').on('click', function() {
                if ($(this).is(':checked')) {
                    $(idAppForm + '\\[' + item + '_act\\]').val('1');
                } else {
                    $(idAppForm + '\\[' + item + '_act\\]').val('0');
                }
            });
            $(idAppForm + '\\[' + item + '_mandatoryview\\]').on('click', function() {
                if ($(this).is(':checked')) {
                    $(idAppForm + '\\[' + item + '_man\\]').val('1');
                } else {
                    $(idAppForm + '\\[' + item + '_man\\]').val('0');
                }
            });
        });

        for (const [key, field] of Object.entries(fields_section)) {
            field.forEach(function(item) {
                var getkey = key.substr(key.length - 12);
                $(idAppForm + '\\[' + key + '_' + item + '_view1\\]').on('click', function() { //hide / show
                    if ($(this).is(':checked')) {
                        $(idAppForm + '\\[' + key + '_' + item + '_act\\]').val('1');
                        $(idAppForm + '\\[' + key + '_' + item + '_view2\\]').prop("disabled", false);

                        //custom setting
                        if (getkey == 'personaldata' && item == 'gender') {
                            $(idAppForm + '\\[' + key + '_' + item + '_view3\\]').prop("disabled", false);
                            $('.diverse_style').css("color", "black");
                        }
                    } else {
                        $(idAppForm + '\\[' + key + '_' + item + '_act\\]').val('0');
                        $(idAppForm + '\\[' + key + '_' + item + '_view2\\]').prop("disabled", true);
                        $(idAppForm + '\\[' + key + '_' + item + '_view2\\]').prop("checked", false);
                        $(idAppForm + '\\[' + key + '_' + item + '_man\\]').val('0');

                        //custom setting
                        if (getkey == 'personaldata' && item == 'gender') {
                            $(idAppForm + '\\[' + key + '_diverse_act\\]').val('0');
                            $(idAppForm + '\\[' + key + '_' + item + '_view3\\]').prop("disabled", true);
                            $(idAppForm + '\\[' + key + '_' + item + '_view3\\]').prop("checked", false);
                            $('.diverse_style').css("color", "gray");
                        }

                    }
                });

                $(idAppForm + '\\[' + key + '_' + item + '_view2\\]').on('click', function() { //mandatory
                    if ($(this).is(':checked')) {
                        $(idAppForm + '\\[' + key + '_' + item + '_man\\]').val('1');
                    } else {
                        $(idAppForm + '\\[' + key + '_' + item + '_man\\]').val('0');
                    }
                });

                $(idAppForm + '\\[' + key + '_' + item + '_view3\\]').on('click', function() { //custom setting (3rd column)
                    if ($(this).is(':checked')) {
                        $(idAppForm + '\\[' + key + '_diverse_act\\]').val('1');
                    } else {
                        $(idAppForm + '\\[' + key + '_diverse_act\\]').val('0');
                    }
                });
            });
        }

        if ($(idAppForm + '\\[expertise_furtherskill_act\\]').val() == '0') $('ul.skillgr_setting').hide();
        $(idAppForm + '\\[expertise_furtherskill_view4\\]').on('click', function() { //custom setting (expertise, furtherskill)
            if ($(this).is(':checked')) {
                $(idAppForm + '\\[expertise_furtherskill_act\\]').val('1');
                $('ul.skillgr_setting').show();
            } else {
                $(idAppForm + '\\[expertise_furtherskill_act\\]').val('0');
                $('ul.skillgr_setting').hide();
            }
        });


        var idPolicy = '#prosolwpclient_privacypolicy';
        var list_policy = [selsite + "policy1", selsite + "policy2", selsite + "policy3", selsite + "policy4", selsite + "policy5", selsite + "policy6"];
        if (!$('input#wpuf-prosolwpclient_frontend\\[' + selsite + 'enable_recruitment\\]').is(':checked')) {
            $('.' + selsite + 'policy6').addClass('hidden');
            $(idPolicy + '\\[' + selsite + 'policy6_act\\]').val('0');
        }

        list_policy.forEach(function(item) {
            $(idPolicy + '\\[' + item + '_view\\]').on('click', function() {
                if ($(this).is(':checked')) {
                    $(idPolicy + '\\[' + item + '_act\\]').val('1');
                } else {
                    $(idPolicy + '\\[' + item + '_act\\]').val('0');
                }
            });
        });

        var idDesTemplate = '#prosolwpclient_designtemplate';
        var list_destempchk = [selsite + 'dessearchjobidbtn', selsite + 'desresultzipcode', selsite + 'desresultplaceofwork', selsite + 'desresultworkplacename', selsite + 'desresultworktime', selsite + 'desresultagentname', selsite + 'desresultjobprojectid', selsite + 'desresultcustomer', selsite + 'desdetailszipcode', selsite + 'desdetailsplaceofwork', selsite + 'desdetailsworktime', selsite + 'desdetailssalary', selsite + 'desdetailsprofession', selsite + 'desdetailsqualification', selsite + 'desdetailsagentname', selsite + 'desdetailsjobprojectid', selsite + 'desdetailscustomer', selsite + 'desdetailstextfield1', selsite + 'desdetailstextfield2', selsite + 'desdetailstextfield3', selsite + 'desdetailstextfield4', selsite + 'desdetailstextfield5', selsite + 'desdetailstextfield6', selsite + 'desdetailstextfield7', selsite + 'desdetailstextfield8', selsite + 'desdetailstextfield9', selsite + 'desdetailstextfield10', selsite + 'desdetailstextfield11', selsite + 'desdetailstextfield12', selsite + 'desdetailstextfield13', selsite + 'desdetailstextfield14', selsite + 'desdetailstextfield15', selsite + 'desdetailstextfield16', selsite + 'desdetailstextfield17', selsite + 'desdetailstextfield18', selsite + 'desdetailstextfield19', selsite + 'desdetailstextfield20', selsite + 'desdetailstextfield21', selsite + 'desdetailstextfield22', selsite + 'desdetailstextfield23', selsite + 'desdetailstextfield24', selsite + 'desdetailstextfield25', selsite + 'desdetailstextfield26', selsite + 'desdetailstextfield27', selsite + 'desdetailstextfield28', selsite + 'desdetailstextfield29', selsite + 'desdetailstextfield30'];

        list_destempchk.forEach(function(item) {
            $(idDesTemplate + '\\[' + item + '_view\\]').on('click', function() {
                if ($(this).is(':checked')) {
                    $(idDesTemplate + '\\[' + item + '_act\\]').val('1');
                    if (item == selsite + 'desresultcustomer' || item == selsite + 'desdetailscustomer') {
                        $(idDesTemplate + '\\[' + item + '_text\\]').show();
                    }
                } else {
                    $(idDesTemplate + '\\[' + item + '_act\\]').val('0');
                    if (item == selsite + 'desresultcustomer' || item == selsite + 'desdetailscustomer') {
                        $(idDesTemplate + '\\[' + item + '_text\\]').hide();
                    }
                }
            });

            // set default        
            if ($(idDesTemplate + '\\[' + selsite + 'desresultcustomer_act\\]').val() == '0') {
                $(idDesTemplate + '\\[' + selsite + 'desresultcustomer_text\\]').hide();
            }
            if ($(idDesTemplate + '\\[' + selsite + 'desdetailscustomer_act\\]').val() == '0') {
                $(idDesTemplate + '\\[' + selsite + 'desdetailscustomer_text\\]').hide();
            }
        });

        var list_destemp = [
            selsite + 'desfont', selsite + 'dessortby', selsite + 'desmaincolor', selsite + 'deslogo', selsite + 'desperpage', selsite + 'dessearchfrontend', selsite + 'dessearchheading', selsite + 'dessearchjobtitle', selsite + 'dessearchplace', selsite + 'dessearchsearchbtn', selsite + 'dessearchjobidbtn', selsite + 'desresultfrontend', selsite + 'desbtnfrontendback', selsite + 'desbtndetailsback', selsite + 'desbtndetailsapplywhatsapp', selsite + 'desbtndetailsapply', selsite + 'desdetailsfrontend', selsite + 'desbtnappformtohome', selsite + 'desbtnappformtodetails', selsite + 'desbtnappformtosearch', selsite + 'desbtnappformnext', selsite + 'desbtnappformback'
        ];

        //add option for font
        //var fontlist={};
        //fontlist={option1:{value:1,text:1},option2:{value:2,text:2}};
        //console.log(fontlist);
        // for(const [key, field] in fontlist){
        // 	$(idDesTemplate+'\\[desfont\\]').append($('<option>', { 
        // 		value: fontlist(fontopt).value,
        // 		text : fontlist(fontopt).text
        // 	}));
        // }

        //default value show / hide tab design template
        if ($(idDesTemplate + '\\[' + selsite + 'destemplate\\]').val() == 1) {
            list_destemp.forEach(function(item) {
                $('tr.' + item).hide();
            });
            $('.' + selsite + 'desbtnresulttojob').hide();
            $('.' + selsite + 'FrontendDetails').hide();
            $('.' + selsite + 'ApplyFrom').hide();
        }
        $(idDesTemplate + '\\[' + selsite + 'destemplate\\]').on("change", function() {
            if ($(this).val() == 0) {
                list_destemp.forEach(function(item) {
                    $('tr.' + item).show();
                });
            } else {
                list_destemp.forEach(function(item) {
                    $('tr.' + item).hide();
                });
                $('.' + selsite + 'desbtnresulttojob').hide();
                $('.' + selsite + 'FrontendDetails').hide();
                $('.' + selsite + 'ApplyFrom').hide();
            }
        });

        function findSingleChecked(whichsite) {
            var isFindSingleChk = 1;
            for (const [key, field] of Object.entries(fields_section)) {
                if ($(idAppForm + '\\[' + whichsite + key + '_act\\]').val() == 1 && key != 'personaldata' && key != 'education' && key != 'workexperience') {
                    isFindSingleChk = 0;
                    field.forEach(function(item) {
                        if ($(idAppForm + '\\[' + whichsite + key + '_' + item + '_act\\]').val() == 1) {
                            isFindSingleChk = 1;
                        }
                    });
                    if (isFindSingleChk == 0) {
                        alert($('.warningtext').html());
                        break;
                    }
                }
            }

            if (isFindSingleChk == 0) {
                return false;
            } else {
                return true;
            }
        }


        $('.apivalid').on('click', function(e) {
            e.preventDefault();

            var button_check = $('.apivalid');
            var url_value = $('#prosolwpclient_api_config\\[' + selsite + 'api_url\\]'); //char [ ] must be escaped
            var user_value = $('#prosolwpclient_api_config\\[' + selsite + 'api_user\\]');
            var pass_value = $('#prosolwpclient_api_config\\[' + selsite + 'api_pass\\]');
            var button_view = $('.apivalid .button-primary');
            var current_txtbutton = button_view.html();
            button_view.addClass('hidden');

            if (!button_check.hasClass('process-busy')) {
                button_check.addClass('process-busy');
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: prosolwpclient_setting.ajaxurl,
                    data: {
                        action: "proSol_url_validate",
                        urlval: url_value.val(),
                        userval: user_value.val(),
                        passval: pass_value.val(),
                        security: prosolwpclient_setting.nonce
                    },
                    success: function(data, textStatus, XMLHttpRequest) {
                        button_check.removeClass('process-busy');
                        button_view.html(data.message);
                        button_view.removeClass('hidden');

                        if (parseInt(data.error) == 1) {
                            button_view.addClass('notvalid');
                            url_value.addClass('error');
                        } else if (parseInt(data.error) == 2) {
                            button_view.addClass('notvalid');
                            user_value.addClass('error');
                            pass_value.addClass('error');
                        } else {
                            button_view.addClass('valid');
                        }
                        setTimeout(function() {
                            button_view.html(current_txtbutton);
                            button_view.removeClass('valid');
                            button_view.removeClass('notvalid');
                        }, 6000);
                    },
                    error: function(request, status, error) {
                        //var txtbutton = JSON.parse( request.responseText );
                        //console.log(request);
                        //console.log(txtbutton);
                        button_check.removeClass('process-busy');
                        //button_view.html(txtbutton);
                        button_view.removeClass('hidden');

                        button_view.addClass('notvalid');
                        user_value.addClass('error');
                        pass_value.addClass('error');

                        setTimeout(function() {
                            button_view.html(current_txtbutton);
                            button_view.removeClass('valid');
                            button_view.removeClass('notvalid');
                        }, 3000);
                    }
                });
            }
        });
        $('form').on('submit', function(e) {
            var isValid = findSingleChecked(selsite);
            if (isValid == false) {
                e.preventDefault();
            }

            var savedapiconfig = $('#prosolwpclient_api_config\\[' + selsite + 'api_pass\\]');

            if (savedapiconfig.val() == '') {
                //savedapiconfig.val($('#prosolwpclient_api_config\\[' + selsite + 'oldapi_pass\\]').val());

            };
        });
    });
})(jQuery);