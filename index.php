<?php
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
 
require_once '/vendor/autoload.php';
include_once('_function.php');
 

$dir='./';
$fileName = null;
$file_secure_url_download = null;
$key = '';
$secret = '';
$region = '';
 
try {
    // S3 client
    $s3client = new S3Client([
        'version'     => 'latest',
        'region'      => $region, //Region of the bucket
        'credentials' => array(
            'key'    => $key,
            'secret' => $secret,
        )
    ]);
 
    $bucket_name = "pdf-raw-2141";
    $s3_pdf_repo = "raw/";
    $fileNameArray = ['sample-page1-1.pdf'];
 
    try {
            foreach($fileNameArray as $fileName):
                    $cmd = $s3client->getCommand('GetObject', [
                        'Bucket' => $bucket_name,
                        'Key' => $s3_pdf_repo.''.$fileName,
                        'ResponseContentDisposition' => 'attachment; filename="'.$fileName.'"',
                    ]);
                    $request = $s3client->createPresignedRequest($cmd,'+5 minutes');
                    $file_secure_url_download = (string)$request->getUri();
                    //echo $file_secure_url_download;

            endforeach;

        }catch (Exception $exception) {
            echo $exception->getMessage();
        }


    } catch(AwsException $e) {
        // output error message if fails
        error_log($e->getMessage());
    }
    if(isset($file_secure_url_download)):
        downloadPdf($fileName, $file_secure_url_download, $dir);
        echo $fileName." file downloaded for processing \n";
    else:
            echo "No pre signed request file for download \n";
            exit();
    endif;

    // remove existing png files
    removePngFiles($dir, $fileName);
    // convert into png files
        $tempDir = $fileName."~%02d.png";
        exec("gs -d -dNOPAUSE -dDumpMediaSizes -sDEVICE=png16m -r300 -dDownScaleFactor=3 -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -sOutputFile=$tempDir '$fileName' -dBATCH");
    
    $listOfImages = getListOfPngFiles($dir, $fileName);

    if(!empty($listOfImages)):
        foreach($listOfImages as $img):
            $path = $img;
            // getting the image name and path
            $imageFilename = pathinfo($path, PATHINFO_FILENAME);
            $imageExtension = pathinfo($path, PATHINFO_EXTENSION);
            $imageBaseName = pathinfo($path, PATHINFO_BASENAME);

            // put object to the aws s3
            putImagetoS3($fileName, $imageFilename, $imageExtension, $path, $bucket_name, $s3client);                                         
            echo $imageFilename ." png object pushed to s3 on path ".$bucket_name."/raw \n";  
        endforeach;
    else:
        echo "no png... \n";
    endif;
?>