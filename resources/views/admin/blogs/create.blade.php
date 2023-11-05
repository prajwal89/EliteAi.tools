@extends('layouts.admin')
@section('title', 'Add blog')
@section('content')
    <div class="card">

        <div class="card-header">
            Add blog
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.blogs.store') }}">
                @csrf

                <div class="form-group mb-2">
                    <label>Title</label>
                    <input type="text" class="form-control" name="title" required>
                </div>

                <div class="form-group mb-2">
                    <label>min_semantic_score</label>
                    <input type="text" class="form-control" value="0.85" name="min_semantic_score" required>
                </div>

                <div class="form-group mb-2">
                    <label>description</label>
                    <textarea type="text" rows="24" class="form-control" name="description"></textarea>
                </div>

                <div class="form-group mb-4">
                    <label class="fw-bold">*Blog type</label>
                    <select type="select" class="form-control" name="blog_type" required>
                        @foreach (App\Enums\BlogType::cases() as $blogType)
                            <option value="{{ $blogType->value }}">{{ $blogType->value }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary my-3">Add</button>
            </form>
        </div>

    </div>
@stop
