<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExternalCompetition;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon; 
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExternalCompDataExport;
use App\Imports\ExternalCompDataImport;
use App\Models\ExternalBoutTempExcel;
use App\Models\ExternalBouts;
use App\Models\ExternalBoutParticipantDetail;
use Config;
use setasign\Fpdi\Fpdi;
use App\Models\FilesModel;
use App\PdfRotate;
use PDF;

class ExternalBoutGenerationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private  $dt;

    public function __construct()
    {
        $this->dt = Carbon::now();
    }
    
    public function index()
    {
        return View('admin.external_bout.index');
    }

    public function report(Request $request)
    {
        $external_competition = ExternalCompetition::all();

        return View('admin.external_bout.report',compact('external_competition'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('admin.external_bout.create');
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
        $dataObj = new ExternalCompetition();
        $dataObj->short_description = $request->short_description;
        $dataObj->name = $request->name;
        $dataObj->additional_details = $request->additional_details;

        $dataObj->start_date = $request->start_date;
        $dataObj->end_date = $request->end_date;
        $dataObj->user_id =  Auth::user()->id;

        $dataObj->last_modified = \Carbon\Carbon::now();
        $dataObj->last_modified_user_id = Auth::user()->id;

        $dataObj->save();

        return response([
            'data' => $dataObj,
            'message' => 'ExternalCompetition Inserted successfully',
            'alert-type' => 'success'
        ], 200);
    }

    
    public function boardIndex($external_comp_id)
    {
        $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();

        return View('admin.external_bout.board.index')
        ->with('external_comp_id',$external_comp_id)
        ->with('external_competition',$external_competition);
    }

    public function boardReport(Request $request, $external_comp_id)
    {
        $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();

        return View('admin.external_bout.board.report')
        ->with('external_comp_id',$external_comp_id)
        ->with('external_competition',$external_competition);
    }


    public function exportExcel($external_comp_id)
    {
        $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();
        return Excel::download(new ExternalCompDataExport($external_competition->id), 'ExternalCompetition_'.$external_competition->id.'.xlsx');
    }

    public function importExcel($external_comp_id, Request $request)
    {
        $details_key = $request->query('details_key');

        return View('admin.external_bout.board.import_excel',compact('details_key'))
        ->with('external_comp_id',$external_comp_id);
    }

    public function postImportExcel($external_comp_id, Request $request) {
        $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();

        $data = ExternalBoutTempExcel::where('external_competition_id',$external_comp_id)->delete();
        $bouts = ExternalBouts::where('external_competition_id',$external_comp_id)->delete();
        $boutParticipantDetails = ExternalBoutParticipantDetail::where('external_competition_id',$external_comp_id)->delete();

        Excel::import(new ExternalCompDataImport($external_competition->id), 
                      $request->file('file')->store('files'));

        $excelRecords = ExternalBoutTempExcel::where('external_competition_id',$external_comp_id)
        ->orderBy('gender')
        ->orderBy('category')
        ->get();

        $participant_cnt = 1;

        foreach($excelRecords as $records) {
            $boutData = ExternalBouts::where('external_competition_id', $external_comp_id)
                ->where('gender',$records->gender)
                ->where('category',$records->category)
                ->first();
            
            if($boutData) {
                $participant_cnt = $participant_cnt + 1;

            } else {
                $boutData = new ExternalBouts();
                $boutData->external_competition_id = $external_comp_id;
                $boutData->gender = $records->gender;
                $boutData->category = $records->category;
                $boutData->age_category = $records->age_category;
                $boutData->weight_category = $records->weight_category;
                $boutData->rank_category = $records->rank_category;
                $boutData->tatami = $records->tatami;
                $boutData->session = $records->session;
                $boutData->bout_number= $records->bout_number;
                $boutData->user_id= Auth::user()->id;
                $boutData->save();
                $participant_cnt = 1;
            }

            $compBoutParticipantDetail = new ExternalBoutParticipantDetail();
            $compBoutParticipantDetail->external_competition_id = $external_comp_id;
            $compBoutParticipantDetail->bout_id = $boutData->id;
            $compBoutParticipantDetail->full_name = $records->full_name;
            $compBoutParticipantDetail->gender = $records->gender;
            $compBoutParticipantDetail->team = $records->team;
            $compBoutParticipantDetail->coach_name = $records->coach_name;
            $compBoutParticipantDetail->rank = $records->rank;
            $compBoutParticipantDetail->age = $records->age;
            $compBoutParticipantDetail->weight = $records->weight;
            $compBoutParticipantDetail->participant_sequence = $participant_cnt;
            $compBoutParticipantDetail->user_id = Auth::user()->id;
            $compBoutParticipantDetail->save();
        }
        return response([
            'data' => '',
            'message' => 'Excel Imported successfully',
            'alert-type' => 'success'
        ], 200);
    }

    public function data_table($external_comp_id)
    {
        $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();
        
        return View('admin.external_bout.data_table',compact('external_comp_id'))
        ->with('external_competition',$external_competition);
    }

    public function data_table_report($external_comp_id)
    {
        $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();

        $participants_records = DB::table("external_participants")    
        ->where('external_participants.external_competition_id',$external_comp_id)
        ->leftJoin("external_bouts", function($join) {
            $join->on("external_bouts.id", "=", "external_participants.bout_id");
        })
        ->select(
            "external_participants.id",
            "external_participants.full_name",
            "external_participants.gender",
            "external_participants.team",
            "external_participants.coach_name",
            "external_participants.rank",
            "external_participants.age",
            "external_participants.weight",
            "external_bouts.category",
            "external_bouts.age_category",
            "external_bouts.weight_category",
            "external_bouts.rank_category",
            "external_bouts.tatami",
            "external_bouts.session",
            "external_bouts.bout_number",
            )
        ->orderBy('external_bouts.id')
        ->get();

        return View('admin.external_bout.data_table_report',compact('external_comp_id'))
        ->with('participants_records',$participants_records);
    }

    public function board_list_index($external_comp_id)
    {
        $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();
        
        return View('admin.external_bout.bout.index',compact('external_comp_id'))
        ->with('external_competition',$external_competition);
    }

    public function board_list_report($external_comp_id)
    {
        $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();

        $bout_records = DB::table("external_participants")    
        ->where('external_participants.external_competition_id',$external_comp_id)
        ->join("external_bouts", function($join) {
            $join->on("external_bouts.id", "=", "external_participants.bout_id");
        })
        ->select("external_bouts.id", "external_bouts.category", "external_bouts.gender",
            DB::raw('count(*) as participant_count') 
        )
        ->groupBy('external_bouts.id')
        ->groupBy('external_bouts.category')
        ->groupBy('external_bouts.gender')
        ->orderBy('external_bouts.id')
        ->get();
        
        return View('admin.external_bout.bout.report',compact('external_comp_id'))
        ->with('bout_records',$bout_records);
    }

    public function board_list_index_participants($external_comp_id, $bout_id)
    {
        $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();

        $participants_records = DB::table("external_participants")    
        ->where('external_participants.external_competition_id',$external_comp_id)
        ->where('external_participants.bout_id',$bout_id)
        ->join("external_bouts", function($join) {
            $join->on("external_bouts.id", "=", "external_participants.bout_id");
        })
        ->select(
            "external_participants.id",
            "external_participants.full_name",
            "external_participants.gender",
            "external_participants.team",
            "external_participants.coach_name",
            "external_participants.rank",
            "external_participants.age",
            "external_participants.weight",
            "external_bouts.category",
            "external_bouts.age_category",
            "external_bouts.weight_category",
            "external_bouts.rank_category",
            "external_bouts.tatami",
            "external_bouts.session",
            "external_bouts.bout_number",
            "external_participants.participant_sequence",
            "external_participants.id as participants_id"
        )
        ->orderBy('external_participants.participant_sequence')
        ->get();
        $boutObj = ExternalBouts::find($bout_id);
            
        // dd($participants_records);
        return View('admin.external_bout.bout.participants',compact('external_comp_id'))
        ->with('participants_records',$participants_records)
        ->with('boutObj',$boutObj);
    }

    public function board_list_index_karate_ka($external_comp_id, $bout_id, $participant_id)
    {
        $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();
        $boutObj = ExternalBouts::find($bout_id);

        $participants = DB::table("external_participants")    
        ->where('external_participants.external_competition_id',$external_comp_id)
        ->where('external_participants.id',$participant_id)
        ->select("external_participants.*")
        ->first();

        // dd($participants);
        
        return View('admin.external_bout.bout.karate_ka',compact('external_comp_id'))
        ->with('participants',$participants)
        ->with('bout_id',$bout_id) 
        ->with('details_key','result_details')
        ->with('boutObj',$boutObj);
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
        $fpdi->Text($left_2,$top - 2,ucwords(strtolower($player_data->coach_name)));
        $fpdi->Text($left_2,$top + 2,ucwords(strtolower($player_data->team)));
    }


    public function board_list_download_all_bout($external_comp_id) 
    {
        $bout_records = ExternalBouts::where('external_competition_id',$external_comp_id)
        ->orderBy('id')
        ->get();

        $outputFileList=array();
        
        foreach($bout_records as $key=>$rec) {
            list($fpdi, $outputFilePath) = $this->generate_bout($external_comp_id, $rec->id);
            
            $fpdi->Output($outputFilePath, 'F');
            $BoutPdf = new PdfRotate;
            $outputFilePathNew = "competition/tmp/".$external_comp_id."_". $rec->id."_new_1.pdf";
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
        $pdf->Output('D', $external_comp_id.".pdf", 'F');
    }

    public function generate_bout($external_comp_id, $bout_id)
    {
        $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();

        $participants_records = DB::table("external_participants")    
        ->where('external_participants.external_competition_id',$external_comp_id)
        ->where('external_participants.bout_id',$bout_id)
        ->join("external_bouts", function($join) {
            $join->on("external_bouts.id", "=", "external_participants.bout_id");
        })
        ->select("external_participants.*")
        ->orderBy('external_participants.participant_sequence')
        ->get();
        $bout_record = ExternalBouts::find($bout_id);

        $player_count = count($participants_records);
        $player_conf = Config::get('constants.competition.'.$player_count);

        // dd($player_conf);

        $fpdi = new FPDI;
        
        $filePath = "competition/template/".$player_count.".pdf";
        $outputFilePath = "competition/tmp/".$external_comp_id."_". $bout_record->bout_number.".pdf";
        
        $count = $fpdi->setSourceFileWithParserParams($filePath);
  
        for ($i=1; $i<=$count; $i++) {
  
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);
              
            $fpdi->Image("logo.png",10,2, 32, 32);

            $fpdi->SetFont("helvetica", "b", 18);
  
            $left = 50;
            $top = 10;
            $fpdi->Text($left,$top,$external_competition->name);

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
        $fpdi = $this->generate_back_page($fpdi, $bout_record, $external_competition);
        return array( $fpdi, $outputFilePath);
    }

    public function board_list_download_bout($external_comp_id, $bout_id)
    {
        list($fpdi, $outputFilePath) = $this->generate_bout($external_comp_id, $bout_id);
        $fpdi->Output( $outputFilePath, 'F');

        $DownloadBoutPdf = new PdfRotate;
        $outputFilePathNew = "competition/tmp/".$external_comp_id."_". $bout_id."_new.pdf";
        $DownloadBoutPdf->rotatePdfPage($outputFilePath, $outputFilePathNew, $DownloadBoutPdf::DEGREES_270,2);

        $headers = array(
            'Content-Type: application/pdf',
          );

        return response()->download($outputFilePathNew, $external_comp_id."_". $bout_id.".pdf", $headers);
    }

    public function generate_back_page($fpdi, $bout_record, $external_competition)
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
            $fpdi->Text($left,$top,$external_competition->name);

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

    

}