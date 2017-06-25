<?php

namespace App\Http\Controllers;

use App\File;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * FileController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Order $order
     */
    public function store(Order $order)
    {
        if (request()->hasFile('file')) {
            $path = request()->file('file')->store("files/{$order->id}");
            $file = $order->attachFile($path);
        }
        return $file;
    }

    /**
     * Display the specified resource.
     *
     * @param $order
     * @param $file
     * @return \Illuminate\Http\Response
     */
    public function show($order, $file)
    {
        $filename = "files/{$order}/{$file}";
        if (Storage::exists($filename)) {
            return response()->download(storage_path() ."/app/{$filename}");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        //
    }
}
