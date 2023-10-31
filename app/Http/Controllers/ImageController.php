<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function show(Folder $folder, Image $image)
    {
        $previous = Image::where('folder_id', '=', $folder->id)->where('created_at', '<', $image->created_at)->orderBy('created_at', 'desc')->first();
        $next = Image::where('folder_id', '=', $folder->id)->where('created_at', '>', $image->created_at)->orderBy('created_at', 'asc')->first();

        return view('images.show', [
            'image' => $image,
            'previous' => $previous,
            'next' => $next
        ]);
    }
    public function create(Folder $folder)
    {
        return view('images.create', [
            'folder' => $folder
        ]);
    }

    public function store(Request $request)
    {
        if (!auth()->check())
            return back()->with('error', 'You can\'t do that.');

        $attributes = $request->validate([
            'files' => 'required'
        ]);

        $path = storage_path('app\public\images');

        foreach ($request->file('files') as $file) {
            $orig = pathinfo($file->getClientOriginalName());

            $onlyFileName = str_replace(' ', '', $orig['filename']);
            $fullFileName = $onlyFileName . '_' . time() . '.' . $orig['extension'];
            $fileNameNoExt = $onlyFileName;
            if (Image::whereSlug($onlyFileName)->exists())
                $fileNameNoExt .= '_' . time();
            // dd($fullFileName);

            $file->move($path, $fullFileName);

            \Intervention\Image\Facades\Image::make($path . '\\' . $fullFileName)->resize(2000, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path . '\\' . $fullFileName);

            $image = Image::create([
                'file' =>  $fullFileName,
                'slug' => $fileNameNoExt,
                'folder_id' => $request->folder
            ]);
        }
        $folder = Folder::where('id', $request->folder)->first();

        return redirect()->route('folder.show', ['folder' => $folder])->with('success', 'You have successfully uploaded images!');
    }
}
