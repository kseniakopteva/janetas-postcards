<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function create(Folder $folder)
    {
        return view('images.create', [
            'folder' => $folder
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->document);

        foreach ($request->file('document', []) as $file) {

            $path = storage_path('app\public\images');


            $imageName = $file; //strtolower($request->user()->username) . '_' . time() . '.' . $request->image->extension();

            $attributes['image'] = $imageName;
            $attributes['name'] = $imageName;
            $attributes['slug'] = $imageName;
            $attributes['folder_id'] = $request->folder;

            $file->move($path, $imageName);

            \Intervention\Image\Facades\Image::make($path . '\\' . $imageName)->resize(1000, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path . '\\' . $imageName);

            // return $file;
        }



        // $ds          = DIRECTORY_SEPARATOR;  //1

        // $storeFolder = public_path() . 'images';   //2

        // if (!empty($_FILES)) {

        //     $tempFile = $_FILES['file']['tmp_name'];          //3

        //     $targetPath = dirname(__FILE__) . $ds . $storeFolder . $ds;  //4
        //     dd($targetPath);
        //     $targetFile =  $targetPath . $_FILES['file']['name'];  //5

        //     $image = Image::create([
        //         'name' => $_FILES['file']['name'],
        //         'slug' => strtolower(str_replace(' ', '-', $_FILES['file']['name'])),
        //         'folder_id' => $request->folder,
        //     ]);



        //     move_uploaded_file($tempFile, $targetFile); //6

        redirect()->back();
    }

    public function uploads(Request $request)
    {
        $path = storage_path('tmp/uploads');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }
}
