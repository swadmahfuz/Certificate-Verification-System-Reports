<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TÜV Austria BIC CVS | Certificate Verification System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <style>
        body { background-color: #f8f9fa; font-size: 13px; }
        .container { max-width: 800px; margin: auto; padding-top: 40px; }
        .form-control { font-size: 14px; padding: 10px; }
        .btn { font-size: 14px; font-weight: 600; border-radius: 8px; padding: 10px 15px; }
        h1, h3, h4 { text-align: center; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 6px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mb-4">
            <img src="images/TUV Austria Logo.png" alt="TUV Logo" width="250">
            <h1>Verify Report Certificate</h1>
            <p>Enter the Certificate Number and click the "Verify" button.</p>
        </div>

        <form id="s-form" method="get" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" id="search" class="form-control" placeholder="Ex: RPT-TUVAT-2025-0725-001" required>
                <button class="btn btn-primary" type="submit">VERIFY</button>
            </div>
        </form>

        @isset($certificates)
            <div>
                @if($certificates->count() < 1)
                    <div class="alert alert-warning text-center">
                        ⚠️ No records of the certificate number you entered can be found in our database. ⚠️<br>
                        Please contact us for further inquiry or clarification. <br>
                        Tel: +88 02 8836403 ; Email: info@tuvat.com.bd 
                    </div>
                @endif

                @foreach ($certificates as $certificate)
                    <div class="mb-4">
                        @if ($certificate->status == 'Deleted')
                            <h3 class="text-danger">This certificate has been deleted and is no longer valid. ❌</h3>
                        @elseif (empty($certificate->report_validity_date) || ! \Carbon\Carbon::parse($certificate->report_validity_date)->isPast())
                            <h3 class="text-success">Certificate Authentic and Valid! ✅</h3>
                            <h6><center>Please verify the details below:</center></h6>
                        @else
                            <h3 class="text-warning">Certificate Authentic but Expired! ⚠️</h3>
                        @endif

                        <table class="table table-bordered mt-3">
                            <tr><td><strong>Certificate Number</strong></td><td>{{ $certificate->certificate_number }}</td></tr>
                            <tr><td><strong>Client Name</strong></td><td>{{ $certificate->client_name }}</td></tr>
                            <tr><td><strong>Location</strong></td><td>{{ $certificate->location }}</td></tr>
                            <tr><td><strong>Team Members</strong></td><td>{{ $certificate->team_members ?? 'N/A' }}</td></tr>
                            <tr><td><strong>Report Prepared By</strong></td><td>{{ $certificate->report_prepared_by }}</td></tr>
                            <tr><td><strong>Report Approved By</strong></td><td>{{ $certificate->report_approved_by }}</td></tr>
                            <tr>
                                <td><strong>Report Issue Date</strong></td>
                                <td>
                                    @if (!empty($certificate->report_issue_date))
                                        {{ \Carbon\Carbon::parse($certificate->report_issue_date)->format('d M Y') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Validity Date</strong></td>
                                <td>
                                    @if (!empty($certificate->report_validity_date))
                                        {{ \Carbon\Carbon::parse($certificate->report_validity_date)->format('d M Y') }}
                                    @else
                                        No Expiry Date
                                    @endif
                                </td>
                            </tr>
                            <tr><td><strong>Report Revision</strong></td><td>{{ $certificate->report_revision ?? 'N/A' }}</td></tr>
                            <tr><td><strong>Report Remarks</strong></td><td>{{ $certificate->report_remarks ?? 'N/A' }}</td></tr>
                        </table>

                        @if ($certificate->certificate_pdf)
                            <div class="text-center mt-3 mb-4">
                                <a href="{{ route('certificate.downloadPdf', $certificate->id) }}" class="btn btn-secondary" target="_blank">
                                    <i class="fa-solid fa-file-pdf me-1"></i> Download Certificate PDF
                                </a>
                            </div>
                        @else
                            <div class="alert alert-warning text-center">
                                Certificate is not available for download.
                            </div>
                        @endif

                        {{-- Toggleable Inline PDF Viewer (toggle is in the header title area) --}}
                        @if($certificate->certificate_pdf)
                            @php
                                // Build ViewerJS URL (assets published under /public/laraview/)
                                // If your server exposes /laraview/ directly, change to asset('laraview/index.html')
                                $viewerBase = asset('public/laraview/index.html');
                                $pdfFolder  = 'Certificate PDFs';
                                $viewerSrc  = $viewerBase
                                            . '#../' . rawurlencode($pdfFolder)
                                            . '/'    . rawurlencode($certificate->certificate_pdf);

                                $collapseId = 'pdfViewerCollapse-' . $certificate->id;
                                $toggleId   = 'togglePdfHeaderBtn-' . $certificate->id;
                            @endphp

                            <div class="card mt-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    {{-- This button looks like plain text and toggles the collapse --}}
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
                                        If it doesn’t load, <a href="{{ route('certificate.downloadPdf', $certificate->id) }}" target="_blank">download</a>.
                                    </small>
                                </div>

                                <div class="collapse" id="{{ $collapseId }}">
                                    <div class="card-body p-0" style="height: 75vh;">
                                        {{-- Lazy-load the viewer only when opened --}}
                                        <iframe
                                            data-viewer-src="{{ $viewerSrc }}"
                                            title="Certificate PDF"
                                            style="width:100%; height:100%; border:0;"
                                            allow="fullscreen"
                                            loading="lazy"></iframe>
                                    </div>
                                </div>
                            </div>

                            {{-- Toggle & lazy-load logic --}}
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const collapseEl = document.getElementById('{{ $collapseId }}');
                                    const btn        = document.getElementById('{{ $toggleId }}');
                                    if (!collapseEl || !btn) return;

                                    const iframe = collapseEl.querySelector('iframe');

                                    collapseEl.addEventListener('show.bs.collapse', function () {
                                        // Load the viewer src only on first open
                                        if (!iframe.getAttribute('src')) {
                                            iframe.setAttribute('src', iframe.dataset.viewerSrc);
                                        }
                                        btn.setAttribute('aria-expanded', 'true');
                                    });

                                    collapseEl.addEventListener('hide.bs.collapse', function () {
                                        btn.setAttribute('aria-expanded', 'false');
                                        // Optional: unload to free memory
                                        // iframe.removeAttribute('src');
                                    });
                                });
                            </script>
                        @else
                            <div class="alert alert-warning mt-4">
                                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                                No certificate PDF uploaded yet.
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endisset

        @include('layouts.footer')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
