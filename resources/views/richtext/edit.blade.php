@extends('layouts.app')

@section('container_class', 'narrow')

@section('content')

    <a href="{{ route('richtext.index') }}" class="back-link">
        <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
    </a>

    <div class="editor-card">
        <div class="editor-header">
            <h2>Edit Content</h2>
        </div>
        <div class="editor-body">
            <form method="POST" action="{{ route('richtext.update', $content->id) }}" id="editForm">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" id="titleInput" value="{{ old('title', $content->title) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" id="categoryInput" required>
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
                        <label>Tags</label>
                        <input type="text" name="tags" id="tagsInput" value="{{ old('tags', $content->tags) }}" placeholder="comma separated">
                    </div>
                    <div class="form-group">
                        <label>Featured Image URL</label>
                        <input type="url" name="featured_image" id="imageInput" value="{{ old('featured_image', $content->featured_image) }}" placeholder="https://...">
                    </div>
                </div>

                <div class="form-group">
                    <label>Content</label>

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

                    <input id="bio" type="hidden" name="bio" value="{{ $content->content }}">
                    <trix-editor input="bio" id="bio_editor"></trix-editor>
                    <div class="word-count" id="wordCount">0 words · 0 characters</div>
                </div>

                @if($content->versions->count())
                    <div class="form-group">
                        <button type="button" class="btn-icon btn-versions" onclick="toggleVersions({{ $content->id }})">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            {{ $content->versions->count() }} previous version{{ $content->versions->count() > 1 ? 's' : '' }}
                        </button>
                        <div class="versions-list" id="versions-{{ $content->id }}" style="display:none;">
                            @foreach($content->versions as $v)
                                <div class="version-row">
                                    <span>v{{ $v->version }} · {{ $v->title }}</span>
                                    <span class="version-date">{{ $v->updated_at->format('M d, Y H:i') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <label class="checkbox-group">
                        <input type="checkbox" name="is_published" {{ $content->is_published ? 'checked' : '' }}>
                        <span>Published</span>
                    </label>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn-update">
                        Update Content
                    </button>
                    <span class="autosave-status" id="autoSaveStatus">Not saved yet</span>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    const editorEl = document.querySelector('#bio_editor');
    const wordCountEl = document.querySelector('#wordCount');
    const autoSaveStatus = document.querySelector('#autoSaveStatus');
    let autoSaveTimer;
    const recordId = '{{ $content->id }}';

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
        formData.append('record_id', recordId);

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

    editorEl.addEventListener('trix-initialize', updateWordCount);

    editorEl.addEventListener('trix-change', function () {
        updateWordCount();
        triggerAutoSave();
    });

    document.querySelector('#titleInput').addEventListener('input', triggerAutoSave);
    document.querySelector('#categoryInput').addEventListener('change', triggerAutoSave);
    document.querySelector('#tagsInput').addEventListener('input', triggerAutoSave);
</script>
@endpush