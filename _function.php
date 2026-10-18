<?php
function downloadPdf($fileName, $file_secure_url_download, $dir='./') {
    try {
        //Initialize the cURL session
        $ch = curl_init($file_secure_url_download);
        $save_file_loc = $dir . $fileName;
        if(file_exists($save_file_loc)) {
            unlink($save_file_loc);
        }
        $fp = fopen($save_file_loc, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }
    catch(Exception $e) {
        echo 'Message: ' .$e->getMessage();
    }
}
function removePngFiles($dir, $fileName) {
    $pngFiles = glob($dir."*".$fileName."~*" );
    for($i =0; $i < count($pngFiles); $i++){
        unlink($pngFiles[$i]);
    }
}
function getListOfPngFiles($dir, $fileName) {
    $pngFiles = glob($dir."*".$fileName."~*" );
    natsort($pngFiles);
    return $pngFiles;
}
function putImagetoS3($fileName, $name, $ext, $sourceFile, $target_bucket_name, $s3client) {
 
    if($ext == 'png' || $ext == 'PNG') {
        try {
            // Upload
            $result = $s3client->putObject([
                'Bucket' => $target_bucket_name,
                'Key'    => 'raw/'.$name.'.'.$ext,
                'SourceFile' => $sourceFile,
            ]);
        } catch (S3Exception $e) {
            echo $e->getMessage();
            exit();
        }
    }
}
?>