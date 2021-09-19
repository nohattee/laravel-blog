<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use App\Http\Resources\FileCollection;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $files = File::query();
        if ($request->input('filters')) {
            $filters = json_decode($request->input('filters'), true);
            $files = $files->filter($filters);
        }

        if ($request->input('page')) {
            $pageSize = $request->input('page_size', 10);
            $files = $files->paginate($pageSize);
        } else {
            $files = $files->get();
        }
        
        return new FileCollection($files);
    }

    /**
     * Upload files.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $paramFiles = $request->file('files');

        foreach ($paramFiles as $f) {
            $path = $f->store('upload');
            $file = new File();
            $file->filename = $f->getClientOriginalName();
            $file->mime_type = $f->getMimeType();
            $file->size = $f->getSize();
            $file->path = $path;
            $file->url = Storage::url($path);
            $file->save();
        }

        return response()->json([
            'message' => 'upload_success',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
