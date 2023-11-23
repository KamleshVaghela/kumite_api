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
use App\Exports\CompDataExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CompDataImport;
use App\Models\BoutTempExcel;
use App\Models\customBout;
use App\Models\FightDetail;

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
            ->join("RANK_MST", function($join) {
                $join->on("KARATE_KA.RANK_ID", "=", "RANK_MST.RANK_ID");
            })
            ->join("COACH", function($join) {
                $join->on("KARATE_KA.COACH_ID", "=", "COACH.COACH_ID");
            })
            ->join("DISTRICT_MST", function($join) {
                $join->on("KARATE_KA.DIS_ID", "=", "DISTRICT_MST.DIS_ID");
            })
            ->join("DISTRICT_GEO", function($join) {
                $join->on("DISTRICT_MST.GEOID", "=", "DISTRICT_GEO.GEOID");
            })
            ->leftJoin("DOJO_MST", function($join) {
                $join->on("KARATE_KA.DOJO_ID", "=", "DOJO_MST.DOJO_ID");
            })
            ->leftJoin("SCHOOL_MASTER", function($join) {
                $join->on("KARATE_KA.SM_ID", "=", "SCHOOL_MASTER.SM_ID");
            })
            ->leftJoin(DB::raw('(
                SELECT KARATE_KA_ID, COUNT(PART_COMP_ID) as NoOfPart 
                FROM PART_COMPETITION
                WHERE KARATE_KA_ID='.$competition_part->KARATE_KA_ID.' 
                GROUP BY KARATE_KA_ID
            ) X '), 
                function($join)
                {
                    $join->on('KARATE_KA.KARATE_KA_ID', '=', 'X.KARATE_KA_ID');
                }
            )
            ->leftJoin(DB::raw('(
                SELECT KARATE_KA_ID, COUNT(RENEWAL_ID) as NoOfYear 
                FROM RENEWAL_DTL 
                WHERE KARATE_KA_ID='.$competition_part->KARATE_KA_ID.' 
                GROUP BY KARATE_KA_ID
            ) Y '), 
                function($join)
                {
                    $join->on('KARATE_KA.KARATE_KA_ID', '=', 'Y.KARATE_KA_ID');
                }
            )
            ->where('KARATE_KA.KARATE_KA_ID', $competition_part->KARATE_KA_ID)
            ->select("KARATE_KA.*", DB::raw("KARATE_KA.TITLE"),DB::raw("IFNULL(KARATE_KA.NAME,'N/A') AS NAME"),DB::raw("IFNULL(KARATE_KA.M_NAME,'N/A') as M_NAME"),DB::raw("IFNULL(KARATE_KA.L_NAME,'N/A') as L_NAME"),  "RANK_MST.RANK" ,"DOJO_MST.DOJO_NAME", "SCHOOL_MASTER.SCHOOL_NAME",
            "COACH.COACH_NAME", "COACH.COACH_CODE", "DISTRICT_GEO.DISTRICT",
            "X.NoOfPart", "Y.NoOfYear")
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
                    $compParticipant->team = "$competition->TYPE-$karateKa->DOJO_NAME ($karateKa->DOJO_ID)";
                } else if ($competition->TYPE == "D") {
                    //District
                    $compParticipant->team = "$competition->TYPE-$karateKa->COACH_NAME ($karateKa->COACH_CODE)";
                } else if ($competition->TYPE == "S") {
                    //State
                    $compParticipant->team = "$competition->TYPE-$karateKa->DISTRICT";
                } else if ($competition->TYPE == "N") {
                    //National
                    $compParticipant->team = "$competition->TYPE-$karateKa->DISTRICT";
                }            
                // $compParticipant->team = 

                $compParticipant->full_name = $karateKa->TITLE.' '.$karateKa->NAME.' '.$karateKa->M_NAME.' '.$karateKa->L_NAME;
                if($karateKa->TITLE =="Mr") {
                    $compParticipant->gender = "Male";
                } else {
                    $compParticipant->gender = "Female";
                }
                $compParticipant->rank = $karateKa->RANK;
                $compParticipant->rank_id = $karateKa->RANK_ID;
                $compParticipant->external_coach_code = $karateKa->COACH_ID;
                $compParticipant->external_coach_name = $karateKa->COACH_NAME;

                $compParticipant->no_of_part = $karateKa->NoOfPart;
                $compParticipant->no_of_year = $karateKa->NoOfYear;
        
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

        $start_date = strtotime(Config::get('constants.start_date'));
        if($compParticipants->count() == 0 && $start_date < strtotime($compModel->start_date)) {
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
        ->with('competition_id',$compModel->id)
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

    public function refreshData($decrypted_comp_id, Request $request)
    {
        $details_key = $request->query('details_key');
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();

        $tmpIdsSql= "select I, group_concat(external_unique_id) as external_unique_id 
        from (
            SELECT MOD(id, 5) as I, external_unique_id FROM participants where competition_id=$compModel->id 
        ) X 
        group by I ";
        $tmpIds = DB::select($tmpIdsSql);

        $idsCondition = "";
        foreach($tmpIds as $data) {
            $idsCondition = $idsCondition . " AND A.PART_COMP_ID NOT IN ( ".$data->external_unique_id.")";
        }

        $pendingPartCompetitionSql= " SELECT A.*, K.TITLE, IFNULL(K.NAME,'N/A') AS NAME, IFNULL(K.M_NAME,'N/A') as M_NAME,IFNULL(K.L_NAME,'N/A') as L_NAME
        FROM PART_COMPETITION A 
        inner join KARATE_KA K on K.KARATE_KA_ID = A.KARATE_KA_ID
        where A.COMP_ID=$decrypted_comp_id  $idsCondition";

        $pendingPartCompetitions = DB::connection('rksys_app')->select($pendingPartCompetitionSql);

        return View('admin.competition.board.refresh_data',compact('details_key'))
        ->with('decrypted_comp_id',$decrypted_comp_id)
        ->with('pendingPartCompetitions',$pendingPartCompetitions);
    }

    public function saveRefreshData($decrypted_comp_id, Request $request)
    {
        $details_key = $request->query('details_key');

        $competition = CompetitionModel::where('COMP_ID',$decrypted_comp_id)->first();
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();

        $tmpIdsSql= "select I, group_concat(external_unique_id) as external_unique_id 
        from (
            SELECT MOD(id, 5) as I, external_unique_id FROM participants where competition_id=$compModel->id 
        ) X 
        group by I ";
        $tmpIds = DB::select($tmpIdsSql);

        $idsCondition = "";
        foreach($tmpIds as $data) {
            $idsCondition = $idsCondition . " AND A.PART_COMP_ID NOT IN ( ".$data->external_unique_id.")";
        }

        $pendingPartCompetitionSql= " SELECT A.*, K.TITLE, IFNULL(K.NAME,'N/A') AS NAME, IFNULL(K.M_NAME,'N/A') as M_NAME,IFNULL(K.L_NAME,'N/A') as L_NAME
        FROM PART_COMPETITION A 
        inner join KARATE_KA K on K.KARATE_KA_ID = A.KARATE_KA_ID
        where A.COMP_ID=$decrypted_comp_id  $idsCondition";

        $pendingPartCompetitions = DB::connection('rksys_app')->select($pendingPartCompetitionSql);

        foreach($pendingPartCompetitions as $data) {
            $this->refreshParticipantDetails($data, $competition, $compModel);
        }
        
        return response([
            'data' => $pendingPartCompetitions,
            'message' => 'Data refreshed successfully',
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
        $data = BoutTempExcel::where('competition_id',$compModel->id)->delete();
        $bouts = customBout::where('competition_id',$compModel->id)->delete();
        
        return response([
            'data' => '',
            'message' => 'Data delete successfully',
            'alert-type' => 'success'
        ], 200);
    }

    public function resultDetails($decrypted_comp_id, Request $request)
    {
        $details_key = $request->query('details_key');
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();
        $result_data = DB::select('
            SELECT external_coach_name,  SUM(total_gold) as total_gold, SUM(total_silver) as total_silver, SUM(total_bronze_1) as total_bronze_1, SUM(total_bronze_2) as total_bronze_2 
            FROM (
                SELECT F.external_coach_name, count(C.first) as total_gold , 0 as total_silver, 0 as total_bronze_1 , 0 as total_bronze_2
                FROM custom_bouts C 
                INNER join participants F on C.first = F.id
                where C.competition_id='.$compModel->id.' 
                group by F.external_coach_name
                UNION ALL 
                SELECT F.external_coach_name, 0 as total_gold, count(C.second) as total_silver, 0 as total_bronze_1, 0 as total_bronze_2
                FROM custom_bouts C 
                INNER join participants F on C.second = F.id
                where C.competition_id='.$compModel->id.' 
                group by F.external_coach_name
                UNION ALL 
                SELECT F.external_coach_name, 0 as total_gold , 0 as total_silver, count(C.third_1) as total_bronze_1 , 0 as total_bronze_2
                FROM custom_bouts C 
                INNER join participants F on C.third_1 = F.id
                where C.competition_id='.$compModel->id.' 
                group by F.external_coach_name
                UNION ALL 
                SELECT F.external_coach_name, 0 as total_gold , 0 as total_silver, 0 as total_bronze_1, count(C.third_2) as total_bronze_2 
                FROM custom_bouts C 
                INNER join participants F on C.third_2 = F.id
                where C.competition_id='.$compModel->id.' 
                group by F.external_coach_name
            ) X 
            GROUP BY external_coach_name
            ORDER BY total_gold desc,total_silver desc
        ');

        return View('admin.competition.board.result_details',compact('details_key'))
        ->with('result_data',$result_data);
    }

    // public function saveResultDetails($decrypted_comp_id, Request $request)
    // {
        
    // }

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

    public function exportExcel($decrypted_comp_id)
    {
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();
        return Excel::download(new CompDataExport($compModel->id), 'Competition_'.$compModel->id.'.xlsx');
    }

    public function importExcel($decrypted_comp_id, Request $request)
    {
        $details_key = $request->query('details_key');

        return View('admin.competition.board.import_excel',compact('details_key'))
        ->with('decrypted_comp_id',$decrypted_comp_id)
        // ->with('competition',$competition)
        ;
    }

    public function postImportExcel($decrypted_comp_id, Request $request) {
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();
        $data = BoutTempExcel::where('competition_id',$compModel->id)->delete();
        $bouts = customBout::where('competition_id',$compModel->id)->delete();
        $boutParticipantDetails = BoutParticipantDetail::where('competition_id',$compModel->id)->delete();

        Excel::import(new CompDataImport($compModel->id), 
                      $request->file('file')->store('files'));

        $excelRecords = BoutTempExcel::where('competition_id',$compModel->id)
        ->orderBy('gender')
        // ->orderBy('bout_number')
        ->orderBy('category')
        ->get();
        // $male_cnt = 1;
        $participant_cnt = 1;
        // $female_cnt = 1;
        foreach($excelRecords as $records) {
            $boutData = customBout::where('competition_id', $records->competition_id)
                ->where('gender',$records->gender)
                ->where('category',$records->category)
                // ->where('bout_number',$male_cnt)
                ->first();
            if($boutData) {
                $participant_cnt = $participant_cnt + 1;

            } else {
                $boutData = new customBout();
                $boutData->competition_id = $records->competition_id;
                $boutData->gender = $records->gender;
                $boutData->category = $records->category;
                $boutData->tatami = $records->tatami;
                $boutData->bout_number= $records->bout_number;
                // if ($records->gender == "Male") {
                //     $boutData->bout_number = $male_cnt;
                //     $male_cnt = $male_cnt + 1;
                // }
                // else {
                //     $boutData->bout_number = $female_cnt;
                //     $female_cnt = $female_cnt + 1;
                // }
                $boutData->save();
                $participant_cnt = 1;
            }

            $compBoutParticipantDetail = new BoutParticipantDetail();
            $compBoutParticipantDetail->competition_id = $records->competition_id;
            $compBoutParticipantDetail->custom_bouts_id = $boutData->id;
            $compBoutParticipantDetail->participant_id = $records->unique_id;
            $compBoutParticipantDetail->participant_sequence = $participant_cnt;
            $compBoutParticipantDetail->user_id = Auth::user()->id;
            $compBoutParticipantDetail->last_modified = \Carbon\Carbon::now();
            $compBoutParticipantDetail->last_modified_user_id = Auth::user()->id;
            $compBoutParticipantDetail->save();

        }

        // $customBoutData = customBout::where('competition_id',$records->competition_id)
        // ->orderBy('id')
        // ->get();

        // foreach($customBoutData as $boutData) {
            
        //     $participants_records = DB::table("bout_participant_details")
        //     ->where('bout_participant_details.custom_bouts_id',$boutData->id)
        //     ->join("participants", function($join) {
        //         $join->on("bout_participant_details.participant_id", "=", "participants.id");
        //     })
        //     ->select("bout_participant_details.id", "participants.external_coach_code")
        //     ->get();

        //     $this->processFightData($participants_records, $boutData->id);
        // }

        return response([
            'data' => '',
            'message' => 'Excel Imported successfully',
            'alert-type' => 'success'
        ], 200);
    }

    function processFightData($result, $bouts_id) {
        // dd($bouts_id);

        $number_of_competitor = count($result);

        $number_of_bout = ceil($number_of_competitor/2);
    
        $data = array();
        if( $number_of_competitor > 0 ) {
            foreach($result as $row) { 
                array_push($data, $row);
            }
            
            $my_array = array();
            $half_of_bout = ceil($number_of_bout/2);
            $arr = range(0,$number_of_bout-1);
            
            foreach($arr as $item) {
                $AKA = $data[$item];
                // $AO = !isset($data[$number_of_bout + $item]) ? array("id"=>0, "competitor"=>"Bye", "coach"=>"Bye" ): $data[$number_of_bout + $item];
                $AO = !isset($data[$number_of_bout + $item]) ?  (object) array('id' => 0) : $data[$number_of_bout + $item];
                $my_d = array(
                      "Bout"=>$item + 1,
                      "AKA"=> $AKA,
                      "AO"=> $AO
                );
                array_push($my_array,$my_d);
            }
    
            $chunk = array_chunk($my_array,$half_of_bout);
            try {
                $first_set = $chunk[0]; 
                if(count($chunk) > 1) {
                    $second_set = $chunk[1];
                }
            } catch (\Exception $e) {
                dd(count($chunk));
            }
            
    
            $my_array_new = array();
            $half_of_half_of_bout = ceil($half_of_bout/2);
    
            $arr = range(0,$half_of_bout-1);
    
            $cnt=1;
    
            foreach($arr as $item) {
                $first_set[$item]['Bout'] = $cnt;
                array_push($my_array_new, $first_set[$item]);
                $cnt = $cnt + 1;
    
                if($number_of_bout>=$cnt)
                {
                      $second_set[$item]['Bout'] = $cnt;
                      array_push($my_array_new, $second_set[$item]);
                      $cnt = $cnt + 1;
                }
            }
    
            
            foreach ($my_array_new as $qid) { 
                try {
                    $fightDetail = new FightDetail();
                    $fightDetail->bout_id = $bouts_id;
                    $fightDetail->fight_number = $qid['Bout'];
                    $fightDetail->aka = $qid['AKA']->id;
                    $fightDetail->ao = $qid['AO']->id;
                    $fightDetail->winner = 0;
                    if ($qid['AO']->id == 0 ) {
                        $fightDetail->bye = 1;
                    } else {
                        $fightDetail->bye = 0;
                    }
                    $fightDetail->user_id = Auth::user()->id;
                    $fightDetail->last_modified = \Carbon\Carbon::now();
                    $fightDetail->last_modified_user_id = Auth::user()->id;
                    $fightDetail->save();
                  
                  } catch (\Exception $e) {
                  
                    dd( $arr);
                  }
            }
        }
    }
}
