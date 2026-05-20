<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RichText;
use Illuminate\Support\Str;

class RichTextController extends Controller
{
    public function index(Request $request)
    {
        $query = RichText::query();

        // Search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%')
                  ->orWhere('tags', 'like', '%' . $request->search . '%');
            });
        }

        // Category filter
        if ($request->filled('category') && $request->category != 'all') {
            $query->where('category', $request->category);
        }

        // Status filter
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('is_published', $request->status == 'published');
        }

        // Sorting
        switch ($request->get('sort', 'latest')) {
            case 'oldest':
                $query->oldest();
                break;
            case 'az':
                $query->orderBy('title', 'asc');
                break;
            case 'za':
                $query->orderBy('title', 'desc');
                break;
            default:
                $query->latest();
        }

        $contents = $query->paginate(6);
        $categories = RichText::distinct()->pluck('category');
        
        // Stats
        $totalArticles = RichText::count();
        $todayPosts = RichText::whereDate('created_at', today())->count();
        $publishedPosts = RichText::where('is_published', true)->count();

        return view('richtext.create', compact('contents', 'categories', 'totalArticles', 'todayPosts', 'publishedPosts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'bio' => 'required',
            'category' => 'required',
            'featured_image' => 'nullable|url'
        ]);

        RichText::create([
            'title' => $request->title,
            'content' => $request->bio,
            'category' => $request->category,
            'tags' => $request->tags,
            'featured_image' => $request->featured_image,
            'is_published' => $request->has('is_published')
        ]);

        return redirect()->route('richtext.index')->with('success', 'Content Saved Successfully!');
    }

    public function edit($id)
    {
        $content = RichText::findOrFail($id);
        $categories = RichText::distinct()->pluck('category');

        return view('richtext.edit', compact('content', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'bio' => 'required',
            'category' => 'required',
            'featured_image' => 'nullable|url'
        ]);

        $content = RichText::findOrFail($id);

        $content->update([
            'title' => $request->title,
            'content' => $request->bio,
            'category' => $request->category,
            'tags' => $request->tags,
            'featured_image' => $request->featured_image,
            'is_published' => $request->has('is_published')
        ]);

        return redirect()->route('richtext.index')->with('success', 'Content Updated Successfully!');
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
        
        $status = $content->is_published ? 'Published' : 'Unpublished';
        return redirect()->back()->with('success', "Content {$status} Successfully!");
    }
}