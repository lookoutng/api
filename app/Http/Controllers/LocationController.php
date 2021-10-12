<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Location;

class LocationController extends Controller
{
    public function store(Request $request){
        
        $datas = $request->validate([
            'lat' => 'required',
            'long' => 'required',
        ]);
        extract($datas);

        $location = Location::create([
            'parent_id' => $request->user()->id,
            'lat' => $lat,
            'long' => $long,
        ]);

        $response = [
            'location' => $location,
            'messsage' => 'location Created Successsfully'

        ];
        return response($response, 201);

    }
    public function show(){
        
    }
}
