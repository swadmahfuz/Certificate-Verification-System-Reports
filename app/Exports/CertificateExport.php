<?php

namespace App\Exports;

use App\Models\Certificate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

/*
|--------------------------------------------------------------------------
| Inspection Inspection Certificate Verification System (CVS) 
| TUV Austria Bureau of Inspection & Certification 
| Developed by: Swad Ahmed Mahfuz (Assistant Manager - Sales & Operations, Bangladesh)
| Contact: swad.mahfuz@gmail.com, +1-725-867-7718, +88 01733 023 008
| Project Start: 12 October 2022
|--------------------------------------------------------------------------
*/

class CertificateExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Export columns in the exact order matching headings
        return Certificate::withTrashed()
            ->orderBy('certificate_number')
            ->get([
                'id',
                'certificate_number',
                'client_name',
                'location',
                'team_members',
                'report_prepared_by',
                'report_approved_by',
                'report_issue_date',
                'report_validity_date',
                'report_revision',
                'report_remarks',
                'report_internal_notes',
                'status',
                'created_by',
                'created_by_id',
                'created_at',
                'review_by',
                'review_by_id',
                'reviewed_at',
                'approval_by',
                'approval_by_id',
                'approved_at',
                'updated_by',
                'updated_by_id',
                'updated_at',
                'certificate_pdf',
                'pdf_uploaded_by',
                'pdf_uploaded_by_id',
                'pdf_uploaded_at',
                'deleted_by',
                'deleted_by_id',
                'deleted_at',
            ]);
    }

    /**
     * Define headings for export
     */
    public function headings(): array
    {
        return [
            'DB ID',
            'Certificate Number',
            'Client Name',
            'Location',
            'Team Members',
            'Report Prepared By',
            'Report Approved By',
            'Report Issue Date',
            'Report Validity Date',
            'Report Revision',
            'Report Remarks',
            'Report Internal Notes',
            'Status',
            'Created By',
            'Created By ID',
            'Created At',
            'Review By',
            'Review By ID',
            'Reviewed At',
            'Approval By',
            'Approval By ID',
            'Approved At',
            'Updated By',
            'Updated By ID',
            'Updated At',
            'Certificate PDF',
            'PDF Uploaded By',
            'PDF Uploaded By ID',
            'PDF Uploaded At',
            'Deleted By',
            'Deleted By ID',
            'Deleted At',
        ];
    }
}

