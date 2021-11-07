<?php

namespace App\Http\Controllers;

use App\Services\CommissionService;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    protected $weeklyOperations = [];

    public function upload(Request $request){
        $request->validate([
            'csv_file' => 'required|max:1024',
        ]);
        $file = $request->file('csv_file');

        $fileName = time() . '.csv';
        $file->move(public_path('uploads'), $fileName);
        $commissionService = new CommissionService();

        $data = $this->readCsv(public_path('uploads') . "/" . $fileName);
        if(isset($data['error'])){
            return redirect('/')->with('error', $data['error']);
        }
        $commissions = $commissionService->generateCommission($data);
        return redirect('/')->with('commissions', $commissions);
    }

    private function readCsv($filename = '', $delimiter = ','){
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if(count($row) != env('TOTAL_COLUMNS_IN_CSV', 6)){
                    return ['error' => 'CSV file is not well formatted'];
                }
                array_push($data, $row);
            }
            fclose($handle);
        }

        return $data;
    }

}
