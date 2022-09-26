<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessEmployees;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\Bus;

class UploadController extends Controller
{
    public function index(){
        return view('upload');
    } 

    public function progress(){
        return view('progress');
    } 

    /** 
    *
    * @param Request $request
    * @return void
    */
    public function uploadFileAndStoreInDatabase(Request $request){
        
        try{
            // dd($request->all());
            if( $request->has('csvFile')){
                $fileName = $request->csvFile->getClientOriginalName();
                $fileWithPath = public_path('uploads').'/'.$fileName;
                if(!file_exists($fileWithPath)){
                    $request->csvFile->move( public_path('uploads'), $fileName);
                }

                $header = null; 
                $dataFromCSV = array();
                $records = array_map('str_getcsv', file( $fileWithPath )) ;
                // dd( $records );

                foreach( $records as $record){
                    if( !$header ){
                        $header = $record; 
                    } else{
                        $dataFromCSV[] = $record;
                    }
                }               

                /*  breaking data for example 10k to 1k/300 each  */
                $dataFromCSV = array_chunk($dataFromCSV, 300);
                $batch = Bus::batch( [] )->dispatch();

                foreach ($dataFromCSV as $index => $dataCSV) {
                    foreach( $dataCSV as $data){
                         
                        if(count($header) == count( $data)){
                            $employeeData[$index][] = array_combine($header, $data);
                        }
                        
                        // ProcessEmployees::dispatch( $employeeData[$index] );
                        $batch->add(new ProcessEmployees( $employeeData[$index] ));
                    }
                }
                // dd( $employeeData );
                return $batch;
            }

        }catch(Exception $e){
            Log::info( $e  ) ;
        }
    }
}
