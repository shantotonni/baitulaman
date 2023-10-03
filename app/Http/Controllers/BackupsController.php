<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupsController extends Controller
{
    public function index()
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        $files = $disk->files(config('backup.backup.name'));
        $backups = [];
        foreach ($files as $file) {
            if (substr($file, -4) == '.zip' && $disk->exists($file)) {
                $file_name = str_replace(config('backup.backup.name') . '/', '', $file);
                $backups[] = [
                    'file_path' => $file,
                    'file_name' => $file_name,
                    'file_size' => $this->bytesTopHuman($disk->size($file)),
                    'created_at' => Carbon::parse($disk->lastModified($file))->diffForHumans(),
//                    'download_link' => action([BackupsController::class,'download',[$file_name]])
                ];
            };
        }
        $backups = array_reverse($backups);
        return response()->json([
            'data' => $backups
        ]);
    }

    private function bytesTopHuman($bytes)
    {
        $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . '' . $units[$i];
    }

    public function store()
    {

        try {
            Artisan::call('backup:run',['--only-db' => true]);
            $output = Artisan::output();
            return response()->json([
                $output
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'data' => $exception->getMessage()
            ], 500);
        }
    }
    public function download($file_name){
        $file = config('backup.backup.name') . '/' . $file_name;
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        if ($disk->exists($file)){
            $fs =  Storage::disk(config('backup.backup.destination.disks')[0])->getDriver();
            $stream =$fs->readStream($file);
            return \Response::stream(function () use ($stream){
                fpassthru($stream);
            },200,[
                "Content-Type" => $fs->getMimetype($file),
                "Content-Length" => $fs->getSize($file),
                "Content-disposition" => "attachment; filename=\"".basename($file)."\"",
            ]);
        }


    }
    public function destroy($file_name)
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        if ($disk->exists(config('backup.backup.name') . '/' . $file_name)) {
            $disk->delete(config('backup.backup.name') . '/' . $file_name);
        }
        return redirect()->back()->with('success', 'Successfully deleted');
    }


}
