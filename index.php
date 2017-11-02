<?php
/**
Serve an upload form with a custom settings using the built-in php webserver.

Usage: 
    php index.php # generated php.ini
    php -s 0.0.0.0:8080 -c php.ini # serve upload form
*/

// Configuration
$max_upload_filesize_in_mb = 50;
$max_memory_in_mb = 256;
$phpini = dirname(__file__).'/php.ini';
$incoming_folder = dirname(__file__).'/incoming';

// Create incoming folder
if(!is_dir($incoming_folder)) {
    mkdir($incoming_folder,0770);
    echo '[*] Incoming folder '.$incoming_folder.' created'.PHP_EOL;
}

// Generate php.ini file
if(!is_file($phpini)) {
    file_put_contents($phpini,trim('
; http://php.net/display-errors
display_errors = On
; Maximum size of POST data that PHP will accept.
; http://php.net/post-max-size
post_max_size = '.$max_upload_filesize_in_mb.'M
; Maximum allowed size for uploaded files.
; http://php.net/upload-max-filesize
upload_max_filesize = '.$max_upload_filesize_in_mb.'M
; Maximum amount of memory a script may consume (128MB)
; http://php.net/memory-limit
memory_limit = '.$max_memory_in_mb.'M
    '));
    die('[*] php.ini generated, run local server using php -S 0.0.0.0:8080 -c php.ini'.PHP_EOL);
}

// Check for file upload
if(isset($_FILES['fileToUpload'])) {
    $target_file = $incoming_folder.'/'.basename($_FILES["fileToUpload"]["name"]);
    if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo 'upload complete';
    } else {
        echo 'error while uploading';
    }
}
?>
<!DOCTYPE html>
<html>
    <body>
        <form method="post" enctype="multipart/form-data">
            Select file to upload:
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload Image" name="submit">
        </form>
    </body>
</html>
