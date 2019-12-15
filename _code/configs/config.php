<?php

include_once 'config.hidden.php';
include_once 'enums.php';

define('SITE_URL_LOCAL', 'http://localhost:8080/'); // Site URL
define('DB_NAME_LOCAL', 'snow_capture_local'); // Database name
define('DB_USER_LOCAL', 'snow'); // Database user
define('DB_PASS_LOCAL', 'snow'); // Database password
define('DB_HOST_LOCAL', 'localhost:3306'); // Database host

define('FACEBOOK_APP_ID', '1023118374414733');

define('METHOD_ERROR', 'Something went wrong, please try again later'); // Generic error message when something fails that shouldn't

define('DB_TABLE', 'image'); //Table name to store upload data
define('UPLOAD_DIR', '/uploads/'); //Files path
define('PROFILE_PLACEHOLDER', '/template/images/placeholder.jpg'); // Placeholder path
define('PROFILE_COVER_PLACEHOLDER', '/template/images/holding-cover.jpg'); // Placeholder cover path

define('MIN_UPLOAD_SIZE', 0); //Image min size
define('MAX_UPLOAD_SIZE', 2000000000); //Image max size

define('IMG_MAX_WIDTH', 2500); //Image max width
define('IMG_MAX_HEIGHT', 1500); //Image max height

define('IMG_DISPLAY_WIDTH', 1024); //Image max width
define('IMG_DISPLAY_HEIGHT', 768); //Image max height

define('THUMBNAIL_WIDTH', 600); //Thumbnail width
define('THUMBNAIL_HEIGHT', 450); //Thumnail height

define('THUMBNAIL_PATH_SYSTEM', 'thumbnails/system/'); //Thumnail height
define('THUMBNAIL_PATH_CUSTOM', 'thumbnails/custom/'); //Thumnail height

define('ALLOW_MULTIPLEUPLOAD', true); // Allow multiple uploading?
define('DRAFT_LIMIT', 5); // Number of multiple uploads
define('UPLOAD_LIMIT', 100); // Max number of images that can be uploaded
define('ROW_LIMIT', 12); // How many images to display per row

define('ACCEPT_FILE_TYPES', 'jpeg/jpg'); // Filetypes allowed for upload

define('MAX_LOGIN_ATTEMPTS', 10); // How many attempt before locking a user out
define('LOCKED_HOURS', 2); // How many hours before locked out resets?
define('TIMEOUT_MINUTES', 60); // How many minuetes before timeout prompted?
define('ONLINE_MINUTES', 2); // How many minutes since last active to be online?

define('MAJOR_ATTEMPTS', 1); // How many attempts for major hack attempts
define('MEDIUM_ATTEMPTS', 3); // How many attempts for medium hack attempts
define('MINOR_ATTEMPTS', 10); // How many attempts for minor hack attempts

define('REVIEW_EMAIL', 'review@snowcapture.com'); // Review image Email

define('PASSWORD_RESET_MINUTES', '-10 minutes'); // How many minutes from now to determine password reset total
define('PASSWORD_RESET_ATTEMPTS', 10); // How many attempts password reset in above time

define('WEB_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/');
define('LIB_ROOT', WEB_ROOT . '_code/');

?>