<?php
namespace App\Http\Traits;
use Illuminate\Support\Str;
/*use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;*/

Trait FileTrait
{
    public function uploadFile($file, $path)
    {
       if ( $file ) {
           // الامتداد يُشتق من نوع الملف على الخادم لا من اسم المستخدم (منع حقن أسماء/امتدادات)
           $ext = $file->extension() ?: $file->getClientOriginalExtension();
           $file_name = Str::random(40) . ($ext ? '.' . $ext : '');
           $file->move(public_path('uploads/mol/'), $file_name);

           return $file_name;
       }
    }
}
