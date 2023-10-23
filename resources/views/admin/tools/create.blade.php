@extends('layouts.admin')
@section('title', 'Add new tool')
@section('content')
    <div class="card">

        <div class="card-header">
            Add new tool
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.tools.store') }}">
                @csrf

                <div class="form-group mb-2">
                    <label>name</label>
                    <input type="text" class="form-control" name="name" required>
                </div>

                <div class="form-group mb-2">
                    <label>Provider type</label>
                    <select type="select" class="form-control" name="provider_type" required>
                        @foreach (App\Enums\ProviderType::cases() as $provider)
                            <option value="{{ $provider->value }}">{{ $provider->value }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary my-3">Add</button>
            </form>
        </div>

    </div>
@stop
