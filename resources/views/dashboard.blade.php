@extends('layouts.app')
@push('css')
    <style>
        .info-card {
            width: 150px;
            height: 150px;
            margin-right: 20px;
            transition: transform .15s ease-in-out;
        }
        .info-card:hover {
            transform: scale(1.05);
        }
    </style>
@endpush

@section('content')
    <x-container pageTitle="Dashboard">

        <div class="row mt-1">
            @if (auth()->user()->id != 1)
                <div class="info-card card shadow bg-warning border-0">
                    <div class="card-body fw-bold text-center">
                        <p class="fs-5">Pending</p>
                        <p class="fs-1">{{ $counters['pending'] }}</p>
                    </div>
                </div>
            @endif
            <div class="info-card card shadow bg-info border-0">
                <div class="card-body fw-bold text-center">
                    <p class="fs-5">In Progress</p>
                    <p class="fs-1">{{ $counters['inProgress'] }}</p>
                </div>
            </div>
            <div class="info-card card shadow bg-success border-0">
                <div class="card-body text-white fw-bold text-center">
                    <p class="fs-5">Completed</p>
                    <p class="fs-1">{{ $counters['completed'] }}</p>
                </div>
            </div>
            <div class="info-card card shadow bg-primary text-white border-0">
                <div class="card-body fw-bold text-center px-1">
                    <p class="fs-5 mb-0">Total</p>
                    @if (auth()->user()->id != 1)
                    <span style="font-size: 10px;">(In Progress + Completed)</span>
                    @endif
                    <p class="fs-1">{{ $counters['total'] }}</p>
                </div>
            </div>
        </div>
    </x-container>
@endsection