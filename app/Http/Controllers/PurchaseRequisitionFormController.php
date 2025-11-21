<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequisitionForm;
use App\Services\RequestTypeService;
use Illuminate\Http\Request;

class PurchaseRequisitionFormController extends Controller
{
    protected $requestType;

    public function __construct(RequestTypeService $service)
    {
        $this->requestType = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requestTypes = $this->requestType->getRequestType();

        return view('admin.approval', compact('requestTypes'));
    }

    public function showForm()
    {
        return view('users.requisition-form');
    }

    public function showHistory()
    {
        return view('users.requisition-history');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseRequisitionForm  $purchaseRequisitionForm
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseRequisitionForm $purchaseRequisitionForm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseRequisitionForm  $purchaseRequisitionForm
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseRequisitionForm $purchaseRequisitionForm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseRequisitionForm  $purchaseRequisitionForm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseRequisitionForm $purchaseRequisitionForm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseRequisitionForm  $purchaseRequisitionForm
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseRequisitionForm $purchaseRequisitionForm)
    {
        //
    }
}
