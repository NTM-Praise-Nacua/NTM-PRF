@extends('layouts.app')

@section('content')
    <x-container pageTitle="User List">
        <button class="btn btn-primary float-end">+ Add</button>

        <div class="">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <td>Name</td>
                        <td>Forms</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2">No data.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </x-container>
@endsection