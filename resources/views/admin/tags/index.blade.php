@extends('layouts.admin')

@section('admin_content')
@include('admin.blogs._management_styles')
<div class="blog-admin-editor">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 mb-0 text-gray-800">Blog Tags</h1>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addTagModal">
            <i class="fas fa-plus mr-1"></i> Add Tag
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
            <h6 class="m-0 font-weight-bold text-primary">All Tags</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tags as $tag)
                            <tr>
                                <td><strong>#{{ $tag->name }}</strong></td>
                                <td><code>{{ $tag->slug }}</code></td>
                                <td>{{ $tag->description ?? 'None' }}</td>
                                <td>
                                    <span class="badge badge-{{ $tag->status ? 'success' : 'secondary' }}">
                                        {{ $tag->status ? 'Active' : 'Hidden' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-info btn-xs" data-toggle="modal" data-target="#editTagModal-{{ $tag->id }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.blog-tags.destroy', $tag->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this tag?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editTagModal-{{ $tag->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-md" role="document">
                                    <form action="{{ route('admin.blog-tags.update', $tag->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Tag: #{{ $tag->name }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Tag Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="name" class="form-control" value="{{ $tag->name }}" required onkeyup="document.getElementById('edit-slug-{{ $tag->id }}').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '')">
                                                </div>
                                                <div class="form-group">
                                                    <label>Slug <span class="text-danger">*</span></label>
                                                    <input type="text" name="slug" id="edit-slug-{{ $tag->id }}" class="form-control" value="{{ $tag->slug }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <textarea name="description" class="form-control" rows="3">{{ $tag->description }}</textarea>
                                                </div>
                                                <div class="form-group form-check">
                                                    <input type="checkbox" name="status" class="form-check-input" id="edit-status-{{ $tag->id }}" value="1" {{ $tag->status ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="edit-status-{{ $tag->id }}">Active / Visible</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary btn-sm">Update Tag</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No tags created yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addTagModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form action="{{ route('admin.blog-tags.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Tag</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tag Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required onkeyup="document.getElementById('add-slug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '')">
                    </div>
                    <div class="form-group">
                        <label>Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" id="add-slug" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" name="status" class="form-check-input" id="add-status" value="1" checked>
                        <label class="form-check-label" for="add-status">Active / Visible</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Tag</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
