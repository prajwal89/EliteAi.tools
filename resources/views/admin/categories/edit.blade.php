@extends('layouts.admin')
@section('title', 'Edit Category')
@section('content')
    <div class="card">

        <div class="card-header d-flex justify-content-between">
            <span>Edit Category</span>
            <div>
                <a class="btn btn-outline-primary float-right" target="_blank"
                    href="{{ route('category.show', ['category' => $category->slug]) }}">
                    View
                </a>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    Delete
                </button>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.update', ['category' => $category->id]) }}">
                @csrf
                @method('PUT')

                <div class="form-group mb-2">
                    <label>name</label>
                    <input type="text" class="form-control" value="{{ $category->name }}" name="name" required>
                </div>

                <div class="form-group mb-2">
                    <label>
                        <span>serp_title</span>
                        <br>
                        ex. Top {count}+|{count} TTS AI tools
                    </label>
                    <input type="text" value="{{ $category->serp_title }}" class="form-control" name="serp_title"
                        required>
                </div>

                <div class="form-group mb-2">
                    <label>description</label>
                    <textarea type="text" rows="10" class="form-control" name="description">{{ $category->description }}</textarea>
                </div>


                <button type="submit" class="btn btn-primary my-3">Update</button>
            </form>
        </div>





        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete User: {{ $category->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger"><strong>This action cannot be reversed</strong></p>
                    </div>
                    <div class="modal-footer">
                        <form method="POST"
                            action="{{ route('admin.categories.destroy', ['category' => $category->id]) }}">
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
