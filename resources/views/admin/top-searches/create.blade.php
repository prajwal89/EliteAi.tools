@extends('layouts.admin')
@section('title', 'Add new category')
@section('content')
    <div class="card">

        <div class="card-header">
            Add new Top Search
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.top-searches.store') }}">
                @csrf

                <div class="form-group mb-2">
                    <label>query</label>
                    <input type="text" class="form-control" name="query" required>
                </div>

                <div class="form-group mb-2">
                    <label>
                        <span>serp_title</span>
                        <br>
                        ex. Top {count}+|{count} TTS AI tools
                    </label>
                    <input type="text" class="form-control" name="serp_title">
                </div>

                <div class="form-group mb-2">
                    <label>description</label>
                    <textarea type="text" rows="30" class="form-control" name="description"></textarea>
                </div>

                <button type="submit" class="btn btn-primary my-3">Add</button>
            </form>
        </div>

    </div>
@stop
