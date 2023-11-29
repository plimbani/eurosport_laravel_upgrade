<?php

namespace App\Custom\Helper;

use Storage;

class Document
{
    /*
   * Common function for uploading image
   *
   * @return response
   */
    public static function uploadDocument($documentPath, $documentName, $documentString)
    {
        $s3 = Storage::disk('s3');
        $document = explode(',', $documentString);
        if (count($document) > 1) {
            $documentData = base64_decode($document[1]);
        } else {
            return '';
        }

        $pathParts = pathinfo($documentName);
        $documentName = $pathParts['filename'].'-'.microtime(true).rand(10, 99).'.'.$pathParts['extension'];

        $path = $documentPath.$documentName;
        $s3->put($path, $documentData);

        return $documentName;
    }
}
