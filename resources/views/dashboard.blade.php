@extends('layouts.app')
@push('css')
    <style>
        .info-card {
            position: relative;
            width: 150px;
            height: 150px;
            margin-right: 20px;
            overflow: hidden;
            border-radius: 10px;
            background: #111;
            transition: transform .15s ease-in-out, box-shadow .3s ease;
        }
        .info-card:hover {
            transform: scale(1.05);

            box-shadow: 0 0 15px rgba(255, 255, 255, 0.25);
        }
        .info-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                0deg,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0) 30%,
                rgba(255, 255, 255, 0.35) 50%,
                rgba(255, 255, 255, 0) 70%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: rotate(-45deg);
            opacity: 0;
            transition: all 0.5s ease;
            pointer-events: none;
        }
        .info-card:hover::before {
            opacity: 1;
            transform: rotate(-45deg) translateY(100%);
        }
        .bg-complete {
            background: #28A745 !important;
        }
        .bg-inprogress {
            background: #42A5F5 !important;
        }
        .bg-executed {
            background: #00BCD4 !important
        }
        /* .datepicker-dropdown {
            padding: .75rem !important;
            border-radius: .5rem !important;
        } */
        #monthYear {
            width: 200px !important;
        }
    </style>
@endpush

@php
    $isAdmin = auth()->user()->id == 1;
@endphp

@section('content')
    <x-container :pageTitle="$formatted">
        {{-- <div class="mt-2">
            <label for="monthYear" class="form-label">Select Month & Year</label>
            <input type="text" class="form-control" id="monthYear" name="month_year">
        </div> --}}
        <div class="date my-2">
            <input type="text" class="form-control" id="monthYear" name="month_year" placeholder="Pick a Date" autocomplete="off">
            <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
            </div>
        </div>
        <div class="row mx-0 mt-1 gap-3">
            <div class="info-card card shadow bg-warning bg-gradient border-0">
                <div class="card-body fw-bold text-center">
                    <p class="fs-6">Pending</p>
                    <p class="fs-1">{{ $counters['pending'] }}</p>
                </div>
            </div>
            <div class="info-card card shadow bg-success bg-gradient border-0">
                <div class="card-body text-white fw-bold text-center">
                    <p class="fs-6">Approved</p>
                    <p class="fs-1">{{ $counters['approved'] }}</p>
                </div>
            </div>
            <div class="info-card card shadow bg-danger bg-gradient border-0">
                <div class="card-body text-white fw-bold text-center">
                    <p class="fs-6">Rejected</p>
                    <p class="fs-1">{{ $counters['rejected'] }}</p>
                </div>
            </div>
            <div class="info-card card shadow bg-inprogress bg-gradient border-0">
                <div class="card-body fw-bold text-center">
                    <p class="fs-6">In Progress</p>
                    <p class="fs-1">{{ $counters['inProgress'] }}</p>
                </div>
            </div>
            <div class="info-card card shadow bg-executed bg-gradient border-0">
                <div class="card-body fw-bold text-center">
                    <p class="fs-6">Executed</p>
                    <p class="fs-1">{{ $counters['executed'] }}</p>
                </div>
            </div>
            <div class="info-card card shadow bg-complete bg-gradient border-0">
                <div class="card-body text-white fw-bold text-center">
                    <p class="fs-6">Completed</p>
                    <p class="fs-1">{{ $counters['completed'] }}</p>
                </div>
            </div>
            <div class="info-card card shadow bg-primary bg-gradient border-0">
                <div class="card-body fw-bold text-center px-1">
                    <p class="fs-6 {{ $isAdmin ? "" : "mb-0" }}">Total</p>
                    <p class="fs-1">{{ $counters['total'] }}</p>
                </div>
            </div>
        </div>
    </x-container>
@endsection

@push('js')
    <script>
        $(function () {
            $('#monthYear').datepicker({
                format: "mm-yyyy",
                startView: "months",
                minViewMode: "months",
                autoclose: true
            });

            $('#monthYear').on('change', function() {
                const monthYear = $(this).val();
                window.location.href = `{{ route('home') }}?month_year=${monthYear}`;
            });
        });
    </script>
@endpush