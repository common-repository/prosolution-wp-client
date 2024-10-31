<?php

	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
?>
<style>
    .prosolwpclientcustombootstrap .radio-inline{
        margin-left:0 !important;
    }
</style>
<!-- Attachment Modal -->
<div class="modal fade" id="attachmentModal" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?php esc_html_e( 'File Attachment', 'prosolwpclient' ) ?></h4>
			</div>
			<div class="modal-body">
				<div id="attachmentModalContent"></div>
                <!--- temporary for updating upload thirdparty library
                    <form action="/file-upload" class="dropzone" id="my-dropzone">
                        
                        
                        <div class="previews"></div>
                        
                        <button type="submit">Submit data and files!</button>
                    
                    </form>
                --->
            

				<script id="attachment_modal_template" type="x-tmpl-mustache">
                    


                    <form class="form-horizontal attachmentModalForm"
                        action="<?php echo esc_url( home_url( '/' ) ); ?>"
                        method="post" role="form"
                        enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="attachtype"
                                class="col-lg-2 control-label"><?php esc_html_e( 'Type', 'prosolwpclient' ) ?></label>

                            <div class="col-lg-10 error-msg-show">
                                <label class="radio-inline pswp-attach-radio-docu">
                                    <input name="attachtype" type="radio" value="docu" class="pswp-attach-type" id="attachtype" required checked>
                                    <span><?php esc_html_e( 'This is an application document', 'prosolwpclient' ) ?></span>
                                    <span class="radiomark" ></span>
                                </label>
                                <label class="radio-inline pswp-attach-radio-photo">
                                    <input name="attachtype" type="radio" value="photo" class="pswp-attach-type" id="attachtype" required> 
                                    <span><?php esc_html_e( 'This is my photo', 'prosolwpclient' ) ?></span>
                                    <span class="radiomark" ></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sidetitle"
                                class="col-sm-2 control-label"><?php esc_html_e( 'Title', 'prosolwpclient' ) ?> *</label>
                            <div class="col-sm-10 error-msg-show">
                                <input type="text" name="sidetitle"
                                    class="form-control pswp-side-title"
                                    id="sidetitle" required data-rule-maxlength="35"
                                    placeholder="<?php esc_html_e( 'Title', 'prosolwpclient' ) ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="pswp-side-description"
                                class="col-sm-2 control-label"><?php esc_html_e( 'Description', 'prosolwpclient' ) ?></label>
                            <div class="col-sm-10 error-msg-show">
                                <input type="text" name="description" class="form-control pswp-side-description"
                                    id="description" data-rule-maxlength="150"
                                    placeholder="<?php esc_html_e( 'Description', 'prosolwpclient' ) ?>">
                            </div>
                        </div>

                        <div class="form-group attachment_thread" id="attachmentThread">
                            <label for="pswp-file"
                                class="col-sm-2 control-label"><?php esc_html_e( 'File', 'prosolwpclient' ) ?> *</label>

                            <div class="col-sm-10 error-msg-show" id="fileupload_">
                                <!-- The fileinput-button span is used to style the file input field as button -->
                                <!-- <span class="btn btn-success fileinput-button">
                                    <i class="glyphicon glyphicon-plus"></i><span><?php // esc_html_e( 'Attach File', 'prosolwpclient' ) ?></span> -->

                                    <!-- The file input field used as target for the file upload widget -->
                                <!-- <input id="attachmentFileUpload" type="file" name="files[]" class="attachmentFileUpload"/></span><br><br> -->
                                
                                <span id="attached_file_info"
                                    data-name=""
                                    data-size=""
                                    data-newfilename=""
                                    data-mime-type=""
                                    data-ext="">
                                </span>

                                <input type="hidden" name="newfilename" class="newfilename" value=""
                                    id="newfilename" required/>
                                <input type="hidden" name="mime-type" class="uploaded-mime-type" value="" />
                                <input type="hidden" name="ext" class="uploaded-ext" value=""/>
                                <input type="hidden" name="filesize" class="uploaded-filesize" value=""/>

                                <!-- <div id="attachment_file_upload_progress" class="progress">
                                    <div class="progress-bar progress-bar-success"></div>
                                </div> -->

                                <?php $now_time = time(); ?>
                                <!-- The container for the uploaded files -->
                                <!-- <div id="attachment_file_upload">

                                </div> -->
                            </div>
                            <!--New bootstrap file input by vishruti -->
                            <div class="file-loading">
                                <input id="input-711" name="files[]" type="file">
                            </div>
                            <div id="kartik-file-errors"></div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10" style="float:right !important;">
                                <p style="">
                                    <?php esc_html_e( 'Allowed file extensions: ', 'prosolwpclient' ) ?>
                                    <b class="allowed-file-ext"><?php esc_html_e( 'pdf, doc, docx, xls, xlsx, txt, odt, ods, odp, rtf, pps, ppt, pptx, ppsx, vcf, msg, eml, ogg, mp3, wav, wma, asf, mov, avi, mpg, mpeg, mp4, wmf, 3g2, 3gp, png, jpg, jpeg, gif, bmp, tif, tiff, key, numbers, pages', 'prosolwpclient' ) ?></b>
                                </p>
                                <p>
                                    <?php esc_html_e( 'Maximum file size: ', 'prosolwpclient' ) ?><strong><?php esc_html_e( '2 MB', 'prosolwpclient' ) ?></strong>, <?php esc_html_e( 'Maximum total file size: ', 'prosolwpclient' ) ?>
                                    <strong><?php esc_html_e( '10 MB', 'prosolwpclient' ) ?></strong>
                                </p>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <span class="pull-left"><?php esc_html_e( '(*) =  Required!', 'prosolwpclient' ) ?></span>
                            <button type="button" class="btn btn-default"
                                data-bs-dismiss="modal"><?php esc_html_e( 'Abort, stop', 'prosolwpclient' ) ?>
                            </button>
                            <button type="submit" class="btn btn-default attach-modal-btn">
                                <?php esc_html_e( 'Submit', 'prosolwpclient' ) ?>
                            </button>
                        </div>
                    </form>

				</script>
			</div>
		</div>
	</div>
</div>
