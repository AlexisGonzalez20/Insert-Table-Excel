<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\InsertTableExcelController;

class ExampleController extends Controller
{
    public function create(Request $request)
    {
         /**
         * Insert excel data into a database table.
         *
         * @param string $name of the table where the data is inserted
         * @param array  $order of columns to insert
         * @param int    $number from where the Excel data starts
         * @param file   $excel file from which the data to be inserted is extracted
         */

        //Practical example of how to use the class.

        $class = new InsertTableExcelController(
            $request->tablename,
            $request->columns,
            $request->row,
            $request
        );

        return $class->Insert();
    }
}
