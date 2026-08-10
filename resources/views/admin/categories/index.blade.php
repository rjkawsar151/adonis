@extends('layouts.admin')

@section('admin_content')
@include('admin.blogs._management_styles')
<div class="blog-admin-editor">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 mb-0 text-gray-800">Blog Categories</h1>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addCategoryModal">
            <i class="fas fa-plus mr-1"></i> Add Category
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Categories</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>SEO Title</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td style="width: 80px;">
                                    @if($category->featured_image)
                                        <img src="{{ str_starts_with($category->featured_image, 'http') ? $category->featured_image : asset($category->featured_image) }}" alt="{{ $category->name }}" class="img-thumbnail" style="max-height: 50px;">
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td>{{ Str::limit($category->description, 60) }}</td>
                                <td>{{ $category->seo_title ?? 'None' }}</td>
                                <td>
                                    <span class="badge badge-{{ $category->status ? 'success' : 'secondary' }}">
                                        {{ $category->status ? 'Active' : 'Hidden' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-info btn-xs" data-toggle="modal" data-target="#editCategoryModal-{{ $category->id }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.blog-categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editCategoryModal-{{ $category->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <form action="{{ route('admin.blog-categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Category: {{ $category->name }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 form-group">
                                                        <label>Category Name <span class="text-danger">*</span></label>
                                                        <input type="text" name="name" class="form-control" value="{{ $category->name }}" required onkeyup="document.getElementById('edit-slug-{{ $category->id }}').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '')">
                                                    </div>
                                                    <div class="col-md-6 form-group">
                                                        <label>Slug <span class="text-danger">*</span></label>
                                                        <input type="text" name="slug" id="edit-slug-{{ $category->id }}" class="form-control" value="{{ $category->slug }}" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <textarea name="description" class="form-control" rows="3">{{ $category->description }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Featured Image</label>
                                                    <input type="file" name="featured_image" class="form-control-file">
                                                    @if($category->featured_image)
                                                        <div class="mt-2">
                                                            <img src="{{ str_starts_with($category->featured_image, 'http') ? $category->featured_image : asset($category->featured_image) }}" style="max-height: 80px;" class="img-thumbnail">
                                                        </div>
                                                    @endif
                                                </div>
                                                <hr>
                                                <h6 class="font-weight-bold text-primary">SEO Settings</h6>
                                                <div class="form-group">
                                                    <label>SEO Title</label>
                                                    <input type="text" name="seo_title" class="form-control" value="{{ $category->seo_title }}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Meta Description</label>
                                                    <textarea name="meta_description" class="form-control" rows="2">{{ $category->meta_description }}</textarea>
                                                </div>
                                                <div class="form-group form-check">
                                                    <input type="checkbox" name="status" class="form-check-input" id="edit-status-{{ $category->id }}" value="1" {{ $category->status ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="edit-status-{{ $category->id }}">Active / Visible</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary btn-sm">Update Category</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No categories created yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('admin.blog-categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required onkeyup="document.getElementById('add-slug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '')">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" id="add-slug" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Featured Image</label>
                        <input type="file" name="featured_image" class="form-control-file">
                    </div>
                    <hr>
                    <h6 class="font-weight-bold text-primary">SEO Settings</h6>
                    <div class="form-group">
                        <label>SEO Title</label>
                        <input type="text" name="seo_title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" name="status" class="form-check-input" id="add-status" value="1" checked>
                        <label class="form-check-label" for="add-status">Active / Visible</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Category</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
