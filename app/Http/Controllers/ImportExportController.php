<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Exports\CompDataExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CompDataImport;


class ImportExportController extends Controller
{
    /**
    * 
    */
    public function importExportView()
    {
       return view('importexport');
    }
    public function export() 
    {
        return Excel::download(new CompDataExport(56), 'bulkData.xlsx');
    }
    public function import(Request $request){
        Excel::import(new CompDataImport, 
                      $request->file('file')->store('files'));
        return redirect()->back();
    }
}