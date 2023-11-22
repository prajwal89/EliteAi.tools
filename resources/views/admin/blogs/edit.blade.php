@extends('layouts.admin')
@section('title', 'Edit blog')
@section('content')
    <div class="card">

        <div class="card-header d-flex justify-content-between">
            <span>Edit blog</span>
            <div>
                <a class="btn btn-outline-primary float-right" target="_blank"
                    href="{{ route('blog.show', ['blog' => $blog->slug]) }}">
                    View
                </a>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    Delete
                </button>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.blogs.update', ['blog' => $blog->id]) }}">
                @csrf
                @method('PUT')

                <div class="form-group mb-2">
                    <label>Title</label>
                    <input type="text" class="form-control" value="{{ $blog->title }}" name="title" required>
                </div>

                <div class="form-group mb-2">
                    <label>serp_title</label>
                    <input type="text" class="form-control" value="{{ $blog->serp_title }}" name="serp_title">
                </div>

                <div class="form-group mb-2">
                    <label>description</label>
                    <textarea type="text" rows="12" class="form-control" name="description">{{ $blog->description }}</textarea>
                </div>

                <div class="form-group mb-2">
                    <label>min_semantic_score</label>
                    <input type="text" class="form-control" value="{{ $blog->min_semantic_score }}"
                        name="min_semantic_score" required>
                </div>


                <div class="form-group mb-4">
                    <label class="fw-bold">*Blog type</label>
                    <select type="select" class="form-control" name="blog_type" required>
                        @if (isset($blog->blog_type))
                            <option value="{{ $blog->blog_type->value }}" selected>
                                {{ $blog->blog_type->value }}
                            </option>
                        @endif

                        @foreach (App\Enums\BlogType::cases() as $blogType)
                            <option value="{{ $blogType->value }}">{{ $blogType->value }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary my-3">Update</button>
            </form>
        </div>





        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete User: {{ $blog->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger"><strong>This action cannot be reversed</strong></p>
                    </div>
                    <div class="modal-footer">
                        <form method="POST" action="{{ route('admin.blogs.destroy', ['blog' => $blog->id]) }}">
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
