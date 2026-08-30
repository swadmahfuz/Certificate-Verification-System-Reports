@extends('layouts.admin')

@section('title', 'View Certificate')

@push('styles')
<style>
    .header-toggle.btn-link { text-decoration: none; padding: 0; }
    .header-toggle .chev { transition: transform .2s ease; }
    .header-toggle[aria-expanded="true"] .chev { transform: rotate(90deg); }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div>
        <h1>Certificate Details</h1>
        <p>{{ $certificate->certificate_number }}</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-primary btn-sm" href="{{ route('certificates.index') }}">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Certificates
        </a>
        @if($certificate->status !== 'Deleted')
            @canMutate
            <a class="btn btn-primary btn-sm" href="{{ route('certificate.createForm') }}">
                <i class="fa-solid fa-plus me-1"></i> Add New
            </a>
            <a class="btn btn-warning btn-sm" href="{{ route('certificate.edit', $certificate->id) }}">
                <i class="fa-solid fa-pen-to-square me-1"></i> Edit
            </a>
            @endcanMutate
            @if($certificate->certificate_pdf)
                <a class="btn btn-secondary btn-sm" href="{{ route('certificate.downloadPdf', $certificate->id) }}" target="_blank">
                    <i class="fa-solid fa-file-pdf me-1"></i> Download PDF
                </a>
            @endif
            @canMutate
            @if(Auth::id() == $certificate->review_by_id && $certificate->status == 'Pending Review')
                <form action="{{ route('certificate.review', $certificate->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-info btn-sm" type="submit" data-confirm="Mark this certificate as Reviewed?">
                        <i class="fa-solid fa-thumbs-up me-1"></i> Mark as Reviewed
                    </button>
                </form>
            @endif
            @if(Auth::id() == $certificate->approval_by_id && $certificate->status == 'Pending Approval')
                <form action="{{ route('certificate.approve', $certificate->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success btn-sm" type="submit" data-confirm="Mark this certificate as Approved?">
                        <i class="fa-solid fa-check me-1"></i> Mark as Approved
                    </button>
                </form>
            @endif
            <form action="{{ route('certificate.delete', $certificate->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm" type="submit" data-confirm="Delete this certificate?">
                    <i class="fa-solid fa-trash me-1"></i> Delete
                </button>
            </form>
            @endcanMutate
        @endif
    </div>
</div>

@canMutate
@if($certificate->status !== 'Deleted' &&
    (Auth::id() == $certificate->created_by_id ||
     Auth::id() == $certificate->review_by_id ||
     Auth::id() == $certificate->approval_by_id))
    <section class="admin-card mb-3">
        <div class="admin-card-body">
            <form action="{{ route('certificate.uploadPdf', $certificate->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="input-group">
                    <input type="file" name="certificate_pdf" class="form-control" accept="application/pdf" required>
                    <button class="btn btn-primary" type="submit">
                        <i class="fa-solid fa-upload me-1"></i>
                        {{ $certificate->certificate_pdf ? 'Re-upload Certificate PDF' : 'Upload Certificate PDF' }}
                    </button>
                </div>
            </form>
            @if($certificate->certificate_pdf)
                <div class="mt-2 text-muted small">
                    Last uploaded by <strong>{{ $certificate->pdf_uploaded_by }}</strong>
                    on {{ \Carbon\Carbon::parse($certificate->pdf_uploaded_at)->format('d M Y \a\t H:i') }}
                </div>
            @endif
        </div>
    </section>
@endif
@endcanMutate

<section class="admin-card">
    <div class="admin-card-header"><h2>Report Certificate Information</h2></div>
    <div class="table-responsive">
        <table class="table admin-table">
            <tbody>
                <tr><th>Certificate Number</th><td>{{ $certificate->certificate_number }}</td></tr>
                <tr>
                    <th>Certificate Validity</th>
                    <td>
                        @if ($certificate->status === 'Deleted')
                            <span class="text-danger">This certificate has been deleted</span>
                        @elseif ($certificate->status === 'Pending Review')
                            <span class="text-warning">Certificate Pending Review</span>
                        @elseif ($certificate->status === 'Pending Approval')
                            <span class="text-warning">Certificate Pending Approval</span>
                        @elseif (empty($certificate->report_validity_date) || \Carbon\Carbon::now() <= \Carbon\Carbon::parse($certificate->report_validity_date))
                            <span class="text-success">Certificate Valid</span>
                        @else
                            <span class="text-danger">Certificate Expired</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Approval Status</th>
                    <td><x-admin.status-badge :status="$certificate->status" /></td>
                </tr>
                <tr><th>Client</th><td>{{ $certificate->client_name }}</td></tr>
                <tr><th>Location</th><td>{{ $certificate->location }}</td></tr>
                <tr><th>Team Members</th><td>{{ $certificate->team_members }}</td></tr>
                <tr><th>Report Prepared By</th><td>{{ $certificate->report_prepared_by }}</td></tr>
                <tr><th>Report Approved By</th><td>{{ $certificate->report_approved_by }}</td></tr>
                <tr>
                    <th>Report Issue Date</th>
                    <td>{{ $certificate->report_issue_date ? \Carbon\Carbon::parse($certificate->report_issue_date)->format('d M Y') : 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Valid Till</th>
                    <td>{{ $certificate->report_validity_date ? \Carbon\Carbon::parse($certificate->report_validity_date)->format('d M Y') : 'No Expiry Date' }}</td>
                </tr>
                <tr><th>Report Revision</th><td>{{ $certificate->report_revision }}</td></tr>
                <tr><th>Report Remarks</th><td>{{ $certificate->report_remarks }}</td></tr>
                <tr><th>Internal Notes</th><td>{{ $certificate->report_internal_notes }}</td></tr>
                <tr>
                    <th>Certificate PDF File</th>
                    <td>
                        @if($certificate->certificate_pdf)
                            <a href="{{ route('certificate.downloadPdf', $certificate->id) }}" target="_blank">
                                <strong>{{ $certificate->certificate_pdf }}</strong>
                            </a>
                        @else
                            <span class="text-danger">No certificate PDF uploaded yet</span>
                        @endif
                    </td>
                </tr>
                <tr><th>Review By (System)</th><td>{{ $certificate->review_by }}</td></tr>
                <tr>
                    <th>Reviewed on</th>
                    <td>{{ $certificate->reviewed_at ? $certificate->reviewed_at->format('d M Y \a\t H:i:s') : 'Not yet reviewed' }}</td>
                </tr>
                <tr><th>Approval By (System)</th><td>{{ $certificate->approval_by }}</td></tr>
                <tr>
                    <th>Approved on</th>
                    <td>{{ $certificate->approved_at ? $certificate->approved_at->format('d M Y \a\t H:i:s') : 'Not yet approved' }}</td>
                </tr>
                <tr>
                    <th>QR Code</th>
                    <td>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/').'?search='.$certificate->certificate_number) }}" alt="QR code">
                    </td>
                </tr>
                <tr><th>Created By</th><td>{{ $certificate->created_by }}</td></tr>
                <tr><th>Created On</th><td>{{ $certificate->created_at->format('d M Y \a\t H:i:s') }}</td></tr>
                <tr><th>Last Updated By</th><td>{{ $certificate->updated_by }}</td></tr>
                <tr><th>Updated On</th><td>{{ $certificate->updated_at ? $certificate->updated_at->format('d M Y \a\t H:i:s') : '' }}</td></tr>
                <tr>
                    <th>Deleted by</th>
                    <td>{{ $certificate->status === 'Deleted' ? $certificate->deleted_by : 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Deleted on</th>
                    <td>{{ $certificate->deleted_by && $certificate->deleted_at ? $certificate->deleted_at->format('d M Y \a\t H:i:s') : 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if($certificate->certificate_pdf)
        @php
            $viewerBase = asset('public/laraview/index.html');
            $pdfFolder = 'Certificate PDFs';
            $viewerSrc = $viewerBase . '#../' . rawurlencode($pdfFolder) . '/' . rawurlencode($certificate->certificate_pdf);
            $collapseId = 'pdfViewerCollapse-' . $certificate->id;
            $toggleId = 'togglePdfHeaderBtn-' . $certificate->id;
        @endphp
        <div class="admin-card-body border-top">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <button
                    id="{{ $toggleId }}"
                    class="btn btn-link header-toggle d-flex align-items-center"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $collapseId }}"
                    aria-expanded="false"
                    aria-controls="{{ $collapseId }}">
                    <i class="fa-solid fa-chevron-right me-2 chev"></i>
                    <span>Certificate PDF Preview</span>
                </button>
                <small class="text-muted">
                    If it doesn't load, <a href="{{ route('certificate.downloadPdf', $certificate->id) }}" target="_blank">download</a>.
                </small>
            </div>
            <div class="collapse" id="{{ $collapseId }}">
                <div style="height: 75vh;">
                    <iframe
                        data-viewer-src="{{ $viewerSrc }}"
                        title="Certificate PDF"
                        style="width:100%; height:100%; border:0;"
                        allow="fullscreen"
                        loading="lazy"></iframe>
                </div>
            </div>
        </div>
    @endif
</section>
@endsection

@if($certificate->certificate_pdf)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const collapseEl = document.getElementById('{{ $collapseId }}');
    const btn = document.getElementById('{{ $toggleId }}');
    if (!collapseEl || !btn) return;

    const iframe = collapseEl.querySelector('iframe');

    collapseEl.addEventListener('show.bs.collapse', function () {
        if (!iframe.getAttribute('src')) {
            iframe.setAttribute('src', iframe.dataset.viewerSrc);
        }
        btn.setAttribute('aria-expanded', 'true');
    });

    collapseEl.addEventListener('hide.bs.collapse', function () {
        btn.setAttribute('aria-expanded', 'false');
    });
});
</script>
@endpush
@endif
