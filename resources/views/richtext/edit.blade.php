<!DOCTYPE html>
<html>

<head>
    <title>Edit Content</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@2.0.3/dist/trix.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f7fb;
            padding: 30px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #4299e1;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .back-link:hover {
            color: #3182ce;
        }

        .editor-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .editor-header {
            background: #f7fafc;
            padding: 20px 25px;
            border-bottom: 1px solid #e2e8f0;
        }

        .editor-header h2 {
            font-size: 1.3rem;
            color: #2d3748;
        }

        .editor-body {
            padding: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #4a5568;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66,153,225,0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        trix-editor {
            min-height: 300px;
            background: white;
            border-radius: 8px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-update {
            background: #4299e1;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-update:hover {
            background: #3182ce;
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
<div class="container">
    <a href="{{ route('richtext.index') }}" class="back-link">
         Back to Dashboard
    </a>

    <div class="editor-card">
        <div class="editor-header">
            <h2> Edit Content</h2>
        </div>
        <div class="editor-body">
            <form method="POST" action="{{ route('richtext.update', $content->id) }}">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label> Title</label>
                        <input type="text" name="title" value="{{ old('title', $content->title) }}" required>
                    </div>
                    <div class="form-group">
                        <label> Category</label>
                        <select name="category" required>
                            <option value="General" {{ $content->category == 'General' ? 'selected' : '' }}>General</option>
                            <option value="Technology" {{ $content->category == 'Technology' ? 'selected' : '' }}>Technology</option>
                            <option value="Lifestyle" {{ $content->category == 'Lifestyle' ? 'selected' : '' }}>Lifestyle</option>
                            <option value="Business" {{ $content->category == 'Business' ? 'selected' : '' }}>Business</option>
                            <option value="Health" {{ $content->category == 'Health' ? 'selected' : '' }}>Health</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label> Tags</label>
                        <input type="text" name="tags" value="{{ old('tags', $content->tags) }}" placeholder="comma separated">
                    </div>
                    <div class="form-group">
                        <label> Featured Image URL</label>
                        <input type="url" name="featured_image" value="{{ old('featured_image', $content->featured_image) }}" placeholder="https://...">
                    </div>
                </div>

                <div class="form-group">
                    <label> Content</label>
                    <input id="bio" type="hidden" name="bio" value="{{ $content->content }}">
                    <trix-editor input="bio"></trix-editor>
                </div>

                <div class="form-group">
                    <label class="checkbox-group">
                        <input type="checkbox" name="is_published" {{ $content->is_published ? 'checked' : '' }}>
                        <span>Published</span>
                    </label>
                </div>

                <button type="submit" class="btn-update">
                    Update Content
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/trix@2.0.3/dist/trix.umd.min.js"></script>
</body>
</html>