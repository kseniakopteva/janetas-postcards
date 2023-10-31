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
        return redirect((url()->previous()) . '/' . $folder->slug);
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
