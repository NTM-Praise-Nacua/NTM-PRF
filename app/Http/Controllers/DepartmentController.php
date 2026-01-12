<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\PurchaseRequisitionForm;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('name', 'ASC')->get();
        return view('users.department', compact('users'));
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

    public function getDepartmentsData()
    {
        // eager load
        $department = Department::with(['creator'])
            ->leftJoin('users', 'users.id', '=', 'departments.approver')
            ->select('departments.*', 'users.name as approver_name');

        return DataTables::of($department)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('M d, Y') : '';
            })
            ->addColumn('actions', function ($row) {
                return '<a href="javascript:void(0);" data-id="'.$row->id.'" class="btn btn-sm btn-primary edit-btn">Edit</a>';
            })
            ->editColumn('created_by', function ($row) {
                return $row->creator->name;
            })
            ->editColumn('approver_name', function ($row) {
                return $row->approver_name ?? '---';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getDepartmentInfo(Request $request)
    {
        $department = Department::find($request->id);

        return json_encode([
            'status' => 'success',
            'data' => $department
        ]);
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
            'name' => 'required',
            'shortcut' => 'required',
            'approver' => 'required',
        ]);

        Department::create([
            'name' => $request->name, 
            'shortcut' => $request->shortcut,
            'created_by' => auth()->user()->id,
            'approver' => $request->approver,
        ]);

        return redirect()->route('department.list');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'departmentId' => 'required',
            'name' => 'required',
            'shortcut' => 'required',
            'approver' => 'required',
        ]);

        $department = Department::find($request->departmentId);

        $department->name = $request->name;
        $department->shortcut = $request->shortcut;
        $department->approver = $request->approver;
        $department->save();

        return json_encode([
            'status' => 'success',
            'message' => 'Department Details Updated!',
            'data' => $department
        ]);
    }

    public function getMembers(Request $request)
    {
        $request->validate([
            'requisition_id' => 'required'
        ]);
        $requisition = PurchaseRequisitionForm::find($request->requisition_id);
        $users = User::where('department_id', $requisition->next_department)->get()->toArray();

        return json_encode([
            'users' => $users
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
