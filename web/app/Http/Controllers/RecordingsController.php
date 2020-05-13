<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;

class RecordingsController extends Controller
{

    public function showRecordingsPage()
    {
        $recordingsPath = app('path') . '/../public/recordings/';
        $audioFiles = new Collection(
            array_diff(scandir($recordingsPath), array('.', '..'))
        );
        return view('recordings')->with('recordings', $audioFiles);
    }

}
