<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrdersCreatesController extends Controller
{
    //
    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }
    public function readcsv(Request $request)
    {
        echo "heelo";
        // print_r($request);
        if ($request->file("file")) {
            $file = $request->file('file');
            $customerArr = $this->csvToArray($file);
            dd($customerArr[0]['Phone']);
        } else {
            echo "file not uploaded";
        }
        die();
    }
}
