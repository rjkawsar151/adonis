@extends('layouts.admin')

@section('admin_content')
@include('admin.blogs._editor_styles')
<div class="blog-admin-editor">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 mb-0 text-gray-800">Create Blog Post</h1>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
    </div>

    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
        @csrf
        <div class="row">
            <!-- Left Panel: Content -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Content Editor</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Post Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Enter post title" required onkeyup="document.getElementById('slug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '')">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" id="slug" class="form-control" placeholder="post-slug-url" required>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Excerpt / Summary <span class="text-danger">*</span></label>
                            <textarea name="excerpt" id="excerpt" class="form-control" rows="3" placeholder="Write a short summary..."></textarea>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Article Body <span class="text-danger">*</span></label>
                            <textarea name="contentHtml" id="contentEditor" class="form-control"></textarea>
                        </div>
                    </div>
                </div>

                <!-- SEO Section -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-light d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-search-plus mr-1"></i> On-Page SEO Engine</h6>
                        <span class="badge badge-info" id="seoScoreBadge">SEO Check: Unchecked</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>SEO Title (Google Title)</label>
                                <input type="text" name="seoTitle" id="seoTitle" class="form-control" placeholder="Target under 60 characters">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Breadcrumb Title</label>
                                <input type="text" name="breadcrumb_title" class="form-control" placeholder="e.g. Grooming Guides">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>SEO Meta Description</label>
                            <textarea name="seoDescription" id="seoDescription" class="form-control" rows="2" placeholder="Target under 160 characters"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Focus Keyword</label>
                                <input type="text" name="focus_keyword" id="focus_keyword" class="form-control" placeholder="Primary keyword to search for">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Secondary Keywords</label>
                                <input type="text" name="secondary_keywords" class="form-control" placeholder="Comma separated keywords">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Canonical URL</label>
                                <input type="url" name="canonical_url" class="form-control" placeholder="https://example.com/canonical">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Schema.org Structured Data Type</label>
                                <select name="schema_type" class="form-control">
                                    <option value="BlogPosting" selected>BlogPosting (Default)</option>
                                    <option value="Article">Article</option>
                                    <option value="NewsArticle">NewsArticle</option>
                                    <option value="FAQPage">FAQPage</option>
                                </select>
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col-md-6 form-check pl-4">
                                <input type="checkbox" name="robots_index" class="form-check-input" id="robots_index" value="1" checked>
                                <label class="form-check-label" for="robots_index">Allow Search Engines to Index (index)</label>
                            </div>
                            <div class="col-md-6 form-check pl-4">
                                <input type="checkbox" name="robots_follow" class="form-check-input" id="robots_follow" value="1" checked>
                                <label class="form-check-label" for="robots_follow">Instruct Search Engines to Follow links (follow)</label>
                            </div>
                        </div>

                        <!-- Live Snippet Preview -->
                        <div class="border p-3 my-3 bg-light rounded-sm">
                            <label class="small text-muted font-weight-bold uppercase">Google Snippet Live Preview</label>
                            <div class="seo-preview-box text-left bg-white p-3 border rounded">
                                <div class="text-xs text-muted font-mono" id="previewUrl">https://adonis.com.bd/blog/...</div>
                                <h6 class="text-primary font-serif font-bold mb-1" id="previewTitle">Google Search Result Title</h6>
                                <p class="text-xs text-dark leading-snug mb-0" id="previewDesc">Enter your meta description in the box above to see how search engines might render your blog description text.</p>
                            </div>
                        </div>

                        <!-- SEO Audit Feedback list -->
                        <div class="mt-3">
                            <label class="small font-weight-bold">Grooming SEO Audit Feedback</label>
                            <ul class="text-xs text-muted pl-4" id="seoFeedbackList">
                                <li>Waiting for content inputs to analyze SEO density...</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Open Graph & Social Cards -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-light">
                        <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-share-alt mr-1"></i> Social Meta (Open Graph & Twitter Cards)</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="font-weight-bold text-primary mb-3">Facebook Open Graph</h6>
                                <div class="form-group">
                                    <label>OG Title</label>
                                    <input type="text" name="og_title" class="form-control" placeholder="Leave blank to fallback to SEO Title">
                                </div>
                                <div class="form-group">
                                    <label>OG Description</label>
                                    <textarea name="og_description" class="form-control" rows="2" placeholder="Leave blank to fallback to excerpt"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="font-weight-bold text-info mb-3">X / Twitter Card</h6>
                                <div class="form-group">
                                    <label>Twitter Title</label>
                                    <input type="text" name="twitter_title" class="form-control" placeholder="Leave blank to fallback to SEO Title">
                                </div>
                                <div class="form-group">
                                    <label>Twitter Description</label>
                                    <textarea name="twitter_description" class="form-control" rows="2" placeholder="Leave blank to fallback to excerpt"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Configurations -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Metadata & Status</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Publish Status</label>
                            <select name="status" class="form-control" required>
                                <option value="draft">Draft (Private)</option>
                                <option value="published">Published (Public)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Scheduled Publication</label>
                            <input type="datetime-local" name="published_at" class="form-control">
                            <small class="text-muted">Leave empty to publish immediately on saving.</small>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label class="font-weight-bold">Assign Author</label>
                            <select name="author_id" class="form-control">
                                <option value="">Select Author</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Assign Category</label>
                            <select name="category_id" class="form-control">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Assign Tags</label>
                            <div class="border p-3" style="max-height: 180px; overflow-y: auto;">
                                @foreach($tags as $tag)
                                    <div class="form-check">
                                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="form-check-input" id="tag-{{ $tag->id }}">
                                        <label class="form-check-label" for="tag-{{ $tag->id }}">#{{ $tag->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label class="font-weight-bold">Featured Image (Upload)</label>
                            <input type="file" name="coverImage" class="form-control-file mb-2">
                            <label class="font-weight-bold mt-2">Or Featured Image URL</label>
                            <input type="text" name="coverImage_url" value="{{ old('coverImage_url') }}" class="form-control mb-2" placeholder="e.g. /assets/images/adonis_styling_chairs_1779270725139.png">
                            <small class="text-muted">Max file size 5MB. Automatic WebP compression applied.</small>
                        </div>

                        <div class="form-group form-check pl-4">
                            <input type="checkbox" name="is_featured" class="form-check-input" id="is_featured" value="1">
                            <label class="form-check-label font-weight-bold" for="is_featured">Mark as Featured Post</label>
                        </div>

                        <div class="form-group form-check pl-4">
                            <input type="checkbox" name="is_pinned" class="form-check-input" id="is_pinned" value="1">
                            <label class="form-check-label font-weight-bold" for="is_pinned">Pin Post to Top</label>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-primary btn-block">Save and Publish Post</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Load CKEditor 5 from CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    // Initialize rich text editor
    ClassicEditor
        .create(document.querySelector('#contentEditor'), {
            toolbar: [
                'heading', '|', 'bold', 'italic', 'underline', 'strikethrough', '|',
                'numberedList', 'bulletList', '|', 'outdent', 'indent', '|',
                'blockQuote', 'insertTable', 'link', '|', 'undo', 'redo'
            ]
        })
        .then(editor => {
            // Hook up editor changes to SEO analyses
            editor.model.document.on('change:data', () => {
                analyzeSEO(editor.getData());
            });
        })
        .catch(error => {
            console.error(error);
        });

    // Real-time Snippet Preview & SEO Analyzer
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    const seoTitleInput = document.getElementById('seoTitle');
    const seoDescInput = document.getElementById('seoDescription');
    const focusKeywordInput = document.getElementById('focus_keyword');

    const previewUrl = document.getElementById('previewUrl');
    const previewTitle = document.getElementById('previewTitle');
    const previewDesc = document.getElementById('previewDesc');
    const seoFeedbackList = document.getElementById('seoFeedbackList');
    const seoScoreBadge = document.getElementById('seoScoreBadge');

    function updatePreview() {
        const titleVal = seoTitleInput.value || titleInput.value || 'Google Search Result Title';
        const slugVal = slugInput.value || 'slug-url';
        const descVal = seoDescInput.value || 'Enter your meta description in the box above to see how search engines might render your blog description text.';

        previewTitle.textContent = titleVal;
        previewUrl.textContent = 'https://adonis.com.bd/blog/' + slugVal;
        previewDesc.textContent = descVal;
    }

    function analyzeSEO(htmlContent = '') {
        const title = titleInput.value;
        const seoTitle = seoTitleInput.value || title;
        const metaDesc = seoDescInput.value;
        const keyword = focusKeywordInput.value.toLowerCase();

        const feedback = [];
        let score = 100;

        // Title Length
        if (seoTitle.length < 30) {
            feedback.push('<span class="text-warning">⚠️ SEO Title is too short</span> (should be at least 30 characters).');
            score -= 15;
        } else if (seoTitle.length > 60) {
            feedback.push('<span class="text-danger">❌ SEO Title is too long</span> (should be under 60 characters).');
            score -= 15;
        } else {
            feedback.push('<span class="text-success">✓ SEO Title length is perfect</span> (30-60 characters).');
        }

        // Meta Description Length
        if (metaDesc.length < 80) {
            feedback.push('<span class="text-warning">⚠️ Meta Description is too short</span> (should be at least 80 characters).');
            score -= 20;
        } else if (metaDesc.length > 160) {
            feedback.push('<span class="text-danger">❌ Meta Description is too long</span> (should be under 160 characters).');
            score -= 20;
        } else {
            feedback.push('<span class="text-success">✓ Meta Description length is perfect</span> (80-160 characters).');
        }

        // Keyword Density checks
        if (keyword) {
            const lowerContent = htmlContent.toLowerCase();
            const keywordInTitle = seoTitle.toLowerCase().includes(keyword);
            const keywordInDesc = metaDesc.toLowerCase().includes(keyword);
            const keywordCount = (lowerContent.match(new RegExp(keyword, 'g')) || []).length;

            if (!keywordInTitle) {
                feedback.push(`<span class="text-warning">⚠️ Focus keyword "${keyword}" not found in SEO Title</span>.`);
                score -= 15;
            } else {
                feedback.push('<span class="text-success">✓ Focus keyword found in Title</span>.');
            }

            if (!keywordInDesc) {
                feedback.push(`<span class="text-warning">⚠️ Focus keyword "${keyword}" not found in Meta Description</span>.`);
                score -= 15;
            } else {
                feedback.push('<span class="text-success">✓ Focus keyword found in Meta Description</span>.');
            }

            if (keywordCount === 0) {
                feedback.push(`<span class="text-danger">❌ Focus keyword "${keyword}" not found in article body</span>.`);
                score -= 20;
            } else {
                feedback.push(`<span class="text-success">✓ Focus keyword found in body content</span> (${keywordCount} times).`);
            }
        } else {
            feedback.push('<span class="text-muted">Enter a focus keyword to analyze density.</span>');
            score = Math.max(score - 20, 0);
        }

        // Render Feedback
        seoFeedbackList.innerHTML = feedback.map(item => `<li>${item}</li>`).join('');
        
        // Update score badge
        seoScoreBadge.textContent = `SEO score: ${score}/100`;
        seoScoreBadge.className = `badge badge-${score >= 80 ? 'success' : (score >= 50 ? 'warning' : 'danger')}`;
    }

    [titleInput, slugInput, seoTitleInput, seoDescInput, focusKeywordInput].forEach(input => {
        input.addEventListener('keyup', () => {
            updatePreview();
            analyzeSEO();
        });
        input.addEventListener('change', () => {
            updatePreview();
            analyzeSEO();
        });
    });
</script>
@endsection
