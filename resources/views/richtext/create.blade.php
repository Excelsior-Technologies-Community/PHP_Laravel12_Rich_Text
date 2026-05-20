<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Rich Text CMS</title>

    <!-- Trix CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@2.0.3/dist/trix.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f5f7fb;
            color: #1a202c;
            padding: 30px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 2.2rem;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .header p {
            color: #718096;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-card i {
            font-size: 28px;
            color: #4299e1;
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
            color: #2d3748;
        }

        .stat-card .label {
            color: #718096;
            font-size: 14px;
            margin-top: 5px;
        }

        /* Editor Card */
        .editor-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 40px;
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
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
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
            min-height: 250px;
            background: white;
            border-radius: 8px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-group input {
            width: auto;
        }

        .btn-submit {
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

        .btn-submit:hover {
            background: #3182ce;
        }

        /* Filters */
        .filters-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
            justify-content: space-between;
        }

        .filters-left {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }

        .filter-input {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
        }

        .filter-select {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: white;
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }

        .content-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.2s;
        }

        .content-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .featured-image {
            height: 180px;
            background-size: cover;
            background-position: center;
            background-color: #edf2f7;
        }

        .card-content {
            padding: 20px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2d3748;
            text-decoration: none;
        }

        .card-title:hover {
            color: #4299e1;
        }

        .category-badge {
            background: #ebf8ff;
            color: #3182ce;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
        }

        .status-published {
            background: #c6f6d5;
            color: #276749;
        }

        .status-draft {
            background: #fed7d7;
            color: #9b2c2c;
        }

        .excerpt {
            color: #718096;
            font-size: 14px;
            line-height: 1.5;
            margin: 12px 0;
        }

        .meta-info {
            display: flex;
            gap: 15px;
            font-size: 12px;
            color: #a0aec0;
            margin-bottom: 15px;
        }

        .card-actions {
            display: flex;
            gap: 10px;
            border-top: 1px solid #edf2f7;
            padding-top: 15px;
        }

        .btn-icon {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-edit {
            background: #edf2f7;
            color: #4a5568;
        }

        .btn-edit:hover {
            background: #e2e8f0;
        }

        .btn-toggle {
            background: #e9d8fd;
            color: #6b46c1;
        }

        .btn-delete {
            background: #fed7d7;
            color: #c53030;
            border: none;
            cursor: pointer;
        }

        .pagination {
            margin-top: 40px;
            display: flex;
            justify-content: center;
        }

        .pagination nav {
            display: flex;
            gap: 8px;
        }

        .alert {
            background: #c6f6d5;
            color: #276749;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .filters-card {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filters-left {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>Rich Text CMS</h1>
        <p>Create, manage, and organize your content beautifully</p>
    </div>

    @if(session('success'))
        <div class="alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
          
            <div class="number">{{ $totalArticles }}</div>
            <div class="label">Total Articles</div>
        </div>
        <div class="stat-card">
          
            <div class="number">{{ $todayPosts }}</div>
            <div class="label">Today's Posts</div>
        </div>
        <div class="stat-card">
           
            <div class="number">{{ $publishedPosts }}</div>
            <div class="label">Published</div>
        </div>
    </div>

    <!-- Editor -->
    <div class="editor-card">
        <div class="editor-header">
            <h2> Create New Content</h2>
        </div>
        <div class="editor-body">
            <form method="POST" action="{{ route('richtext.store') }}">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label> Title *</label>
                        <input type="text" name="title" placeholder="Enter title..." required>
                    </div>
                    <div class="form-group">
                        <label> Category *</label>
                        <select name="category" required>
                            <option value="General">General</option>
                            <option value="Technology">Technology</option>
                            <option value="Lifestyle">Lifestyle</option>
                            <option value="Business">Business</option>
                            <option value="Health">Health</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label> Tags (comma separated)</label>
                        <input type="text" name="tags" placeholder="e.g., laravel, php, webdev">
                    </div>
                    <div class="form-group">
                        <label>Featured Image URL</label>
                        <input type="url" name="featured_image" placeholder="https://...">
                    </div>
                </div>

                <div class="form-group">
                    <label>Content *</label>
                    <input id="bio" type="hidden" name="bio">
                    <trix-editor input="bio"></trix-editor>
                    @error('bio')
                        <p style="color: #e53e3e; font-size: 13px; margin-top: 8px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="checkbox-group">
                        <input type="checkbox" name="is_published" checked>
                        <span>Publish immediately</span>
                    </label>
                </div>

                <button type="submit" class="btn-submit">
                     Save Content
                </button>
            </form>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-card">
        <div class="filters-left">
            <form method="GET" action="{{ route('richtext.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <input type="text" name="search" class="filter-input" placeholder="Search..." value="{{ request('search') }}">
                <select name="category" class="filter-select" onchange="this.form.submit()">
                    <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                <select name="status" class="filter-select" onchange="this.form.submit()">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                <select name="sort" class="filter-select" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>A - Z</option>
                    <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Z - A</option>
                </select>
            </form>
        </div>
        <div>
            <span style="font-size: 14px; color: #718096;">
                {{ $contents->total() }} items
            </span>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        @forelse($contents as $item)
            <div class="content-card">
                @if($item->featured_image)
                    <div class="featured-image" style="background-image: url('{{ $item->featured_image }}')"></div>
                @endif
                <div class="card-content">
                    <div class="card-header">
                        <span class="category-badge">{{ $item->category }}</span>
                        <span class="status-badge {{ $item->is_published ? 'status-published' : 'status-draft' }}">
                            {{ $item->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                    <a href="#" class="card-title">{{ $item->title ?? 'Untitled' }}</a>
                    <div class="excerpt">{!! $item->excerpt !!}</div>
                    <div class="meta-info">
                        <span> {{ $item->created_at->format('M d, Y') }}</span>
                        <span>{{ $item->tags ?: 'No tags' }}</span>
                    </div>
                    <div class="card-actions">
                        <a href="{{ route('richtext.edit', $item->id) }}" class="btn-icon btn-edit">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('richtext.toggle', $item->id) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-icon btn-toggle">
                               
                                {{ $item->is_published ? 'Unpublish' : 'Publish' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('richtext.delete', $item->id) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete" onclick="return confirm('Delete this content?')">
                               Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 60px; color: #718096;">
               
                <p>No content found. Create your first article above!</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pagination">
        {{ $contents->appends(request()->query())->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/trix@2.0.3/dist/trix.umd.min.js"></script>
</body>
</html>