<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function show(Folder $folder)
    {
        return view('folders.show', [
            'folder' => $folder,
            'ancestors' => $folder->ancestors()
        ]);
    }

    public function store(Request $request, $isSection = null)
    {
        if (!auth()->check())
            return back()->with('error', 'You can\'t do that.');

        if ($isSection) $name = 'section_name';
        else $name = 'folder_name';

        $attributes = $request->validate([
            $name => 'required|max:255|unique:folders,name'
        ]);
        $attributes['slug'] = strtolower(str_replace(' ', '-', $attributes[$name]));
        if ($request['parent']) {
            $attributes['parent_id'] = $request['parent'];
        }
        $attributes['name'] = $attributes[$name];
        unset($attributes[$name]);

        $folder = Folder::create($attributes);
        return redirect((url()->previous())); // . '/' . $folder->slug);
    }

    public function update(Request $request)
    {
        $attributes = $request->validate([
            'new_folder_name' => 'required|max:255'
        ]);
        $folder = Folder::find($request->folder);

        $new = $attributes['new_folder_name'];
        $new_slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $new));

        if (!is_null($folder->parent))
            if (Folder::whereSlug($new_slug)->where('folder_id', '=', $folder->parent->id)->exists())
                $new_slug .= '_' . time();
            elseif (Folder::whereSlug($new_slug)->where('parent_id', null)->exists())
                $new_slug .= '_' . time();

        if (is_null($new_slug) || !isset($new_slug) || $new_slug == '') {
            $new_slug = time();
        }

        Folder::where('id', $request->folder)->update([
            'name' => $new,
            'slug' => $new_slug,
        ]);

        $array = explode('/', url()->previous());
        $url = implode('/', array_slice($array, 0, count($array) - 1)) . '/' . $new_slug;

        return redirect($url)->with('success', 'You have successfully renamed the folder!');
    }

    public function destroy(Request $request)
    {
        $folder = Folder::find($request->id);
        $parent = $folder->parent;
        if (auth()->check()) {
            $folder->delete();

            $array = explode('/', url()->previous());
            $url = implode('/', array_slice($array, 0, count($array) - 1));

            if (is_null($parent))
                return redirect()->route('home')->with('success', 'Folder deleted.');
            else
                return redirect($url)
                    ->with('success', 'Folder deleted.');
        } else {
            return redirect()->back()->with('error', 'You can\'t do that.');
        }
    }
}
