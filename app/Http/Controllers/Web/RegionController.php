<?php

namespace App\Http\Controllers\Web;

use App\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegionController extends Controller
{



    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {

        $regions = Region::latest()->paginate(5);

        return view('regions.index',compact('regions'))

            ->with('i', (request()->input('page', 1) - 1) * 5);

    }


    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        return view('regions.create');

    }


    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

    {

        request()->validate([

            'name' => 'required',

            'detail' => 'required',

        ]);


        region::create($request->all());


        return redirect()->route('regions.index')

            ->with('success','region created successfully.');

    }


    /**

     * Display the specified resource.

     *

     * @param  \App\region  $region

     * @return \Illuminate\Http\Response

     */

    public function show(Region $region)

    {

        return view('regions.show',compact('region'));

    }


    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\region  $region

     * @return \Illuminate\Http\Response

     */

    public function edit(region $region)

    {

        return view('regions.edit',compact('region'));

    }


    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\region  $region

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, Region $region)

    {

        request()->validate([

            'name' => 'required',

            'detail' => 'required',

        ]);


        $region->update($request->all());


        return redirect()->route('regions.index')

            ->with('success','region updated successfully');

    }


    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\region  $region

     * @return \Illuminate\Http\Response

     */

    public function destroy(Region $region)

    {

        $region->delete();


        return redirect()->route('regions.index')

            ->with('success','region deleted successfully');

    }
}
