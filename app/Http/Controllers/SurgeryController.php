<?php

namespace App\Http\Controllers;

use App\Models\RequestedSurgery;
use App\Models\LocalSurgery;
use Illuminate\Http\Request;

class SurgeryController extends Controller
{
    /**
     * Display a list of surgeries from the external database.
     */
    public function index()
    {
        $surgeries = RequestedSurgery::all();
        return view('theatre.surgeries.requested_surgeries', compact('surgeries'));
    }

    /**
     * Save a specific surgery from the external database to the local one.
    //  */
    // public function saveToLocal(Request $request)
    // {
    //     // Fetch the external record using the provided ID from the request
    //     $externalSurgery = RequestedSurgery::findOrFail($request->input('id'));
        
    //     // Map the fields from the external schema to your local schema
    //     LocalSurgery::create([
    //         'FullName' => $externalSurgery->full_name,
    //         'MRN' => $externalSurgery->patient_number,
    //         'Diagnosis' => 'Awaiting Diagnosis', // Example: Manually add a default value
    //         'BookingStatus' => 'Pending', // Default value from your schema
    //         'SchedulingStatus' => 'Need Surgery', // Default value from your schema
    //         'AppointmentId' => $externalSurgery->appointment_number,
            
    //         // For fields that don't exist in the remote schema, you can set defaults or null
    //         // or let the user fill them out later.
    //         'PhoneNumbers' => null,
    //         'TheatreRoom' => null,
    //         // ... and so on for all other local columns
    //     ]);

    //     return redirect()->route('surgeries.index')->with('success', 'Surgery record saved locally!');
    // }
}