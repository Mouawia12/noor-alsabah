<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Traits\FileTrait;


class UploadController extends Controller
{
    use FileTrait;

    public function images(Request $request)
    {

        $request->validate([
            'images' => 'nullable|mimes:jpg,jpeg,png,pdf',
            'experience_cert' => 'nullable|mimes:jpg,jpeg,png,pdf',
            'good_manners_cert' => 'nullable|mimes:jpg,jpeg,png,pdf',
            'custody_attach' => 'nullable|mimes:jpg,jpeg,png,pdf',
            'custody_attach_store' => 'nullable|mimes:jpg,jpeg,png,pdf',
            'custody_attach_3' => 'nullable|mimes:jpg,jpeg,png,pdf',
            'custody_attach_4' => 'nullable|mimes:jpg,jpeg,png,pdf',
            'custody_attach_5' => 'nullable|mimes:jpg,jpeg,png,pdf',
        ]);
        $rr[] = $request->images;
        $response = [];

        if ($request->hasFile('images')) {
            // $response = $this->uploadFile($request->i_path, $request->images);
            $response = $this->uploadFile($request->images, $request->i_path);

            // echo $request->i_path;

            return response()->json(['uploadURL' => $response]);
        }

    }

    public function delete_file(Request $request)
    {
        // محصور بمجلد الرفع فقط: نأخذ اسم الملف المجرّد لا مساراً كاملاً (منع حذف ملفات عشوائية)
        $name = basename((string) $request->images);
        $target = public_path('uploads/mol/' . $name);

        if ($name !== '' && is_file($target)) {
            @unlink($target);
        }

        /*      $rr[] =$request->images;
            $response=[];
      if ($request->hasFile('images')) {
         $response = $this->uploadFile($request->images,$request->i_path);


        return response()->json(['uploadURL' => $response]);
            }*/

    }
}
