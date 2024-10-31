(function ($) {
	'use strict';
	
	$(document).ready(function () {
		function getSelectedValue(skillgr,skillid,skillval){
			
	        var index = $('#knowledge_data :selected').val();
		    $.ajax({
		    	type : "post",
		    	url : prosolObj.ajaxurl,
		    	
		    	data : {
		    		action: 'goup_data_id',
		    		security: prosolObj.nonce,
		    		groupid:index

		    	},
		    	success:function(data){
		    		
					$('#expertiseModal .modal-dialog .modal-content .modal-body .form-group .table-responsive .table-fixheader div .pswp-expertise-table tbody').html(data);
					
		    	}
		    });
		}
	    function showdeletebutton(mainthis){
			        	
	    	var nthis = $(mainthis);
	    	
			var skillid = nthis.data('skillid');
			
			var skillgroupid = nthis.data('skillgroupid');
			
			var trash_col = nthis.parents('tr');
			
			trash_col.find('#skill_' + skillid + '_' + skillgroupid).css('display', 'block');
		}
	});
})(jQuery);