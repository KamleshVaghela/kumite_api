<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompetitionLevelDetail;
use Auth;
use Carbon\Carbon; 

class CompetitionLevelDetailController extends Controller
{
    public function index()
    {
        return CompetitionLevelDetail::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|unique:competition_type_masters',
        ]);

        $dataObj = new CompetitionLevelDetail();
        $dataObj->type = $request->type;
        
        $dataObj->user_id = Auth::user()->id;
        $dataObj->last_modified = Carbon::now();
        $dataObj->last_modified_user_id = Auth::user()->id;
        $dataObj->save();

        return response([
            'data' => $dataObj,
            'message' => 'Competition Type inserted successfully',
            'success' => 'true'
        ], 200);
    }

    public function show($id)
    {
        return CompetitionLevelDetail::find($id);
    }

    public function update(Request $request, $id)
    {
        $dataObj = CompetitionLevelDetail::find($id);
        if ($dataObj) {
            $validatedData = $request->validate([
                'type' => 'required|unique:competition_type_masters,type,'.$dataObj->id,
            ]);

            $dataObj->type = $request->type;
            $dataObj->last_modified = Carbon::now();
            $dataObj->last_modified_user_id = Auth::user()->id;
            $dataObj->save();

            return response([
                'data' => $dataObj,
                'message' => 'Competition Type updated successfully',
                'success' => 'true'
            ], 200);
        }
        else {
            return response([
                'message' => 'Competition Type not found',
                'success' => 'false'
            ], 404);
        }
    }

    public function destroy($id)
    {
        $dataObj = CompetitionLevelDetail::find($id);
        if ($dataObj) {
            $dataObj->delete();

            return response([
                'message' => 'Competition Type Deleted Successfully',
                'success' => 'true'
            ], 200);
        }
        else {
            return response([
                'message' => 'Competition Type not found',
                'success' => 'false'
            ], 404);
        }
    }
}
