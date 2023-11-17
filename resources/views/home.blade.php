@extends('layouts.app-full-width', ['livewire' => true])
@section('title', $pageDataDTO->title)
@section('description', $pageDataDTO->description)
@section('conical_url', $pageDataDTO->conicalUrl)

@section('content')
    @livewire('master-home-page', ['pageType' => 'home'])
@stop
