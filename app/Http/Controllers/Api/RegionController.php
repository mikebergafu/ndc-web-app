<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Region;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store()
    {
        Region::create($this->getValidateRequest());
    }

    /**
     * Display the specified resource.
     *
     * @param Region $region
     * @return Response
     */
    public function show(Region $region)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Region $region
     * @return Response
     */
    public function edit(Region $region)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param Region $region
     * @return Response
     */
    public function update(Region $region)
    {
       $region->update($this->getValidateRequest());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Region $region
     * @return Response
     */
    public function destroy(Region $region)
    {
        $region->delete();
    }

    /**
     * @return mixed
     */
    protected function getValidateRequest()
    {
        return request()->validate([
            'code' => 'required',
            'description' => 'required',
        ]);
    }
}
