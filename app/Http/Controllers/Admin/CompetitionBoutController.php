<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Competition;
use App\Models\CompetitionModel;
use App\Models\BoutParticipantDetail;
use App\Models\Bout;
use App\Models\Participant;
use Auth;
use Carbon\Carbon; 
use Illuminate\Support\Facades\DB;
use Config;
use setasign\Fpdi\Fpdi;
// use setasign\Fpdi\PdfReader\PageBoundaries;
use App\Models\customBout;
use App\Models\CompetitionPartModel;
use App\Models\FilesModel;
use App\PdfRotate;
use PDF;

class CompetitionBoutController extends Controller
{
    private  $dt;

    public function __construct()
    {
        $this->dt = Carbon::now();
    }

    public function index($decrypted_comp_id)
    {
        $competition = CompetitionModel::where('COMP_ID',$decrypted_comp_id)->first();
        
        return View('admin.bout.index',compact('decrypted_comp_id'))
        ->with('competition',$competition);
    }

    public function report($decrypted_comp_id)
    {
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();

        $bout_type = DB::table("bout_participant_details")    
        ->where('bout_participant_details.competition_id',$compModel->id)
        ->select(
            DB::raw('count(bout_id) as bout_id_count'),
            DB::raw('count(custom_bouts_id) as custom_bouts_id_count')
        )
        ->first();

        if($bout_type) {
            if($bout_type->bout_id_count != "0") {
                $bout_records = DB::table("participants")    
                ->where('participants.competition_id',$compModel->id)
                ->where(function ($query) {
                    $query->whereNull('participants.kumite')
                          ->orWhere('participants.kumite', '=', '1');
                })
                ->join("bout_participant_details", function($join) {
                    $join->on("bout_participant_details.participant_id", "=", "participants.id");
                })
                ->join("bouts", function($join) {
                    $join->on("bouts.id", "=", "bout_participant_details.bout_id");
                })
                ->select("bouts.id as bouts_id", "bouts.category as bouts_category",
                    DB::raw('count(*) as participant_count'), DB::raw("0 as custom_bout_id"), "bouts.gender" #DB::raw("'' as bout_number")
                )
                ->groupBy('bouts.id')
                ->groupBy('bouts.category')
                ->groupBy('bouts.gender')
                ->orderBy('bouts.id')
                ->get();
            } else if($bout_type->custom_bouts_id_count != "0") {
                $bout_records = DB::table("participants")    
                ->where('participants.competition_id',$compModel->id)
                ->where(function ($query) {
                    $query->whereNull('participants.kumite')
                          ->orWhere('participants.kumite', '=', '1');
                })
                ->leftJoin("bout_participant_details", function($join) {
                    $join->on("bout_participant_details.participant_id", "=", "participants.id");
                })
                ->leftJoin("custom_bouts", function($join) {
                    $join->on("custom_bouts.id", "=", "bout_participant_details.custom_bouts_id");
                })
                ->select(DB::raw("ifnull(custom_bouts.id,0) as custom_bout_id"), "custom_bouts.category as bouts_category",
                    DB::raw('count(*) as participant_count'), DB::raw("0 as bouts_id"), "custom_bouts.gender", "custom_bouts.bout_number" 
                )
                ->groupBy('custom_bouts.id')
                ->groupBy('custom_bouts.bout_number')
                ->groupBy('custom_bouts.category')
                ->groupBy('custom_bouts.gender')
                
                ->orderBy('custom_bouts.bout_number')
                ->get();
            } else {
                $bout_records = [];
            }
        } else {
            $bout_records = [];
        }
        return View('admin.bout.report',compact('decrypted_comp_id'))
        ->with('bout_records',$bout_records)
        ->with('bout_id',$bout_type->bout_id_count)
        ->with('custom_bout_id',$bout_type->custom_bouts_id_count)
        ;
    }

    public function data_table($decrypted_comp_id)
    {
        $competition = CompetitionModel::where('COMP_ID',$decrypted_comp_id)->first();
        
        return View('admin.bout.data_table',compact('decrypted_comp_id'))
        ->with('competition',$competition);
    }

    public function data_table_report($decrypted_comp_id)
    {
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();

        $bout_type = DB::table("bout_participant_details")    
        ->where('bout_participant_details.competition_id',$compModel->id)
        ->select(
            DB::raw('count(bout_id) as bout_id_count'),
            DB::raw('count(custom_bouts_id) as custom_bouts_id_count')
        )
        ->first();

        if($bout_type) {
            if($bout_type->bout_id_count != "0") {
                $participants_records = DB::table("participants")    
                ->where('participants.competition_id',$compModel->id)
                ->where(function ($query) {
                    $query->whereNull('participants.kumite')
                          ->orWhere('participants.kumite', '=', '1');
                })
                ->leftJoin("bout_participant_details", function($join) {
                    $join->on("bout_participant_details.participant_id", "=", "participants.id");
                })
                ->leftJoin("bouts", function($join) {
                    $join->on("bouts.id", "=", "bout_participant_details.bout_id");
                })
                ->select("bouts.id as bouts_id", "bouts.category as bouts_category",
                    "participants.*", DB::raw("0 as custom_bout_id")
                )
                ->orderBy('bouts.id')
                ->get();
            } else if($bout_type->custom_bouts_id_count != "0") {
                $participants_records = DB::table("participants")    
                ->where('participants.competition_id',$compModel->id)
                ->where(function ($query) {
                    $query->whereNull('participants.kumite')
                          ->orWhere('participants.kumite', '=', '1');
                })
                ->leftJoin("bout_participant_details", function($join) {
                    $join->on("bout_participant_details.participant_id", "=", "participants.id");
                })
                ->leftJoin("custom_bouts", function($join) {
                    $join->on("custom_bouts.id", "=", "bout_participant_details.custom_bouts_id");
                })
                ->select("custom_bouts.id as custom_bout_id", "custom_bouts.category as bouts_category",
                    "participants.*", DB::raw("0 as bouts_id")
                )
                ->orderBy('custom_bouts.id')
                ->get();
            } else {
                $participants_records = [];
            }
        } else {
            $participants_records = [];
        }
        return View('admin.bout.data_table_report',compact('decrypted_comp_id'))
        ->with('participants_records',$participants_records)
        ->with('bout_id',$bout_type->bout_id_count)
        ->with('custom_bout_id',$bout_type->custom_bouts_id_count);
    }

    public function participants($decrypted_comp_id, $bout_id, $custom_bout_id)
    {
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();

        if($custom_bout_id != 0) {
            $participants_records = DB::table("bout_participant_details")
            ->where('bout_participant_details.custom_bouts_id',$custom_bout_id)
            ->where('participants.competition_id',$compModel->id)
            ->join("participants", function($join) {
                $join->on("bout_participant_details.participant_id", "=", "participants.id");
            })
            ->leftJoin("custom_bouts", function($join) {
                $join->on("custom_bouts.id", "=", "bout_participant_details.custom_bouts_id");
            })
            ->select("participants.*", "custom_bouts.first", "custom_bouts.second", "custom_bouts.third_1", "custom_bouts.third_2", "bout_participant_details.participant_sequence")
            ->orderBy('bout_participant_details.participant_sequence')
            ->get();
            $boutObj = customBout::find($custom_bout_id);
        }
        else if($bout_id != "0") {
            $participants_records = DB::table("participants")    
            ->where('participants.competition_id',$compModel->id)
            ->where(function ($query) {
                $query->whereNull('participants.kumite')
                      ->orWhere('participants.kumite', '=', '1');
            })
            ->leftJoin("bout_participant_details", function($join) {
                $join->on("bout_participant_details.participant_id", "=", "participants.id");
            })
            ->leftJoin("bouts", function($join) {
                $join->on("bouts.id", "=", "bout_participant_details.bout_id");
            })
            ->select("participants.*", "bouts.first", "bouts.second", "bouts.third_1", "bouts.third_2", "bout_participant_details.participant_sequence")
            ->orderBy('bout_participant_details.participant_sequence')
            ->get();
            $boutObj = Bout::find($bout_id);
        } else {
            $participants_records = DB::table("participants")    
            // ->where('bout_participant_details.bout_id',$bout_id)
            ->where('participants.competition_id',$compModel->id)
            ->where(function ($query) {
                $query->whereNull('participants.kumite')
                      ->orWhere('participants.kumite', '=', '1');
            })
            ->leftJoin("bout_participant_details", function($join) {
                $join->on("bout_participant_details.participant_id", "=", "participants.id");
            })
            // ->leftJoin("bouts", function($join) {
            //     $join->on("bouts.id", "=", "bout_participant_details.bout_id");
            // })
            ->whereNull('bout_participant_details.id')
            ->select("participants.*", "bout_participant_details.participant_sequence" )
            ->orderBy('bout_participant_details.participant_sequence')
            ->get();
            $boutObj = Bout::find($bout_id);
        }
        // dd($participants_records);
        return View('admin.bout.participants',compact('decrypted_comp_id'))
        ->with('participants_records',$participants_records)
        ->with('bout_id',$bout_id)
        ->with('custom_bout_id',$custom_bout_id)
        ->with('boutObj',$boutObj);
    }

    public function change_bout($decrypted_comp_id, $bout_id, $custom_bout_id, $participant_id)
    {
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();
        $boutList = null;

        $participant = DB::table("participants")    
        ->where('participants.competition_id',$compModel->id)
        ->where('participants.id',$participant_id)
        ->select("participants.*")
        ->first();


        if($bout_id != "0") {
            $boutListSql= " 
            SELECT C.id, C.gender,C.category, 0 as bout_number ,count(D.id) as total_participants
            FROM bouts C
            LEFT JOIN bout_participant_details D on D.custom_bouts_id = C.id
            where C.competition_id=$compModel->id AND C.gender='$participant->gender' AND C.first IS NULL AND C.second IS NULL AND C.third_1 IS NULL AND C.third_2 IS NULL 
            group by C.id, C.gender,C.category, C.bout_number
            Having total_participants < 9
            ";
        } else if($custom_bout_id != "0") {
            $boutListSql= "  
            SELECT C.id, C.gender,C.category, C.bout_number ,count(D.id) as total_participants
            FROM custom_bouts C
            LEFT JOIN bout_participant_details D on D.custom_bouts_id = C.id
            where C.competition_id=$compModel->id AND C.gender='$participant->gender' AND C.first IS NULL AND C.second IS NULL AND C.third_1 IS NULL AND C.third_2 IS NULL 
            group by C.id, C.gender,C.category, C.bout_number
            Having total_participants < 9
            ";
        } else {
            $bout_type = DB::table("bout_participant_details")    
            ->where('bout_participant_details.competition_id',$compModel->id)
            ->select(
                DB::raw('count(bout_id) as bout_id_count'),
                DB::raw('count(custom_bouts_id) as custom_bouts_id_count')
            )
            ->first();
            if($bout_type->bout_id_count != "0") {
                $boutListSql= " 
                SELECT C.id, C.gender,C.category, 0 as bout_number ,count(D.id) as total_participants
                FROM bouts C
                LEFT JOIN bout_participant_details D on D.custom_bouts_id = C.id
                where C.competition_id=$compModel->id AND C.gender='$participant->gender' AND C.first IS NULL AND C.second IS NULL AND C.third_1 IS NULL AND C.third_2 IS NULL 
                group by C.id, C.gender,C.category, C.bout_number
                Having total_participants < 9
                ";
            } else if($bout_type->custom_bouts_id_count != "0") {
                $boutListSql= " 
                SELECT C.id, C.gender,C.category, C.bout_number ,count(D.id) as total_participants
                FROM custom_bouts C
                LEFT JOIN bout_participant_details D on D.custom_bouts_id = C.id
                where C.competition_id=$compModel->id AND C.gender='$participant->gender' AND C.first IS NULL AND C.second IS NULL AND C.third_1 IS NULL AND C.third_2 IS NULL 
                group by C.id, C.gender,C.category, C.bout_number
                Having total_participants < 9
                ";
            } else {
                $boutListSql = "";
            }
        }
        // dd($boutListSql);
        if ($boutListSql != "") {
            $boutList = DB::select($boutListSql);
        }
        
        return View('admin.bout.change_bout',compact('decrypted_comp_id'))
        ->with('participants',$participant)
        ->with('bout_id',$bout_id) 
        ->with('custom_bout_id',$custom_bout_id)
        ->with('details_key','change_bout_details')
        ->with('boutList',$boutList);
    }

    public function save_change_bout(Request $request,$decrypted_comp_id, $bout_id, $custom_bout_id, $participant_id) {
        $request->validate([
            'bout_id' => 'required',
            'bout_number' => 'required_if:bout_id,!=,0',
            'category' => 'required_if:bout_id,!=,0',
            'tatami' => 'required_if:bout_id,!=,0',
            'session' => 'required_if:bout_id,!=,0',
        ]);

        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();
        
        $bout_type = DB::table("bout_participant_details")    
        ->where('bout_participant_details.competition_id',$compModel->id)
        ->select(
            DB::raw('count(bout_id) as bout_id_count'),
            DB::raw('count(custom_bouts_id) as custom_bouts_id_count')
        )
        ->first();

        $boutData = null;

        if ($request->bout_id != 0) {
            if($bout_type->bout_id_count != "0") {
                $boutData = Bout::where('id', $request->bout_id)->first();
            } else if($bout_type->custom_bouts_id_count != "0") {
                $boutData = customBout::where('id', $request->bout_id)->first();
            }
        } else {
            if($bout_type->bout_id_count != "0") {
                $boutData = new Bout();
                $boutData->competition_id = $compModel->id;
                $boutData->gender = $request->gender;
                $boutData->category = $request->category;
                $boutData->bout_number= $request->bout_number;
                $boutData->tatami = $request->tatami;
                $boutData->session = $request->session;
                $boutData->save();

            } else if($bout_type->custom_bouts_id_count != "0") {
                $boutData = new customBout();
                $boutData->competition_id = $compModel->id;
                $boutData->gender = $request->gender;
                $boutData->category = $request->category;
                $boutData->bout_number= $request->bout_number;
                $boutData->tatami = $request->tatami;
                $boutData->session = $request->session;
                $boutData->save();
            }
        }

        $compBoutParticipantDetail = BoutParticipantDetail::where('participant_id',$participant_id)
        ->where('competition_id', $boutData->competition_id)
        ->first();


        if($compBoutParticipantDetail) {
            $compBoutParticipantDetailList = BoutParticipantDetail::where('competition_id', $boutData->competition_id)
            ->where('participant_sequence', '>', $compBoutParticipantDetail->participant_sequence);

            if ($boutData instanceof \App\Models\Bout) {
                $compBoutParticipantDetailList
                ->where('bout_id', $compBoutParticipantDetail->bout_id)
                ->update([
                    'participant_sequence' => DB::raw('`participant_sequence` - 1')
                ]);
            } else {
                $compBoutParticipantDetailList
                ->where('custom_bouts_id', $compBoutParticipantDetail->custom_bouts_id)
                ->update([
                    'participant_sequence' => DB::raw('`participant_sequence` - 1')
                ]);
            }


            if ($boutData instanceof \App\Models\Bout) {
                $compBoutParticipantDetail->bout_id = $boutData->id;
            } else {
                $compBoutParticipantDetail->custom_bouts_id = $boutData->id;
            }
            $compBoutParticipantDetail->participant_sequence = $request->sequence;
            $compBoutParticipantDetail->last_modified = \Carbon\Carbon::now();
            $compBoutParticipantDetail->last_modified_user_id = Auth::user()->id;
            $compBoutParticipantDetail->save();

        } else {
            $compBoutParticipantDetail = new BoutParticipantDetail();
            $compBoutParticipantDetail->competition_id = $boutData->competition_id;
            if ($boutData instanceof \App\Models\Bout) {
                $compBoutParticipantDetail->bout_id = $boutData->id;
            } else {
                $compBoutParticipantDetail->custom_bouts_id = $boutData->id;
            }
            $compBoutParticipantDetail->participant_id = $participant_id;
            $compBoutParticipantDetail->participant_sequence = $request->sequence;
            $compBoutParticipantDetail->user_id = Auth::user()->id;
            $compBoutParticipantDetail->last_modified = \Carbon\Carbon::now();
            $compBoutParticipantDetail->last_modified_user_id = Auth::user()->id;
            $compBoutParticipantDetail->save();

        }

        $compBoutParticipantDetailList = BoutParticipantDetail::where('competition_id', $boutData->competition_id)
        ->where('participant_sequence', '>=', $compBoutParticipantDetail->participant_sequence)
        ->where('id', '!=' , $compBoutParticipantDetail->id);

        if ($boutData instanceof \App\Models\Bout) {
            $compBoutParticipantDetailList
            ->where('bout_id', $boutData->id)
            ->update([
                'participant_sequence' => DB::raw('`participant_sequence` + 1')
            ]);
        } else {
            $compBoutParticipantDetailList
            ->where('custom_bouts_id', $boutData->id)
            ->update([
                'participant_sequence' => DB::raw('`participant_sequence` + 1')
            ]);
        }


        return response([
            'data' => $compBoutParticipantDetail,
            'updated_data' => $compBoutParticipantDetailList,
            'message' => 'Result updated successfully',
            'alert-type' => 'success'
        ], 200);
    }


    public function karate_ka($decrypted_comp_id, $bout_id, $custom_bout_id, $participant_id)
    {
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();
        $boutObj = null;

        if($bout_id != "0") {
            $boutObj = Bout::find($bout_id);

        } else if($custom_bout_id != "0") {
            $boutObj = customBout::find($custom_bout_id);
        }
        // dd($dataObj);    

        $participants = DB::table("participants")    
        ->where('participants.competition_id',$compModel->id)
        ->where('participants.id',$participant_id)
        ->select("participants.*")
        ->first();

        // dd($participants);
        
        return View('admin.bout.karate_ka',compact('decrypted_comp_id'))
        ->with('participants',$participants)
        ->with('bout_id',$bout_id) 
        ->with('custom_bout_id',$custom_bout_id)
        ->with('details_key','result_details')
        ->with('boutObj',$boutObj);
    }

    public function save_data(Request $request,$decrypted_comp_id, $bout_id, $custom_bout_id, $participant_id) {
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();
        $participant = Participant::find($participant_id);
        $competitionPartModel = CompetitionPartModel::find($participant->external_unique_id);

        if($bout_id != "0") {
            $dataObj = Bout::find($bout_id);

        } else if($custom_bout_id != "0") {
            $dataObj = customBout::find($custom_bout_id);
        }
        if($request->result == "1") {
            $dataObj->first = $participant_id;
            $competitionPartModel->KUMITE_RES = 1;

            if($dataObj->second == $participant_id) {
                $dataObj->second = 0;
            }
            if($dataObj->third_1 == $participant_id) {
                $dataObj->third_1 = 0;
            }
            if($dataObj->third_2 == $participant_id) {
                $dataObj->third_2 = 0;
            }
        } else if($request->result == "2") {
            $dataObj->second = $participant_id;
            $competitionPartModel->KUMITE_RES = 2;

            if($dataObj->first == $participant_id) {
                $dataObj->first = 0;
            }
            if($dataObj->third_1 == $participant_id) {
                $dataObj->third_1 = 0;
            }
            if($dataObj->third_2 == $participant_id) {
                $dataObj->third_2 = 0;
            }

        } else if($request->result == "3") {
            $dataObj->third_1 = $participant_id;
            $competitionPartModel->KUMITE_RES = 3;

            if($dataObj->first == $participant_id) {
                $dataObj->first = 0;
            }
            if($dataObj->second == $participant_id) {
                $dataObj->second = 0;
            }
            if($dataObj->third_2 == $participant_id) {
                $dataObj->third_2 = 0;
            }
        } else if($request->result == "4") {
            $dataObj->third_2 = $participant_id;
            $competitionPartModel->KUMITE_RES = 3;

            if($dataObj->first == $participant_id) {
                $dataObj->first = 0;
            }
            if($dataObj->second == $participant_id) {
                $dataObj->second = 0;
            }
            if($dataObj->third_1 == $participant_id) {
                $dataObj->third_1 = 0;
            }
        }
        $dataObj->save();
        $competitionPartModel->save();

        return response([
            'data' => $dataObj,
            'message' => 'Result updated successfully',
            'alert-type' => 'success'
        ], 200);
    }

    public function download_all_bout($decrypted_comp_id, $bout_id, $custom_bout_id) 
    {
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();

        if($custom_bout_id != 0) {
            $bout_records = customBout::where('competition_id',$compModel->id)->orderBy('bout_number')->get();
        }
        else if($bout_id == "0") {
            $bout_records = Bout::where('competition_id',$compModel->id)->get();
        } else {
            $bout_records = Bout::where('competition_id',$compModel->id)->get();
        }

        $outputFileList=array();
        
        foreach($bout_records as $key=>$rec) {
            if($custom_bout_id != 0) {
                $boutParticipantDetailCount = BoutParticipantDetail::where('competition_id', $compModel->id)->where('custom_bouts_id',$rec->id)->get()->count();
                if($boutParticipantDetailCount != 0 ){
                    list($fpdi, $outputFilePath) = $this->generate_bout($decrypted_comp_id, 0, $rec->id);
                }
            } else {
                $boutParticipantDetailCount = BoutParticipantDetail::where('competition_id', $compModel->id)->where('bout_id',$rec->id)->get()->count();
                if($boutParticipantDetailCount != 0 ){
                    list($fpdi, $outputFilePath) = $this->generate_bout($decrypted_comp_id, $rec->id, 0);
                }
            }
            $fpdi->Output($outputFilePath, 'F');
            $BoutPdf = new PdfRotate;
            $outputFilePathNew = "competition/tmp/".$decrypted_comp_id."_". $rec->id."_new_1.pdf";
            $BoutPdf->rotatePdfPage($outputFilePath, $outputFilePathNew, $BoutPdf::DEGREES_270,2);  

            array_push($outputFileList,$outputFilePathNew);
        }

        // dd($outputFileList);
        $pageCount = 0;
        // initiate FPDI
        $pdf = new FPDI();

        // iterate through the files
        foreach ($outputFileList AS $file) {
            // get the page count
            $pageCount = $pdf->setSourceFile($file);
            // iterate through all pages
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                // import a page
                $templateId = $pdf->importPage($pageNo);
                // get the size of the imported page
                $size = $pdf->getTemplateSize($templateId);

                $pdf->AddPage($size['orientation'], array($size['width'], $size['height']));

                // create a page (landscape or portrait depending on the imported page size)
                // if ($size['w'] > $size['h']) {
                //     $pdf->AddPage('L', array($size['w'], $size['h']));
                // } else {
                //     $pdf->AddPage('P', array($size['w'], $size['h']));
                // }

                // use the imported page
                $pdf->useTemplate($templateId);

                // $pdf->SetFont('Helvetica');
                // $pdf->SetXY(5, 5);
                // $pdf->Write(8, 'Generated by FPDI');
            }
        }
        $pdf->Output('D', "Kumite_".$decrypted_comp_id.".pdf", 'F');
    }

    public function generate_bout($decrypted_comp_id, $bout_id, $custom_bout_id)
    {
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();

        if($custom_bout_id != 0) {
            $participants_records = DB::table("bout_participant_details")
            ->where('bout_participant_details.custom_bouts_id',$custom_bout_id)
            ->where('participants.competition_id',$compModel->id)
            ->join("participants", function($join) {
                $join->on("bout_participant_details.participant_id", "=", "participants.id");
            })
            ->select("participants.*")
            ->orderBy('bout_participant_details.participant_sequence')
            ->get();
            $bout_record = customBout::find($custom_bout_id);
        }
        else if($bout_id == "0") {
            $participants_records = DB::table("participants")    
            ->where('participants.competition_id',$compModel->id)
            ->where(function ($query) {
                $query->whereNull('participants.kumite')
                      ->orWhere('participants.kumite', '=', '1');
            })
            ->leftJoin("bout_participant_details", function($join) {
                $join->on("bout_participant_details.participant_id", "=", "participants.id");
            })
            ->whereNull('bout_participant_details.bout_id')
            ->select("participants.*")
            ->orderBy('bout_participant_details.participant_sequence')
            ->get();
            $bout_record = Bout::find($bout_id);
        } else {
            $participants_records = DB::table("bout_participant_details")    
            ->where('bout_participant_details.bout_id',$bout_id)
            ->where('participants.competition_id',$compModel->id)
            ->join("participants", function($join) {
                $join->on("bout_participant_details.participant_id", "=", "participants.id");
            })
            ->select("participants.*")
            ->orderBy('bout_participant_details.participant_sequence')
            ->get();
            $bout_record = Bout::find($bout_id);
        }
        // dd($bout_record);

        $player_count = count($participants_records);
        $player_conf = Config::get('constants.competition.'.$player_count);

        // dd($player_conf);

        $fpdi = new FPDI;
        
        $filePath = "competition/template/".$player_count.".pdf";
        $outputFilePath = "competition/tmp/".$compModel->id."_". $bout_record->bout_number.".pdf";
        
        $count = $fpdi->setSourceFileWithParserParams($filePath);
  
        for ($i=1; $i<=$count; $i++) {
  
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);
              
            $fpdi->Image("logo.png",10,2, 32, 32);

            $fpdi->SetFont("helvetica", "b", 18);
            // $fpdi->SetTextColor(153,0,153);
  
            $left = 50;
            $top = 10;
            $fpdi->Text($left,$top,$compModel->name);

            $left = 260;
            $top = 21;
            $fpdi->SetFont("helvetica", "b", 17);
            $fpdi->Text($left,$top,$bout_record->bout_number);
            
            $left = 50;
            $top = 20;
            $fpdi->SetFont("helvetica", "b", 17);
            // $text = "U7 - Male - WYO - Upto 20 Kg";
            $fpdi->Text($left,$top,$bout_record->category);
  
            $left = 235;
            $top = 32;
            $fpdi->SetFont("helvetica", "b", 17);
           
            $fpdi->Text($left,$top, 'Tatami '. $bout_record->tatami.' - '.$bout_record->session);

            foreach($participants_records as $key=>$rec) {
                $competition_conf = Config::get('constants.competition.player_location_'.$player_count.'.'.$key+1);
                $this->print_player_text($fpdi, $competition_conf, $rec);
                
                $left = 200;
                $top = 205;
                $fpdi->SetFont("helvetica", "i", 10);
    
                $fpdi->Text($left,$top,"Bout Generated on ".$this->dt->toDateTimeString()." by Kumite App");
            }
        }
        $fpdi = $this->generate_back_page($fpdi, $bout_record, $compModel);
        return array( $fpdi, $outputFilePath);
    }

    public function download_bout($decrypted_comp_id, $bout_id, $custom_bout_id)
    {
        list($fpdi, $outputFilePath) = $this->generate_bout($decrypted_comp_id, $bout_id, $custom_bout_id);
        $fpdi->Output( $outputFilePath, 'F');

        $DownloadBoutPdf = new PdfRotate;
        $outputFilePathNew = "competition/tmp/".$decrypted_comp_id."_". $bout_id."_new.pdf";
        $DownloadBoutPdf->rotatePdfPage($outputFilePath, $outputFilePathNew, $DownloadBoutPdf::DEGREES_270,2);

        $headers = array(
            'Content-Type: application/pdf',
          );

        return response()->download($outputFilePathNew, "Kumite_".$decrypted_comp_id."_". $bout_id.".pdf", $headers);

        // $fpdi->Output('I', $outputFilePathNew, true);

    }

    public function generate_back_page($fpdi, $bout_record, $compModel)
    {
        $backPagefilePath = "competition/template/back_page.pdf";

        $count = $fpdi->setSourceFile($backPagefilePath);
  
        for ($i=1; $i<=$count; $i++) {
  
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);

            $left = 25;
            $top = 10;
            $fpdi->SetFont("helvetica", "b", 12);
            $fpdi->Text($left,$top,$compModel->name);

            $left = 25;
            $top = 15;
            $fpdi->SetFont("helvetica", "b", 12);
            $fpdi->Text($left,$top,$bout_record->category);

            $left = 165;
            $top = 27;
            $fpdi->SetFont("helvetica", "b", 17);
            $fpdi->Text($left,$top,$bout_record->bout_number);

            $left = 25;
            $top = 51;
            $fpdi->SetFont("helvetica", "b", 17);
            $fpdi->Text($left,$top, 'Tatami '. $bout_record->tatami.' - '.$bout_record->session);


            $left = 120;
            $top = 283;
            $fpdi->SetFont("helvetica", "i", 10);

            $fpdi->Text($left,$top,"Bout Generated on ".$this->dt->toDateTimeString()." by Kumite App");
        }
        return $fpdi;
    }

    public function result_view($decrypted_comp_id, $bout_id, $custom_bout_id) {

    }
    
    public function print_player_text($fpdi, $competition_conf, $player_data) {
        $left_1 = $competition_conf['left_1'];
        $left_2 = $competition_conf['left_2']; 
        $top = $competition_conf['right']; 
        $font = $competition_conf['font']; 
        $style = $competition_conf['style'];
        $fontsize = $competition_conf['fontsize']; 
        
        $fpdi->SetFont($font, $style, $fontsize);
        $fpdi->Text($left_1,$top,$player_data->full_name);
        $fpdi->SetFont($font, $style, $fontsize-4);
        $fpdi->Text($left_2,$top - 2,ucwords(strtolower($player_data->external_coach_name)));
        $fpdi->Text($left_2,$top + 2,ucwords(strtolower($player_data->team)));
        // $fpdi->Text($left_2 - 15,$top + 1,ucwords(strtolower($player_data->team)));
        // $this->print_text($fpdi, $left_1, $top, strtoupper($player_data["full_name"]), 15);
        // $this->print_text($fpdi, $left_2, $top, strtoupper($player_data["external_coach_name"]), 15);
        // strtoupper
    }

    public function print_text($fpdi, $left, $top, $text, $fontsize) {
        $fpdi->SetFont("helvetica", "b", $fontsize);
        $fpdi->Text($left,$top,$text);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function results_index($decrypted_comp_id)
    {
        $competition = CompetitionModel::where('COMP_ID',$decrypted_comp_id)->first();
        
        return View('admin.bout.results_index',compact('decrypted_comp_id'))
        ->with('competition',$competition);
    }

    public function results_report($decrypted_comp_id)
    {
        $competition = CompetitionModel::where('COMP_ID',$decrypted_comp_id)->first();
        return View('admin.bout.results_report',compact('decrypted_comp_id'))
        ->with('view_type',"index")
        ->with('competition',$competition);
    }

    public function results_report_view_type($decrypted_comp_id, $view_type, Request $request)
    {
        $details_key = $request->query('details_key');
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();
        $competition = CompetitionModel::where('COMP_ID',$decrypted_comp_id)->first();

        $result_data = null;
        $coach_list = null;

        switch($view_type) {
            case('coach'):
                $result_data = DB::select('
                    SELECT external_coach_name, SUM(total_gold) as total_gold, SUM(total_silver) as total_silver, SUM(total_bronze_1) as total_bronze_1, SUM(total_bronze_2) as total_bronze_2 
                    ,SUM(total_gold_kata) as total_gold_kata, SUM(total_silver_kata) as total_silver_kata, SUM(total_bronze_1_kata) as total_bronze_1_kata, SUM(total_bronze_2_kata) as total_bronze_2_kata 
                    FROM (
                        SELECT F.external_coach_name, count(C.first) as total_gold , 0 as total_silver, 0 as total_bronze_1 , 0 as total_bronze_2
                        ,0 as total_gold_kata , 0 as total_silver_kata, 0 as total_bronze_1_kata , 0 as total_bronze_2_kata
                        FROM custom_bouts C 
                        INNER join participants F on C.first = F.id
                        where C.competition_id='.$compModel->id.' 
                        group by F.external_coach_name
                        UNION ALL 
                        SELECT F.external_coach_name, 0 as total_gold, count(C.second) as total_silver, 0 as total_bronze_1, 0 as total_bronze_2
                        ,0 as total_gold_kata , 0 as total_silver_kata, 0 as total_bronze_1_kata , 0 as total_bronze_2_kata
                        FROM custom_bouts C 
                        INNER join participants F on C.second = F.id
                        where C.competition_id='.$compModel->id.' 
                        group by F.external_coach_name
                        UNION ALL 
                        SELECT F.external_coach_name, 0 as total_gold , 0 as total_silver, count(C.third_1) as total_bronze_1 , 0 as total_bronze_2
                        ,0 as total_gold_kata , 0 as total_silver_kata, 0 as total_bronze_1_kata , 0 as total_bronze_2_kata
                        FROM custom_bouts C 
                        INNER join participants F on C.third_1 = F.id
                        where C.competition_id='.$compModel->id.' 
                        group by F.external_coach_name
                        UNION ALL 
                        SELECT F.external_coach_name, 0 as total_gold , 0 as total_silver, 0 as total_bronze_1, count(C.third_2) as total_bronze_2 
                        ,0 as total_gold_kata , 0 as total_silver_kata, 0 as total_bronze_1_kata , 0 as total_bronze_2_kata
                        FROM custom_bouts C 
                        INNER join participants F on C.third_2 = F.id
                        where C.competition_id='.$compModel->id.' 
                        group by F.external_coach_name
                        UNION ALL

                        SELECT F.external_coach_name, 0 as total_gold , 0 as total_silver, 0 as total_bronze_1 , 0 as total_bronze_2
                        ,count(C.first) as total_gold_kata , 0 as total_silver_kata, 0 as total_bronze_1_kata , 0 as total_bronze_2_kata
                        FROM custom_kata_bouts C 
                        INNER join participants F on C.first = F.id
                        where C.competition_id='.$compModel->id.' 
                        group by F.external_coach_name
                        UNION ALL 
                        SELECT F.external_coach_name, 0 as total_gold, 0 as total_silver, 0 as total_bronze_1, 0 as total_bronze_2
                        ,0 as total_gold_kata , count(C.second) as total_silver_kata, 0 as total_bronze_1_kata , 0 as total_bronze_2_kata
                        FROM custom_kata_bouts C 
                        INNER join participants F on C.second = F.id
                        where C.competition_id='.$compModel->id.' 
                        group by F.external_coach_name
                        UNION ALL 
                        SELECT F.external_coach_name, 0 as total_gold , 0 as total_silver,0  as total_bronze_1 , 0 as total_bronze_2
                        ,0 as total_gold_kata , 0 as total_silver_kata, count(C.third_1) as total_bronze_1_kata , 0 as total_bronze_2_kata
                        FROM custom_kata_bouts C 
                        INNER join participants F on C.third_1 = F.id
                        where C.competition_id='.$compModel->id.' 
                        group by F.external_coach_name
                        UNION ALL 
                        SELECT F.external_coach_name, 0 as total_gold , 0 as total_silver, 0 as total_bronze_1, 0 as total_bronze_2 
                        ,0 as total_gold_kata , 0 as total_silver_kata, 0 as total_bronze_1_kata , count(C.third_2) as total_bronze_2_kata
                        FROM custom_kata_bouts C 
                        INNER join participants F on C.third_2 = F.id
                        where C.competition_id='.$compModel->id.' 
                        group by F.external_coach_name
                    ) X 
                    GROUP BY external_coach_name
                    ORDER BY total_gold desc,total_silver desc
                ');
                break;

            case('team'):
                $result_data = DB::select('
                    SELECT team,  SUM(total_gold) as total_gold, SUM(total_silver) as total_silver, SUM(total_bronze_1) as total_bronze_1, SUM(total_bronze_2) as total_bronze_2 
                    ,SUM(total_gold_kata) as total_gold_kata, SUM(total_silver_kata) as total_silver_kata, SUM(total_bronze_1_kata) as total_bronze_1_kata, SUM(total_bronze_2_kata) as total_bronze_2_kata 
                    FROM (
                        SELECT F.team, count(C.first) as total_gold , 0 as total_silver, 0 as total_bronze_1 , 0 as total_bronze_2
                        ,0 as total_gold_kata , 0 as total_silver_kata, 0 as total_bronze_1_kata , 0 as total_bronze_2_kata
                        FROM custom_bouts C 
                        INNER join participants F on C.first = F.id
                        where C.competition_id='.$compModel->id.' 
                        group by F.team
                        UNION ALL 
                        SELECT F.team, 0 as total_gold, count(C.second) as total_silver, 0 as total_bronze_1, 0 as total_bronze_2
                        ,0 as total_gold_kata , 0 as total_silver_kata, 0 as total_bronze_1_kata , 0 as total_bronze_2_kata
                        FROM custom_bouts C 
                        INNER join participants F on C.second = F.id
                        where C.competition_id='.$compModel->id.' 
                        group by F.team
                        UNION ALL 
                        SELECT F.team, 0 as total_gold , 0 as total_silver, count(C.third_1) as total_bronze_1 , 0 as total_bronze_2
                        ,0 as total_gold_kata , 0 as total_silver_kata, 0 as total_bronze_1_kata , 0 as total_bronze_2_kata
                        FROM custom_bouts C 
                        INNER join participants F on C.third_1 = F.id
                        where C.competition_id='.$compModel->id.' 
                        group by F.team
                        UNION ALL 
                        SELECT F.team, 0 as total_gold , 0 as total_silver, 0 as total_bronze_1, count(C.third_2) as total_bronze_2
                        ,0 as total_gold_kata , 0 as total_silver_kata, 0 as total_bronze_1_kata , 0 as total_bronze_2_kata
                        FROM custom_bouts C 
                        INNER join participants F on C.third_2 = F.id
                        where C.competition_id='.$compModel->id.' 
                        group by F.team

                        UNION ALL 


                        SELECT F.team, count(C.first) as total_gold , 0 as total_silver, 0 as total_bronze_1 , 0 as total_bronze_2
                        ,count(C.first) as total_gold_kata , 0 as total_silver_kata, 0 as total_bronze_1_kata , 0 as total_bronze_2_kata
                        FROM custom_kata_bouts C 
                        INNER join participants F on C.first = F.id
                        where C.competition_id='.$compModel->id.' 
                        group by F.team
                        UNION ALL 
                        SELECT F.team, 0 as total_gold, 0 as total_silver, 0 as total_bronze_1, 0 as total_bronze_2
                        ,0 as total_gold_kata , count(C.second) as total_silver_kata, 0 as total_bronze_1_kata , 0 as total_bronze_2_kata
                        FROM custom_kata_bouts C 
                        INNER join participants F on C.second = F.id
                        where C.competition_id='.$compModel->id.' 
                        group by F.team
                        UNION ALL 
                        SELECT F.team, 0 as total_gold , 0 as total_silver, 0 as total_bronze_1 , 0 as total_bronze_2
                        ,0 as total_gold_kata , 0 as total_silver_kata, count(C.third_1) as total_bronze_1_kata , 0 as total_bronze_2_kata
                        FROM custom_kata_bouts C 
                        INNER join participants F on C.third_1 = F.id
                        where C.competition_id='.$compModel->id.' 
                        group by F.team
                        UNION ALL 
                        SELECT F.team, 0 as total_gold , 0 as total_silver, 0 as total_bronze_1, 0 as total_bronze_2
                        ,0 as total_gold_kata , 0 as total_silver_kata, 0 as total_bronze_1_kata , count(C.third_2) as total_bronze_2_kata
                        FROM custom_kata_bouts C 
                        INNER join participants F on C.third_2 = F.id
                        where C.competition_id='.$compModel->id.' 
                        group by F.team

                    ) X 
                    GROUP BY team
                    ORDER BY total_gold desc,total_silver desc
                ');
                break;
            case('download'):
                $coach_list = DB::select('
                    SELECT DISTINCT external_coach_code,external_coach_name FROM participants where competition_id='.$compModel->id.' 
                    order by external_coach_name
                ');
                break;

            default:
                $msg = 'Something went wrong.';
        }
        // dd($result_data);
        return View('admin.bout.results_report',compact('decrypted_comp_id'))
        ->with('view_type',$view_type)
        ->with('details_key', $details_key)
        ->with('competition',$competition)
        ->with('result_data',$result_data)
        ->with('coach_list',$coach_list)
        ;

    }

    public function results_report_download_external_coach_code($decrypted_comp_id, $external_coach_code, $download_type, Request $request)
    {
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();
     
        // dd($compModel);
        $coach = DB::select('
            SELECT DISTINCT external_coach_code,external_coach_name 
            FROM participants 
            where external_coach_code='.$external_coach_code.' and competition_id='.$compModel->id.'
        ');

        $participants = DB::select( "select * 
            from 
            (
                SELECT C.bout_number,  C.gender, C.category, C.first, C.second, C.third_1, C.third_2, P.full_name, P.id, 
                CASE WHEN P.id = C.first THEN 'Gold' WHEN P.id = C.second THEN 'Silver' WHEN P.id = C.third_1 THEN 'Bronze' WHEN P.id = C.third_2 THEN 'Bronze' ELSE ' - ' END as Result,
                CASE WHEN P.id = C.first THEN 1 WHEN P.id = C.second THEN 2 WHEN P.id = C.third_1 THEN 3 WHEN P.id = C.third_2 THEN 4 ELSE 5 END as Result_seq,
                CASE WHEN P.id = C.first THEN '1st' WHEN P.id = C.second THEN '2nd' WHEN P.id = C.third_1 THEN '3rd' WHEN P.id = C.third_2 THEN '3rd' ELSE 'no' END as medal,
                P.team, C.age_category, C.weight_category, C.rank_category, C.session, C.tatami, P.external_unique_id,
                P.age, P.weight    
                FROM participants P
                INNER JOIN bout_participant_details B on P.id = B.participant_id
                INNER JOIN custom_bouts C on C.id = B.custom_bouts_id
                where P.external_coach_code=".$external_coach_code." and P.competition_id=".$compModel->id."
            ) as X
            Order by X.session, X.team, X.gender, X.bout_number,  X.Result_seq
            "
        );

        if($download_type == "result") {
            $pdf = PDF::loadView('admin/bout/download_result',array(
                'decrypted_comp_id'=> $decrypted_comp_id,
                'compModel' => $compModel,
                'coach' => $coach[0],
                'participants' => $participants,
            ))->setPaper('a4', 'landscape');
            return $pdf->download('CompetitionResult_'.$external_coach_code.'.pdf');
        } else if($download_type == "cards") {
            $this->generate_cards_for_coach($compModel, $coach[0], $participants);
            // return $pdf->download('CompetitionCards_'.$external_coach_code.'.pdf');
        } else if($download_type == "certificate") {
            $this->generate_certificate_for_coach($compModel, $coach[0], $participants);
            // return $pdf->download('CompetitionCertificate_'.$external_coach_code.'.pdf');
        }
        

        // return View('admin.bout.download_result',compact('decrypted_comp_id'))
        // ->with('compModel',$compModel)
        // ->with('coach', $coach[0])
        // ->with('participants',$participants)
        // // ->with('result_data',$result_data)
        // // ->with('coach_list',$coach_list)
        // ;
    }

    public function generate_certificate_for_coach($compModel, $coach, $participants) {

        $outputFileList=array();
        foreach($participants as $key=>$participant) {
            list($fpdi, $outputFilePath) = $this->generate_certificate($compModel, $coach,$participant);
            $fpdi->Output($outputFilePath, 'F');
            array_push($outputFileList,$outputFilePath);
        }

        $pdf = new FPDI();

        // iterate through the files
        foreach ($outputFileList AS $file) {
            // get the page count
            $pageCount = $pdf->setSourceFile($file);
            // iterate through all pages
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                // import a page
                $templateId = $pdf->importPage($pageNo);
                // get the size of the imported page
                $size = $pdf->getTemplateSize($templateId);
                $pdf->AddPage($size['orientation'], array($size['width'], $size['height']));
                // use the imported page
                $pdf->useTemplate($templateId);
            }
        }
        $pdf->Output('D', $compModel->id."_". $coach->external_coach_name. ".pdf", 'F');
    }

    public function generate_certificate($compModel, $coach, $participant ) {
        $certificate_left_conf = Config::get('constants.competition_certificate.'.$compModel->comp_id.'.left');
        $certificate_right_conf = Config::get('constants.competition_certificate.'.$compModel->comp_id.'.right');
        $certificate_fields_conf = Config::get('constants.competition_certificate.'.$compModel->comp_id.'.fields');
        $certificate_text_1_conf = Config::get('constants.competition_certificate.'.$compModel->comp_id.'.text_1');

        $certificate_medal_conf = Config::get('constants.competition_certificate.'.$compModel->comp_id.'.'.$participant->medal);

        $certificate_gender_conf = Config::get('constants.competition_certificate.'.$compModel->comp_id.'.'.$participant->gender);

        $fpdi = new FPDI;
        
        $filePath = "competition/template/certificates/".$compModel->comp_id.".pdf";
        $outputFilePath = "competition/tmp/".$compModel->id."_". $coach->external_coach_code. "_". $participant->id. ".pdf";
        
        $count = $fpdi->setSourceFileWithParserParams($filePath);
  
        for ($i=1; $i<=$count; $i++) {
  
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);
              
            $fpdi->SetFont("arial", "b", 13);
              
            foreach($certificate_left_conf as $key=>$left) {
                
                $player_name_left = $left;
                $player_name_right = $certificate_right_conf[$key]; 
                $fpdi->Text($player_name_left,$player_name_right, $participant->{$certificate_fields_conf[$key]}.$certificate_text_1_conf[$key]);
                
            }
            $fpdi->SetFont("arial", "b", 18);
            foreach($certificate_medal_conf as $key=>$val) {
                $fpdi->Text($val[0],$val[1], 'X');
            }
            $fpdi->Text($certificate_gender_conf[0],$certificate_gender_conf[1], 'X');            
        }
        return array( $fpdi, $outputFilePath);
    }

    public function generate_cards_for_coach($compModel, $coach, $participants) {
        
        $participants_count = count($participants);
        $participants_array_chunk = array_chunk($participants,14);

        $outputFileList=array();
        foreach($participants_array_chunk as $key=>$participant) {
            list($fpdi, $outputFilePath) = $this->generate_cards($compModel, $coach,$participant, $key+1);
            $fpdi->Output($outputFilePath, 'F');
            array_push($outputFileList,$outputFilePath);
        }
        // dd($outputFileList);
        $pdf = new FPDI();

        // iterate through the files
        foreach ($outputFileList AS $file) {
            // get the page count
            $pageCount = $pdf->setSourceFile($file);
            // iterate through all pages
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                // import a page
                $templateId = $pdf->importPage($pageNo);
                // get the size of the imported page
                $size = $pdf->getTemplateSize($templateId);
                $pdf->AddPage($size['orientation'], array($size['width'], $size['height']));
                // use the imported page
                $pdf->useTemplate($templateId);
            }
        }
        $pdf->Output('D', "cards_".$compModel->id."_". $coach->external_coach_name. ".pdf", 'F');
    }
    
    public function generate_cards($compModel, $coach, $participants, $page_no) {

        $fpdi = new FPDI;
        
        $filePath = "competition/template/cards/card.pdf";
        $blueImage = "competition/template/cards/blue.png";
        $redImage = "competition/template/cards/red.png";
        
        $count = $fpdi->setSourceFileWithParserParams($filePath);
        // $count = 1;
        
        $outputFileList=array();
        
        $outputFilePath = "competition/tmp/".$compModel->id."_". $coach->external_coach_code. "_". $page_no. ".pdf";
        
        $certificate_text_1_conf = Config::get('constants.competition_certificate.'.$compModel->comp_id.'.text_1');

        $competition_logo_left = Config::get('constants.competition_card.competition_logo_left');
        $competition_logo_right = Config::get('constants.competition_card.competition_logo_right');
        
        $competition_name_left = Config::get('constants.competition_card.competition_name_left');
        $competition_name_right = Config::get('constants.competition_card.competition_name_right');

        $competition_image_left = Config::get('constants.competition_card.competition_image_left');
        $competition_image_right = Config::get('constants.competition_card.competition_image_right');

        $competition_part_name_left = Config::get('constants.competition_card.competition_part_name_left');
        $competition_part_name_right = Config::get('constants.competition_card.competition_part_name_right');
        $competition_name_text_1 = Config::get('constants.competition_card.'.$compModel->comp_id.'.competition_name_text_1');
        $competition_name_text_2 = Config::get('constants.competition_card.'.$compModel->comp_id.'.competition_name_text_2');

        $competition_part_name_size = Config::get('constants.competition_card.'.$compModel->comp_id.'.competition_part_name_size');

        $competition_date = Config::get('constants.competition_card.'.$compModel->comp_id.'.competition_date');
        $competition_vanue = Config::get('constants.competition_card.'.$compModel->comp_id.'.competition_vanue');
        
        for ($i=1; $i<=$count; $i++) {
  
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            
            $fpdi->useTemplate($template);

            $fpdi->AddFont('ariblk', '', 'ariblk.php');
            
            $fpdi->SetFont("arial", "b", 13);

            foreach($participants as $key=>$participant) {

                $diff = fmod($key,2) == 0 ? 0 : 150;
                $sequence = floor($key/2);
                // Display Card
                if ($participant->session == "MORNING") {
                    $fpdi->Image($blueImage ,$diff + 5 ,($sequence * 65) + 5, 120, 60, "PNG");
                } else {
                    $fpdi->Image($redImage ,$diff + 5 ,($sequence * 65) + 5, 120, 60, "PNG");
                }

                // Display Image
                $fpdi->Rect($competition_image_left + $diff, ($sequence * 65) + $competition_image_right, 27, 27);
                $fpdi->Image($this->get_image_url($participant->external_unique_id) ,$competition_image_left + $diff + 1, ($sequence * 65) + $competition_image_right + 1, 25, 25);
                
                $fpdi->Image('competition/logo.png' , $competition_logo_left + $diff , ($sequence * 65) + $competition_logo_right, 18, 18, "PNG");
                
                $fpdi->SetTextColor(255,255,255);
                $fpdi->SetFont("ariblk", "", $competition_part_name_size);
                
                $fpdi->Text($competition_name_left + $diff, ($sequence * 65) + $competition_name_right, $competition_name_text_1, 'C');
                $fpdi->Text($competition_name_left + $diff - 5, ($sequence * 65) + $competition_name_right + 7, $competition_name_text_2, 'C');

                $fpdi->SetFont("arial", "b", 10);
                
                $fpdi->Text($competition_name_left + 15 + $diff, ($sequence * 65) + $competition_name_right + 12, $competition_date, 'C');

                $fpdi->SetTextColor(0,0,0);
                $fpdi->SetFont("arial", "b", 10);
                $fpdi->Text($competition_name_left + $diff - 15, ($sequence * 65) + $competition_name_right + 17, $competition_vanue, 'C');

                $fpdi->SetFont("arial", "b", 11);
                $fpdi->Text($competition_part_name_left + $diff, ($sequence * 65) + $competition_part_name_right, 'Name:', 'c');
                
                $fpdi->SetFont("arial", "bu", 11);
                $fpdi->Text($competition_part_name_left + $diff + 20, ($sequence * 65) + $competition_part_name_right, $participant->full_name, 'c');

                $fpdi->SetFont("arial", "b", 11);
                $fpdi->Text($competition_part_name_left + $diff , ($sequence * 65) + $competition_part_name_right + 6, 'Bout No:', 'c');
                $fpdi->SetFont("arial", "bu", 11);
                $fpdi->Text($competition_part_name_left + $diff + 20 , ($sequence * 65) + $competition_part_name_right + 6, $participant->bout_number, 'c');

                $fpdi->SetFont("arial", "b", 11);
                $fpdi->Text($competition_part_name_left + 50 + $diff, ($sequence * 65) + $competition_part_name_right + 6, 'Tatami No:', 'c');
                $fpdi->SetFont("arial", "bu", 11);
                $fpdi->Text($competition_part_name_left + 70 + $diff, ($sequence * 65) + $competition_part_name_right + 6,$participant->tatami, 'c');
                
                // $fpdi->SetFont("arial", "b", 13);
                if ($participant->session == "MORNING") {
                    $fpdi->Text($competition_part_name_left + $diff + 10, ($sequence * 65) + $competition_part_name_right + 20, 'Morning Session (Before Lunch)', 'c');
                } else {
                    $fpdi->Text($competition_part_name_left + $diff + 10, ($sequence * 65) + $competition_part_name_right + 20, 'Afternoon Session (Post Lunch)', 'c');
                }
            }           
        }
        return array( $fpdi, $outputFilePath);
        // $fpdi->Output('D', "cards_".$compModel->id."_". $coach->external_coach_name. ".pdf", 'F');
    }
    
    function get_image_url($part_comp_id) {
        $fileSql = "
            SELECT F.name 
            FROM PART_COMPETITION PC
            INNER JOIN files F on F.k_id = PC.KARATE_KA_ID
            WHERE PC.PART_COMP_ID = $part_comp_id
        ";
        $dataObj = collect(DB::connection('rksys_app')->select($fileSql))->first();

        if ($dataObj) {
            $response_code = $this->get_http_response_code(Config::get('constants.avatar_url').'/'.$dataObj->name);
            if($response_code == "200") {
                return Config::get('constants.avatar_url').'/'.$dataObj->name;
            } else {
                return 'competition/NoImageFound.png';
            }
        } else {
            return 'competition/NoImageFound.png';
        }
    }

    function get_http_response_code($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }
}