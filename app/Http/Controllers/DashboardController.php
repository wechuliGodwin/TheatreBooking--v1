<?php

namespace App\Http\Controllers;

use App\Models\Surgeries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $theatreRepository;

    public function __construct(TheatreRepository $theatreRepository)
    {
        $this->theatreRepository = $theatreRepository;
    }

    public function index()
    {
        $start_date = now()->subDays(30)->toDateString();
        $end_date = now()->toDateString();

        // Define statuses for counting
        $statuses = [
            'Need Surgery',
            'SHA Submitted; Pending Approval',
            'Insurance Approved/Deposit Paid; Ready to Schedule',
            'Scheduled',
            'Completed',
            'Inactive',
            'SHA Rejected',
            'Cancelled',
        ];

        // Initialize counts array
        $counts = [];

        // Get all local session numbers to filter remote records
        $localSessionNumbers = Surgeries::pluck('session_number')->toArray();

        // Fetch local surgeries (Surgeries model)
        foreach ($statuses as $status) {
            if ($status === 'Need Surgery') {
                // For 'Need Surgery', include remote bookings with Status = 'BOOKING' and BillingApproved = 0 or NULL
                $remoteSurgeries = $this->theatreRepository->getBookingsByDateRange($start_date, $end_date, null, 'BOOKING', 0);

                // Filter out remote surgeries that already exist in local database
                $filteredRemoteSurgeries = array_filter($remoteSurgeries, function ($surgery) use ($localSessionNumbers) {
                    return !in_array($surgery->SessionNumber, $localSessionNumbers);
                });

                $localSurgeries = Surgeries::where('scheduling_status', 'Need Surgery')
                    ->whereBetween('created_at', [$start_date, $end_date . ' 23:59:59'])
                    ->count();
                $counts[$status] = count($filteredRemoteSurgeries) + $localSurgeries;
            } elseif ($status === 'Cancelled') {
                // Count local surgeries with cancelled_at not null
                $counts[$status] = Surgeries::whereNotNull('cancelled_at')
                    ->whereBetween('created_at', [$start_date, $end_date . ' 23:59:59'])
                    ->count();
            } else {
                // Count local surgeries for other statuses
                $counts[$status] = Surgeries::where('scheduling_status', $status)
                    ->whereBetween('created_at', [$start_date, $end_date . ' 23:59:59'])
                    ->count();
            }
        }

        // Fetch recent surgeries (limit to 5, combining local and remote)
        $recentLocalSurgeries = Surgeries::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentRemoteSurgeries = $this->theatreRepository->getBookingsByDateRange($start_date, $end_date, null, null, null);

        // Filter out remote surgeries that already exist in local database
        $filteredRecentRemoteSurgeries = array_filter($recentRemoteSurgeries, function ($surgery) use ($localSessionNumbers) {
            return !in_array($surgery->SessionNumber, $localSessionNumbers);
        });

        // Map remote surgeries to match local Surgeries model structure
        $mappedRemoteSurgeries = collect($filteredRecentRemoteSurgeries)->map(function ($surgery) {
            return (object) [
                'session_number' => $surgery->SessionNumber,
                'session_date' => $surgery->booking_date,
                'theatre_request_date' => $surgery->Requested_on,
                'full_name' => $surgery->PatientName,
                'patient_number' => $surgery->PatientNumber,
                'phone_numbers' => null,
                'theatre_room' => $surgery->OperationRoom,
                'age' => $surgery->Age,
                'scheduling_status' => $surgery->Status,
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

        // Combine local and remote surgeries using concat instead of merge
        $recentSurgeries = $recentLocalSurgeries->concat($mappedRemoteSurgeries->take(5 - $recentLocalSurgeries->count()));

        // Log the fetched data
        Log::info('Dashboard data fetched', [
            'counts' => $counts,
            'recent_surgeries_count' => $recentSurgeries->count(),
            'sample_surgeries' => $recentSurgeries->take(2)->map(function ($surgery) {
                return [
                    'session_number' => $surgery->session_number ?? $surgery->SessionNumber ?? 'N/A',
                    'status' => $surgery->scheduling_status ?? $surgery->Status ?? 'N/A',
                ];
            })->toArray(),
        ]);

        return view('theatre.dashboard', compact('counts', 'recentSurgeries'));
    }

    public function surgeries(Request $request)
    {
        $start_date = $request->input('start_date', now()->subDays(30)->toDateString());
        $end_date = $request->input('end_date', now()->toDateString());
        $query = $request->input('query');

        // Fetch only 'Booking' surgeries with BillingApproved = 0 or NULL
        $bookingSurgeries = $this->theatreRepository->getBookingsByDateRange($start_date, $end_date, $query, 'BOOKING', 0);

        // Get all local session numbers to filter remote records
        $localSessionNumbers = Surgeries::pluck('session_number')->toArray();

        // Filter out remote surgeries that already exist in local database
        $filteredBookingSurgeries = array_filter($bookingSurgeries, function ($surgery) use ($localSessionNumbers) {
            return !in_array($surgery->SessionNumber, $localSessionNumbers);
        });

        // Log the number of surgeries fetched
        Log::info('Surgeries fetched for Booking status', [
            'count' => count($filteredBookingSurgeries),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'query' => $query,
        ]);

        return view('theatre.surgeries.pending_surgery_list', compact(
            'filteredBookingSurgeries',
            'query',
            'start_date',
            'end_date'
        ));
    }

    public function surgeryDetails($id)
    {
        $sessionNumber = urldecode($id);
        $surgery = $this->theatreRepository->getBookingBySessionNumber($sessionNumber);

        if (!$surgery) {
            Log::error('Surgery not found for SessionNumber: ' . $sessionNumber);
            return redirect()->back()->with('error', 'Surgery record not found.');
        }

        return view('theatre.surgeries.surgery_details', compact('surgery'));
    }
}