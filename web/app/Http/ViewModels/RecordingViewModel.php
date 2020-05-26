<?php


namespace App\Http\ViewModels;


class RecordingViewModel extends ViewModel
{

    public $name;

    public $filename;

    public $filesize;

    public $created_at;

    public $timestamp;

    public function __construct(\SplFileInfo $file)
    {
        $this->format($file);
    }

    private function format(\SplFileInfo $file)
    {
        $this->name = rtrim($file->getFilename(), '.' . $file->getExtension());
        $this->filename = rtrim($file->getFilename());
        $this->filesize = $file->getSize() / 1024;
        $this->timestamp = $file->getMTime();
        $this->created_at = date(self::TIME_SECS_FORMAT . ' ' . self::DATE_FORMAT, $this->timestamp);

    }
}
