@extends('layouts.admin')
@section('title', 'Edit user')
@section('content')
    <div class="card">

        <div class="card-header d-flex justify-content-between">
            <h3> Edit user</h3>
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                Delete
            </button>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', ['user' => $user->id]) }}">
                @csrf
                @method('PUT')

                <div class="form-group mb-2">
                    <label>name</label>
                    <input type="text" class="form-control" value="{{ $user->name }}" name="name" required>
                </div>

                <div class="form-group mb-2">
                    <label>email</label>
                    <input type="text" class="form-control" value="{{ $user->email }}" name="email" required>
                </div>

                <div class="form-group mb-2">
                    <label>Provider type</label>
                    <select type="select" class="form-control" name="provider_type" required>
                        @foreach (App\Enums\ProviderType::cases() as $provider)
                            @if ($provider->value == $user->provider_type->value)
                                <option value="{{ $provider->value }}" selected>{{ $provider->value }}*</option>
                            @else
                                <option value="{{ $provider->value }}">{{ $provider->value }}</option>
                            @endif
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
                        <h5 class="modal-title" id="deleteModalLabel">Delete User: {{ $user->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger"><strong>This action cannot be reversed</strong></p>
                    </div>
                    <div class="modal-footer">
                        <form method="POST" action="{{ route('admin.users.destroy', ['user' => $user->id]) }}">
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
