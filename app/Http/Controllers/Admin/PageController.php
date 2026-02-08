<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = \App\Models\Page::all();
        return view('admin.pages.index', compact('pages'));
    }

    public function edit(\App\Models\Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, \App\Models\Page $page)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'meta' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $data = $request->except(['image', 'is_active']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('pages', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        $page->update($data);

        return redirect()->route('admin.pages.index')->with('success', 'Page content updated successfully.');
    }
}
