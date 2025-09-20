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
            $sql .= " AND a.Status = ?";
            $params[] = $status;
        }

        if ($billingApproved !== null) {
            $sql .= " AND a.BillingApproved = ?";
            $params[] = $billingApproved;
        }

        if ($query) {
            $sql .= " AND (a.PatientNumber LIKE ? OR CIC.PreferredName LIKE ? OR p.EmployeeName LIKE ?)";
            $searchTerm = '%' . $query . '%';
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
        }

        $sql .= " ORDER BY a.SessionDate DESC";

        return DB::connection($this->connection)->select($sql, $params);
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


        Log::info('Querying SessionNumber: ' . $sessionNumber); // Add logging
        $results = DB::connection($this->connection)->select($sql, [$sessionNumber]);

        Log::info('Query Results: ', (array) $results); // Log results
        return $results ? $results[0] : null;
    }
}
