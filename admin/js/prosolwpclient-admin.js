(function ($) {
    'use strict';

    jQuery(document).ready(function ($) {

        var defselsite = '';
		var getcookie = document.cookie; 
		var cookie_arr = getcookie.split(";");
		cookie_arr.forEach(function(item){
			var item_arr = item.split("=");
			if(item_arr[0].trim() == 'selsite')defselsite=item_arr[1];
		});
	
		if(defselsite == ''){
			$('select#selsite option\[value=0\]').attr('selected','selected');
		}else{
			$('select#selsite option\[value='+defselsite+'\]').attr('selected','selected');
		}

		var selectval = $('#selsite option:selected').val();
		$('#selsite').on('change',function(){
			selectval = $('#selsite option:selected').val();
            //sessionStorage.setItem("selsite", selectval);
		});

		$('#submitselsite').on('click', function(e){
			selectval = $('#selsite option:selected').val();
            document.cookie = "selsite="+selectval; 
		});	
        
        //click on sync button if api key exists and send ajax request for sync
        $('.prosolsyncsingle').on('click', function (e) {
            e.preventDefault();

            var $this = $(this);
            var tablename = $this.data('tablename');
            var redirect = parseInt($this.data('redirect'));
            var syntype = $this.data('synctype');

            $this.attr("disabled", true);
            var sync_time_id = $('#synctime-' + tablename);
            //var sync_time_val_backup = sync_time_id.text();
			sync_time_id.html('');
			sync_time_id.addClass('synctime-common-busy');

            $.ajax({
                type: "post",
                dataType: "json",
                url: prosolwpclient.ajaxurl,
                data: {
                    action: "proSol_ajaxTablesync",
                    table: tablename,
                    security: prosolwpclient.nonce,
                    synctype: syntype
                },
                success: function (data, textStatus, XMLHttpRequest) {
                    $this.attr("disabled", false);  
                    if (parseInt(data.error) == 1) {
                        alert(data.message);
						sync_time_id.removeClass('synctime-common-busy');
						sync_time_id.html(prosolwpclient.sync_failed);
                    }
                    else { 
                        $(".syncjobsch").removeAttr("disabled");
						sync_time_id.removeClass('synctime-common-busy');
                        sync_time_id.html(data.synctime);
                        window.location.reload();
                        if (redirect == 1) {
                            window.location = $this.attr('href');
                        }
                    }
                }
            });
        });

        //click on clear log
        $('.prosolrefreshlog_reset').on('click', function (e) {
            e.preventDefault();

            var $this = $(this);
            var clear_type = $this.data('cleartype');

            $this.attr("disabled", true);

            $('#prosolwpclient_log').html('<li>' + prosolwpclient.loading + '</li>');

            $.ajax({

                type: "post",
                dataType: "json",
                url: prosolwpclient.ajaxurl,
                data: {
                    action: "proSol_ajaxClearlog",
                    security: prosolwpclient.nonce,
                    cleartype: clear_type
                },
                success: function (data, textStatus, XMLHttpRequest) {
                    $this.attr("disabled", false);

                    $('#prosolwpclient_log').html(data);
                }
            });
        });
    });

})(jQuery);
