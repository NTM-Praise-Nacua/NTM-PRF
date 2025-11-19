<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequisitionForm;
use Illuminate\Http\Request;

class PurchaseRequisitionFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('approval.approval');
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
