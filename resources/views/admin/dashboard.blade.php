@extends('layouts.admin')
@section('head')
    @livewireChartsScripts
@stop
@section('content')
    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
    <div class="my-4" style="height: 20rem;">
        <livewire:livewire-column-chart :column-chart-model="$columnChartModel" />
    </div>

    <div class="row">
        <div class="col-6" style="height: 32rem;">
        </div>
        <div class="col-6"></div>

    </div>
@stop
