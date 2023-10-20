@extends('layouts.app')

@section('content')
    <a href="{{ route('auth.redirect', ['social' => App\Enums\ProviderType::GOOGLE->value]) }}">Login with Google</a>
@stop
