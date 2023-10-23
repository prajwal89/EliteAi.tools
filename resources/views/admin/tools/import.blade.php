@extends('layouts.admin')
@section('title', 'Import tool from json')
@section('head')
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
@stop

@section('content')
    <div class="card">

        <div class="card-header">
            Import tool from json
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.tools.import') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-4">
                    <label class="fw-bold">summary</label>
                    <textarea type="text" class="form-control" name="tool_json_sting"></textarea>
                </div>

                <button type="submit" class="btn btn-primary my-3">
                    Import
                </button>
            </form>
        </div>

    </div>
@stop
