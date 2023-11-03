<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function show(Folder $folder, Image $image)
    {
        $previous = Image::where('folder_id', '=', $folder->id)->where('id', '<', $image->id)->orderBy('id', 'desc')->first();
        $next = Image::where('folder_id', '=', $folder->id)->where('id', '>', $image->id)->orderBy('id', 'asc')->first();

        $i = Image::where('folder_id', '=', $folder->id)->where('slug', '=', $image->slug)->first();

        $temp = explode('/', url()->previous());
        $slug = end($temp);
        $from_image = Image::where('folder_id', '=', $folder->id)->where('slug', '=', $slug)->first();
        if (!is_null($from_image)) {
            if ($from_image->created_at > $i->created_at)
                $slide = 'left';
            else
                $slide = 'right';
        } else $slide = null;

        $pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
        if ($pageWasRefreshed) {
            $slide = null;
        }

        return view('images.show', [
            'image' => $i,
            'previous' => $previous,
            'next' => $next,
            'slide' => $slide
        ]);
    }

    public function create(Folder $folder)
    {
        return view('images.create', [
            'folder' => $folder
        ]);
    }

    public function update(Request $request)
    {
        $attributes = $request->validate([
            'image_name' => 'required|max:255'
        ]);
        $image = Image::find($request->image);

        $new = $attributes['image_name'];
        $new_slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $new));

        if (Image::whereSlug($new_slug)->where('folder_id', '=', $image->folder->id)->exists())
            $new_slug .= '_' . time();

        if (is_null($new_slug) || !isset($new_slug) || $new_slug == '') {
            $new_slug = time();
        }

        Image::where('id', $request->image)->update([
            'name' => $new,
            'slug' => $new_slug,
        ]);

        $array = explode('/', url()->previous());
        $url = implode('/', array_slice($array, 0, count($array) - 1)) . '/' . $new_slug;

        return redirect($url)->with('success', 'You have successfully renamed the image!');
    }

    public function store(Request $request)
    {
        if (!auth()->check())
            return back()->with('error', 'You can\'t do that.');

        $attributes = $request->validate([
            'files' => 'required'
        ]);

        $path = storage_path('app\public\images');

        /*
        name: 3 (3 always)
        slug: 3 (3_313423525 if exists in folder)
        file: 3.jpg (unique)
        */

        foreach ($request->file('files') as $file) {
            $orig = pathinfo($file->getClientOriginalName());

            $onlyFileName = str_replace(' ', '-', $orig['filename']);
            $fullFileName = $onlyFileName . '_' . time() . '.' . $orig['extension'];
            $fileNameNoExt = $onlyFileName;
            if (Image::whereSlug($onlyFileName)->where('folder_id', '=', $request->folder)->exists())
                $fileNameNoExt .= '_' . time();

            $file->move($path, $fullFileName);

            \Intervention\Image\Facades\Image::make($path . '\\' . $fullFileName)->resize(1920, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($path . '\\' . $fullFileName);

            $image = Image::create([
                'name' => $orig['filename'],
                'slug' => preg_replace('/[^A-Za-z0-9\-]/', '', $fileNameNoExt),
                'file' =>  $fullFileName,
                'folder_id' => $request->folder
            ]);
        }
        $folder = Folder::where('id', $request->folder)->first();

        return redirect()->route('folder.show', ['folder' => $folder])->with('success', 'You have successfully uploaded images!');
    }

    public function destroy(Request $request)
    {
        $image = Image::find($request->image_id);
        $folder = $image->folder;

        if (auth()->check()) {
            unlink(storage_path('app\\public\\images\\' . $image->file));
            $image->delete();

            $domain = substr($request->root(), 7);
            $url = $domain;
            foreach ($folder->ancestors as $a) {
                $url .= '/' . $a->slug;
            }
            $url .= '/' . $folder->slug;

            return redirect($url)->with('success', 'Image deleted.');
        } else {
            return redirect()->back()->with('error', 'You can\'t do that.');
        }
    }

    public function mass_destroy(Request $request)
    {
        return view('folders.show', [
            'folder' => Folder::find($request->id),
            'edit' => true,
        ]);
    }

    public function mass_destroy_images(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->back()->with('error', 'You can\'t do that.');
        }

        foreach ($request->input('images') as $id) {
            $image = Image::find($id);

            unlink(storage_path('app\\public\\images\\' . $image->file));
            $image->delete();
        }

        $folder = Folder::find($request->folder);

        $domain = substr($request->root(), 7);
        $url = $domain;
        foreach ($folder->ancestors as $a) {
            $url .= '/' . $a->slug;
        }
        $url .= '/' . $folder->slug;

        return redirect($url)->with('success', 'Images deleted.');
    }
}
