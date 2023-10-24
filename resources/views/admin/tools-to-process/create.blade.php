@extends('layouts.admin')
@section('title', 'Add new category')
@section('content')
    <div class="card">

        <div class="card-header">
            Add new category
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf

                <div class="form-group mb-2">
                    <label>name</label>
                    <input type="text" class="form-control" name="name" required>
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
