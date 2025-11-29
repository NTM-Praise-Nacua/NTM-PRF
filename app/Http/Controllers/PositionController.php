<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.position');
    }

    public function getPositionData()
    {
        // eager load
        $positions = Position::with(['creator']);

        return DataTables::of($positions)
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
            'name' => 'required',
        ]);

        $result = Position::create([
            'name' => $request->name, 
            'created_by' => auth()->user()->id,
        ]);

        return redirect()->route('position.list');
    }

    public function getPositionInfo(Request $request)
    {
        $position = Position::find($request->id);

        return json_encode([
            'status' => 'success',
            'data' => $position
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function show(Position $position)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function edit(Position $position)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'positionId' => 'required',
            'name' => 'required'
        ]);

        $position = Position::find($request->positionId);

        $position->name = $request->name;
        $position->save();

        return json_encode([
            'status' => 'success',
            'message' => 'Position Details Updated!',
            'data' => $position
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function destroy(Position $position)
    {
        //
    }
}
