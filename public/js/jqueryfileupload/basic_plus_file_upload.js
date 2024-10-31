(function ($) {
    'use strict';

	$(function () {
		var $attachmentFileUpload = $('#attachmentFileUpload1');
		var $maxFiles = 10;
		var $attachment_file_count = 0;

		// handle attachment file upload
		$attachmentFileUpload.fileupload({
			url: prosolObj.ajaxurl,
			formData: {
				action: "proSol_fileUploadProcess",
				security: prosolObj.nonce,
			},
			dataType: 'json',

			// acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
			acceptFileTypes: /^application\/(pdf|msword)$|^doc$|^docx$/i,
			autoUpload: true,
			maxFileSize: 2000000, // 2MB
			// Enable image resizing, except for Android and Opera,
			// which actually support image resizing, but fail to
			// send Blob objects via XHR requests:
			
		}).on('fileuploadadd', function (e, data) {

			if ($('#attachment_file_upload').find('.attachment-file-upload').length > 0) {
				$('#attachment_file_upload').find('.attachment-file-upload').remove();
			}
			//increase current file count for add file
			$attachment_file_count++;
			//take care file count , if >= max count then abort the process
			

			if ($attachment_file_count <= $maxFiles) {
				data.context = $('<div class="attachment-file-upload"/>').appendTo('#attachment_file_upload');
			} else {
				return false;
			}

			$.each(data.files, function (index, file) {

				var node = $('<p/>');
				var node_btn = $('<span/>');

				if (!index) {
					// node_btn
					
				}
				node.appendTo(data.context);
				node_btn.appendTo(data.context);

			});

		}).on('fileuploadprocessalways', function (e, data) {

			var index = data.index,
				file = data.files[index],
				node = $(data.context.children()[index]);
			if (file.preview) {
				node
				// .prepend('<br>')
					.prepend(file.preview);
			}
			if (file.error) {
				node
					.append('<br>')
					.append($('<span class="text-danger"/>').text(file.error));
			}
			if (index + 1 === data.files.length) {
				data.context.find('a.btn-delete')
					.text(prosolObj.delete)
					.prop('disabled', !!data.files.error);
			}
		}).on('fileuploadprogressall', function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$('#attachment_file_upload_progress .progress-bar').css(
				'width',
				progress + '%'
			);
		}).on('fileuploaddone', function (e, data) {
			var $this = $(this);

			$.each(data.result.files, function (index, file) {

				if (file.url) {
					


					var $attachmentThread = $('#attachmentThread');

					$attachmentThread.find('#attached_file_info').attr('data-name', file.name).attr('data-size', file.size)
						.attr('data-newfilename', file.newfilename).attr('data-mime-type', file.type).attr('data-ext', file.extension);

					$attachmentThread.find('.newfilename').val(file.newfilename);
					$attachmentThread.find('.uploaded-mime-type').val(file.type);
					$attachmentThread.find('.uploaded-ext').val(file.extension);

					// console.log('Done event: count = '+$attachment_file_count+' maxcount = '+$maxFiles);
				} else if (file.error) {
					var error = $('<span class="text-danger"/>').text(file.error);
					$(data.context.children()[index])
						.append('<br>')
						.append(error);
				}
			});
		}).on('fileuploadfail', function (e, data) {
			$.each(data.files, function (index) {
				var error = $('<span class="text-danger"/>').text(prosolObj.file_upload_failed);
				$(data.context.children()[index])
					.append('<br>')
					.append(error);
			});
		}).prop('disabled', !$.support.fileInput)
			.parent().addClass($.support.fileInput ? undefined : 'disabled');

	});
})(jQuery);

