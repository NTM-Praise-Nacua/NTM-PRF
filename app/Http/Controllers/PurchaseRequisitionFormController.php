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
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

        $additionalOptions = [
            [
                "id" => -2,
                "name" => "Requestor",
                "shortcut" => "Requestor",
                "created_by" => null,
                "isActive" => 1,
                "created_at" => null,
                "updated_at" => null,
            ],
            [
                "id" => -1,
                "name" => "Immediate Head",
                "shortcut" => "Immediate Head",
                "created_by" => null,
                "isActive" => 1,
                "created_at" => null,
                "updated_at" => null,
            ]
        ];

        $departments = array_merge($additionalOptions, $departments);
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
        $requestTypes = RequestType::orderBy('created_at', 'asc')->get();
        $departments = Department::where('isActive', 1)->get();
        $user_info = User::with('approver')->find(auth()->user()->id);
        $approver = $user_info->approver ?? $user_info->department->departmentApprover;

        $departments->prepend(
            (new Department())->forceFill([
                "id" => -1,
                "name" => "Immediate Head",
                "shortcut" => "Immediate Head",
                "created_by" => null,
                "isActive" => 1,
                "created_at" => null,
                "updated_at" => null,
            ])
        );
        
        $departments->prepend(
            (new Department())->forceFill([
                "id" => -2,
                "name" => "Requestor",
                "shortcut" => "Requestor",
                "created_by" => null,
                "isActive" => 1,
                "created_at" => null,
                "updated_at" => null,
            ])
        );
        // dd($departments);

        return view('requisition.requisition-form', compact('requestTypes', 'departments', 'approver'));
    }

    public function savePRF(StorePRFRequest $request)
    {
        // dd($request->all());
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
            // 'assign_employee',
        ]);

        // dd($prfData);

        DB::transaction(function () use ($request, $prfData) {

            // $prfData['next_department'] = (int) $prfData['next_department'];
            $PRF = PurchaseRequisitionForm::create($prfData);
            // dd($prfData);
        // dd('here');

            $uploadedFileIds = [];
            foreach ($request->file('upload_pdf') as $file) {
                $uploadedFile = $this->uploadFile($file);
                $uploadedFileIds[] = $uploadedFile->id;
            }

            // dd($currentWorkflowStepId);

            $PRF->files()->attach($uploadedFileIds);

            // Insertion of requisiton list should always insert a double row for the tracker table
            // First step of the process flow will always be the requestor
            RequisitionWorkflowTracker::create([
                'requisition_id' => $PRF->id,
                'department_id' => 0,
                'employee_id' => $PRF->request_by,
                'submitted_at' => now(),
            ]);

            // If the passed "next_department" is negative then it means it's the requestor's department
            $next_department = $PRF->next_department < 0 ? $PRF->department : $PRF->next_department;
            RequisitionWorkflowTracker::create([
                'requisition_id' => $PRF->id,
                'department_id' => $next_department,
                // 'employee_id' => $PRF->assign_employee,
            ]);
        });

        return redirect()->route('requisition.history');
    }

    public function approveOrReject(Request $request)
    {
        // dd('test', $request->all());
        $request->validate([
            'id' => 'required',
            'status' => 'required',
        ]);

        switch ($request->status) {
            case 'Approved':
                $status = 1;
                break;
            case 'Reject':
                $status = 2;
                break;
            default:
                $status = 0;
                break;
        }

        $empId = null;

        $requisition = PurchaseRequisitionForm::find($request->id);
        // $PRFWorkflow = $requisition->workflowSteps()->orderBy('id', 'asc')->get();
        $requestType = RequestType::find($requisition->request_type);
        if (($requestType->slug == "laptop_plan" || strtolower($request->name) == "laptop plan") && $status == 1) {
            $empId = 34; // Ashvee
        }

        $skip = $status == 2 ? 1 : 0;
        $latestTracker = $requisition->tracker()
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->skip($skip)
            ->first();

        if ($latestTracker) {
            $latestTracker->submitted_at = now();
            $latestTracker->save();
        }

        $nextDepartment = $request->nextDepartment < 0 ? 0 : $request->nextDepartment;

        if ($nextDepartment == 0) {
            $user = User::find($latestTracker->employee_id);
            $empId = $user->approver_id ?? $user->department->departmentApprover->id;
            // dd($user, $user->department->departmentApprover);
            // dd($empId);
        }
        
        $requisition->remarks = null;
        $requisition->status = $status;
        $requisition->next_department = $nextDepartment;
        $requisition->assign_employee = $empId;
        $requisition->save();

        $currentWorkflowStepId = $request->workflow_step_id;
            
        if ($skip) {
            $this->deleteFiles($currentWorkflowStepId);
        } else {
            RequisitionWorkflowTracker::create([
                'requisition_id' => $requisition->id,
                'department_id' => $nextDepartment,
                'employee_id' => $empId,
            ]);
        }

        return json_encode([
            'status' => 'success',
            'data' => $requisition
        ]);
    }

    public function getPDFFiles(Request $request)
    {
        $files = UploadedFile::where('request_type_id', $request->request_id)->get();

        return json_encode([
            'status' => 'success',
            'files' => $files
        ]);
    }

    public function deleteRequestType(Request $request)
    {
        $request->validate([
            'type_id' => 'required'
        ]);

        $id = $request->type_id;
        $type = RequestType::find($id);
        UploadedFile::where('request_type_id', $id)->get()
            ->each(function ($file) {
                Storage::disk('public')->delete($file->path);
            });
        UploadedFile::where('request_type_id', $id)->delete();
        $workFlow = PRWorkFlowSteps::where('type_id', $id)->delete();
        
        if ($type) {
            $type->delete();
        }

        return json_encode([
            'status' => 'success',
            'message' => 'Request Type successfully deleted!'
        ]);
    }

    public function otherPRFDetails(Request $request)
    {
        $PRFWorkflow = optional(
            PRWorkFlowSteps::where('type_id', $request->request_id)->orderBy('id', 'asc')->skip(1)->first()
        )->toArray();
        
        $completeFlow = PRWorkFlowSteps::with('department')->where('type_id', $request->request_id)->orderBy('id', 'asc')->get()
            ->map(function ($step) {
                if ($step->department) {
                    return $step->department->name;
                }

                if ($step->ordering == -2) {
                    return 'Requestor';
                } else if ($step->ordering == -1) {
                    return 'Immediate Head';
                }

                return null;
            });
        // dd($completeFlow);
        // $completeFlow = $completeFlow->pluck('department.name');
        
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

    public function showPRFList(Request $request)
    {
        $prfData = PurchaseRequisitionForm::with([
            'requestType',
            'requestBy.approver',
            'positionName',
            'departmentName',
            'assignedEmployee',
            'tracker' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(1);
            }
        ])->orderBy('created_at','desc');

        if (auth()->user()->role_id != 1) {
            $userId = auth()->user()->id;
            $depId = auth()->user()->department_id;
            $prfData->where(function ($query) use ($userId, $depId, $request) {
                // dd($request->forms_by);

                if ($request->forms_by == "0") {
                    $query->where('request_by', $userId);
                } else {
                    $query->where(function ($q) use ($userId, $depId) {
                        $q->where('assign_employee', $userId) // logged user must be the assigned ee
                        ->orWhere(function ($q2) use ($depId) { // or logged user's department is the assigned department but no assigned ee & status is rejected
                            $q2->where('next_department', $depId)
                                ->whereNull('assign_employee')
                                ->where('status', '!=', 2);
                        })
                        ->orWhereHas('requestBy', function ($q3) use ($userId) { // or logged user is the approver of current requisition
                            $q3->where('approver_id', $userId);
                        })
                        ->orWhereHas('departmentApprover', function ($q4) use ($userId) { // or logged user is the approver of requestor's department
                            $q4->where('approver', $userId);
                        })
                        ->orWhereHas('approverByDepartment', function ($q5) use ($userId) { // or logged user is the approver of assigned department
                            $q5->where('approver', $userId);
                        });
                    });
                }
            });
        }

        // dd($prfData->toSql());

        // Column Filters
        if ($request->status != "") {
            $prfData->where('status', $request->status);
        }

        if ($request->date_requested) {
            // $prfData->whereDate('date_request', $request->date_requested);
            $prfData->whereBetween('date_request', [$request->date_requested, Carbon::today()]);
        } 
        // else {
        //     $prfData->whereBetween('date_request', [Carbon::now()->startOfMonth(), Carbon::today()]);
        // }

         

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
                    3 => 'In Progress',
                    4 => 'Executed',
                    5 => 'Completed',
                ];
                return $status[$row->status];
            })
            ->editColumn('request_by', function ($row) {
                return $row->requestBy->name;
            })
            ->editColumn('requestor_id', function ($row) {
                return $row->requestBy->id;
            })
            ->editColumn('approver_id', function ($row) {
                return $row->requestBy->approver_id ?? null;
            })
            ->editColumn('position', function ($row) {
                return $row->positionName->name;
            })
            ->editColumn('department', function ($row) {
                return $row->departmentName->name;
            })
            ->editColumn('assign_employee', function ($row) {
                $isImmediateHead = $row->approverByDepartment?->approver == auth()->user()->id;
                $sameDepartment = $row->next_department == auth()->user()->department_id;

                $assignContent = $row->assignedEmployee?->name ?? '---';
                if ($row->status === 0 && $row->next_department === 0 && $assignContent === '---') {
                    // Assigned Employee is Immediate Head if the status is pending, no assigned department & employee
                    $assignContent = 'Immediate Head';
                } else if ($assignContent === '---' && ($isImmediateHead || $sameDepartment) && $row->status !== 2) {
                    // Assigned Employee becomes a button if there's no assigned employee, the logged user is the approver of next department or a member of the next department
                    $assignContent = '<a href="javascript:void(0);" class="btn btn-sm btn-light '. ($isImmediateHead ? 'btn-assignto' : 'btn-assign') . '" data-requisition-id="' . $row->id . '">Assign to '. ($isImmediateHead ? '' : 'Me') . '</a>';
                }

                return $assignContent;
            })
            ->addColumn('actions', function ($row) {
                return '<a href="'.route('requisition.edit', $row->id).'" class="btn btn-sm btn-info">View</a>';
            })
            ->rawColumns(['assign_employee', 'actions'])
            ->make(true);
    }

    public function getEmployeeByDepartment(Request $request)
    {
        $users = User::where('department_id', $request->department_id)->where('id', '!=', $request->requestor_id)->get();

        // dd($request->requisition_id);
        $requisition = PurchaseRequisitionForm::find($request->requisition_id);
        $latestTracker = $requisition->tracker()->latest()->first();
        
        return json_encode([
            'status' => 'success',
            'data' => $users,
            'rejector' => $latestTracker?->employee_id
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
        $requestTypeFlow = PRWorkFlowSteps::where('type_id', $request->request_id)->orderBy('id', 'asc')->get();

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
        $this->authorize('edit', $requisition);

        $requisition->load(['requestType', 'attachmentsByPRF', 'workflowSteps']);
        
        $requestTypes = RequestType::orderBy('id', 'asc')->get();
        $departments = Department::where('isActive', 1)->get();

        // $attachments = $requisition->attachmentsByPRF()->where('uploaded_by', $requisition->request_by)->get();
        $files = $requisition->files()
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();

        // dd($files);

        [$grouped, $currentGroup] = $this->groupAttachments($files);

        if (!empty($currentGroup)) {
            $grouped[] = $currentGroup;
            $attachments = $grouped[0];
        }

        // dd($attachments);
        // dd($attachments->toArray());
        $PRFWorkflow = $requisition->workflowSteps()->orderBy('id', 'asc')->get();
        $tracker = $requisition->tracker()->orderBy('id', 'asc')->get()->toArray();

        $user_info = User::with('approver')->find($requisition->request_by);
        // dd($user_info->department->departmentApprover);
        $approver = $user_info->approver ?? $user_info->department->departmentApprover;
        // dd($requisition->toArray());
        // $second_step = 

        return view('requisition.requisition-edit', 
            compact('requisition',
                'requestTypes',
                'departments',
                'attachments',
                'PRFWorkflow',
                'tracker',
                'approver'
            ));
    }

    public function groupAttachments($files)
    {
        $grouped = [];
        $currentGroup = [];
        $previousUploader = null;

        foreach ($files as $index => $file) {
            if ($file['uploaded_by'] !== $previousUploader) {
                if (!empty($currentGroup)) {
                    $grouped[] = $currentGroup;
                }
                $currentGroup = [$file];
                $previousUploader = $file['uploaded_by'];
                
            } else {
                $currentGroup[] = $file;
            }

            // if ($index == 3) {
            //     dd($file, $file['uploaded_by'] !== $previousUploader, !empty($currentGroup), $currentGroup, $previousUploader, $grouped);
            // }
        }

        // dd($grouped, $currentGroup);

        return [$grouped, $currentGroup];
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
        // dd($request->all());
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
    
            $requisition->remarks = null;
            $requisition->status = 4;
            $requisition->next_department = $requisition->department;
            $requisition->assign_employee = $requisition->request_by;
            $requisition->save();
            
            $uploadedFileIds = collect($request->file('upload_pdf'))
                ->map(function ($file) {
                    return $this->uploadFile($file)->id;
                })
                ->toArray();
            
            $requisition->files()->attach($uploadedFileIds);
        } else if ($request->exists('status')) {
            // For Reject Status
            $remarks = $request->input('remarks', null);

            $latestTracker = null;
            $next_department = null;
            $assign_employee = null;
            // checks if requisition status is Pending/Approved
            if($requisition->toArray()['id'] == $request->requisition_id && in_array($requisition->status, [0, 1])) {
                // assign back to requestor's department
                $next_department = $requisition->toArray()['department'];
            } else {
                // get 2nd latestTracker
                $latestTracker = $requisition->tracker()
                    ->orderBy('created_at', 'desc')
                    ->orderBy('id', 'desc')
                    ->skip(1)
                    ->first();

                $next_department = $latestTracker->department_id ?? null;
                $assign_employee = $latestTracker->employee_id ?? null;
            }
            
            $requisition->next_department = $next_department;
            $requisition->assign_employee = $assign_employee;
            $requisition->remarks = $remarks;
            $requisition->status = 2; // reject
            $requisition->save();
        } else {
            // Approve
            $request->validate([
                // 'assign_employee' => 'required',
                'department_id' => 'required',
                'workflow_step_id' => 'required',
                'upload_pdf' => 'required|array',
                'upload_pdf.*' => 'file|mimes:pdf',
            ]);

            $skip = $requisition->status == 2 ? 1 : 0;

            $latestTracker = $requisition->tracker()
                ->latest()
                ->skip($skip)
                ->first();
    
            if ($latestTracker) {
                $latestTracker->submitted_at = now();
                $latestTracker->save();
            }

            $assign_employee = null;
            $requestType = RequestType::find($requisition->request_type);
            if (($requestType->slug == "laptop_plan" || strtolower($request->name) == "laptop plan") && $requisition->next_department === 2) { // checks if the currently assigned department is from HR
                $assign_employee = 45;  // Jojel Bautista
            }
                
            $requisition->remarks = null;
            $requisition->status = 3;
            $requisition->next_department = $request->department_id;
            $requisition->assign_employee = $assign_employee;
            $requisition->save();
            
            $currentWorkflowStepId = $request->workflow_step_id;
            
            if ($skip) {
                $this->deleteFiles($currentWorkflowStepId);
            } else {
                RequisitionWorkflowTracker::create([
                    'requisition_id' => $requisition->id,
                    'department_id' => $request->department_id,
                    'employee_id' => $assign_employee,
                ]);
            }

            $uploadedFileIds = collect($request->file('upload_pdf'))
                ->map(function ($file) {
                    return $this->uploadFile($file)->id;
                })
                ->toArray();
            
            // $requisition->files()->attach($uploadedFileIds);
            $requisition->files()->attach(
                collect($uploadedFileIds)->mapWithKeys(function ($fileId) use ($currentWorkflowStepId) {
                    return [$fileId => ['workflow_step_id' => $currentWorkflowStepId]];
                })->toArray() 
            );
        }
        
        return redirect()->route("requisition.history");
    }

    public function deleteFiles($workflowStepId)
    {
        $uploadedFileIds = RequestFile::where('workflow_step_id', $workflowStepId)->pluck('file_id');

        $uploadedFiles = UploadedFile::whereIn('id', $uploadedFileIds)->get();

        foreach ($uploadedFiles as $file) {
            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }
        }

        UploadedFile::whereIn('id', $uploadedFileIds)->delete();
        RequestFile::where('workflow_step_id', $workflowStepId)->delete();
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required',
        ]);

        $requisition = PurchaseRequisitionForm::find($request->id);
        $requisition->status = $request->status;
        $requisition->save();

        return json_encode([
            'status' => 'success',
            'message' => 'Requisition Completed!',
            'data' => $requisition,
        ]);
    }

    public function getRequestStatus(Request $request)
    {
        $requisition = PurchaseRequisitionForm::with('tracker.department')->find($request->id);

        // dd($requisition->toArray(), $requisition->departmentName->toArray());
        $requestorDepartment = [$requisition->departmentName->shortcut ?? 'N/A'];

        $workflowDepartments = $requisition->tracker->sortBy('id')->map(function($step) {
            return $step->department->shortcut ?? 'N/A';
        })->toArray();
        // dd($workflowDepartments);

        // $departments = array_merge($requestorDepartment, $workflowDepartments);
        $departments = $workflowDepartments;
        $departments[0] = $requestorDepartment; // First Index of step flow will always be the requestor's

        $files = $requisition->files()->orderBy('created_at','asc')->get()->toArray();
        // dd($files);
        
        [$grouped, $currentGroup] = $this->groupAttachments($files);

        if (!empty($currentGroup)) {
            $grouped[] = $currentGroup;
        }

        // dd($grouped);

        $return = [            
            'status' => 'success',
            'data' => [
                'departments' => $departments,
                'files' => $grouped,
            ]
        ];

        return json_encode($return);
    }

    public function assignEmployee(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'prf_id' => 'required',
        ]);

        $prf = PurchaseRequisitionForm::find($request->prf_id);

        if (!$prf->assign_employee) {
            $prf->assign_employee = $request->user_id;
            $prf->save();

            $prfFlowTracker = RequisitionWorkflowTracker::where('requisition_id', $request->prf_id)
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->first();
            $prfFlowTracker->employee_id = $request->user_id;
            $prfFlowTracker->save();
    
            return json_encode([
                'status' => 'success',
                'message' => 'Assigned Successfully!',
            ]);
        } else {
            return json_encode([
                'status' => 'warning',
                'message' => 'PRF already assigned!',
            ]);
        }

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
