<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;


class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::all();
        return view('document.index',compact('documents'));
    }

    public function store(Request $request)
    {
        $file = $request->file('file');

        $dir = $file->extension() == 'pdf' ? 'documents' : 'images';
        $path = Storage::disk('documents')->put($dir, $file);

        $document = Document::create([
            'file_path' => $path
        ]);

        $document->addMediaFromRequest('file')
            ->toMediaCollection('documents');



        return redirect()->back();
    }

   public function __invoke()
    {
        return view('upload.large');
    }

    public function uploadFileChunk(Request $request)
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (!$receiver->isUploaded()) {
            dd('file not uploaded', $request->all(), $request->files);
        }

        $fileReceived = $receiver->receive(); // receive file chunk
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded

            list($fileName, $path) = $this->finalizeUpload($fileReceived);
            return [
                'path' => asset('storage/' . $path),
                'filename' => $fileName
            ];
        }

        // otherwise return percentage information
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
    }

    /**
     * @param \Pion\Laravel\ChunkUpload\Save\AbstractSave|bool $fileReceived
     * @return array
     */
    public function finalizeUpload(\Pion\Laravel\ChunkUpload\Save\AbstractSave|bool $fileReceived): array
    {
        $file = $fileReceived->getFile(); // get file
        $extension = $file->getClientOriginalExtension();
        $fileName = str_replace('.' . $extension, '', $file->getClientOriginalName()); //file name without extenstion
        $fileName .= '_' . md5(time()) . '.' . $extension; // a unique file name

        $disk = Storage::disk('public');
        $path = $disk->putFileAs('video_test', $file, $fileName);

        // delete chunked file
        @unlink($file->getPathname());

        return array($fileName, $path);
    }




}
