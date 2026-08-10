@extends('layouts.admin')

@section('admin_content')
@include('admin.blogs._management_styles')
<div class="blog-admin-editor">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 mb-0 text-gray-800">Blog Authors</h1>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addAuthorModal">
            <i class="fas fa-plus mr-1"></i> Add Author
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
            <h6 class="m-0 font-weight-bold text-primary">All Authors</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Email</th>
                            <th>Socials</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($authors as $author)
                            <tr>
                                <td style="width: 80px;">
                                    @if($author->profile_photo)
                                        <img src="{{ str_starts_with($author->profile_photo, 'http') ? $author->profile_photo : asset($author->profile_photo) }}" alt="{{ $author->name }}" class="img-thumbnail" style="max-height: 50px;">
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>
                                <td><strong>{{ $author->name }}</strong></td>
                                <td>{{ $author->designation ?? 'None' }}</td>
                                <td>{{ $author->email ?? 'None' }}</td>
                                <td>
                                    <div class="flex gap-1">
                                        @if($author->facebook_url)<a href="{{ $author->facebook_url }}" target="_blank" class="text-primary mr-1"><i class="fab fa-facebook"></i></a>@endif
                                        @if($author->linkedin_url)<a href="{{ $author->linkedin_url }}" target="_blank" class="text-info mr-1"><i class="fab fa-linkedin"></i></a>@endif
                                        @if($author->twitter_url)<a href="{{ $author->twitter_url }}" target="_blank" class="text-dark"><i class="fab fa-twitter"></i></a>@endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $author->status ? 'success' : 'secondary' }}">
                                        {{ $author->status ? 'Active' : 'Hidden' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-info btn-xs" data-toggle="modal" data-target="#editAuthorModal-{{ $author->id }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.blog-authors.destroy', $author->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this author?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editAuthorModal-{{ $author->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <form action="{{ route('admin.blog-authors.update', $author->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Author: {{ $author->name }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 form-group">
                                                        <label>Author Name <span class="text-danger">*</span></label>
                                                        <input type="text" name="name" class="form-control" value="{{ $author->name }}" required>
                                                    </div>
                                                    <div class="col-md-6 form-group">
                                                        <label>Designation</label>
                                                        <input type="text" name="designation" class="form-control" value="{{ $author->designation }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 form-group">
                                                        <label>Email</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $author->email }}">
                                                    </div>
                                                    <div class="col-md-6 form-group">
                                                        <label>Website URL</label>
                                                        <input type="url" name="website" class="form-control" value="{{ $author->website }}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Biography</label>
                                                    <textarea name="biography" class="form-control" rows="3">{{ $author->biography }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Profile Photo</label>
                                                    <input type="file" name="profile_photo" class="form-control-file">
                                                    @if($author->profile_photo)
                                                        <div class="mt-2">
                                                            <img src="{{ str_starts_with($author->profile_photo, 'http') ? $author->profile_photo : asset($author->profile_photo) }}" style="max-height: 80px;" class="img-thumbnail">
                                                        </div>
                                                    @endif
                                                </div>
                                                <hr>
                                                <h6 class="font-weight-bold text-primary">Social Connections</h6>
                                                <div class="row">
                                                    <div class="col-md-4 form-group">
                                                        <label>Facebook URL</label>
                                                        <input type="url" name="facebook_url" class="form-control" value="{{ $author->facebook_url }}">
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label>LinkedIn URL</label>
                                                        <input type="url" name="linkedin_url" class="form-control" value="{{ $author->linkedin_url }}">
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label>X / Twitter URL</label>
                                                        <input type="url" name="twitter_url" class="form-control" value="{{ $author->twitter_url }}">
                                                    </div>
                                                </div>
                                                <div class="form-group form-check">
                                                    <input type="checkbox" name="status" class="form-check-input" id="edit-status-{{ $author->id }}" value="1" {{ $author->status ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="edit-status-{{ $author->id }}">Active Status</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary btn-sm">Update Profile</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No authors registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addAuthorModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('admin.blog-authors.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Register Author Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Author Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Designation</label>
                            <input type="text" name="designation" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Website URL</label>
                            <input type="url" name="website" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Biography</label>
                        <textarea name="biography" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Profile Photo</label>
                        <input type="file" name="profile_photo" class="form-control-file">
                    </div>
                    <hr>
                    <h6 class="font-weight-bold text-primary">Social Connections</h6>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Facebook URL</label>
                            <input type="url" name="facebook_url" class="form-control">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>LinkedIn URL</label>
                            <input type="url" name="linkedin_url" class="form-control">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>X / Twitter URL</label>
                            <input type="url" name="twitter_url" class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" name="status" class="form-check-input" id="add-status" value="1" checked>
                        <label class="form-check-label" for="add-status">Active Status</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Profile</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
