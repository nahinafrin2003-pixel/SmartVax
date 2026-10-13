<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\areas;
use App\Models\vaccineReq;
use App\Models\VaccineList; // Import VaccineList model
use Illuminate\Support\Facades\Auth;

class RequestvaccineController extends Controller
{
    /**
     * Display the vaccine request form and the user's current requests.
     */
    public function reqVax()
    {
        // Get data for the form dropdowns
        $areas = areas::all();
        $available_vaccines = VaccineList::where('quantity', '>', 0)->distinct()->get(['vaccine_name']);

        // Get the current user's pending requests
        $my_requests = vaccineReq::where('user_id', Auth::id())->get();

        // Pass all data to the view
        return view('reqVax', compact('areas', 'available_vaccines', 'my_requests'));
    }

    /**
     * Store a new vaccine request.
     */
    public function post_req(Request $request)
    {
        // Validation can be added here if needed
        $data = new vaccineReq;

        $data->user_id = Auth::id();
        $data->Name = $request->Name;
        $data->date = $request->date;
        $data->Vaccine = $request->Vaccine;
        $data->area = $request->area;

        $data->save();

        return redirect()->back()->with('message', 'Your request was submitted successfully!');
    }

    /**
     * Cancel a vaccine request.
     */
    public function cancel_request($id)
    {
        $request = vaccineReq::find($id);

        // Ensure the user can only cancel their own request
        if ($request && $request->user_id == Auth::id()) {
            $request->delete();
            return redirect()->back()->with('message', 'Your request has been cancelled.');
        }

        return redirect()->back()->with('error', 'Could not cancel the request.');
    }
}

