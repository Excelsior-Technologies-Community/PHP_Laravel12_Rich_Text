@extends('layouts.app')

@section('content')

    <div class="header">
        <h1>Rich Text CMS</h1>
        <p>Create, manage, and organize your content beautifully</p>
    </div>

    @if(session('success'))
        <div class="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="stats-grid">
        <div class="stat-card">
            <i class="fa-solid fa-file-lines"></i>
            <div class="number">{{ $totalArticles }}</div>
            <div class="label">Total Articles</div>
        </div>
        <div class="stat-card">
            <i class="fa-solid fa-calendar-day"></i>
            <div class="number">{{ $todayPosts }}</div>
            <div class="label">Today's Posts</div>
        </div>
        <div class="stat-card">
            <i class="fa-solid fa-circle-check"></i>
            <div class="number">{{ $publishedPosts }}</div>
            <div class="label">Published</div>
        </div>
    </div>

    <div class="editor-card">
        <div class="editor-header">
            <h2>Create New Content</h2>
        </div>
        <div class="editor-body">
            <form method="POST" action="{{ route('richtext.store') }}" id="createForm">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" name="title" id="titleInput" placeholder="Enter title..." required>
                    </div>
                    <div class="form-group">
                        <label>Category *</label>
                        <select name="category" id="categoryInput" required>
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
                        <label>Tags (comma separated)</label>
                        <input type="text" name="tags" id="tagsInput" placeholder="e.g., laravel, php, webdev">
                    </div>
                    <div class="form-group">
                        <label>Featured Image URL</label>
                        <input type="url" name="featured_image" id="imageInput" placeholder="https://...">
                    </div>
                </div>

                <div class="form-group">
                    <label>Content *</label>

                    <div class="template-bar">
                        <span class="template-label">Quick Templates:</span>
                        <button type="button" class="btn-template" onclick="loadTemplate('blog')">
                            <i class="fa-solid fa-blog"></i> Blog Post
                        </button>
                        <button type="button" class="btn-template" onclick="loadTemplate('report')">
                            <i class="fa-solid fa-file-lines"></i> Report
                        </button>
                        <button type="button" class="btn-template" onclick="loadTemplate('newsletter')">
                            <i class="fa-solid fa-envelope-open-text"></i> Newsletter
                        </button>
                    </div>

                    <input id="bio" type="hidden" name="bio">
                    <trix-editor input="bio" id="bio_editor"></trix-editor>
                    <div class="word-count" id="wordCount">0 words · 0 characters</div>
                    @error('bio')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="checkbox-group">
                        <input type="checkbox" name="is_published" checked>
                        <span>Publish immediately</span>
                    </label>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn-submit">
                        Save Content
                    </button>
                    <span class="autosave-status" id="autoSaveStatus">Not saved yet</span>
                </div>
            </form>
        </div>
    </div>

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
            <span style="font-size: 14px; color: var(--text-secondary);">
                {{ $contents->total() }} items
            </span>
        </div>
    </div>

    <div class="content-grid">
        @forelse($contents as $item)
            <div class="content-card">
                @if($item->featured_image)
                    <div class="featured-image" style="background-image: url('{{ $item->featured_image }}')"></div>
                @endif
                <div class="card-content">
                    <div class="card-header">
                        <div class="badge-group">
                            <span class="category-badge">{{ $item->category }}</span>
                            <span class="version-tag">v{{ $item->version }}</span>
                        </div>
                        <span class="status-badge {{ $item->is_published ? 'status-published' : 'status-draft' }}">
                            {{ $item->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                    <a href="#" class="card-title">{{ $item->title ?? 'Untitled' }}</a>
                    <div class="excerpt">{!! $item->excerpt !!}</div>
                    <div class="meta-info">
                        <span>{{ $item->created_at->format('M d, Y') }}</span>
                        <span>{{ $item->word_count }} words</span>
                        <span>{{ $item->tags ?: 'No tags' }}</span>
                    </div>

                    @if($item->versions->count())
                        <button type="button" class="btn-icon btn-versions" onclick="toggleVersions({{ $item->id }})">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            {{ $item->versions->count() }} previous version{{ $item->versions->count() > 1 ? 's' : '' }}
                        </button>
                        <div class="versions-list" id="versions-{{ $item->id }}" style="display:none;">
                            @foreach($item->versions as $v)
                                <div class="version-row">
                                    <span>v{{ $v->version }} · {{ $v->title }}</span>
                                    <span class="version-date">{{ $v->updated_at->format('M d, Y H:i') }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="card-actions">
                        <a href="{{ route('richtext.edit', $item->id) }}" class="btn-icon btn-edit">
                            <i class="fa-solid fa-pen"></i> Edit
                        </a>
                        <form method="POST" action="{{ route('richtext.toggle', $item->id) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-icon btn-toggle">
                                <i class="fa-solid fa-toggle-on"></i>
                                {{ $item->is_published ? 'Unpublish' : 'Publish' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('richtext.delete', $item->id) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete" onclick="return confirm('Delete this content?')">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 60px; color: var(--text-secondary);">
                <p>No content found. Create your first article above!</p>
            </div>
        @endforelse
    </div>

    <div class="pagination">
        {{ $contents->appends(request()->query())->links() }}
    </div>

@endsection

@push('scripts')
<script>
    const editorEl = document.querySelector('#bio_editor');
    const wordCountEl = document.querySelector('#wordCount');
    const autoSaveStatus = document.querySelector('#autoSaveStatus');
    let autoSaveTimer;

    function updateWordCount() {
        const text = editorEl.innerText.trim();
        const words = text.length ? text.split(/\s+/).length : 0;
        wordCountEl.textContent = words + ' words · ' + text.length + ' characters';
    }

    function loadTemplate(type) {
        fetch(`{{ route('richtext.template') }}?type=${type}`)
            .then(res => res.json())
            .then(data => {
                editorEl.editor.loadHTML(data.content);
                updateWordCount();
                triggerAutoSave();
            });
    }

    function triggerAutoSave() {
        clearTimeout(autoSaveTimer);
        autoSaveStatus.textContent = 'Typing...';
        autoSaveTimer = setTimeout(saveDraftNow, 2000);
    }

    function saveDraftNow() {
        const formData = new FormData();
        formData.append('title', document.querySelector('#titleInput').value);
        formData.append('bio', document.querySelector('#bio').value);
        formData.append('category', document.querySelector('#categoryInput').value);
        formData.append('tags', document.querySelector('#tagsInput').value);
        formData.append('record_id', 'new');

        fetch(`{{ route('richtext.draft') }}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            autoSaveStatus.textContent = 'Draft saved at ' + data.time;
        })
        .catch(() => {
            autoSaveStatus.textContent = 'Auto-save failed';
        });
    }

    function toggleVersions(id) {
        const el = document.getElementById('versions-' + id);
        el.style.display = el.style.display === 'none' ? 'flex' : 'none';
    }

    editorEl.addEventListener('trix-change', function () {
        updateWordCount();
        triggerAutoSave();
    });

    document.querySelector('#titleInput').addEventListener('input', triggerAutoSave);
    document.querySelector('#categoryInput').addEventListener('change', triggerAutoSave);
    document.querySelector('#tagsInput').addEventListener('input', triggerAutoSave);

    document.addEventListener('DOMContentLoaded', updateWordCount);
</script>
@endpush