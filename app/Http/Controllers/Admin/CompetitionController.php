<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Competition;
use App\Models\CompetitionModel;
use App\Models\CompetitionPartModel;
use App\Models\Participant;
use App\Models\KarateKaModel;
use App\Models\DefaultCategoryMaster;
use App\Models\BoutParticipantDetail;
use App\Models\Bout;
use Auth;
use Carbon\Carbon; 
use Illuminate\Support\Facades\DB;
use Config;

class CompetitionController extends Controller
{
    public function index()
    {
        return View('admin.competition.index');
    }

    public function report(Request $request)
    {
        $from = date($request->from_date.' 00:00:00');
        $to = date($request->to_date.' 23:59:59');
        $sql = "
        SELECT COMP_TYPE,COMP_ID,COMP_NAME,FEES,TYPE,STATE,DATE_FORMAT(COMP_DATE, '%d-%m-%Y') as COMP_DATE,COMP_END_DATE,CLOSE_DATE_C,CLOSE_DATE_K,DISTRICT,TOTAL,NON_REG ,
		KARATE_KA_DISPLAY, COACH_FEES
		FROM
		(
            SELECT COMP_TYPE,COMP_ID,COMP_NAME,FEES,TYPE,STATE,COMP_DATE,COMP_END_DATE,CLOSE_DATE_C,CLOSE_DATE_K,DISTRICT,COUNT(KARATE_KA_ID) AS TOTAL,SUM(NON_REG) AS NON_REG ,
            Y.KARATE_KA_DISPLAY, Y.COACH_FEES
            FROM (
                SELECT P.KARATE_KA_ID,X.COMP_TYPE, X.COMP_ID,COMP_NAME,FEES,TYPE,STATE,COMP_DATE,COMP_END_DATE,CLOSE_DATE_C,CLOSE_DATE_K,DISTRICT,CASE WHEN P.KARATE_KA_ID IS NOT NULL AND P.WEIGHT IS NULL THEN 1 ELSE 0 END AS NON_REG,
                X.KARATE_KA_DISPLAY, X.COACH_FEES
                FROM (
                    SELECT CT.COMP_TYPE,C.COMP_ID, C.COMP_NAME,C.FEES,C.TYPE, C.STATE, COMP_DATE, DATE_FORMAT(C.COMP_END_DATE, '%d-%m-%Y') as COMP_END_DATE, DATE_FORMAT(C.CLOSE_DATE_C, '%d-%m-%Y') as CLOSE_DATE_C, DATE_FORMAT(C.CLOSE_DATE_K, '%d-%m-%Y') as CLOSE_DATE_K ,G.DISTRICT,
                    C.KARATE_KA_DISPLAY, C.COACH_FEES
                    FROM COMPETITION C
                    LEFT JOIN (
                        SELECT C.COMP_ID,GROUP_CONCAT(T.TYPE) AS COMP_TYPE  
                        FROM COMPETITION_TYPE C 
                        INNER JOIN COMP_TYPE T ON T.CT_ID = C.CT_ID 
                        GROUP BY C.COMP_ID
                    ) CT ON CT.COMP_ID = C.COMP_ID
                    LEFT JOIN DISTRICT_GEO G ON C.GEOID = G.GEOID
                    ORDER BY C.COMP_DATE
                ) X
                LEFT JOIN PART_COMPETITION P ON X.COMP_ID = P.COMP_ID
            ) Y 
            GROUP BY COMP_ID,COMP_NAME,TYPE,STATE,COMP_DATE,CLOSE_DATE_C,CLOSE_DATE_K,DISTRICT
		) K
		ORDER BY COMP_ID DESC";
        $competition = DB::connection('rksys_app')->select($sql);

        return View('admin.competition.report',compact('competition'));
    }

    public function create()
    {
        $sql = "SELECT CT_ID,TYPE FROM COMP_TYPE WHERE STATUS='Active'";
        $competition_type = DB::connection('rksys_app')->select($sql);
        return View('admin.competition.create',compact('competition_type'));
    }
    public function changeLevel($level)
    {
        if ($level == "IDJ") {
            $coachSql = "SELECT COACH_ID,COACH_NAME FROM COACH ORDER BY COACH_NAME";
            $coaches = DB::connection('rksys_app')->select($coachSql);
            return View('admin.competition.change_level',compact('level'))->with('coaches',$coaches);
        }
        else if ($level == "ISC") {

            $districtSql= " SELECT DISTINCT D.GEOID,B.DISTRICT, B.STATE FROM DISTRICT_MST D
                    INNER JOIN DISTRICT_GEO B ON D.GEOID=B.GEOID
		            ORDER BY B.DISTRICT ";
            // $stateSql= " SELECT DISTINCT B.STATE FROM DISTRICT_MST D
            //     INNER JOIN DISTRICT_GEO B ON D.GEOID=B.GEOID
            //     ORDER BY STATE ";
            // $states = DB::connection('rksys_app')->select($stateSql);
            // return View('admin.competition.change_level',compact('level'))->with('states',$states);

            $districts = DB::connection('rksys_app')->select($districtSql);
            return View('admin.competition.change_level',compact('level'))->with('districts',$districts);

        }
        else if ($level == "IDS") {

        }
        else if ($level == "D") {

        }
        else if ($level == "IST") {

        }
        else if ($level == "S") {

        }
        else if ($level == "N") {

        }
        else if ($level == "I") {

        }
        return View('admin.competition.change_level',compact('level'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $compModelObj = new CompetitionModel();
        $compModelObj->COMP_NAME = $request->name;
        $compModelObj->save();

        $compModel  = new Competition();
        $compModel->comp_id = $compModelObj->COMP_ID;
        $compModel->name = $request->name;
        $compModel->user_id = Auth::user()->id;
        $compModel->last_modified = \Carbon\Carbon::now();
        $compModel->last_modified_user_id = Auth::user()->id;
        $compModel->save();

        return response([
            'data' => $compModelObj,
            'message' => 'Competition inserted successfully',
            'alert-type' => 'success'
        ], 200);
    }

    function copyData($competition) {
        $compModel  = new Competition();
        $compModel->comp_id = $competition->COMP_ID;
        $compModel->name = $competition->COMP_NAME;
        $compModel->short_description = $competition->KARATE_KA_DISPLAY;
        $compModel->additional_details = $competition->REMARKS;

        $compModel->fees = $competition->FEES;
        $compModel->kata_fees = $competition->FEES_KATA;
        $compModel->kumite_fees = $competition->FEES_KUMITE;
        $compModel->team_kata_fees = $competition->FEES_T_KATA;
        $compModel->team_kumite_fees = $competition->FEES_T_KUMITE;
        $compModel->coach_fees = $competition->COACH_FEES;

        // $table->string('level_id');

        $compModel->start_date = $competition->COMP_DATE;
        $compModel->end_date = $competition->COMP_END_DATE;
        $compModel->student_reg_end_date = $competition->CLOSE_DATE_C;
        $compModel->coach_end_date = $competition->CLOSE_DATE_K;

        // $table->string('type_id');
        
        $compModel->user_id = Auth::user()->id;
        $compModel->last_modified = \Carbon\Carbon::now();
        $compModel->last_modified_user_id = Auth::user()->id;
        
        $compModel->save();
    }

    function refreshParticipantDetails($competition_part, $competition, $compModel) {

        // $compModel = Competition::where('comp_id',$competition_part->COMP_ID)->first();

        $compParticipant = Participant::where('competition_id',$compModel->id)
        ->where('external_unique_id',$competition_part->PART_COMP_ID)
        ->first();

        if(is_null($compParticipant)) {
            // $karateKa = KarateKaModel::where('KARATE_KA_ID', $competition_part->KARATE_KA_ID)->first();
            // $competition = DB::connection('rksys_app')->select($sql);
            $karateKa = DB::connection('rksys_app')->table("KARATE_KA")
            ->leftJoin("RANK_MST", function($join) {
                $join->on("KARATE_KA.RANK_ID", "=", "RANK_MST.RANK_ID");
            })
            ->leftJoin("DOJO_MST", function($join) {
                $join->on("KARATE_KA.DOJO_ID", "=", "DOJO_MST.DOJO_ID");
            })
            ->leftJoin("SCHOOL_MASTER", function($join) {
                $join->on("KARATE_KA.SM_ID", "=", "SCHOOL_MASTER.SM_ID");
            })
            ->leftJoin("COACH", function($join) {
                $join->on("KARATE_KA.COACH_ID", "=", "COACH.COACH_ID");
            })
            ->where('KARATE_KA_ID', $competition_part->KARATE_KA_ID)
            ->select("KARATE_KA.*", "RANK_MST.RANK" ,"DOJO_MST.DOJO_NAME", "SCHOOL_MASTER.SCHOOL_NAME",
            "COACH.COACH_NAME", "COACH.COACH_CODE")
            ->first();    

            if($karateKa) {
                $compParticipant = new Participant();

                $compParticipant->competition_id = $compModel->id;
                $compParticipant->external_unique_id = $competition_part->PART_COMP_ID;
                
                if ($competition->TYPE == "ISC") {
                    //Inter School
                    $compParticipant->team = "$competition->TYPE-$karateKa->SCHOOL_NAME ($karateKa->SM_ID)";
                } else if ($competition->TYPE == "IDJ") {
                    //Inter Dojo
                    $compParticipant->team = "Dojo-$competition->TYPE-$karateKa->DOJO_NAME ($karateKa->DOJO_ID)";
                } else if ($competition->TYPE == "D") {
                    //District
                    $compParticipant->team = "Coach-$competition->TYPE-$karateKa->COACH_NAME ($karateKa->COACH_CODE)";
                } else if ($competition->TYPE == "S") {
                    //State
                    $compParticipant->team = "District-$competition->TYPE-$karateKa->DIS_ID";
                } else if ($competition->TYPE == "N") {
                    //National
                    $compParticipant->team = "District-$competition->TYPE-$karateKa->DIS_ID";
                }            
                // $compParticipant->team = 

                $compParticipant->full_name = $karateKa->NAME.' '.$karateKa->L_NAME;
                if($karateKa->TITLE =="Mr") {
                    $compParticipant->gender = "Male";
                } else {
                    $compParticipant->gender = "Female";
                }
                $compParticipant->rank = $karateKa->RANK;
                $compParticipant->rank_id = $karateKa->RANK_ID;
                $compParticipant->external_coach_code = $karateKa->COACH_ID;
                    
                
        
                $compParticipant->age = $competition_part->AGE;
                $compParticipant->weight = $competition_part->WEIGHT;

                $compParticipant->user_id = Auth::user()->id;
                $compParticipant->last_modified = \Carbon\Carbon::now();
                $compParticipant->last_modified_user_id = Auth::user()->id;

                $compParticipant->save();
            }
        }        
    }

    public function boardIndex($decrypted_comp_id)
    {
        $competition = CompetitionModel::where('COMP_ID',$decrypted_comp_id)->first();

        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();

        if(!isset($compModel)) {
            $this->copyData($competition);
        }

        return View('admin.competition.board.index')
        ->with('decrypted_comp_id',$decrypted_comp_id)
        ->with('competition',$competition);
    }

    public function boardReport($decrypted_comp_id)
    {
        $competition = CompetitionModel::where('COMP_ID',$decrypted_comp_id)->first();
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();

        $competition_parts = CompetitionPartModel::where('COMP_ID',$decrypted_comp_id)->get();
        $compParticipants = Participant::where('competition_id',$compModel->id)->get();

        if($compParticipants->count() == 0) {
            foreach($competition_parts as $data) {
                $this->refreshParticipantDetails($data, $competition, $compModel);
            }
            $compParticipants = Participant::where('competition_id',$compModel->id)->get();
        }
        // $bouts = Bout::where('competition_id',$compModel->id)->get();
        $bout_participant_details = BoutParticipantDetail::where('competition_id',$compModel->id)->get();
        $bouts = BoutParticipantDetail::where('competition_id',$compModel->id)
        ->select("bout_id", DB::raw('count(*) as participant_count'))
        ->groupBy('bout_id')
        ->get();

        return View('admin.competition.board.report')
        ->with('decrypted_comp_id',$decrypted_comp_id)
        ->with('competition',$competition)
        ->with('competition_parts',$competition_parts)
        ->with('compParticipants',$compParticipants)
        ->with('bouts',$bouts)
        ->with('bout_participant_details',$bout_participant_details);
    }
    
    public function competitionDetails($decrypted_comp_id, Request $request)
    {
        $details_key = $request->query('details_key');
        
        $competition = CompetitionModel::where('COMP_ID',$decrypted_comp_id)->first();

        return View('admin.competition.board.competition_details',compact('details_key'))
        ->with('decrypted_comp_id',$decrypted_comp_id)
        ->with('competition',$competition);
    }
    
    public function saveCompetitionDetails($decrypted_comp_id, Request $request)
    {
        $request->validate([
            'karate_ka_display' => 'required',
            'remarks' => 'required',
        ]);

        $competition = CompetitionModel::where('COMP_ID',$decrypted_comp_id)->first();
        $competition->KARATE_KA_DISPLAY = $request->karate_ka_display;
        $competition->REMARKS = $request->remarks;
        $competition->save();

        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();
        $compModel->short_description = $competition->KARATE_KA_DISPLAY;
        $compModel->additional_details = $competition->REMARKS;
        $compModel->last_modified = \Carbon\Carbon::now();
        $compModel->last_modified_user_id = Auth::user()->id;
        $compModel->save();

        return response([
            'data' => $competition,
            'message' => 'Competition updated successfully',
            'alert-type' => 'success'
        ], 200);
    }

    public function importantDates($decrypted_comp_id, Request $request)
    {
        $details_key = $request->query('details_key');
        $competition = CompetitionModel::where('COMP_ID',$decrypted_comp_id)->first();

        return View('admin.competition.board.important_dates',compact('details_key'))
        ->with('decrypted_comp_id',$decrypted_comp_id)
        ->with('competition',$competition);
    }
    
    public function saveImportantDates($decrypted_comp_id, Request $request)
    {
        $request->validate([
            'comp_start_date' => 'required|date',
            'comp_end_date' => 'required|date',
            'comp_coach_close_date' => 'required|date',
            'comp_karate_ka_close_date' => 'required|date',
        ]);

        $competition = CompetitionModel::where('COMP_ID',$decrypted_comp_id)->first();
        $competition->COMP_DATE = $request->comp_start_date;
        $competition->COMP_END_DATE = $request->comp_end_date;
        $competition->CLOSE_DATE_C = $request->comp_coach_close_date;
        $competition->CLOSE_DATE_K = $request->comp_karate_ka_close_date;
        $competition->save();

        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();
        $compModel->start_date = $competition->COMP_DATE;
        $compModel->end_date = $competition->COMP_END_DATE;
        $compModel->student_reg_end_date = $competition->CLOSE_DATE_C;
        $compModel->coach_end_date = $competition->CLOSE_DATE_K;
        $compModel->last_modified = \Carbon\Carbon::now();
        $compModel->last_modified_user_id = Auth::user()->id;
        $compModel->save();

        return response([
            'data' => $dataObj,
            'message' => 'Competition updated successfully',
            'alert-type' => 'success'
        ], 200);
    }

    public function feesDetails($decrypted_comp_id, Request $request)
    {
        $details_key = $request->query('details_key');
        $competition = CompetitionModel::where('COMP_ID',$decrypted_comp_id)->first();

        $competition->FEES = (empty($competition->FEES)) ? '0' : $competition->FEES;
        $competition->FEES_KATA = (empty($competition->FEES_KATA)) ? '0' : $competition->FEES_KATA;
        $competition->FEES_KUMITE = (empty($competition->FEES_KUMITE)) ? '0' : $competition->FEES_KUMITE;
        $competition->FEES_T_KATA = (empty($competition->FEES_T_KATA)) ? '0' : $competition->FEES_T_KATA;
        $competition->FEES_T_KUMITE = (empty($competition->FEES_T_KUMITE)) ? '0' : $competition->FEES_T_KUMITE;
        $competition->COACH_FEES = (empty($competition->COACH_FEES)) ? '0' : $competition->COACH_FEES;

        return View('admin.competition.board.fees_details',compact('details_key'))
        ->with('decrypted_comp_id',$decrypted_comp_id)
        ->with('competition',$competition);
    }
    
    public function saveFeesDetails($decrypted_comp_id, Request $request)
    {
        $request->validate([
            'fees' => 'required',
            'fees_kata' => 'required',
            'fees_kumite' => 'required',
            'fees_team_kata' => 'required',
            'fees_team_kumite' => 'required',
            'fees_coach' => 'required',
        ]);

        $competition = CompetitionModel::where('COMP_ID',$decrypted_comp_id)->first();
        $competition->FEES = $request->fees;
        $competition->FEES_KATA = $request->fees_kata;
        $competition->FEES_KUMITE = $request->fees_kumite;
        $competition->FEES_T_KATA = $request->fees_team_kata;
        $competition->FEES_T_KUMITE = $request->fees_team_kumite;
        $competition->COACH_FEES = $request->fees_coach;
        $competition->save();

        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();
        $compModel->fees = $competition->FEES;
        $compModel->kata_fees = $competition->FEES_KATA;
        $compModel->kumite_fees = $competition->FEES_KUMITE;
        $compModel->team_kata_fees = $competition->FEES_T_KATA;
        $compModel->team_kumite_fees = $competition->FEES_T_KUMITE;
        $compModel->coach_fees = $competition->COACH_FEES;
        $compModel->last_modified = \Carbon\Carbon::now();
        $compModel->last_modified_user_id = Auth::user()->id;
        $compModel->save();

        return response([
            'data' => $competition,
            'message' => 'Competition updated successfully',
            'alert-type' => 'success'
        ], 200);

    }

    public function levelDetails($decrypted_comp_id, Request $request)
    {
        $details_key = $request->query('details_key');
        
        $competition = CompetitionModel::where('COMP_ID',$decrypted_comp_id)->first();

        // $default_category = DefaultCategoryMaster::select('category_group')->distinct()->get();

        return View('admin.competition.board.level_details',compact('details_key'))
        ->with('decrypted_comp_id',$decrypted_comp_id)
        // ->with('default_category',$default_category)
        ->with('competition',$competition);
    }

    public function saveLevelDetails($decrypted_comp_id, Request $request)
    {
        return response([
            'data' => '',
            'message' => 'Data successfully',
            'alert-type' => 'success'
        ], 200);
    }

    

    public function clearData($decrypted_comp_id, Request $request)
    {
        $details_key = $request->query('details_key');

        return View('admin.competition.board.clear_data',compact('details_key'))
        ->with('decrypted_comp_id',$decrypted_comp_id);
    }

    public function saveClearData($decrypted_comp_id, Request $request)
    {
        // $competition = CompetitionModel::where('COMP_ID',$decrypted_comp_id)->first();
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();
        
        $boutParticipantDetails = BoutParticipantDetail::where('competition_id',$compModel->id)->delete();
        $bouts = Bout::where('competition_id',$compModel->id)->delete();
        $participants = Participant::where('competition_id',$compModel->id)->delete();
        
        return response([
            'data' => '',
            'message' => 'Data delete successfully',
            'alert-type' => 'success'
        ], 200);
    }

    public function resultDetails($decrypted_comp_id, Request $request)
    {
        
    }

    public function saveResultDetails($decrypted_comp_id, Request $request)
    {
        
    }

    public function boutDetails($decrypted_comp_id, Request $request)
    {
        $details_key = $request->query('details_key');
        
        $competition = CompetitionModel::where('COMP_ID',$decrypted_comp_id)->first();

        $default_category = DefaultCategoryMaster::select('category_group')->distinct()->get();

        return View('admin.competition.board.bout_details',compact('details_key'))
        ->with('decrypted_comp_id',$decrypted_comp_id)
        ->with('default_category',$default_category)
        ->with('competition',$competition);
    }
    public function saveBoutDetails($decrypted_comp_id, Request $request)
    {
        $request->validate([
            'category_group' => 'required',
        ]);

        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();

        $default_category = DefaultCategoryMaster::where('category_group',$request->category_group)->get();

        foreach($default_category as $compCategory) {
            $compBout = new Bout();

            $compBout->competition_id = $compModel->id;
            $compBout->gender = $compCategory->gender;
            $compBout->category = $compCategory->category;

            $compBout->from_age = $compCategory->from_age;
            $compBout->to_age = $compCategory->to_age;

            $compBout->from_weight = $compCategory->from_weight;
            $compBout->to_weight = $compCategory->to_weight;

            $compBout->user_id = Auth::user()->id;
            $compBout->last_modified = \Carbon\Carbon::now();
            $compBout->last_modified_user_id = Auth::user()->id;

            $compBout->save();

            $compBoutParticipants = Participant::where('competition_id',$compModel->id)->
                where('gender',$compCategory->gender)->
                where('age','>=',$compCategory->from_age)->
                where('age','<=', $compCategory->to_age)->
                where('weight','>=',$compCategory->from_weight)->
                where('weight','<=', $compCategory->to_weight)->
                when($compCategory->group_rank, function ($query, $group_rank) {
                    if($group_rank == "WYO") {
                        $query->whereIn('rank_id',array(0, 1, 2, 3));
                    } else if($group_rank == "GBP") {
                        $query->whereIn('rank_id',array(4, 5, 6));
                    } else {

                    }
                })->
                get();

            // dd($compBoutParticipants);
            foreach($compBoutParticipants as $compParticipant) {
                $compBoutParticipantDetail = new BoutParticipantDetail();
                $compBoutParticipantDetail->competition_id = $compModel->id;
                $compBoutParticipantDetail->bout_id = $compBout->id;
                $compBoutParticipantDetail->participant_id = $compParticipant->id;
                $compBoutParticipantDetail->participant_sequence = 1;
                $compBoutParticipantDetail->user_id = Auth::user()->id;
                $compBoutParticipantDetail->last_modified = \Carbon\Carbon::now();
                $compBoutParticipantDetail->last_modified_user_id = Auth::user()->id;
                $compBoutParticipantDetail->save();
            }
        }
        return response([
            'data' => '',
            'message' => 'Bout Generated successfully',
            'alert-type' => 'success'
        ], 200);
    }
}
