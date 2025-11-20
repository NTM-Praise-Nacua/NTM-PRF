@extends('layouts.app')

@push('css')
    <style>
        .inner-shadow {
            box-shadow: inset 0px 0px 5px rgba(0, 0, 0, 0.25);
        }
        .tagLabel {
            display: inline-flex;
            width: 300px;
            background: #1447e6;
            clip-path: polygon(0 0, 
            calc(100% - 50px) 0,
            100% 45%,
            100% 55%,
            calc(100% - 50px) 100%,
            0 100%);
            user-select: none;
            cursor: grab;
            transition: transform .15s ease-in-out;
        }
        .tagLabel:hover {
            transform: scale(1.03);
        }
        .tagLabel:active {
            cursor: grabbing;
        }
        .circle {
            height: 45px;
            width: 45px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush

@section('content')
    <x-container pageTitle="Approval Setup">
        <x-section-head headTitle="Approval Process"></x-section-head>

        <div class="row p-3 ">
            <div class="col-3">
                <div class="card pt-3 px-2 pb-1 mb-3">
                    <h5 class="card-title">Request Type</h5>
                    <div class="card-body px-0">
                        <select name="test" id="test" class="form-select">
                            <option value="Office Supply">Office Supply</option>
                            <option value="Safety Equipment">Safety Equipment</option>
                            <option value="Marketing Collaterals">Marketing Collaterals</option>
                            <option value="Store Dress Up">Store Dress Up</option>
                            <option value="IT Equipment">IT Equipment</option>
                            <option value="Utility Supplies">Utility Supplies</option>
                            <option value="Signage Request">Signage Request</option>
                            <option value="Event & Sponsorship">Event & Sponsorship</option>
                            <option value="Calling Card">Calling Card</option>
                            <option value="Fixed Assets">Fixed Assets</option>
                            <option value="Vechile Dress Up">Vechile Dress Up</option>
                            <option value="Reward Items">Reward Items</option>
                        </select>
                    </div>
                </div>

                <button type="button" class="btn btn-primary">
                    + Add
                </button>
            </div>
            <div class="col">
                <div class="d-flex flex-column flex-wrap border rounded overflow-x-auto p-4 inner-shadow" style="height: 350px; row-gap: 16px; column-gap: 16px;">
                    <x-tag-label orderNumber="1" requestType="Sales"></x-tag-label>
                    <x-tag-label orderNumber="2" requestType="Marketing"></x-tag-label>
                    <x-tag-label orderNumber="3" requestType="Sales"></x-tag-label>
                    <x-tag-label orderNumber="4" requestType="Procurement"></x-tag-label>
                </div>
            </div>
        </div>

        <x-section-head headTitle="Upload PDF Template" :headerButton="true" buttonLabel="Upload"></x-section-head>
    </x-container>
@endsection