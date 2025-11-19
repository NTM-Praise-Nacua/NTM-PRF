@extends('layouts.app')

@section('content')
    <x-container pageTitle="Approval Setup">
        <x-section-head headTitle="Approval Process"></x-section-head>

        <div class="row p-3 ">
            <div class="col-3">
                <div class="card pt-3 px-2 pb-1 mb-3">
                    <h5 class="card-title">Request Type</h5>
                    <div class="card-body px-0">
                        <select name="test" id="test" class="form-select">
                            <option value="test">test</option>
                        </select>
                    </div>
                </div>

                <button type="button" class="btn btn-primary">
                    + Add
                </button>
            </div>
            <div class="col">
                <div class="d-flex flex-column flex-wrap border rounded overflow-x-auto p-2" style="height: 206px;">
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                    <x-tag-label orderNumber="1" requestType="test"></x-tag-label>
                </div>
            </div>
        </div>

        <x-section-head headTitle="Upload PDF Template"></x-section-head>
    </x-container>
@endsection