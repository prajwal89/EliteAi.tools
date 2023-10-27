@extends('layouts.admin')
@section('title', 'Import tool')
@section('head')
    @livewireStyles
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
@stop

@section('content')
    @livewire('tool-importer')
@stop

@section('scripts')
    @livewireScripts()
@stop
