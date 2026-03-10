<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RichText;

class RichTextController extends Controller
{
    public function create()
    {
        return view('richtext.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bio' => 'required',
        ]);

        RichText::create([
            'content' => $request->bio,
        ]);

        return redirect()->back()->with('success', 'Content saved!');
    }
}