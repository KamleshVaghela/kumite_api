<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SchoolMaster;

class SchoolMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sql="
        SELECT DISTINCT D.GEOID, D.DISTRICT  
        FROM DISTRICT_MST M
        INNER JOIN DISTRICT_GEO D ON M.GEOID=D.GEOID
        ORDER BY D.DISTRICT ";

        $districts = DB::connection('rksys_app')->select($sql);

        return View('admin.school_master.index',compact('districts'));
    }

    public function report(Request $request)
    {
        $schools = DB::connection('rksys_app')->table("school_masters")
        ->leftJoin("DISTRICT_GEO", function($join){
            $join->on("school_masters.geo_id", "=", "DISTRICT_GEO.GEOID");
        })
        ->when($request->district, function ($query, $district) {
            if($district != "*") {
                $query->where('school_masters.geo_id', $district);
            }
        })
        ->select("school_masters.*", "DISTRICT_GEO.DISTRICT")
        ->orderBy("DISTRICT_GEO.DISTRICT","asc")
        ->orderBy("school_masters.name","asc")
        ->get();

        return View('admin.school_master.report',compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sql="
        SELECT DISTINCT D.GEOID, D.DISTRICT  
        FROM DISTRICT_MST M
        INNER JOIN DISTRICT_GEO D ON M.GEOID=D.GEOID
        ORDER BY D.DISTRICT ";

        $districts = DB::connection('rksys_app')->select($sql);

        return View('admin.school_master.create',compact('districts'));
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
        $dataObj = new SchoolMaster();
        $dataObj->geo_id = $request->geo_id;
        $dataObj->name = $request->name;
        $dataObj->save();

        return response([
            'data' => $dataObj,
            'message' => 'School Inserted successfully',
            'alert-type' => 'success'
        ], 200);
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
