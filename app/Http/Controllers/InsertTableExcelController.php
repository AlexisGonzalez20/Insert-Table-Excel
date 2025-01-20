<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class InsertTableExcelController extends Controller
{

    protected  $tablename;
    protected  $columns;
    protected  $row;
    protected  $excel;

    public function __construct(string $tablename, array $columns, int $row, object $excel) 
    {

        /**
         * Insert excel data into a database table.
         *
         * @param string $name of the table where the data is inserted
         * @param array  $order of columns to insert
         * @param int    $number from where the Excel data starts
         * @param file   $excel file from which the data to be inserted is extracted
         */

        $this->tablename = $tablename;
        $this->columns = $columns;
        $this->row = $row;
        $this->excel = $excel;

    }

    // Main function in charge of executing all the logic.

    public function Insert()
    {
        if(count($this->columns) !== count($this->ExcelToArray()[0][0]))
        {

            return response()->json([
                'msg' => 'the number of columns do not match',
            ], 200);

        }
        else{

            $this->InsertToTable();

            return response()->json([
                'msg' => 'inserted successfully',
            ], 200);

        }
    }

    // Insert the extracted information into the database.
  
    protected function InsertToTable()
    {

        DB::table($this->tablename)->insert(
            $this->PrepareArray()
        );

    }

    // Convert excel matrix to acceptable format using query builder

    protected function PrepareArray()
    {

        $Array = [];
        $i = 1;

        foreach($this->ExcelToArray()[0] as $ar){

            if($i >= $this->row){
                array_push($Array ,$this->PrepareData($ar));
            }
            $i++;
        }

        return $Array;
    }

    // Prepare information column by column in Excel

    protected function PrepareData(array $array)
    {
        $TempArray = [];

        for($i = 0; $i < count($this->columns); $i++){

            $TempArray[$this->columns[$i]] = $array[$i];

        }

        return $TempArray;
    }

    // Convert Excel information into a matrix

    protected function ExcelToArray()
    {

        return Excel::toArray([], $this->excel->file('excel'));

    }
}
