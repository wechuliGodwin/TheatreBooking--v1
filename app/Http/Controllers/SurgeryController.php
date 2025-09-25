<?php

namespace App\Http\Controllers;

use App\Models\Surgeries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SurgeryController extends Controller
{
    protected $theatreRepository;
    public function __construct(TheatreRepository $theatreRepository)
    {
        $this->theatreRepository = $theatreRepository;
    }

    public function showBookingForm($identifier = null)
    {
        $surgery = null;

        if ($identifier) {
            // Try fetching by session_number (remote or local)
            $surgery = $this->theatreRepository->getBookingBySessionNumber($identifier);

            if (!$surgery) {
                // If not found remotely, try fetching by id (local)
                $surgery = Surgeries::find($identifier);
            }

            if (!$surgery) {
                return redirect()->route('requested_surgeries.index')->with('error', 'Surgery record not found.');
            }

            // If remote record exists, check for local record and merge
            if (!($surgery instanceof Surgeries)) {
                $localSurgery = Surgeries::where('session_number', $identifier)->first();
                if ($localSurgery) {
                    $surgery = (object) array_merge((array) $surgery, $localSurgery->toArray());
                }
            }
        }

        // If no identifier, $surgery remains null for a new record
        return view('theatre.surgeries.register_patient', compact('surgery'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'session_number' => 'nullable|string|max:255',
            'session_date' => 'nullable|date',
            'theatre_request_date' => 'nullable|date',
            'full_name' => 'nullable|string|max:255',
            'patient_number' => 'nullable|string|max:255',
            'phone_numbers' => 'nullable|string|max:255',
            'theatre_room' => 'nullable|in:Room1,Room2,Room3,Room4,Other',
            'age' => 'nullable|integer',
            'scheduling_status' => 'nullable|in:Need Surgery,SHA Submitted; Pending Approval,Insurance Approved/Deposit Paid; Ready to Schedule,Scheduled,Completed,Inactive,SHA Rejected',
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

        // Log the validated data for debugging
        Log::info('Saving surgery record', [
            'session_number' => $request->session_number,
            'validated_data' => $validated,
        ]);

        try {
            // Update or create the record
            $surgery = Surgeries::updateOrCreate(
                $request->session_number ? ['session_number' => $request->session_number] : ['id' => $request->id],
                $validated
            );

            // Log success
            Log::info('Surgery record saved', [
                'session_number' => $surgery->session_number,
                'id' => $surgery->id,
            ]);

            $message = $request->action === 'draft' ? 'Surgery record saved as draft successfully.' : 'Surgery record saved successfully.';
            return redirect()->route('surgeries.filter', ['status' => $surgery->scheduling_status ?? 'Need Surgery'])->with('success', $message);
        } catch (\Exception $e) {
            // Log error
            Log::error('Failed to save surgery record', [
                'session_number' => $request->session_number,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Failed to save surgery record: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $surgery = Surgeries::find($id);

        if (!$surgery) {
            return redirect()->route('surgeries.filter')->with('error', 'Surgery record not found.');
        }

        if ($surgery->scheduling_status === 'Need Surgery') {
            return redirect()->route('surgeries.filter')->with('error', 'This record cannot be edited here. Use the registration form for "Need Surgery" records.');
        }

        return view('theatre.surgeries.edit_surgery', compact('surgery'));
    }

    public function update(Request $request, $id)
    {
        $surgery = Surgeries::find($id);

        if (!$surgery) {
            return redirect()->route('surgeries.filter')->with('error', 'Surgery record not found.');
        }

        if ($surgery->scheduling_status === 'Need Surgery') {
            return redirect()->route('surgeries.filter')->with('error', 'This record cannot be edited here. Use the registration form for "Need Surgery" records.');
        }

        $validated = $request->validate([
            'session_number' => 'nullable|string|max:255',
            'session_date' => 'nullable|date',
            'theatre_request_date' => 'nullable|date',
            'full_name' => 'nullable|string|max:255',
            'patient_number' => 'nullable|string|max:255',
            'phone_numbers' => 'nullable|string|max:255',
            'theatre_room' => 'nullable|in:Room1,Room2,Room3,Room4,Other',
            'age' => 'nullable|integer',
            'scheduling_status' => 'required|in:SHA Submitted; Pending Approval,Insurance Approved/Deposit Paid; Ready to Schedule,Scheduled,Completed,Inactive,SHA Rejected,Cancelled',
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
        $attachments = json_decode($surgery->attachments, true) ?? [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $attachments[] = $path;
            }
            $validated['attachments'] = json_encode($attachments);
        } else {
            $validated['attachments'] = json_encode($attachments);
        }

        // Log the validated data for debugging
        Log::info('Updating surgery record', [
            'id' => $id,
            'validated_data' => $validated,
        ]);

        try {
            $surgery->update($validated);

            // Log success
            Log::info('Surgery record updated', [
                'id' => $surgery->id,
                'scheduling_status' => $surgery->scheduling_status,
            ]);

            return redirect()->route('surgeries.filter', ['status' => $surgery->scheduling_status])->with('success', 'Surgery record updated successfully.');
        } catch (\Exception $e) {
            // Log error
            Log::error('Failed to update surgery record', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Failed to update surgery record: ' . $e->getMessage());
        }
    }

    public function filterByStatus(Request $request)
{
    $status = $request->input('status', 'Need Surgery');
    $query = $request->input('query');
    $start_date = $request->input('start_date', now()->subDays(30)->toDateString());
    $end_date = $request->input('end_date', now()->toDateString());
    $cancellation_type = $request->input('cancellation_type');

    // Initialize collection for surgeries
    $surgeries = collect();

    // Initialize variables to avoid undefined errors
    $localSessionNumbers = [];
    $remoteSurgeries = [];
    $filteredRemoteSurgeries = [];

    // Fetch local surgeries
    $localQuery = Surgeries::query();
    if ($status === 'Cancelled') {
        $localQuery->whereNotNull('cancelled_at');
    } else {
        $localQuery->where('scheduling_status', $status);
    }
    $localQuery->when($query, function ($q, $searchTerm) {
        return $q->where(function ($subQ) use ($searchTerm) {
            $subQ->where('session_number', 'like', '%' . $searchTerm . '%')
                ->orWhere('full_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('patient_number', 'like', '%' . $searchTerm . '%')
                ->orWhere('surgeon', 'like', '%' . $searchTerm . '%');
        });
    })->whereBetween('created_at', [$start_date, $end_date . ' 23:59:59'])
        ->orderBy('created_at', 'desc');

    $surgeries = $surgeries->concat($localQuery->get());

    // For 'Need Surgery', also fetch remote surgeries
    if ($status === 'Need Surgery') {
        $remoteSurgeries = $this->theatreRepository->getBookingsByDateRange($start_date, $end_date, $query, 'BOOKING', 0);

        // Get all local session numbers to filter remote records, normalize to lowercase and trim
        $localSessionNumbers = Surgeries::pluck('session_number')->map(function ($sessionNumber) {
            return $sessionNumber ? strtolower(trim($sessionNumber)) : null;
        })->filter()->toArray();

        // Filter out remote surgeries that already exist in local database
        $filteredRemoteSurgeries = array_filter($remoteSurgeries, function ($surgery) use ($localSessionNumbers) {
            $remoteSessionNumber = $surgery->SessionNumber ? strtolower(trim($surgery->SessionNumber)) : null;
            return $remoteSessionNumber && !in_array($remoteSessionNumber, $localSessionNumbers);
        });

        // Map remote surgeries to match local surgery structure
        $mappedRemoteSurgeries = collect($filteredRemoteSurgeries)->map(function ($surgery) {
            return (object) [
                'id' => null,
                'session_number' => $surgery->SessionNumber,
                'session_date' => $surgery->booking_date,
                'theatre_request_date' => $surgery->Requested_on,
                'full_name' => $surgery->PatientName,
                'patient_number' => $surgery->PatientNumber,
                'phone_numbers' => null,
                'theatre_room' => $surgery->OperationRoom,
                'age' => $surgery->Age,
                'scheduling_status' => 'Need Surgery',
                'booking_status' => $surgery->Status,
                'notes_comments' => null,
                'diagnosis' => $surgery->PreferredName,
                'surgery' => $surgery->theatre_procedure_requested,
                'urgent_cancer' => null,
                'surgery_type' => $surgery->SessionType,
                'surgery_category' => null,
                'proposed_date_of_surgery' => null,
                'date_of_surgery' => null,
                'payment_type' => null,
                'sha_procedure' => null,
                'sha_code' => null,
                'cpt_code' => null,
                'sha_approved_amount' => null,
                'sha_expiry_date' => null,
                'secondary_payer' => null,
                'second_payer_approved_amount' => null,
                'length_of_surgery' => null,
                'date_deposit_paid' => null,
                'special_needs' => null,
                'department' => null,
                'surgeon' => $surgery->Consultant,
                'deposit_amount' => null,
                'post_op_location' => null,
                'requires_anesthesia_clearance' => null,
                'anesthesia_clearance_notes' => null,
                'case_order' => null,
                'sha_eligible' => null,
                'icd10_code' => null,
                'department_additional' => null,
                'second_surgeon' => null,
                'attachments' => null,
                'appointment_id' => null,
                'entry_date' => null,
                'cancellation_reason' => null,
                'cancellation_type' => null,
                'cancelled_at' => null,
                'created_at' => null,
            ];
        });
        $surgeries = $surgeries->concat($mappedRemoteSurgeries);
    }

    // Sort combined collection by date (handle null created_at for remote records)
    $surgeries = $surgeries->sortByDesc(function ($surgery) {
        return $surgery->created_at ? $surgery->created_at->timestamp : ($surgery->session_date ? strtotime($surgery->session_date) : 0);
    })->values();

    Log::info('Filtered surgeries by status', [
        'status' => $status,
        'query' => $query,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'total_count' => $surgeries->count(),
        'local_count' => $localQuery->count(),
        'remote_count' => $status === 'Need Surgery' ? count($filteredRemoteSurgeries) : 0,
        'local_session_numbers' => $localSessionNumbers,
        'remote_session_numbers_before' => $remoteSurgeries ? array_map(fn($s) => $s->SessionNumber, $remoteSurgeries) : [],
        'remote_session_numbers_after' => $filteredRemoteSurgeries ? array_map(fn($s) => $s->SessionNumber, $filteredRemoteSurgeries) : [],
    ]);

    if ($status === 'Cancelled') {
        $cancelledSurgeries = Surgeries::whereNotNull('cancelled_at')
            ->when($cancellation_type, function ($q) use ($cancellation_type) {
                $q->where('cancellation_type', $cancellation_type);
            })
            ->orderByDesc('cancelled_at')
            ->get();
        return view('theatre.surgeries.cancelled_surgeries', [
            'surgeries' => $cancelledSurgeries,
            'status' => $status,
            'query' => $query,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'cancellation_type' => $cancellation_type, // <-- always pass
        ]);
    }

    return view('theatre.surgeries.filtered_scheduling_status', compact('surgeries', 'status', 'query', 'start_date', 'end_date'));
}

public function cancelled(Request $request)
    {
        $query = $request->input('query');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $cancellation_type = $request->input('cancellation_type');

        // Initialize query for cancelled surgeries
        $surgeriesQuery = Surgeries::whereNotNull('cancellation_type');

        // Apply cancellation type filter
        if ($cancellation_type) {
            $surgeriesQuery->where('cancellation_type', $cancellation_type);
        }

        // Apply search query
        if ($query) {
            $surgeriesQuery->where(function ($q) use ($query) {
                $q->where('session_number', 'like', '%' . $query . '%')
                  ->orWhere('full_name', 'like', '%' . $query . '%')
                  ->orWhere('patient_number', 'like', '%' . $query . '%')
                  ->orWhere('surgeon', 'like', '%' . $query . '%');
            });
        }

        // Apply date range filter
        if ($start_date) {
            $surgeriesQuery->whereDate('cancelled_at', '>=', $start_date);
        }
        if ($end_date) {
            $surgeriesQuery->whereDate('cancelled_at', '<=', $end_date);
        }

        // Fetch cancelled surgeries
        $surgeries = $surgeriesQuery->get();

        // Log the fetched cancelled surgeries
        Log::info('Fetched cancelled surgeries', [
            'count' => $surgeries->count(),
            'cancellation_type' => $cancellation_type,
        ]);

        return view('theatre.surgeries.cancelled_surgeries', [
            'surgeries' => $surgeries,
            'cancellation_type' => $cancellation_type, // <-- always pass
            'query' => $query,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    public function cancel(Request $request)
    {
        $validated = $request->validate([
            'identifier' => 'required',
            'cancellation_reason' => 'required|string',
            'cancelled_at' => 'required|date',
        ]);

        $identifier = $request->identifier;
        $isRemote = $request->is_remote === 'true';

        try {
            if ($isRemote) {
                // Remote record: pre_cancelled
                $remoteSurgery = $this->theatreRepository->getBookingBySessionNumber($identifier);
                if (!$remoteSurgery) {
                    return response()->json(['success' => false, 'message' => 'Remote surgery record not found.'], 404);
                }

                // Check if already exists locally
                $localSurgery = Surgeries::where('session_number', $identifier)->first();
                if ($localSurgery) {
                    return response()->json(['success' => false, 'message' => 'Surgery already exists locally and cannot be pre-cancelled.'], 400);
                }

                // Create local record with cancellation details
                $surgeryData = [
                    'session_number' => $remoteSurgery->SessionNumber,
                    'full_name' => $remoteSurgery->PatientName,
                    'patient_number' => $remoteSurgery->PatientNumber,
                    'session_date' => $remoteSurgery->booking_date,
                    'theatre_request_date' => $remoteSurgery->Requested_on,
                    'age' => $remoteSurgery->Age,
                    'scheduling_status' => 'Cancelled',
                    'surgery' => $remoteSurgery->theatre_procedure_requested,
                    'surgery_type' => $remoteSurgery->SessionType,
                    'surgeon' => $remoteSurgery->Consultant,
                    'theatre_room' => $remoteSurgery->OperationRoom,
                    'cancellation_reason' => $validated['cancellation_reason'],
                    'cancellation_type' => 'pre_cancelled',
                    'cancelled_at' => $validated['cancelled_at'],
                ];

                Surgeries::create($surgeryData);

                Log::info('Pre-cancelled remote surgery saved locally', [
                    'session_number' => $identifier,
                ]);

                return response()->json(['success' => true, 'message' => 'Surgery pre-cancelled successfully.']);
            } else {
                // Try to find by session_number first, then by id
                $surgery = Surgeries::where('session_number', $identifier)->first();
                if (!$surgery && is_numeric($identifier)) {
                    $surgery = Surgeries::find($identifier);
                }
                if (!$surgery) {
                    return response()->json(['success' => false, 'message' => 'Local surgery record not found.'], 404);
                }
                if ($surgery->cancellation_type) {
                    return response()->json(['success' => false, 'message' => 'Surgery is already cancelled.'], 400);
                }
                if ($surgery->scheduling_status === 'Need Surgery') {
                    return response()->json(['success' => false, 'message' => 'Cannot post-cancel a "Need Surgery" record.'], 400);
                }
                $surgery->update([
                    'scheduling_status' => 'Cancelled',
                    'cancellation_reason' => $validated['cancellation_reason'],
                    'cancellation_type' => 'post_cancelled',
                    'cancelled_at' => $validated['cancelled_at'],
                ]);
                return response()->json(['success' => true, 'message' => 'Surgery cancelled successfully.']);
            }
        } catch (\Exception $e) {
            Log::error('Failed to cancel surgery', [
                'identifier' => $identifier,
                'is_remote' => $isRemote,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to cancel surgery: ' . $e->getMessage()], 500);
        }
    }

}