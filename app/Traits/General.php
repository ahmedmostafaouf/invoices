<?php

namespace App\Traits;
Trait General{
    function SaveImages($photo,$folder){
        // save photo in folder();
        $file_extension= $photo -> getClientOriginalName();
        // بزود تايم عشان لو سيفت اكتر من مره
        $file_name=$file_extension;
        $path=$folder;
        $photo -> move($path,$file_name);
        return $file_name;

    }
}
