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
        return Certificate::all();
    }

    /**
     * Define headings for export
     *
     * @return array
     */
    public function headings(): array
    {
        
        return [
            'DB ID',
            'Certificate Number',
            'Client Name',
            'Location',
            'Calibrator',
            'Equipment Name',
            'Equipment Brand',
            'Equipment ID',
            'Calibration Date',
            'Report Issue Date',
            'Validity Date',
            'Calibration Remarks',
            'Calibration Internal Notes',
            'Status',
            'Created by',
            'Created by ID',
            'Created at',
            'Review by',
            'Review by ID',
            'Reviewed at',
            'Approval by',
            'Approval by ID',
            'Approved at',
            'Updated by',
            'Updated by ID',
            'Updated at',
            'Certificate PDF',
            'PDF Uploaded by',
            'PDF Uploaded by ID',
            'PDF Uploaded at',
            'Deleted by',
            'Deleted by ID',
            'Deleted at',
        ];
    }
}
