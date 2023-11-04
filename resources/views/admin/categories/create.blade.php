@extends('layouts.admin')
@section('title', 'Add new category')
@section('content')
    <div class="card">

        <div class="card-header">
            {{-- Add new category --}}

            <h5>Criteria</h5>
            <u>
                <li>?Should be searchable e.g. "Top ai video tools" is not good instead of "top AI video editing tools"</li>
                <li>Specific about tool but not so specific</li>
            </u>

        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf

                <div class="form-group mb-2">
                    <label>name</label>
                    <input type="text" class="form-control" name="name" required>
                </div>

                <div class="form-group mb-2">
                    <label>
                        <span>serp_title</span>
                        <br>
                        ex. Top {count}+|{count} TTS AI tools
                    </label>
                    <input type="text" class="form-control" name="serp_title" required>
                </div>

                <div class="form-group mb-2">
                    <label>description</label>
                    <textarea type="text" class="form-control" name="description"></textarea>
                </div>

                <button type="submit" class="btn btn-primary my-3">Add</button>
            </form>
        </div>

    </div>
@stop
