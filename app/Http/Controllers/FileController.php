<?php

namespace App\Http\Controllers;

use DirectoryIterator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\Upload;
use XmlValidator\XmlValidator;
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
//        $storageDestinationPath=  $path . $userId . '/' . $folderName ;
//        $xml = $path . $userId .  '/upload/' . $folderName . '/BDOT10k/PL.PZGiK.330.1425__OT_ADJA_A.xml';
        $xsd = $path . $userId .  '/upload/' . $folderName . '/BDOT10kGML/OT_BDOT10k_BDOO.xsd';
        $storageDestinationPath=  $path . $userId . '/upload' ;



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
//            return back()
//                ->with('success','You have successfully upload and extract zip.');
        }

//        https://github.com/seromenho/XmlValidator
//        https://stackoverflow.com/questions/16407930/how-to-get-value-of-an-attribute-with-namespace

//        $xml = "<sample>my xml string</sample>";
//        $xsd = "path_to_xsd_file.xsd";
//        var_dump($dir); die();


        $raport = file_get_contents(public_path('raport.html'));
        $raport = str_replace('NAZWA_ZBIORU',$folderName, $raport);
        file_put_contents($path . $userId .  '/upload/Raport.html', $raport);


// Validate
        $xmlValidator = new XmlValidator();

        $dir = new DirectoryIterator($path . $userId .  '/upload/' . $folderName . '/BDOT10k/');
        foreach ($dir as $fileinfo) {


            if (!$fileinfo->isDot()) {
//                var_dump($fileinfo->getpathName()); die();
//                $xml =  file_get_contents($path . $userId .  '/upload/' . $folderName . '/BDOT10k/PL.PZGiK.330.1425__OT_ADJA_A.xml');
                $xml =  file_get_contents($fileinfo->getpathname());
                try{

                    $xmlValidator->validate($xml,$xsd);
                    $newArray = array();

                    // Check if is valid
                    if(!$xmlValidator->isValid()){

                        // Do whatever with the errors.
                        foreach ($xmlValidator->errors as $error) {
//                    echo sprintf('[%s %s] %s (in %s - line %d, column %d)',
//                        $error->level, $error->code, $error->message,
//                        $error->file, $error->line, $error->column
//                    );
                            $newArray[] = $error;

                        }

//var_dump($newArray); die();
                        return back()->with('message', 'nie');
//                return back()->with('message', 'Your file is bad');
                    }
                } catch (\InvalidArgumentException $e){
                    // catch InvalidArgumentException
                }
            }



        }
//die();





        return back()->with('message', 'Your file is valid');
    }




}
