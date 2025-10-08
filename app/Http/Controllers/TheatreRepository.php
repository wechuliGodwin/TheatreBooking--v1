<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TheatreRepository
{
    protected $connection = 'sqlsrv_remote';

    public function getBookingsByDateRange($startDate, $endDate, $query = null, $status = null, $billingApproved = null)
    {
        $sql = "
            SELECT
                a.SessionNumber,
                a.SessionDate AS booking_date,
                a.TheatreRequestDate AS Requested_on,
                a.PatientName,
                a.PatientNumber,
                DATEDIFF(YEAR, PT.BirthDate, GETDATE()) AS Age,
                PT.Gender,
                req.theatre_procedure_requested,
                a.SessionType,
                CIC.PreferredName,
                p.EmployeeName AS Consultant,
                a.OperationRoom,
                a.Status,
                a.TheatreDayCase,
                a.BillingApproved
            FROM TheatreRequestHeader a
            LEFT JOIN CustomerInformation PT 
                ON a.PatientNumber = PT.CustomerID
            LEFT JOIN ConsultationICDCodes CIC 
                ON a.ServiceReference = CIC.ServiceReference
            LEFT JOIN PayrollEmployees p 
                ON p.EmployeeID = a.SurgeonID
                AND p.BranchID = 'kijabe'
            CROSS APPLY (
                SELECT STRING_AGG(t2.ProcedureID, ',') AS theatre_procedure_requested
                FROM ConsultationProcedureRequest t2
                WHERE t2.ServiceReference = a.ServiceReference
            ) req
            WHERE 
                a.SessionDate >= ?
                AND a.SessionDate < ?
        ";

        $params = [$startDate, date('Y-m-d', strtotime($endDate) + 86400)];

        if ($status) {
            // Use case-insensitive comparison for Status
            $sql .= " AND UPPER(a.Status) = ?";
            $params[] = strtoupper($status);
        }

        if ($billingApproved !== null) {
            // Handle NULL values in BillingApproved to match PHP loose comparison
            $sql .= " AND (a.BillingApproved = ? OR a.BillingApproved IS NULL)";
            $params[] = $billingApproved;
        }

        if ($query) {
            $sql .= " AND (a.PatientNumber LIKE ? OR CIC.PreferredName LIKE ? OR p.EmployeeName LIKE ?)";
            $searchTerm = '%' . $query . '%';
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
        }

        $sql .= " ORDER BY a.SessionDate DESC";

        $results = DB::connection($this->connection)->select($sql, $params);

        // Log the query, parameters, and detailed results
        Log::info('getBookingsByDateRange Query', [
            'sql' => $sql,
            'params' => $params,
            'result_count' => count($results),
            'status_values' => array_unique(array_map(fn($r) => $r->Status, $results)),
            'billing_approved_values' => array_unique(array_map(fn($r) => $r->BillingApproved ?? 'NULL', $results)),
        ]);

        return $results;
    }

    public function getBookingBySessionNumber($sessionNumber)
    {
        $sql = "
            SELECT
                a.SessionNumber,
                a.SessionDate AS booking_date,
                a.TheatreRequestDate AS Requested_on,
                a.PatientName,
                a.PatientNumber,
                DATEDIFF(YEAR, PT.BirthDate, GETDATE()) AS Age,
                PT.Gender,
                req.theatre_procedure_requested,
                a.SessionType,
                CIC.PreferredName,
                p.EmployeeName AS Consultant,
                a.OperationRoom,
                a.Status,
                a.TheatreDayCase,
                a.BillingApproved
            FROM TheatreRequestHeader a
            LEFT JOIN CustomerInformation PT 
                ON a.PatientNumber = PT.CustomerID
            LEFT JOIN ConsultationICDCodes CIC 
                ON a.ServiceReference = CIC.ServiceReference
            LEFT JOIN PayrollEmployees p 
                ON p.EmployeeID = a.SurgeonID
                AND p.BranchID = 'kijabe'
            CROSS APPLY (
                SELECT STRING_AGG(t2.ProcedureID, ',') AS theatre_procedure_requested
                FROM ConsultationProcedureRequest t2
                WHERE t2.ServiceReference = a.ServiceReference
            ) req
            WHERE a.SessionNumber = ?
        ";

        Log::info('Querying SessionNumber: ' . $sessionNumber);
        $results = DB::connection($this->connection)->select($sql, [$sessionNumber]);

        Log::info('getBookingBySessionNumber Results', [
            'sessionNumber' => $sessionNumber,
            'result_count' => count($results),
            'results' => (array) $results,
        ]);

        return $results ? $results[0] : null;
    }
    public function getFinalizedBookings($startDate, $endDate, $query = null)
    {
        return $this->getBookingsByDateRange($startDate, $endDate, $query, 'FINALIZED', null);
    }

    public function getPatientByNumber($patientNumber)
    {
        $sql = "
        SELECT
            ci.CustomerID AS PatientNumber,
            ci.PatientName,
            ci.Gender,
            ci.BirthDate,
            DATEDIFF(YEAR, ci.BirthDate, GETDATE()) AS Age,
            ci.PatientCellPhone,
            ci.County
        FROM CustomerInformation ci
        WHERE ci.CustomerID = ?
    ";

        $results = DB::connection($this->connection)->select($sql, [$patientNumber]);

        return $results ? $results[0] : null;
    }
}
