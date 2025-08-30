<?php

namespace App\Imports;

use App\Models\Certificate;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Inspection Certificate Verification System (CVS) 
| TUV Austria Bureau of Inspection & Certification 
| Developed by: Swad Ahmed Mahfuz (Assistant Manager - Sales & Operations, Bangladesh)
| Contact: swad.mahfuz@gmail.com, +1-725-867-7718, +88 01733 023 008
| Project Start: 12 October 2022
|--------------------------------------------------------------------------
*/

class CertificateImport implements ToModel, WithHeadingRow
{
    /**
     * Map Excel rows to Certificate model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $createdUser  = isset($row['created_by_email'])  ? User::where('email', $row['created_by_email'])->first()  : null;
        $reviewUser   = isset($row['review_by_email'])   ? User::where('email', $row['review_by_email'])->first()   : null;
        $approvalUser = isset($row['approval_by_email']) ? User::where('email', $row['approval_by_email'])->first() : null;
        $loggedInUser = Auth::user();

        return new Certificate([
            'certificate_number'      => $row['certificate_number'] ?? null,
            'client_name'             => $row['client_name'] ?? null,
            'location'                => $row['location'] ?? null,
            'team_members'            => $row['team_members'] ?? null,
            'report_prepared_by'      => $row['report_prepared_by'] ?? null,
            'report_approved_by'      => $row['report_approved_by'] ?? null,
            'report_issue_date'       => $row['report_issue_date'] ?? null,
            'report_validity_date'    => $row['report_validity_date'] ?? null,
            'report_revision'         => $row['report_revision'] ?? null,
            'report_remarks'          => $row['report_remarks'] ?? null,
            'report_internal_notes'   => $row['report_internal_notes'] ?? null,

            'status'        => 'Pending Review',
            'created_by'    => $createdUser ? $createdUser->name : null,
            'created_by_id' => $createdUser ? $createdUser->id   : null,
            'created_at'    => Carbon::now(),

            'review_by'     => $reviewUser ? $reviewUser->name : null,
            'review_by_id'  => $reviewUser ? $reviewUser->id   : null,

            'approval_by'    => $approvalUser ? $approvalUser->name : null,
            'approval_by_id' => $approvalUser ? $approvalUser->id   : null,

            'updated_by'    => $loggedInUser ? $loggedInUser->name : null,
            'updated_by_id' => $loggedInUser ? $loggedInUser->id   : null,
            'updated_at'    => Carbon::now(),
        ]);
    }
}
