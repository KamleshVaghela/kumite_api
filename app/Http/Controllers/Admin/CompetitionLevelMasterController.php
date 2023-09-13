<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompetitionLevelMaster;
use Auth;
use Carbon\Carbon; 

class CompetitionLevelMasterController extends Controller
{
    public function index()
    {
        return CompetitionLevelMaster::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'level' => 'required|unique:competition_level_masters',
        ]);

        $dataObj = new CompetitionLevelMaster();
        $dataObj->level = $request->level;
        
        $dataObj->user_id = Auth::user()->id;
        $dataObj->last_modified = Carbon::now();
        $dataObj->last_modified_user_id = Auth::user()->id;
        $dataObj->save();

        return response([
            'data' => $dataObj,
            'message' => 'Competition Level inserted successfully',
            'success' => 'true'
        ], 200);
    }

    public function show($id)
    {
        return CompetitionLevelMaster::find($id);
    }

    public function update(Request $request, $id)
    {
        $dataObj = CompetitionLevelMaster::find($id);
        if ($dataObj) {
            $validatedData = $request->validate([
                'level' => 'required|unique:competition_level_masters,level,'.$dataObj->id,
            ]);

            $dataObj->level = $request->level;
            $dataObj->last_modified = Carbon::now();
            $dataObj->last_modified_user_id = Auth::user()->id;
            $dataObj->save();

            return response([
                'data' => $dataObj,
                'message' => 'Competition Level updated successfully',
                'success' => 'true'
            ], 200);
        }
        else {
            return response([
                'message' => 'Competition Level not found',
                'success' => 'false'
            ], 404);
        }
    }

    public function destroy($id)
    {
        $dataObj = CompetitionLevelMaster::find($id);
        if ($dataObj) {
            $dataObj->delete();

            return response([
                'message' => 'Competition Level Deleted Successfully',
                'success' => 'true'
            ], 200);
        }
        else {
            return response([
                'message' => 'Competition Level not found',
                'success' => 'false'
            ], 404);
        }
    }
}
