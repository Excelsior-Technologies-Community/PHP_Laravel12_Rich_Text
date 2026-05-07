<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RichText;

class RichTextController extends Controller
{
    public function index()
    {
        $contents = RichText::latest()->get();

        return view('richtext.create', compact('contents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bio' => 'required',
        ]);

        RichText::create([
            'content' => $request->bio,
        ]);

        return redirect()->back()->with('success', 'Content Saved Successfully!');
    }

    public function edit($id)
    {
        $content = RichText::findOrFail($id);

        return view('richtext.edit', compact('content'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bio' => 'required',
        ]);

        $content = RichText::findOrFail($id);

        $content->update([
            'content' => $request->bio,
        ]);

        return redirect('/richtext')
            ->with('success', 'Content Updated Successfully!');
    }

    public function destroy($id)
    {
        RichText::findOrFail($id)->delete();

        return redirect()->back()
            ->with('success', 'Content Deleted Successfully!');
    }
}