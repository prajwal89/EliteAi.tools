@extends('layouts.admin')
@section('title', 'Edit extractedToolDomain')
@section('content')
    <div class="card">

        <div class="card-header d-flex justify-content-between">
            <h3>Edit Category</h3>
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                Delete
            </button>
        </div>

        <div class="card-body">
            <form method="POST"
                action="{{ route('admin.tools-to-process.update', ['tools_to_process' => $extractedToolDomain->id]) }}">
                @csrf
                @method('PUT')

                <div class="form-group mb-2">
                    <label>home_page_url</label>
                    <input type="text" class="form-control" value="{{ $extractedToolDomain->home_page_url }}"
                        name="home_page_url" required>
                </div>

                <div class="form-group mb-2">
                    <label>domain_name</label>
                    <textarea type="text" class="form-control" name="domain_name">{{ $extractedToolDomain->domain_name }}</textarea>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="checkbox1" name="should_process"
                        {{ $extractedToolDomain->should_process ? 'checked' : '' }}>
                    <label class="form-check-label" for="checkbox1">Should Process</label>
                </div>

                <button type="submit" class="btn btn-primary my-3">Update</button>
            </form>
        </div>



        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete User: {{ $extractedToolDomain->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger"><strong>This action cannot be reversed</strong></p>
                    </div>
                    <div class="modal-footer">
                        <form method="POST"
                            action="{{ route('admin.categories.destroy', ['category' => $extractedToolDomain->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
