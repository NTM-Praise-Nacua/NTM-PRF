<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Position;
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
        return view("users.list", compact('departments', 'positions'));
    }

    public function getUsersData()
    {
        $users = User::with (['creator', 'position']);
        return DataTables::of(User::query())
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('M d, Y') : '';
            })
            ->addColumn('actions', function ($row) {
                return '<a href="/users/'.$row->id.'/edit" class="btn btn-sm btn-primary">Edit</a>';
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
            ->rawColumns(['actions'])
            ->make(true);
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
        ]);

        $firstName = $request->first_name;
        $lastName = $request->last_name;
        $fullName = $firstName . " " . $lastName;
        $contact = $request->contact;

        $position = $request->position;
        $department = $request->department;
        
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
