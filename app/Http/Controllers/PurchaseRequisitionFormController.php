<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePRFRequest;
use App\Models\Department;
use App\Models\PRWorkFlowSteps;
use App\Models\PurchaseRequisitionForm;
use App\Models\RequestFile;
use App\Models\RequestType;
use App\Models\RequisitionWorkflowTracker;
use App\Models\UploadedFile;
use App\Models\User;
use App\Services\RequestTypeService;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

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
            'files' => 'required|array',
            'files.*' => 'file|mimes:pdf',
            'request_type' => 'required|string',
        ]);

        $requestType = RequestType::where('id', $request->request_type)->first();
        // dd($requestType, $request->request_type);
        $requestId = $requestType->id;

        $file = $request->file('files');

        $uploadedFileIds = [];

        foreach ($request->file('files') as $file) {
            $uploadedFile = $this->uploadFile($file, $requestId);
            $uploadedFileIds[] = $uploadedFile->id;
        }

        return response()->json([
            'message' => 'successful',
            'url' => $uploadedFileIds
        ]);
    }

    public function uploadFile($file, $requestTypeId = null)
    {
        $filename = Str::uuid() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('pdfs', $filename, 'public');

        $uploadedFile = UploadedFile::create([
            'uploaded_by' => auth()->user()->id,
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $filename,
            'path' => $path,
            'size' => $file->getSize(),
            'type' => $file->getClientMimeType(),
            'request_type_id' => $requestTypeId,
        ]);

        return $uploadedFile;
    }

    public function showForm()
    {
        $requestTypes = RequestType::all();
        $departments = Department::where('isActive', 1)->get();

        return view('requisition.requisition-form', compact('requestTypes', 'departments'));
    }

    public function savePRF(StorePRFRequest $request)
    {
        $prfData = $request->only([
            'date_request',
            'date_needed',
            'status',
            'full_name',
            'request_by',
            'contact',
            'position',
            'department',
            'branch',
            'urgency',
            'request_type',
            'request_details',
            'next_department',
            'assign_employee',
        ]);

        DB::transaction(function () use ($request, $prfData) {

            $PRF = PurchaseRequisitionForm::create($prfData);

            $uploadedFileIds = [];
            foreach ($request->file('upload_pdf') as $file) {
                $uploadedFile = $this->uploadFile($file);
                $uploadedFileIds[] = $uploadedFile->id;
            }

            $PRF->files()->attach($uploadedFileIds);

            RequisitionWorkflowTracker::create([
                'requisition_id' => $PRF->id,
                'department_id' => $PRF->next_department,
                'employee_id' => $PRF->assign_employee,
            ]);
        });

        return redirect()->route('requisition.history');
    }

    public function getPDFFiles(Request $request)
    {
        $files = UploadedFile::where('request_type_id', $request->request_id)->get();

        return json_encode([
            'status' => 'success',
            'files' => $files
        ]);
    }

    public function otherPRFDetails(Request $request)
    {
        $PRFWorkflow = optional(
            PRWorkFlowSteps::where('type_id', $request->request_id)->first()
        )->toArray();

        $completeFlow = PRWorkFlowSteps::with('department')->where('type_id', $request->request_id)->get();
        $completeFlow = $completeFlow->pluck('department.name');
        
        $ordering = $PRFWorkflow['ordering'] ?? null;
        
        // dd($PRFWorkflow,$request->request_id);
        $employees = $ordering ? User::where('department_id', $PRFWorkflow['ordering'])->where('id', '!=', (int)$request->requestor_id)->get() : collect();

        $files = UploadedFile::where('request_type_id', $request->request_id)->get();

        return json_encode([
            'status' => 'success',
            'data' => [
                'department' => $PRFWorkflow,
                'employee' => $employees,
                'file' => $files,
                'completeFlow' => $completeFlow
            ]
        ]);
    }

    public function showHistory()
    {
        return view('requisition.requisition-history');
    }

    public function showPRFList()
    {
        $prfData = PurchaseRequisitionForm::with([
            'requestType',
            'positionName',
            'departmentName',
            'assignedEmployee',
            'tracker' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(1);
            }
        ])->orderBy('created_at','desc');

        if (auth()->user()->role_id != 1) {
            $userId = auth()->user()->id;
            $prfData->where(function ($query) use ($userId) {
                $query->where('request_by', $userId)
                    ->orWhere('assign_employee', $userId);
            });
        }

        // $prfData = $prfData->get()->map(function ($prf) {
        //     $prf->latest_tracker = $prf->tracker->last();
        //     return $prf;
        // });

        return DataTables::of($prfData)
            ->addIndexColumn()
            ->editColumn('request_type', function ($row) {
                return $row->requestType->name;
            })
            ->editColumn('date_request', function ($row) {
                $datefromDB = $row->date_request;
                if (!empty($datefromDB)) {
                    return (new DateTime($row->date_request))->format('M d, Y');
                }
                return '';
            })
            ->editColumn('date_needed', function ($row) {
                $datefromDB = $row->date_needed;
                if (!empty($datefromDB)) {
                    return (new DateTime($row->date_needed))->format('M d, Y');
                }
                return '';
            })
            ->editColumn('status', function ($row) {
                $status = [
                    0 => 'Pending',
                    1 => 'Approved',
                    2 => 'Rejected',
                    3 => 'Executed',
                    4 => 'Confirmed',
                ];
                return $status[$row->status];
            })
            ->editColumn('request_by', function ($row) {
                return $row->requestBy->name;
            })
            ->editColumn('position', function ($row) {
                return $row->positionName->name;
            })
            ->editColumn('department', function ($row) {
                return $row->departmentName->name;
            })
            ->editColumn('assign_employee', function ($row) {
                return $row->assignedEmployee->name;
            })
            ->addColumn('actions', function ($row) {
                return '<a href="'.route('requisition.edit', $row->id).'" class="btn btn-sm btn-info">View</a>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getEmployeeByDepartment(Request $request)
    {
        $users = User::where('department_id', $request->department_id)->where('id', '!=', $request->requestor_id)->get();
        
        return json_encode([
            'status' => 'success',
            'data' => $users
        ]);
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
    public function edit(PurchaseRequisitionForm $requisition)
    {
        $requisition->load(['requestType', 'attachmentsByPRF', 'workflowSteps']);
        
        $requestTypes = RequestType::all();
        $departments = Department::where('isActive', 1)->get();

        $attachments = $requisition->attachmentsByPRF()->where('uploaded_by', $requisition->request_by)->get();

        $PRFWorkflow = $requisition->workflowSteps;
        $tracker = $requisition->tracker()->orderBy('id', 'asc')->get()->toArray();

        return view('requisition.requisition-edit', compact('requisition', 'requestTypes', 'departments', 'attachments', 'PRFWorkflow', 'tracker'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseRequisitionForm  $purchaseRequisitionForm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseRequisitionForm $requisition)
    {
        // dd($request->all(), $requisition);
        if (!$request->exists('assign_employee') && !$request->exists('department_id')) {
            $request->validate([
                'upload_pdf' => 'required|array',
                'upload_pdf.*' => 'file|mimes:pdf',
            ]);

            $latestTracker = $requisition->tracker()->latest()->first();
    
            if ($latestTracker) {
                $latestTracker->submitted_at = now();
                $latestTracker->save();
            }
    
            $requisition->status = 1;
            $requisition->next_department = $requisition->department;
            $requisition->assign_employee = $requisition->request_by;
            $requisition->save();
            
            $uploadedFileIds = collect($request->file('upload_pdf'))
                ->map(function ($file) {
                    return $this->uploadFile($file)->id;
                })
                ->toArray();
            
            $requisition->files()->attach($uploadedFileIds);
        } else {
            $request->validate([
                'assign_employee' => 'required',
                'department_id' => 'required',
                'upload_pdf' => 'required|array',
                'upload_pdf.*' => 'file|mimes:pdf',
            ]);

            $latestTracker = $requisition->tracker()->latest()->first();
    
            if ($latestTracker) {
                $latestTracker->submitted_at = now();
                $latestTracker->save();
            }
    
            $requisition->next_department = $request->department_id;
            $requisition->assign_employee = $request->assign_employee;
            $requisition->save();
            
            $uploadedFileIds = collect($request->file('upload_pdf'))
                ->map(function ($file) {
                    return $this->uploadFile($file)->id;
                })
                ->toArray();
            
            $requisition->files()->attach($uploadedFileIds);
            
            RequisitionWorkflowTracker::create([
                'requisition_id' => $requisition->id,
                'department_id' => $request->department_id,
                'employee_id' => $requisition->assign_employee,
            ]);
        }
        
        
        return redirect()->route("requisition.history");
    }

    public function getRequestStatus(Request $request)
    {
        $requisition = PurchaseRequisitionForm::with('tracker.department')->find($request->id);

        $requestorDepartment = [$requisition->departmentName->shortcut ?? 'N/A'];

        $workflowDepartments = $requisition->tracker->map(function($step) {
            return $step->department->shortcut ?? 'N/A';
        })->toArray();

        $departments = array_merge($requestorDepartment, $workflowDepartments);

        $files = $requisition->files->toArray();
        
        $grouped = [];
        $currentGroup = [];
        $previousUploader = null;

        foreach ($files as $file) {
            if ($file['uploaded_by'] !== $previousUploader) {
                if (!empty($currentGroup)) {
                    $grouped[] = $currentGroup;
                }
                $currentGroup = [$file];
                $previousUploader = $file['uploaded_by'];
            } else {
                $currentGroup[] = $file;
            }
        }

        if (!empty($currentGroup)) {
            $grouped[] = $currentGroup;
        }

        $return = [            
            'status' => 'success',
            'data' => [
                'departments' => $departments,
                'files' => $grouped,
            ]
        ];

        return json_encode($return);
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
