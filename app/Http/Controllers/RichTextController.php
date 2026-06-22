<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RichText;
use Illuminate\Support\Str;

class RichTextController extends Controller
{
    public function index(Request $request)
    {
        $query = RichText::query()->whereNull('original_id')->with('versions');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%')
                  ->orWhere('tags', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category') && $request->category != 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('status') && $request->status != 'all') {
            $query->where('is_published', $request->status == 'published');
        }

        switch ($request->get('sort', 'latest')) {
            case 'oldest': $query->oldest(); break;
            case 'az': $query->orderBy('title', 'asc'); break;
            case 'za': $query->orderBy('title', 'desc'); break;
            default: $query->latest();
        }

        $contents = $query->paginate(6);
        $categories = RichText::whereNull('original_id')->distinct()->pluck('category');

        $totalArticles = RichText::whereNull('original_id')->count();
        $todayPosts = RichText::whereNull('original_id')->whereDate('created_at', today())->count();
        $publishedPosts = RichText::whereNull('original_id')->where('is_published', true)->count();

        return view('richtext.create', compact('contents', 'categories', 'totalArticles', 'todayPosts', 'publishedPosts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'bio' => 'required',
            'category' => 'required',
        ]);

        RichText::create([
            'title' => $request->title,
            'content' => $request->bio,
            'category' => $request->category,
            'tags' => $request->tags,
            'featured_image' => $request->featured_image,
            'is_published' => $request->has('is_published'),
            'version' => 1
        ]);

        session()->forget('draft_content_new');

        return redirect()->route('richtext.index')->with('success', 'Content Saved Successfully!');
    }

    public function edit($id)
    {
        $content = RichText::findOrFail($id);
        $categories = RichText::whereNull('original_id')->distinct()->pluck('category');
        return view('richtext.edit', compact('content', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'bio' => 'required',
            'category' => 'required',
        ]);

        $oldContent = RichText::findOrFail($id);

        RichText::create([
            'title' => $oldContent->title,
            'content' => $oldContent->content,
            'category' => $oldContent->category,
            'tags' => $oldContent->tags,
            'featured_image' => $oldContent->featured_image,
            'is_published' => false,
            'version' => $oldContent->version + 1,
            'original_id' => $id
        ]);

        $oldContent->update([
            'title' => $request->title,
            'content' => $request->bio,
            'category' => $request->category,
            'tags' => $request->tags,
            'featured_image' => $request->featured_image,
            'is_published' => $request->has('is_published')
        ]);

        session()->forget('draft_content_' . $id);

        return redirect()->route('richtext.index')->with('success', 'Content Updated & Version Created!');
    }

    public function destroy($id)
    {
        RichText::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Content Deleted Successfully!');
    }

    public function toggleStatus($id)
    {
        $content = RichText::findOrFail($id);
        $content->update(['is_published' => !$content->is_published]);
        return redirect()->back()->with('success', 'Status Toggled Successfully!');
    }

    public function loadTemplate(Request $request)
    {
        $type = $request->type;
        $templates = [
            'blog' => '<h1>New Blog Post</h1><p>Start writing your thoughts here...</p>',
            'report' => '<h2>Monthly Report</h2><p>Date: '.date('Y-m-d').'<br>Summary: </p>',
            'newsletter' => '<h2>Newsletter Title</h2><p>Hello Subscribers,</p><p>Here is what is new this week...</p>'
        ];
        return response()->json(['content' => $templates[$type] ?? '']);
    }

    public function saveDraft(Request $request)
    {
        $key = 'draft_content_' . ($request->record_id ?: 'new');
        session([$key => $request->all()]);
        return response()->json(['status' => 'saved', 'time' => now()->format('h:i:s A')]);
    }
}