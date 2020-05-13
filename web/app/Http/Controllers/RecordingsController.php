<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RecordingsController extends Controller
{

    const RECORDING_FILE_EXT = ".ogg";

    /**
     * Displays the list of audio recordings.
     * @return \Illuminate\View\View
     */
    public function showRecordingsPage()
    {
        $recordingsPath = app('path') . '/../public/recordings/';

        $audioFiles = new Collection();
        $filesInDirectory = array_diff(scandir($recordingsPath), array('.', '..'));

        foreach ($filesInDirectory as $file) {
            $audioFiles->add(new \SplFileInfo($file));
        }

        return view('recordings')->with('recordings', $audioFiles);
    }

    /**
     * Downloads the audio recording file.
     * @param string $file The filename (without the file extension)
     * @return bool|BinaryFileResponse
     */
    public function downloadAudioFile($file)
    {
        if (file_exists(app('path') . '/../public/recordings/' . $file . self::RECORDING_FILE_EXT)) {
            return response()->download($file);
        }
        return response()->isNotFound();
    }

    /**
     * Deletes an audio recording file.
     * @param string $file The filename (without the file extension)
     * @return bool|BinaryFileResponse
     */
    public function deleteAudioFile($file)
    {
        $filePath = app('path') . '/../public/recordings/' . $file . self::RECORDING_FILE_EXT;
        if (file_exists($filePath)) {
            if (!unlink($filePath)) {
                return response()->isServerError();
            }
            return response()->isSuccessful();
        }
        return response()->isNotFound();
    }

}
