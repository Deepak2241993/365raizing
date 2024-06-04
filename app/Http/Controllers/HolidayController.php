<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Company;

use Illuminate\Http\Request;
use Mail;
use Auth;
use Session;
use Validator;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Holiday::where('holidays.is_deleted', 0)
        ->join('companies', 'holidays.company_id', '=', 'companies.id')
        ->select('holidays.*', 'companies.compname as company_name')
        ->orderBy('holidays.id', 'DESC')
        ->paginate(10);
        return view('admin.holiday.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company=Company::where('status',1)->where('is_deleted',0)->get();
        return view('admin.holiday.create',compact('company'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Holiday $holiday)
    {
       // Retrieve all input data from the request
       $data = $request->all();
       // Concatenate the date and holidays fields with a pipe separator
       $data['date'] = implode("|", $request->date);
       $data['holidays'] = implode("|", $request->holidays);
   
       // Initialize an array to store the names of the days
       $days = array();
   
       // Iterate through each date to get the day name
       foreach ($request->date as $value) {
           // Get the day name from the date
           $day_name = date('l', strtotime($value));
   
           // Add the day name to the days array
           array_push($days, $day_name);
       }
   
       // Concatenate the day names with a pipe separator and add to the data array
       $data['day'] = implode("|", $days);
   
       // Update the holiday with the new data
       $holiday->create($data);
   
       // Redirect to the holiday index route with a success message
       return redirect(route('holiday.index'))->with('message', 'Holiday Created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function show(Holiday $holiday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function edit(Holiday $holiday)
    {
        $company=Company::where('status',1)->where('is_deleted',0)->get();
        return view('admin.tasks.create',compact('holiday','company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Holiday $holiday)
    {
        // Retrieve all input data from the request
        $data = $request->all();
    
        // Concatenate the date and holidays fields with a pipe separator
        $data['date'] = implode("|", $request->date);
        $data['holidays'] = implode("|", $request->holidays);
    
        // Initialize an array to store the names of the days
        $days = array();
    
        // Iterate through each date to get the day name
        foreach ($request->date as $value) {
            // Get the day name from the date
            $day_name = date('l', strtotime($value));
    
            // Add the day name to the days array
            array_push($days, $day_name);
        }
    
        // Concatenate the day names with a pipe separator and add to the data array
        $data['day'] = implode("|", $days);
    
        // Update the holiday with the new data
        $holiday->update($data);
    
        // Redirect to the holiday index route with a success message
        return redirect(route('holiday.index'))->with('message', 'Holiday updated successfully');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function destroy(Holiday $holiday)
    {
        if($holiday->update(['is_deleted'=>1]))
        {
            $response = array('success' => true, 'error' => false, 'message' => 'Data Delete successfully..');
        }
    else{
        $response = array('success' => false, 'error' => true, 'message' => 'Something Went Wrong !');
            }
    return $response;
    }


    function holidayview(Request $request,$id){
      $holiday= Holiday::find($id);
      $day=explode("|",$holiday->day);
      $date=explode("|",$holiday->date);
      $holidays=explode("|",$holiday->holidays);
      return view('admin.holiday.holidayview',compact('day','date','holidays'));

    }
}