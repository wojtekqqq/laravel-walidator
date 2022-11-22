<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Upload;
use ZipArchive;

class FileController extends Controller
{
    public function index()
    {
        return view('home');
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required:max:255',
            'overview' => 'required',
            'price' => 'required|numeric'
        ]);

        auth()->user()->files()->create([
            'title' => $request->get('title'),
            'overview' => $request->get('overview'),
            'price' => $request->get('price')
        ]);
        return back()->with('message', 'Your file is submitted Successfully');
    }
    public function upload(Request $request)
    {
        $userId = $request->user()->id;
        $uploadedFile = $request->file('file');
        $filename = time().$uploadedFile->getClientOriginalName();

//        Storage::disk('local')->putFileAs(
//            'files/' . $userId,
//            $uploadedFile,
//            $filename
//        );

        Storage::putFileAs('' . $userId , $uploadedFile,$filename);


        $path = Storage::path('' );
        $storageDestinationPath = Storage::disk('local')->path($userId . '/' . $uploadedFile->getClientOriginalName());
        $folderName = pathinfo($storageDestinationPath, PATHINFO_FILENAME);
        $storageDestinationPath=  $path . $userId . '/' . $folderName ;
        $upload = new upload;
        $upload->filename = $filename;
        $upload->user()->associate(auth()->user());
        $upload->save();

        if ($request->wantsJson()) {
            return response()->json([
                'id' => $upload->id
            ]);
        }

        $zip = new ZipArchive();

        $status = $zip->open($path . $userId . '/' . $filename);

        if ($status !== true) {
            throw new \Exception($status);
        }
        else{
            Storage::deleteDirectory($storageDestinationPath);
            $zip->extractTo($storageDestinationPath);
            $zip->close();
            return back()
                ->with('success','You have successfully upload and extract zip.');
        }



//        return back()->with('message', 'Your file is submitted Successfully');
    }
}
