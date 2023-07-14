<?php

namespace App\Http\Controllers;

use App\Models\table_no_antrian;
use Illuminate\Http\Request;
use Carbon;

class clientController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function page_konsumen()
    {

        return view('client/client');  
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        // table_no_antrian::create([
        //     'no_antrian'=>$request->no_antrian,      //CREATE VALUE DATABASE
        //     'jenis'=>$request->jenis,
        //    'status'=>$request->status,
        //    'time'=>$request->time,
        //    'counter'=>$request->counter
        // ]);
        // return redirect('client/client');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }

    public function date(){
       
    }
    
    public function print_Laptop(){
        return view('/cetak_no/cetak_laptop');
    }
    public function print_Gadget(){
        return view('/cetak_no/cetakGadget');
    }
     public function print_CPU(){
        return view('/cetak_no/cetakCPU');
    }
     public function print_Printer(){
        return view('/cetak_no/cetakPrinter');
    }
}
