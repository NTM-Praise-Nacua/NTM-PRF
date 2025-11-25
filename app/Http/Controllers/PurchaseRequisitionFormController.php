<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\PRWorkFlowSteps;
use App\Models\PurchaseRequisitionForm;
use App\Models\RequestType;
use App\Models\UploadedFile;
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
        $departments = Department::where('isActive', 1)->get()->toArray();
        $requestTypes = $this->requestType->getRequestType();

        // dd($departments);

        return view('admin.approval', compact('requestTypes', 'departments'));
    }

    public function uploadPdf(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf',
            'request_type' => 'required|string',
        ]);

        $requestType = RequestType::where('slug', $request->request_type)->first();
        $requestId = $requestType->id;


        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('pdfs', $filename, 'public');

        $uploadedFile = UploadedFile::create([
            'uploaded_by' => auth()->user()->id,
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $filename,
            'path' => $path,
            'size' => $file->getSize(),
            'type' => $file->getClientMimeType(),
            'request_type_id' => $requestId,
        ]);


        return response()->json([
            'message' => 'successful',
            'url' => $uploadedFile
        ]);
    }

    public function showForm()
    {
        $requestTypes = RequestType::all();
        return view('users.requisition-form', compact('requestTypes'));
    }

    public function savePRF(Request $request)
    {
        dd($request->all());
    }

    public function otherPRFDetails(Request $request)
    {
        dd($request->all());
        
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
        $request->validate([
            'ordering' => 'required|array|min:1',
            'requestType' => 'required',
        ]);

        $data = [];
        $ordering = $request->ordering;

        $incomingIds = collect($ordering)
            ->pluck('id')
            ->filter()
            ->toArray();

        PRWorkFlowSteps::where('type_id', $request->requestType)
            ->whereNotIn('id', $incomingIds)
            ->delete();

        foreach($ordering as $order) {
            $data[] = [
                'type_id' => $request->requestType,
                'ordering' => (int) $order['orderId'],
                'id' => (int) $order['id'],
                'created_by' => auth()->user()->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        $result = PRWorkFlowSteps::upsert($data, 
            ['id'], 
            ['ordering', 'type_id', 'created_by', 'updated_at']
        );
        
        return json_encode([
            'status' => 'success',
            'message' => 'Successfully Saved!',
            'data' => $result
        ]);
    }

    public function getTypeFlow(Request $request) 
    {
        $requestTypeFlow = PRWorkFlowSteps::where('type_id', $request->request_id)->get();

        return json_encode([
            'status' => 'success',
            'data' => $requestTypeFlow
        ]);
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
