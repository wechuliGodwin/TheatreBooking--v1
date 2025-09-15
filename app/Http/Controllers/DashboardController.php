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

        $requestedSurgeries = $this->theatreRepository->getBookingsByDateRange($start_date, $end_date);

        $totalRequestedTheatre = count($requestedSurgeries);
        $pendingSurgeries = count(array_filter($requestedSurgeries, fn($s) => $s->Status === 'Pending'));
        $completedSurgeries = count(array_filter($requestedSurgeries, fn($s) => $s->Status === 'Completed'));

        return view('theatre.dashboard', compact('totalRequestedTheatre', 'pendingSurgeries', 'completedSurgeries'));
    }

    public function requestedSurgeries(Request $request)
    {
        $start_date = $request->input('start_date', now()->subDays(30)->toDateString());
        $end_date = $request->input('end_date', now()->toDateString());
        $query = $request->input('query');

        // Fetch bookings from the repository, applying search query if provided
        $surgeries = $this->theatreRepository->getBookingsByDateRange($start_date, $end_date, $query);

        return view('theatre.surgeries.requested_surgery_list', compact('surgeries', 'query', 'start_date', 'end_date'));
    }

    public function requestedSurgeriesDetails($id)
    {
        $surgery = $this->theatreRepository->getBookingBySessionNumber($id);

        if (!$surgery) {
            return redirect()->back()->with('error', 'Surgery record not found.');
        }

        return view('theatre.surgeries.requested_surgery_details', compact('surgery'));
    }
}
