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
            transform: scale(1.1);
        }
    </style>
@endpush
@section('content')
    <x-container pageTitle="Dashboard">

        <div class="row">
            <div class="info-card card shadow bg-warning border-0">
                <div class="card-body fw-bold text-center">
                    <p class="fs-5">In Progress</p>
                    <p class="fs-1">100</p>
                </div>
            </div>
            <div class="info-card card shadow bg-success border-0">
                <div class="card-body text-white fw-bold text-center">
                    <p class="fs-5">Completed</p>
                    <p class="fs-1">0</p>
                </div>
            </div>
            <div class="info-card card shadow bg-info border-0">
                <div class="card-body fw-bold text-center">
                    <p class="fs-5">Total</p>
                    <p class="fs-1">100</p>
                </div>
            </div>
        </div>
    </x-container>
@endsection