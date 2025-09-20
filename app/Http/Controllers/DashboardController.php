<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        // Fetch all bookings
        $requestedSurgeries = $this->theatreRepository->getBookingsByDateRange($start_date, $end_date);

        // Calculate counts for each status
        $totalRequestedTheatre = count(array_filter($requestedSurgeries, fn($s) => $s->Status === 'Booking' && $s->BillingApproved == 0));
        $totalIntraOperations = count(array_filter($requestedSurgeries, fn($s) => $s->Status === 'Operation'));
        $completedSurgeries = count(array_filter($requestedSurgeries, fn($s) => $s->Status === 'Finalized'));
        $recoverySurgeries = count(array_filter($requestedSurgeries, fn($s) => $s->Status === 'Recovery'));
        $checklistSurgeries = count(array_filter($requestedSurgeries, fn($s) => $s->Status === 'Checklist'));
        $scheduledSurgeries = count(array_filter($requestedSurgeries, fn($s) => $s->Status === 'Scheduled'));
        $shaRejectedSurgeries = count(array_filter($requestedSurgeries, fn($s) => $s->Status === 'SHA Rejected'));
        $inactiveSurgeries = count(array_filter($requestedSurgeries, fn($s) => $s->Status === 'Inactive'));
        $cancelledSurgeries = count(array_filter($requestedSurgeries, fn($s) => $s->Status === 'Cancelled'));

        return view('theatre.dashboard', compact(
            'totalRequestedTheatre',
            'totalIntraOperations',
            'completedSurgeries',
            'recoverySurgeries',
            'checklistSurgeries',
            'scheduledSurgeries',
            'shaRejectedSurgeries',
            'inactiveSurgeries',
            'cancelledSurgeries'
        ));
    }

    public function surgeries(Request $request, $status = null)
    {
        $start_date = $request->input('start_date', now()->subDays(30)->toDateString());
        $end_date = $request->input('end_date', now()->toDateString());
        $query = $request->input('query');

        // Set billingApproved filter for 'Booking' status
        $billingApproved = $status === 'Booking' ? 0 : null;

        // Fetch filtered surgeries
        $allSurgeries = $this->theatreRepository->getBookingsByDateRange($start_date, $end_date, $query, $status, $billingApproved);

        // Prepare arrays for each status (only the filtered status will have data)
        $bookingSurgeries = $status === 'Booking' ? $allSurgeries : [];
        $recoverySurgeries = $status === 'Recovery' ? $allSurgeries : [];
        $operationSurgeries = $status === 'Operation' ? $allSurgeries : [];
        $checklistSurgeries = $status === 'Checklist' ? $allSurgeries : [];
        $scheduledSurgeries = $status === 'Scheduled' ? $allSurgeries : [];
        $shaRejectedSurgeries = $status === 'SHA Rejected' ? $allSurgeries : [];
        $inactiveSurgeries = $status === 'Inactive' ? $allSurgeries : [];
        $cancelledSurgeries = $status === 'Cancelled' ? $allSurgeries : [];

        // If no status filter, show all surgeries grouped by status
        if (!$status) {
            $bookingSurgeries = array_filter($allSurgeries, fn($s) => $s->Status === 'Booking' && $s->BillingApproved == 0);
            $recoverySurgeries = array_filter($allSurgeries, fn($s) => $s->Status === 'Recovery');
            $operationSurgeries = array_filter($allSurgeries, fn($s) => $s->Status === 'Operation');
            $checklistSurgeries = array_filter($allSurgeries, fn($s) => $s->Status === 'Checklist');
            $scheduledSurgeries = array_filter($allSurgeries, fn($s) => $s->Status === 'Scheduled');
            $shaRejectedSurgeries = array_filter($allSurgeries, fn($s) => $s->Status === 'SHA Rejected');
            $inactiveSurgeries = array_filter($allSurgeries, fn($s) => $s->Status === 'Inactive');
            $cancelledSurgeries = array_filter($allSurgeries, fn($s) => $s->Status === 'Cancelled');
        }

        return view('theatre.surgeries.surgery_list', compact(
            'bookingSurgeries',
            'recoverySurgeries',
            'operationSurgeries',
            'checklistSurgeries',
            'scheduledSurgeries',
            'shaRejectedSurgeries',
            'inactiveSurgeries',
            'cancelledSurgeries',
            'query',
            'start_date',
            'end_date',
            'status'
        ));
    }

    public function surgeryDetails($id)
    {
        $sessionNumber = urldecode($id);
        $surgery = $this->theatreRepository->getBookingBySessionNumber($sessionNumber);

        if (!$surgery) {
            return redirect()->back()->with('error', 'Surgery record not found.');
        }

        return view('theatre.surgeries.surgery_details', compact('surgery'));
    }
}
