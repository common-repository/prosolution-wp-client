<?php

	// If this file is called directly, abort.
	if (!defined('WPINC')) {
		die;
	}
?>
<?php
    /*
     * jQuery File Upload Plugin PHP Class
     * https://github.com/blueimp/jQuery-File-Upload
     *
     * Copyright 2010, Sebastian Tschan
     * https://blueimp.net
     *
     * Licensed under the MIT license:
     * http://www.opensource.org/licenses/MIT
     */

    class CBXProSolWpClient_UploadHandler
    {

        protected $options;

        // PHP File Upload error message codes:
        // http://php.net/manual/en/features.file-upload.errors.php
        protected $error_messages = array(
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk',
            8 => 'A PHP extension stopped the file upload',
            'post_max_size' => 'The uploaded file exceeds the post_max_size directive in php.ini',
            'max_file_size' => 'File is too big',
            'min_file_size' => 'File is too small',
            'accept_file_types' => 'Filetype not allowed',
            'max_number_of_files' => 'Maximum number of files exceeded',
            'max_width' => 'Image exceeds maximum width',
            'min_width' => 'Image requires a minimum width',
            'max_height' => 'Image exceeds maximum height',
            'min_height' => 'Image requires a minimum height',
            'abort' => 'File upload aborted',
            'image_resize' => 'Failed to resize image'
        );

        protected $image_objects = array();

        public function __construct($options = null, $initialize = true, $error_messages = null) {
            $this->response = array();
            $this->options = array(
                'script_url' => $this->proSol_getFullUrl().'/'.$this->proSol_basename($this->proSol_getServerVar('SCRIPT_NAME')),
                'upload_dir' => dirname($this->proSol_getServerVar('SCRIPT_FILENAME')).'/files/',
                'upload_url' => $this->proSol_getFullUrl().'/files/',
                'input_stream' => 'php://input',
                'user_dirs' => false,
                'mkdir_mode' => 0755,
                'param_name' => 'files',
                // Set the following option to 'POST', if your server does not support
                // DELETE requests. This is a parameter sent to the client:
                'delete_type' => 'DELETE',
                'access_control_allow_origin' => '*',
                'access_control_allow_credentials' => false,
                'access_control_allow_methods' => array(
                    'OPTIONS',
                    'HEAD',
                    'GET',
                    'POST',
                    'PUT',
                    'PATCH',
                    'DELETE'
                ),
                'access_control_allow_headers' => array(
                    'Content-Type',
                    'Content-Range',
                    'Content-Disposition'
                ),
                // By default, allow redirects to the referer protocol+host:
                'redirect_allow_target' => '/^'.preg_quote(
                        parse_url($this->proSol_getServerVar('HTTP_REFERER'), PHP_URL_SCHEME)
                        .'://'
                        .parse_url($this->proSol_getServerVar('HTTP_REFERER'), PHP_URL_HOST)
                        .'/', // Trailing slash to not match subdomains by mistake
                        '/' // preg_quote delimiter param
                    ).'/',
                // Enable to provide file downloads via GET requests to the PHP script:
                //     1. Set to 1 to download files via readfile method through PHP
                //     2. Set to 2 to send a X-Sendfile header for lighttpd/Apache
                //     3. Set to 3 to send a X-Accel-Redirect header for nginx
                // If set to 2 or 3, adjust the upload_url option to the base path of
                // the redirect parameter, e.g. '/files/'.
                'download_via_php' => false,
                // Read files in chunks to avoid memory limits when download_via_php
                // is enabled, set to 0 to disable chunked reading of files:
                'readfile_chunk_size' => 10 * 1024 * 1024, // 10 MiB
                // Defines which files can be displayed inline when downloaded:
                'inline_file_types' => '/\.(gif|jpe?g|png)$/i',
                // Defines which files (based on their names) are accepted for upload:
                'accept_file_types' => '/.+$/i',
                // The php.ini settings upload_max_filesize and post_max_size
                // take precedence over the following max_file_size setting:
                'max_file_size' => null,
                'min_file_size' => 1,
                // The maximum number of files for the upload directory:
                'max_number_of_files' => null,
                // Defines which files are handled as image files:
                'image_file_types' => '/\.(gif|jpe?g|png)$/i',
                // Use exif_imagetype on all files to correct file extensions:
                'correct_image_extensions' => false,
                // Image resolution restrictions:
                'max_width' => null,
                'max_height' => null,
                'min_width' => 1,
                'min_height' => 1,
                // Set the following option to false to enable resumable uploads:
                'discard_aborted_uploads' => true,
                // Set to 0 to use the GD library to scale and orient images,
                // set to 1 to use imagick (if installed, falls back to GD),
                // set to 2 to use the ImageMagick convert binary directly:
                'image_library' => 1,
                // Uncomment the following to define an array of resource limits
                // for imagick:

                // Command or path for to the ImageMagick convert binary:
                'convert_bin' => 'convert',
                // Uncomment the following to add parameters in front of each
                // ImageMagick convert call (the limit constraints seem only
                // to have an effect if put in front):
                /*
                'convert_params' => '-limit memory 32MiB -limit map 32MiB',
                */
                // Command or path for to the ImageMagick identify binary:
                'identify_bin' => 'identify',
                'image_versions' => array(
                    // The empty image version key defines options for the original image:
                    '' => array(
                        // Automatically rotate images based on EXIF meta data:
                        'auto_orient' => true
                    ),
                    // Uncomment the following to create medium sized images:

                    'thumbnail' => array(
                        // Uncomment the following to use a defined directory for the thumbnails
                        // instead of a subdirectory based on the version identifier.
                        // Make sure that this directory doesn't allow execution of files if you
                        // don't pose any restrictions on the type of uploaded files, e.g. by
                        // copying the .htaccess file from the files directory for Apache:
                        //'upload_dir' => dirname($this->getServerVar('SCRIPT_FILENAME')).'/thumb/',
                        //'upload_url' => $this->proSol_getFullUrl().'/thumb/',
                        // Uncomment the following to force the max
                        // dimensions and e.g. create square thumbnails:
                        'crop' => true,
                        'max_width' => 120,
                        'max_height' => 120
                    )
                ),
                'print_response' => true
            );
            if ($options) {
                $this->options = $options + $this->options;
            }
            if ($error_messages) {
                $this->error_messages = $error_messages + $this->error_messages;
            }

            //list the files
            if ($initialize) {
                $this->proSol_initialize();
            }
        }

        protected function proSol_initialize() {
            switch ($this->proSol_getServerVar('REQUEST_METHOD')) {

                case 'OPTIONS':
                case 'HEAD':
                    $this->proSol_head();
                    break;
                case 'GET':
                    $this->proSol_get($this->options['print_response']);
                    break;
                case 'PATCH':
                case 'PUT':
                case 'POST':
                    $this->proSol_post($this->options['print_response']);
                    break;
                case 'DELETE':
                    $this->proSol_delete($this->options['print_response']);
                    break;
                default:
                    $this->proSol_header('HTTP/1.1 405 Method Not Allowed');
            }
        }

        protected function proSol_getFullUrl() {
            $https = !empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') === 0 ||
                     !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
                     strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0;
            return
                ($https ? 'https://' : 'http://').
                (!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'].'@' : '').
                (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'].
                                                                         ($https && $_SERVER['SERVER_PORT'] === 443 ||
                                                                          $_SERVER['SERVER_PORT'] === 80 ? '' : ':'.$_SERVER['SERVER_PORT']))).
                substr($_SERVER['SCRIPT_NAME'],0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
        }

        protected function proSol_getUserId() {
            @session_start();
            return session_id();
        }

        protected function proSol_getUserPath() {
            if ($this->options['user_dirs']) {
                return $this->proSol_getUserId().'/';
            }
            return '';
        }

        protected function proSol_getUploadPath($file_name = null, $version = null) {
            $file_name = $file_name ? $file_name : '';
            if (empty($version)) {
                $version_path = '';
            } else {
                $version_dir = @$this->options['image_versions'][$version]['upload_dir'];
                if ($version_dir) {
                    return $version_dir.$this->proSol_getUserPath().$file_name;
                }
                $version_path = $version.'/';
            }
            return $this->options['upload_dir'].$this->proSol_getUserPath()
                   .$version_path.$file_name;
        }

        protected function proSol_getQuerySeparator($url) {
            return strpos($url, '?') === false ? '?' : '&';
        }

        protected function proSol_getDownloadUrl($file_name, $version = null, $direct = false) {
            if (!$direct && $this->options['download_via_php']) {
                $url = $this->options['script_url']
                       .$this->proSol_getQuerySeparator($this->options['script_url'])
                       .$this->proSol_getSingularParamName()
                       .'='.rawurlencode($file_name);
                if ($version) {
                    $url .= '&version='.rawurlencode($version);
                }
                return $url.'&download=1';
            }
            if (empty($version)) {
                $version_path = '';
            } else {
                $version_url = @$this->options['image_versions'][$version]['upload_url'];
                if ($version_url) {
                    return $version_url.$this->proSol_getUserPath().rawurlencode($file_name);
                }
                $version_path = rawurlencode($version).'/';
            }
            return $this->options['upload_url'].$this->proSol_getUserPath()
                   .$version_path.rawurlencode($file_name);
        }

        protected function proSol_setAdditionalFileProperties($file) {

//       $this->options['script_url'] = url('admin/access/admin-file-upload');

            $file->deleteUrl = $this->options['script_url']
                               .$this->proSol_getQuerySeparator($this->options['script_url'])
                               .$this->proSol_getSingularParamName()
                               .'='.rawurlencode($file->name);

            if(isset($this->options['myPath']))
                $file->deleteUrl = $file->deleteUrl.'&myPath='.$this->options['myPath'];
            $file->deleteType = $this->options['delete_type'];
            if ($file->deleteType !== 'DELETE') {
                $file->deleteUrl .= '&_method=DELETE';
            }
            if ($this->options['access_control_allow_credentials']) {
                $file->deleteWithCredentials = true;
            }
        }

        // Fix for overflowing signed 32 bit integers,
        // works for sizes up to 2^32-1 bytes (4 GiB - 1):
        protected function proSol_fixIntegerOverflow($size) {
            if ($size < 0) {
                $size += 2.0 * (PHP_INT_MAX + 1);
            }
            return $size;
        }

        protected function proSol_getFileSize($file_path, $clear_stat_cache = false) {
            if ($clear_stat_cache) {
                if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
                    clearstatcache(true, $file_path);
                } else {
                    clearstatcache();
                }
            }
            return $this->proSol_fixIntegerOverflow(filesize($file_path));
        }

        protected function proSol_isValidFileObject($file_name) {
            $file_path = $this->proSol_getUploadPath($file_name);
            if (is_file($file_path) && $file_name[0] !== '.') {
                return true;
            }
            return false;
        }

        protected function proSol_getFileObject($file_name) {
            if ($this->proSol_isValidFileObject($file_name)) {
                $file = new \stdClass();
                $file->name = $file_name;
                $file->size = $this->proSol_getFileSize(
                    $this->proSol_getUploadPath($file_name)
                );
                $file->url = $this->proSol_getDownloadUrl($file->name);
                foreach ($this->options['image_versions'] as $version => $options) {
                    if (!empty($version)) {
                        if (is_file($this->proSol_getUploadPath($file_name, $version))) {
                            $file->{$version.'Url'} = $this->proSol_getDownloadUrl(
                                $file->name,
                                $version
                            );
                        }
                    }
                }
                $this->proSol_setAdditionalFileProperties($file);
                return $file;
            }
            return null;
        }

        protected function proSol_getFileObjects($iteration_method = 'proSol_getFileObject') {
            $upload_dir = $this->proSol_getUploadPath();
            if (!is_dir($upload_dir)) {
                return array();
            }
            return array_values(array_filter(array_map(
                                                 array($this, $iteration_method),
                                                 scandir($upload_dir)
                                             )));
        }

        protected function proSol_countFileObjects() {
            return count($this->proSol_getFileObjects('proSol_isValidFileObject'));
        }

        protected function proSol_getErrorMessage($error) {
            return isset($this->error_messages[$error]) ?
                $this->error_messages[$error] : $error;
        }

        public function proSol_getConfigBytes($val) {
            $val = trim($val);
            $last = strtolower($val[strlen($val)-1]);
            $val = (int)$val;
            switch ($last) {
                case 'g':
                    $val *= 1024;
                case 'm':
                    $val *= 1024;
                case 'k':
                    $val *= 1024;
            }
            return $this->proSol_fixIntegerOverflow($val);
        }

        protected function proSol_validate($uploaded_file, $file, $error, $index) {
            if ($error) {
                $file->error = $this->proSol_getErrorMessage($error);
                return false;
            }
            $content_length = $this->proSol_fixIntegerOverflow(
                (int)$this->proSol_getServerVar('CONTENT_LENGTH')
            );
            $post_max_size = $this->proSol_getConfigBytes(ini_get('post_max_size'));
            if ($post_max_size && ($content_length > $post_max_size)) {
                $file->error = $this->proSol_getErrorMessage('post_max_size');
                return false;
            }
            if (!preg_match($this->options['accept_file_types'], $file->name)) {
                $file->error = $this->proSol_getErrorMessage('accept_file_types');
                return false;
            }
            if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                $file_size = $this->proSol_getFileSize($uploaded_file);
            } else {
                $file_size = $content_length;
            }
            if ($this->options['max_file_size'] && (
                    $file_size > $this->options['max_file_size'] ||
                    $file->size > $this->options['max_file_size'])
            ) {
                $file->error = $this->proSol_getErrorMessage('max_file_size');
                return false;
            }
            if ($this->options['min_file_size'] &&
                $file_size < $this->options['min_file_size']) {
                $file->error = $this->proSol_getErrorMessage('min_file_size');
                return false;
            }
            if (is_int($this->options['max_number_of_files']) &&
                ($this->proSol_countFileObjects() >= $this->options['max_number_of_files']) &&
                // Ignore additional chunks of existing files:
                !is_file($this->proSol_getUploadPath($file->name))) {
                $file->error = $this->proSol_getErrorMessage('max_number_of_files');
                return false;
            }
            $max_width = @$this->options['max_width'];
            $max_height = @$this->options['max_height'];
            $min_width = @$this->options['min_width'];
            $min_height = @$this->options['min_height'];
            if (($max_width || $max_height || $min_width || $min_height)
                && preg_match($this->options['image_file_types'], $file->name)) {
                list($img_width, $img_height) = $this->proSol_getImageSize($uploaded_file);

                // If we are auto rotating the image by default, do the checks on
                // the correct orientation
                if (
                    @$this->options['image_versions']['']['auto_orient'] &&
                    function_exists('exif_read_data') &&
                    ($exif = @exif_read_data($uploaded_file)) &&
                    (((int) @$exif['Orientation']) >= 5)
                ) {
                    $tmp = $img_width;
                    $img_width = $img_height;
                    $img_height = $tmp;
                    unset($tmp);
                }

            }
            if (!empty($img_width)) {
                if ($max_width && $img_width > $max_width) {
                    $file->error = $this->proSol_getErrorMessage('max_width');
                    return false;
                }
                if ($max_height && $img_height > $max_height) {
                    $file->error = $this->proSol_getErrorMessage('max_height');
                    return false;
                }
                if ($min_width && $img_width < $min_width) {
                    $file->error = $this->proSol_getErrorMessage('min_width');
                    return false;
                }
                if ($min_height && $img_height < $min_height) {
                    $file->error = $this->proSol_getErrorMessage('min_height');
                    return false;
                }
            }
            return true;
        }

        protected function proSol_upcountNameCallback($matches) {
            $index = isset($matches[1]) ? ((int)$matches[1]) + 1 : 1;
            $ext = isset($matches[2]) ? $matches[2] : '';
            return ' ('.$index.')'.$ext;
        }

        protected function proSol_upcountName($name) {
            return preg_replace_callback(
                '/(?:(?: \(([\d]+)\))?(\.[^.]+))?$/',
                array($this, 'proSol_upcountNameCallback'),
                $name,
                1
            );
        }

        protected function proSol_getUniqueFilename($file_path, $name, $size, $type, $error,
                                               $index, $content_range) {
            while(is_dir($this->proSol_getUploadPath($name))) {
                $name = $this->proSol_upcountName($name);
            }
            // Keep an existing filename if this is part of a chunked upload:
            $uploaded_bytes = $this->proSol_fixIntegerOverflow((int)$content_range[1]);
            while (is_file($this->proSol_getUploadPath($name))) {
                if ($uploaded_bytes === $this->proSol_getFileSize(
                        $this->proSol_getUploadPath($name))) {
                    break;
                }
                $name = $this->proSol_upcountName($name);
            }
            return $name;
        }

        protected function proSol_fixFileExtension($file_path, $name, $size, $type, $error,
                                              $index, $content_range) {
            // Add missing file extension for known image types:
            if (strpos($name, '.') === false &&
                preg_match('/^image\/(gif|jpe?g|png)/', $type, $matches)) {
                $name .= '.'.$matches[1];
            }
            if ($this->options['correct_image_extensions'] &&
                function_exists('exif_imagetype')) {
                switch (@exif_imagetype($file_path)){
                    case IMAGETYPE_JPEG:
                        $extensions = array('jpg', 'jpeg');
                        break;
                    case IMAGETYPE_PNG:
                        $extensions = array('png');
                        break;
                    case IMAGETYPE_GIF:
                        $extensions = array('gif');
                        break;
                }
                // Adjust incorrect image file extensions:
                if (!empty($extensions)) {
                    $parts = explode('.', $name);
                    $extIndex = count($parts) - 1;
                    $ext = strtolower(@$parts[$extIndex]);
                    if (!in_array($ext, $extensions)) {
                        $parts[$extIndex] = $extensions[0];
                        $name = implode('.', $parts);
                    }
                }
            }
            return $name;
        }

        protected function proSol_trimFileName($file_path, $name, $size, $type, $error,
                                          $index, $content_range) {
            // Remove path information and dots around the filename, to prevent uploading
            // into different directories or replacing hidden system files.
            // Also remove control characters and spaces (\x00..\x20) around the filename:

            $name = trim($this->proSol_basename(stripslashes($name)), ".\x00..\x20");


            // Use a timestamp for empty filenames:
            if (!$name) {
                $name = str_replace('.', '-', microtime(true));
            }
            return $name;
        }

        protected function proSol_getFileName($file_path, $name, $size, $type, $error,
                                         $index, $content_range) {
            $name = $this->proSol_trimFileName($file_path, $name, $size, $type, $error,
                                          $index, $content_range);
            return $this->proSol_getUniqueFilename(
                $file_path,
                $this->proSol_fixFileExtension($file_path, $name, $size, $type, $error,
                                          $index, $content_range),
                $size,
                $type,
                $error,
                $index,
                $content_range
            );
        }

        protected function proSol_getScaledImageFilePaths($file_name, $version) {
            $file_path = $this->proSol_getUploadPath($file_name);
            if (!empty($version)) {
                $version_dir = $this->proSol_getUploadPath(null, $version);
                if (!is_dir($version_dir)) {
                    mkdir($version_dir, $this->options['mkdir_mode'], true);
                }
                $new_file_path = $version_dir.'/'.$file_name;
            } else {
                $new_file_path = $file_path;
            }
            return array($file_path, $new_file_path);
        }

        protected function proSol_gdGetImageObject($file_path, $func, $no_cache = false) {
            if (empty($this->image_objects[$file_path]) || $no_cache) {
                $this->proSol_gdDestroyImageObject($file_path);
                $this->image_objects[$file_path] = $func($file_path);
            }
            return $this->image_objects[$file_path];
        }

        protected function proSol_gdSetImageObject($file_path, $image) {
            $this->proSol_gdDestroyImageObject($file_path);
            $this->image_objects[$file_path] = $image;
        }

        protected function proSol_gdDestroyImageObject($file_path) {
            $image = (isset($this->image_objects[$file_path])) ? $this->image_objects[$file_path] : null ;
            return $image && imagedestroy($image);
        }

        protected function proSol_gdImageflip($image, $mode) {
            if (function_exists('imageflip')) {
                return imageflip($image, $mode);
            }
            $new_width = $src_width = imagesx($image);
            $new_height = $src_height = imagesy($image);
            $new_img = imagecreatetruecolor($new_width, $new_height);
            $src_x = 0;
            $src_y = 0;
            switch ($mode) {
                case '1': // flip on the horizontal axis
                    $src_y = $new_height - 1;
                    $src_height = -$new_height;
                    break;
                case '2': // flip on the vertical axis
                    $src_x  = $new_width - 1;
                    $src_width = -$new_width;
                    break;
                case '3': // flip on both axes
                    $src_y = $new_height - 1;
                    $src_height = -$new_height;
                    $src_x  = $new_width - 1;
                    $src_width = -$new_width;
                    break;
                default:
                    return $image;
            }
            imagecopyresampled(
                $new_img,
                $image,
                0,
                0,
                $src_x,
                $src_y,
                $new_width,
                $new_height,
                $src_width,
                $src_height
            );
            return $new_img;
        }

        protected function proSol_gdOrientImage($file_path, $src_img) {
            if (!function_exists('exif_read_data')) {
                return false;
            }
            $exif = @exif_read_data($file_path);
            if ($exif === false) {
                return false;
            }
            $orientation = (int)@$exif['Orientation'];
            if ($orientation < 2 || $orientation > 8) {
                return false;
            }
            switch ($orientation) {
                case 2:
                    $new_img = $this->proSol_gdImageflip(
                        $src_img,
                        defined('PROSOLWPCLIENT_IMGFLIPVERTICAL') ? PROSOLWPCLIENT_IMGFLIPVERTICAL : 2
                    );
                    break;
                case 3:
                    $new_img = imagerotate($src_img, 180, 0);
                    break;
                case 4:
                    $new_img = $this->proSol_gdImageflip(
                        $src_img,
                        defined('PROSOLWPCLIENT_IMGFLIPHORIZONTAL') ? PROSOLWPCLIENT_IMGFLIPHORIZONTAL : 1
                    );
                    break;
                case 5:
                    $tmp_img = $this->proSol_gdImageflip(
                        $src_img,
                        defined('PROSOLWPCLIENT_IMGFLIPHORIZONTAL') ? PROSOLWPCLIENT_IMGFLIPHORIZONTAL : 1
                    );
                    $new_img = imagerotate($tmp_img, 270, 0);
                    imagedestroy($tmp_img);
                    break;
                case 6:
                    $new_img = imagerotate($src_img, 270, 0);
                    break;
                case 7:
                    $tmp_img = $this->proSol_gdImageflip(
                        $src_img,
                        defined('PROSOLWPCLIENT_IMGFLIPVERTICAL') ? PROSOLWPCLIENT_IMGFLIPVERTICAL : 2
                    );
                    $new_img = imagerotate($tmp_img, 270, 0);
                    imagedestroy($tmp_img);
                    break;
                case 8:
                    $new_img = imagerotate($src_img, 90, 0);
                    break;
                default:
                    return false;
            }
            $this->proSol_gdSetImageObject($file_path, $new_img);
            return true;
        }

        protected function proSol_gdCreateScaledImage($file_name, $version, $options) {
            if (!function_exists('imagecreatetruecolor')) {
                error_log('Function not found: imagecreatetruecolor');
                return false;
            }
            list($file_path, $new_file_path) =
                $this->proSol_getScaledImageFilePaths($file_name, $version);
            $type = strtolower(substr(strrchr($file_name, '.'), 1));
            switch ($type) {
                case 'jpg':
                case 'jpeg':
                    $src_func = 'imagecreatefromjpeg';
                    $write_func = 'imagejpeg';
                    $image_quality = isset($options['jpeg_quality']) ?
                        $options['jpeg_quality'] : 75;
                    break;
                case 'gif':
                    $src_func = 'imagecreatefromgif';
                    $write_func = 'imagegif';
                    $image_quality = null;
                    break;
                case 'png':
                    $src_func = 'imagecreatefrompng';
                    $write_func = 'imagepng';
                    $image_quality = isset($options['png_quality']) ?
                        $options['png_quality'] : 9;
                    break;
                default:
                    return false;
            }
            $src_img = $this->proSol_gdGetImageObject(
                $file_path,
                $src_func,
                !empty($options['no_cache'])
            );
            $image_oriented = false;
            if (!empty($options['auto_orient']) && $this->proSol_gdOrientImage(
                    $file_path,
                    $src_img
                )) {
                $image_oriented = true;
                $src_img = $this->proSol_gdGetImageObject(
                    $file_path,
                    $src_func
                );
            }
            $max_width = $img_width = imagesx($src_img);
            $max_height = $img_height = imagesy($src_img);
            if (!empty($options['max_width'])) {
                $max_width = $options['max_width'];
            }
            if (!empty($options['max_height'])) {
                $max_height = $options['max_height'];
            }
            $scale = min(
                $max_width / $img_width,
                $max_height / $img_height
            );
            if ($scale >= 1) {
                if ($image_oriented) {
                    return $write_func($src_img, $new_file_path, $image_quality);
                }
                if ($file_path !== $new_file_path) {
                    return copy($file_path, $new_file_path);
                }
                return true;
            }
            if (empty($options['crop'])) {
                $new_width = $img_width * $scale;
                $new_height = $img_height * $scale;
                $dst_x = 0;
                $dst_y = 0;
                $new_img = imagecreatetruecolor($new_width, $new_height);
            } else {
                if (($img_width / $img_height) >= ($max_width / $max_height)) {
                    $new_width = $img_width / ($img_height / $max_height);
                    $new_height = $max_height;
                } else {
                    $new_width = $max_width;
                    $new_height = $img_height / ($img_width / $max_width);
                }
                $dst_x = 0 - ($new_width - $max_width) / 2;
                $dst_y = 0 - ($new_height - $max_height) / 2;
                $new_img = imagecreatetruecolor($max_width, $max_height);
            }
            // Handle transparency in GIF and PNG images:
            switch ($type) {
                case 'gif':
                case 'png':
                    imagecolortransparent($new_img, imagecolorallocate($new_img, 0, 0, 0));
                case 'png':
                    imagealphablending($new_img, false);
                    imagesavealpha($new_img, true);
                    break;
            }
            $success = imagecopyresampled(
                           $new_img,
                           $src_img,
                           $dst_x,
                           $dst_y,
                           0,
                           0,
                           $new_width,
                           $new_height,
                           $img_width,
                           $img_height
                       ) && $write_func($new_img, $new_file_path, $image_quality);
            $this->proSol_gdSetImageObject($file_path, $new_img);
            return $success;
        }

        protected function proSol_imagickGetImageObject($file_path, $no_cache = false) {
            if (empty($this->image_objects[$file_path]) || $no_cache) {
                $this->proSol_imagickDestroyImageObject($file_path);
                $image = new \Imagick();
                if (!empty($this->options['imagick_resource_limits'])) {
                    foreach ($this->options['imagick_resource_limits'] as $type => $limit) {
                        $image->setResourceLimit($type, $limit);
                    }
                }
                $image->readImage($file_path);
                $this->image_objects[$file_path] = $image;
            }
            return $this->image_objects[$file_path];
        }

        protected function proSol_imagickSetImageObject($file_path, $image) {
            $this->proSol_imagickDestroyImageObject($file_path);
            $this->image_objects[$file_path] = $image;
        }

        protected function proSol_imagickDestroyImageObject($file_path) {
            $image = (isset($this->image_objects[$file_path])) ? $this->image_objects[$file_path] : null ;
            return $image && $image->destroy();
        }

        protected function proSol_imagickOrientImage($image) {
            $orientation = $image->getImageOrientation();
            $background = new \ImagickPixel('none');
            switch ($orientation) {
                case \imagick::ORIENTATION_TOPRIGHT: // 2
                    $image->flopImage(); // horizontal flop around y-axis
                    break;
                case \imagick::ORIENTATION_BOTTOMRIGHT: // 3
                    $image->rotateImage($background, 180);
                    break;
                case \imagick::ORIENTATION_BOTTOMLEFT: // 4
                    $image->flipImage(); // vertical flip around x-axis
                    break;
                case \imagick::ORIENTATION_LEFTTOP: // 5
                    $image->flopImage(); // horizontal flop around y-axis
                    $image->rotateImage($background, 270);
                    break;
                case \imagick::ORIENTATION_RIGHTTOP: // 6
                    $image->rotateImage($background, 90);
                    break;
                case \imagick::ORIENTATION_RIGHTBOTTOM: // 7
                    $image->flipImage(); // vertical flip around x-axis
                    $image->rotateImage($background, 270);
                    break;
                case \imagick::ORIENTATION_LEFTBOTTOM: // 8
                    $image->rotateImage($background, 270);
                    break;
                default:
                    return false;
            }
            $image->setImageOrientation(\imagick::ORIENTATION_TOPLEFT); // 1
            return true;
        }

        protected function proSol_imagickCreateScaledImage($file_name, $version, $options) {
            list($file_path, $new_file_path) =
                $this->proSol_getScaledImageFilePaths($file_name, $version);
            $image = $this->proSol_imagickGetImageObject(
                $file_path,
                !empty($options['crop']) || !empty($options['no_cache'])
            );
            if ($image->getImageFormat() === 'GIF') {
                // Handle animated GIFs:
                $images = $image->coalesceImages();
                foreach ($images as $frame) {
                    $image = $frame;
                    $this->proSol_imagickSetImageObject($file_name, $image);
                    break;
                }
            }
            $image_oriented = false;
            if (!empty($options['auto_orient'])) {
                $image_oriented = $this->proSol_imagickOrientImage($image);
            }
            $new_width = $max_width = $img_width = $image->getImageWidth();
            $new_height = $max_height = $img_height = $image->getImageHeight();
            if (!empty($options['max_width'])) {
                $new_width = $max_width = $options['max_width'];
            }
            if (!empty($options['max_height'])) {
                $new_height = $max_height = $options['max_height'];
            }
            if (!($image_oriented || $max_width < $img_width || $max_height < $img_height)) {
                if ($file_path !== $new_file_path) {
                    return copy($file_path, $new_file_path);
                }
                return true;
            }
            $crop = !empty($options['crop']);
            if ($crop) {
                $x = 0;
                $y = 0;
                if (($img_width / $img_height) >= ($max_width / $max_height)) {
                    $new_width = 0; // Enables proportional scaling based on max_height
                    $x = ($img_width / ($img_height / $max_height) - $max_width) / 2;
                } else {
                    $new_height = 0; // Enables proportional scaling based on max_width
                    $y = ($img_height / ($img_width / $max_width) - $max_height) / 2;
                }
            }
            $success = $image->resizeImage(
                $new_width,
                $new_height,
                isset($options['filter']) ? $options['filter'] : \imagick::FILTER_LANCZOS,
                isset($options['blur']) ? $options['blur'] : 1,
                $new_width && $new_height // fit image into constraints if not to be cropped
            );
            if ($success && $crop) {
                $success = $image->cropImage(
                    $max_width,
                    $max_height,
                    $x,
                    $y
                );
                if ($success) {
                    $success = $image->setImagePage($max_width, $max_height, 0, 0);
                }
            }
            $type = strtolower(substr(strrchr($file_name, '.'), 1));
            switch ($type) {
                case 'jpg':
                case 'jpeg':
                    if (!empty($options['jpeg_quality'])) {
                        $image->setImageCompression(\imagick::COMPRESSION_JPEG);
                        $image->setImageCompressionQuality($options['jpeg_quality']);
                    }
                    break;
            }
            if (!empty($options['strip'])) {
                $image->stripImage();
            }
            return $success && $image->writeImage($new_file_path);
        }

        protected function proSol_imagemagickCreateScaledImage($file_name, $version, $options) {
            list($file_path, $new_file_path) =
                $this->proSol_getScaledImageFilePaths($file_name, $version);
            $resize = @$options['max_width']
                      .(empty($options['max_height']) ? '' : 'X'.$options['max_height']);
            if (!$resize && empty($options['auto_orient'])) {
                if ($file_path !== $new_file_path) {
                    return copy($file_path, $new_file_path);
                }
                return true;
            }
            $cmd = $this->options['convert_bin'];
            if (!empty($this->options['convert_params'])) {
                $cmd .= ' '.$this->options['convert_params'];
            }
            $cmd .= ' '.escapeshellarg($file_path);
            if (!empty($options['auto_orient'])) {
                $cmd .= ' -auto-orient';
            }
            if ($resize) {
                // Handle animated GIFs:
                $cmd .= ' -coalesce';
                if (empty($options['crop'])) {
                    $cmd .= ' -resize '.escapeshellarg($resize.'>');
                } else {
                    $cmd .= ' -resize '.escapeshellarg($resize.'^');
                    $cmd .= ' -gravity center';
                    $cmd .= ' -crop '.escapeshellarg($resize.'+0+0');
                }
                // Make sure the page dimensions are correct (fixes offsets of animated GIFs):
                $cmd .= ' +repage';
            }
            if (!empty($options['convert_params'])) {
                $cmd .= ' '.$options['convert_params'];
            }
            $cmd .= ' '.escapeshellarg($new_file_path);
            exec($cmd, $output, $error);
            if ($error) {
                error_log(implode('\n', $output));
                return false;
            }
            return true;
        }

        protected function proSol_getImageSize($file_path) {
            if ($this->options['image_library']) {
                if (extension_loaded('imagick')) {
                    $image = new \Imagick();
                    try {
                        if (@$image->pingImage($file_path)) {
                            $dimensions = array($image->getImageWidth(), $image->getImageHeight());
                            $image->destroy();
                            return $dimensions;
                        }
                        return false;
                    } catch (\Exception $e) {
                        error_log($e->getMessage());
                    }
                }
                if ($this->options['image_library'] === 2) {
                    $cmd = $this->options['identify_bin'];
                    $cmd .= ' -ping '.escapeshellarg($file_path);
                    exec($cmd, $output, $error);
                    if (!$error && !empty($output)) {
                        // image.jpg JPEG 1920x1080 1920x1080+0+0 8-bit sRGB 465KB 0.000u 0:00.000
                        $infos = preg_split('/\s+/', substr($output[0], strlen($file_path)));
                        $dimensions = preg_split('/x/', $infos[2]);
                        return $dimensions;
                    }
                    return false;
                }
            }
            if (!function_exists('getimagesize')) {
                error_log('Function not found: getimagesize');
                return false;
            }
            return @getimagesize($file_path);
        }

        protected function proSol_createScaledImage($file_name, $version, $options) {
            if ($this->options['image_library'] === 2) {
                return $this->proSol_imagemagickCreateScaledImage($file_name, $version, $options);
            }
            if ($this->options['image_library'] && extension_loaded('imagick')) {
                return $this->proSol_imagickCreateScaledImage($file_name, $version, $options);
            }
            return $this->proSol_gdCreateScaledImage($file_name, $version, $options);
        }

        protected function proSol_destroyImageObject($file_path) {
            if ($this->options['image_library'] && extension_loaded('imagick')) {
                return $this->proSol_imagickDestroyImageObject($file_path);
            }
        }

        protected function proSol_isValidImageFile($file_path) {
            if (!preg_match($this->options['image_file_types'], $file_path)) {
                return false;
            }
            if (function_exists('exif_imagetype')) {
                return @exif_imagetype($file_path);
            }
            $image_info = $this->proSol_getImageSize($file_path);
            return $image_info && $image_info[0] && $image_info[1];
        }

        protected function proSol_handleImageFile($file_path, $file) {
            $failed_versions = array();
            foreach ($this->options['image_versions'] as $version => $options) {
                if ($this->proSol_createScaledImage($file->name, $version, $options)) {
                    if (!empty($version)) {
                        $file->{$version.'Url'} = $this->proSol_getDownloadUrl(
                            $file->name,
                            $version
                        );
                    } else {
                        $file->size = $this->proSol_getFileSize($file_path, true);
                    }
                } else {
                    $failed_versions[] = $version ? $version : 'original';
                }
            }
            if (count($failed_versions)) {
                $file->error = $this->proSol_getErrorMessage('image_resize')
                               .' ('.implode($failed_versions, ', ').')';
            }
            // Free memory:
            $this->proSol_destroyImageObject($file_path);
        }

        protected function proSol_handleFileUpload($uploaded_file, $name, $size, $type, $error,
                                              $index = null, $content_range = null) {
            $file = new \stdClass();
            $file->name = $this->proSol_getFileName($uploaded_file, $name, $size, $type, $error,
                                               $index, $content_range);
            $file->size = $this->proSol_fixIntegerOverflow((int)$size);
            $file->type = $type;
            if ($this->proSol_validate($uploaded_file, $file, $error, $index)) {
                $this->proSol_handleFormData($file, $index);
                $upload_dir = $this->proSol_getUploadPath();
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, $this->options['mkdir_mode'], true);
                }
                $file_path = $this->proSol_getUploadPath($file->name);
                $append_file = $content_range && is_file($file_path) &&
                               $file->size > $this->proSol_getFileSize($file_path);
                if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                    // multipart/formdata uploads (POST method uploads)
                    if ($append_file) {
                        file_put_contents(
                            $file_path,
                            fopen($uploaded_file, 'r'),
                            FILE_APPEND
                        );
                    } else {
                        move_uploaded_file($uploaded_file, $file_path);
                    }
                } else {
                    // Non-multipart uploads (PUT method support)
                    file_put_contents(
                        $file_path,
                        fopen($this->options['input_stream'], 'r'),
                        $append_file ? FILE_APPEND : 0
                    );
                }
                $file_size = $this->proSol_getFileSize($file_path, $append_file);
                if ($file_size === $file->size) {
                    $file->url = $this->proSol_getDownloadUrl($file->name);
                    if ($this->proSol_isValidImageFile($file_path)) {
                        $this->proSol_handleImageFile($file_path, $file);
                    }
                } else {
                    $file->size = $file_size;
                    if (!$content_range && $this->options['discard_aborted_uploads']) {
                        unlink($file_path);
                        $file->error = $this->proSol_getErrorMessage('abort');
                    }
                }
                $this->proSol_setAdditionalFileProperties($file);
            }
            return $file;
        }

        protected function proSol_readfile($file_path) {
            $file_size = $this->proSol_getFileSize($file_path);
            $chunk_size = $this->options['readfile_chunk_size'];
            if ($chunk_size && $file_size > $chunk_size) {
                $handle = fopen($file_path, 'rb');
                while (!feof($handle)) {
                    echo fread($handle, $chunk_size);
                    @ob_flush();
                    @flush();
                }
                fclose($handle);
                return $file_size;
            }
            return proSol_readfile($file_path);
        }

        protected function proSol_body($str) {
            echo $str;
        }

        protected function proSol_header($str) {
            proSol_header($str);
        }

        protected function proSol_getUploadData($id) {
            return @$_FILES[$id];
        }

        protected function proSol_getPostParam($id) {
            return @$_POST[$id];
        }

        protected function proSol_getQueryParam($id) {
            return @$_GET[$id];
        }

        protected function proSol_getServerVar($id) {
            return @$_SERVER[$id];
        }

        protected function proSol_handleFormData($file, $index) {
            // Handle form data, e.g. $_POST['description'][$index]
            /* $temp = $_REQUEST['mp_id'];
             echo $temp;
             exit();*/
        }

        protected function proSol_getVersionParam() {
            return $this->proSol_basename(stripslashes($this->proSol_getQueryParam('version')));
        }

        protected function proSol_getSingularParamName() {
            return substr($this->options['param_name'], 0, -1);
        }

        protected function proSol_getFileNameParam() {
            $name = $this->proSol_getSingularParamName();
            return $this->proSol_basename(stripslashes($this->proSol_getQueryParam($name)));
        }

        protected function proSol_getFileNamesParams() {
            $params = $this->proSol_getQueryParam($this->options['param_name']);
            if (!$params) {
                return null;
            }
            foreach ($params as $key => $value) {
                $params[$key] = $this->proSol_basename(stripslashes($value));
            }
            return $params;
        }

        protected function proSol_getFileType($file_path) {
            switch (strtolower(pathinfo($file_path, PATHINFO_EXTENSION))) {
                case 'jpeg':
                case 'jpg':
                    return 'image/jpeg';
                case 'png':
                    return 'image/png';
                case 'gif':
                    return 'image/gif';
                default:
                    return '';
            }
        }

        protected function proSol_download() {
            switch ($this->options['download_via_php']) {
                case 1:
                    $redirect_header = null;
                    break;
                case 2:
                    $redirect_header = 'X-Sendfile';
                    break;
                case 3:
                    $redirect_header = 'X-Accel-Redirect';
                    break;
                default:
                    return $this->proSol_header('HTTP/1.1 403 Forbidden');
            }
            $file_name = $this->proSol_getFileNameParam();
            if (!$this->proSol_isValidFileObject($file_name)) {
                return $this->proSol_header('HTTP/1.1 404 Not Found');
            }
            if ($redirect_header) {
                return $this->proSol_header(
                    $redirect_header.': '.$this->proSol_getDownloadUrl(
                        $file_name,
                        $this->proSol_getVersionParam(),
                        true
                    )
                );
            }
            $file_path = $this->proSol_getUploadPath($file_name, $this->proSol_getVersionParam());
            // Prevent browsers from MIME-sniffing the content-type:
            $this->proSol_header('X-Content-Type-Options: nosniff');
            if (!preg_match($this->options['inline_file_types'], $file_name)) {
                $this->proSol_header('Content-Type: application/octet-stream');
                $this->proSol_header('Content-Disposition: attachment; filename="'.$file_name.'"');
            } else {
                $this->proSol_header('Content-Type: '.$this->proSol_getFileType($file_path));
                $this->proSol_header('Content-Disposition: inline; filename="'.$file_name.'"');
            }
            $this->proSol_header('Content-Length: '.$this->proSol_getFileSize($file_path));
            $this->proSol_header('Last-Modified: '.gmdate('D, d M Y H:i:s T', filemtime($file_path)));
            $this->proSol_readfile($file_path);
        }

        protected function proSol_sendContentTypeHeader() {
            $this->proSol_header('Vary: Accept');
            if (strpos($this->proSol_getServerVar('HTTP_ACCEPT'), 'application/json') !== false) {
                $this->proSol_header('Content-type: application/json');
            } else {
                $this->proSol_header('Content-type: text/plain');
            }
        }

        protected function proSol_sendAccessControlHeaders() {
            $this->proSol_header('Access-Control-Allow-Origin: '.$this->options['access_control_allow_origin']);
            $this->proSol_header('Access-Control-Allow-Credentials: '
                          .($this->options['access_control_allow_credentials'] ? 'true' : 'false'));
            $this->proSol_header('Access-Control-Allow-Methods: '
                          .implode(', ', $this->options['access_control_allow_methods']));
            $this->proSol_header('Access-Control-Allow-Headers: '
                          .implode(', ', $this->options['access_control_allow_headers']));
        }

        public function proSol_generateResponse($content, $print_response = true) {
            $this->response = $content;
            if ($print_response) {
                $json = json_encode($content);
                $redirect = stripslashes($this->proSol_getPostParam('redirect'));
                if ($redirect && preg_match($this->options['redirect_allow_target'], $redirect)) {
                    $this->proSol_header('Location: '.sprintf($redirect, rawurlencode($json)));
                    return;
                }
                $this->proSol_head();
                if ($this->proSol_getServerVar('HTTP_CONTENT_RANGE')) {
                    $files = isset($content[$this->options['param_name']]) ?
                        $content[$this->options['param_name']] : null;
                    if ($files && is_array($files) && is_object($files[0]) && $files[0]->size) {
                        $this->proSol_header('Range: 0-'.(
                                          $this->proSol_fixIntegerOverflow((int)$files[0]->size) - 1
                                      ));
                    }
                }
                $this->proSol_body($json);
            }
            return $content;
        }

        public function proSol_getResponse () {
            return $this->response;
        }

        public function proSol_head() {
            $this->proSol_header('Pragma: no-cache');
            $this->proSol_header('Cache-Control: no-store, no-cache, must-revalidate');
            $this->proSol_header('Content-Disposition: inline; filename="files.json"');
            // Prevent Internet Explorer from MIME-sniffing the content-type:
            $this->proSol_header('X-Content-Type-Options: nosniff');
            if ($this->options['access_control_allow_origin']) {
                $this->proSol_sendAccessControlHeaders();
            }
            $this->proSol_sendContentTypeHeader();
        }

        public function proSol_get($print_response = true) {
            if ($print_response && $this->proSol_getQueryParam('download')) {
                return $this->proSol_download();
            }
            $file_name = $this->proSol_getFileNameParam();
            if ($file_name) {
                $response = array(
                    $this->proSol_getSingularParamName() => $this->proSol_getFileObject($file_name)
                );
            } else {
                $response = array(
                    $this->options['param_name'] => $this->proSol_getFileObjects()
                );
            }
            return $this->proSol_generateResponse($response, $print_response);
        }

        public function proSol_post($print_response = true) {
            if ($this->proSol_getQueryParam('_method') === 'DELETE') {
                return $this->proSol_delete($print_response);
            }
            $upload = $this->proSol_getUploadData($this->options['param_name']);
            // Parse the Content-Disposition header, if available:
            $content_disposition_header = $this->proSol_getServerVar('HTTP_CONTENT_DISPOSITION');
            $file_name = $content_disposition_header ?
                rawurldecode(preg_replace(
                                 '/(^[^"]+")|("$)/',
                                 '',
                                 $content_disposition_header
                             )) : null;
            // Parse the Content-Range header, which has the following form:
            // Content-Range: bytes 0-524287/2000000
            $content_range_header = $this->proSol_getServerVar('HTTP_CONTENT_RANGE');
            $content_range = $content_range_header ?
                preg_split('/[^0-9]+/', $content_range_header) : null;
            $size =  $content_range ? $content_range[3] : null;
            $files = array();
            if ($upload) {
                if (is_array($upload['tmp_name'])) {
                    // param_name is an array identifier like "files[]",
                    // $upload is a multi-dimensional array:
                    foreach ($upload['tmp_name'] as $index => $value) {
                        $files[] = $this->proSol_handleFileUpload(
                            $upload['tmp_name'][$index],
                            $file_name ? $file_name : $upload['name'][$index],
                            $size ? $size : $upload['size'][$index],
                            $upload['type'][$index],
                            $upload['error'][$index],
                            $index,
                            $content_range
                        );
                    }
                } else {
                    // param_name is a single object identifier like "file",
                    // $upload is a one-dimensional array:
                    $files[] = $this->proSol_handleFileUpload(
                        isset($upload['tmp_name']) ? $upload['tmp_name'] : null,
                        $file_name ? $file_name : (isset($upload['name']) ?
                            $upload['name'] : null),
                        $size ? $size : (isset($upload['size']) ?
                            $upload['size'] : $this->proSol_getServerVar('CONTENT_LENGTH')),
                        isset($upload['type']) ?
                            $upload['type'] : $this->proSol_getServerVar('CONTENT_TYPE'),
                        isset($upload['error']) ? $upload['error'] : null,
                        null,
                        $content_range
                    );
                }
            }
            $response = array($this->options['param_name'] => $files);
            return $this->proSol_generateResponse($response, $print_response);
        }

        public function proSol_delete($print_response = true) {
            $file_names = $this->proSol_getFileNamesParams();
            if (empty($file_names)) {
                $file_names = array($this->proSol_getFileNameParam());
            }
            $response = array();
            foreach ($file_names as $file_name) {
                $file_path = $this->proSol_getUploadPath($file_name);
                $success = is_file($file_path) && $file_name[0] !== '.' && unlink($file_path);
                if ($success) {
                    foreach ($this->options['image_versions'] as $version => $options) {
                        if (!empty($version)) {
                            $file = $this->proSol_getUploadPath($file_name, $version);
                            if (is_file($file)) {
                                unlink($file);
                            }
                        }
                    }
                }
                $response[$file_name] = $success;
            }
            return $this->proSol_generateResponse($response, $print_response);
        }

        protected function proSol_basename($filepath, $suffix = null) {
            $splited = preg_split('/\//', rtrim ($filepath, '/ '));
            return substr(basename('X'.$splited[count($splited)-1], $suffix), 1);
        }
    }
