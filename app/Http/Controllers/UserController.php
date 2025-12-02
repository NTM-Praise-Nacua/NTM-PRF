<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::where('isActive', 1)->get();
        $positions = Position::all();
        $roles = Role::where('is_active', 1)->get();
        $approvers = collect();
        return view("users.list", compact('departments', 'positions','roles', 'approvers'));
    }

    public function getUsersData()
    {
        $users = User::with (['creator', 'position', 'department']);
        return DataTables::of($users)
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('M d, Y') : '';
            })
            ->addColumn('actions', function ($row) {
                return '<a href="javascript:void(0);" data-id="'.$row->id.'" class="btn btn-sm btn-primary edit-btn">Edit</a>';
            })
            ->editColumn('created_by', function ($row) {
                return $row->creator->name;
            })
            ->editColumn('position_id', function ($row) {
                return $row->position->name ?? '---';
            })
            ->editColumn('department_id', function ($row) {
                return $row->department->name ?? '---';
            })
            ->editColumn('role_id', function ($row) {
                return $row->role->name ?? '---';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getUserInfo(Request $request)
    {
        $user = User::find($request->id);
        $departmentTeam = $this->getUsersByDepartment($user->department_id);

        return json_encode([
            'status' => 'success',
            'data' => $user,
            'departmentTeam' => $departmentTeam
        ]);
    }

    public function getUsersByDepartment($id)
    {
        $users = User::where('department_id', $id)
            ->get();

        return $users;
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'department' => 'required',
            'position' => 'required',
            'role' => 'required',
            'approver' => 'required',
        ]);

        $firstName = $request->first_name;
        $lastName = $request->last_name;
        $fullName = $firstName . " " . $lastName;
        $contact = $request->contact;

        $position = $request->position;
        $department = $request->department;
        $role = $request->role;
        $approver = $request->approver;
        
        $email = $request->email;
        $password = Hash::make($request->password);

        $user = User::create([
            'name' => $fullName,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => $password,
            'created_by' => auth()->user()->id,
            'weak_password' => $request->password,
            'contact_no' => $contact,
            'department_id' => $department,
            'role_id' => $role,
            'approver_id' => $approver,
            'position_id' => $position,
        ]);

        return redirect()->route('user.list');
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
        $user = User::find($id);
        
        return view('users.edit', compact('user'));
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
            'userId' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'contact' => 'required',
            'position' => 'required',
            'department' => 'required',
            'role' => 'required',
            'approver' => 'required',
            'oldpassword' => 'nullable|string',
            'newpassword' => 'nullable|string',
        ]);

        $user = User::find((int)$request->userId);

        if ($request->filled('oldpassword') && $request->filled('newpassword')) {
            if (!Hash::check($request->oldpassword, $user->password)) {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Old Password Incorrect!'
                ]);
            } else {
                $user->password = Hash::make($request->newpassword);
                $user->weak_password = $request->newpassword;
            }
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->name = $request->first_name . " " . $request->last_name;
        $user->email = $request->email;
        $user->contact_no = $request->contact;
        $user->position_id = $request->position;
        $user->department_id = $request->department;
        $user->role_id = $request->role;
        $user->approver_id = $request->approver;
        $user->save();

        return json_encode([
            'status' => 'success',
            'message' => 'User Details Updated!',
            'data' => $user
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
