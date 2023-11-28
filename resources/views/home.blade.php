@extends('layouts.app-full-width')
@section('title', $pageDataDTO->title)
@section('description', $pageDataDTO->description)
@section('conical_url', $pageDataDTO->conicalUrl)

@section('content')
    @livewire('master-home-page', ['pageType' => $pageType, 'pageDataDTO' => collect($pageDataDTO), 'category' => @$category, 'topSearch' => @$topSearch, 'searchQuery' => @request()->input('q')])
@stop
