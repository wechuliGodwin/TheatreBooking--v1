<?php

namespace App\Http\Controllers;

use App\Models\RequestedSurgery;
use App\Models\LocalSurgery;
use Illuminate\Http\Request;

class SurgeryController extends Controller
{
    //create a new theatre request save to the local db
    public function newTheatreRequest(Request $request)
    {
        $externalSurgery = RequestedSurgery::findOrFail($request->input('id'));

        // Map the fields from the remote schema to  local schema
        Surgery::create([
            'FullName' => $externalSurgery->full_name,
            'MRN' => $externalSurgery->patient_number,
            'Diagnosis' => 'Awaiting Diagnosis', // Example: Manually add a default value
            'BookingStatus' => 'Pending', // Default value from your schema
            'SchedulingStatus' => 'Need Surgery', // Default value from your schema
            'AppointmentId' => $externalSurgery->appointment_number,

            // For fields that don't exist in the remote schema, you can set defaults or null
            // or let the user fill them out later.
            'PhoneNumbers' => null,
            'TheatreRoom' => null,
            // ... and so on for all other local columns
        ]);

        return redirect()->route('surgeries.index')->with('success', 'Surgery record saved locally!');
    }
}
