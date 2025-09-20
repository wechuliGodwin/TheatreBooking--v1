<?php

namespace App\Http\Controllers;

use App\Models\Surgeries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SurgeryController extends Controller
{
    public function showBookingForm($sessionNumber)
    {
        $theatreRepo = app(TheatreRepository::class);
        $surgery = $theatreRepo->getBookingBySessionNumber($sessionNumber);

        if (!$surgery) {
            return redirect()->back()->with('error', 'Surgery record not found.');
        }

        // Check if a local record exists
        $localSurgery = Surgeries::where('SessionNumber', $sessionNumber)->first();

        // If local record exists, merge its data with remote data for form pre-filling
        if ($localSurgery) {
            $surgery = (object) array_merge((array) $surgery, $localSurgery->toArray());
        }

        return view('theatre.surgeries.register_patient', compact('surgery'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'session_number' => 'nullable|string',
            'session_date' => 'nullable|date',
            'theatre_request_date' => 'nullable|date',
            'full_name' => 'nullable|string|max:255',
            'patient_number' => 'nullable|string|max:255',
            'phone_numbers' => 'nullable|string|max:255',
            'theatre_room' => 'nullable|in:Room1,Room2,Room3,Room4,Other',
            'age' => 'nullable|integer',
            'scheduling_status' => 'nullable|in:Need Surgery,SHA Submitted, Pending Approval,Insurance Approved/Deposit Paid; Ready to Schedule,Scheduled,Completed,Inactive,SHA Rejected',
            'booking_status' => 'nullable|string|max:255',
            'notes_comments' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'surgery' => 'nullable|string',
            'urgent_cancer' => 'nullable|boolean',
            'surgery_type' => 'nullable|in:Elective,Emergency,Minor,Major',
            'surgery_category' => 'nullable|string|max:255',
            'proposed_date_of_surgery' => 'nullable|date',
            'date_of_surgery' => 'nullable|date',
            'payment_type' => 'nullable|in:Cash,Other Insurance,Compassionate,SHA + Other,SHA',
            'sha_procedure' => 'nullable|string|max:255',
            'sha_code' => 'nullable|string|max:255',
            'cpt_code' => 'nullable|string|max:255',
            'sha_approved_amount' => 'nullable|numeric',
            'sha_expiry_date' => 'nullable|date',
            'secondary_payer' => 'nullable|in:AAP Insurance,Britam Insurance,First Assurance Insurance,GA Insurance,Jubilee Insurance,None',
            'second_payer_approved_amount' => 'nullable|numeric',
            'length_of_surgery' => 'nullable|string|max:255',
            'date_deposit_paid' => 'nullable|date',
            'special_needs' => 'nullable|string',
            'department' => 'nullable|in:General Surgery,Orthopedics,Neurosurgery,Cardiology,Other',
            'surgeon' => 'nullable|string|max:255',
            'deposit_amount' => 'nullable|numeric',
            'post_op_location' => 'nullable|in:Ward,ICU,Recovery Room,Outpatient',
            'requires_anesthesia_clearance' => 'nullable|boolean',
            'anesthesia_clearance_notes' => 'nullable|string',
            'case_order' => 'nullable|string|max:255',
            'sha_eligible' => 'nullable|boolean',
            'icd10_code' => 'nullable|string|max:255',
            'department_additional' => 'nullable|in:None,Radiology,Pathology,Anesthesiology',
            'second_surgeon' => 'nullable|string|max:255',
            'attachments.*' => 'nullable|file|mimes:pdf,jpg,png|max:4096',
            'appointment_id' => 'nullable|string|max:255',
            'entry_date' => 'nullable|date',
            'cancellation_reason' => 'nullable|string',
            'cancellation_type' => 'nullable|string|max:255',
            'cancelled_at' => 'nullable|date',
        ]);

        // Handle file uploads
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $attachments[] = $path;
            }
            $validated['attachments'] = json_encode($attachments);
        }

        // Update or create the record
        $surgery = Surgeries::updateOrCreate(
            ['SessionNumber' => $request->session_number],
            $validated
        );

        return redirect()->route('requested_surgeries.index')->with('success', 'Surgery record saved successfully.');
    }
}
