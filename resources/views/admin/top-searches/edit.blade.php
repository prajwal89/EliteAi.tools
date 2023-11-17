@extends('layouts.admin')
@section('title', 'Edit Category')
@section('content')
    <div class="card">

        <div class="card-header d-flex justify-content-between">
            <span>Edit Category</span>
            <div>
                <a class="btn btn-outline-primary float-right" target="_blank"
                    href="{{ route('search.popular.show', ['top_search' => $topSearch->slug]) }}">
                    View
                </a>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    Delete
                </button>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.top-searches.update', ['top_search' => $topSearch->id]) }}">
                @csrf
                @method('PUT')

                <div class="form-group mb-2">
                    <label>query</label>
                    <input type="text" class="form-control" value="{{ $topSearch->query }}" name="query" required>
                </div>

                <div class="form-group mb-2">
                    <label>
                        <span>serp_title</span>
                        <br>
                        ex. Top {count}+|{count} TTS AI tools
                    </label>
                    <input type="text" value="{{ $topSearch->serp_title }}" class="form-control" name="serp_title">
                </div>

                <div class="form-group mb-2">
                    <label>description</label>
                    <textarea type="text" rows="10" class="form-control" name="description">{{ $topSearch->description }}</textarea>
                </div>


                <button type="submit" class="btn btn-primary my-3">Update</button>
            </form>
        </div>





        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete User: {{ $topSearch->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger"><strong>This action cannot be reversed</strong></p>
                    </div>
                    <div class="modal-footer">
                        <form method="POST"
                            action="{{ route('admin.top-searches.destroy', ['top_search' => $topSearch->id]) }}">
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
