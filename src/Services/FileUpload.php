<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;

class FileUpload
{
    

    
    public function UploadFile($file_key,$request)
    {
        $file_request = $request->files->get($file_key);
        $file = fopen($file_request->getRealPath(),"rb");
        return ($file);
    }
}