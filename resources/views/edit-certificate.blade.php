<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Calibration Certificate</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <style>
        body { font-size: 13px; }
        .btn {
            display: flex; align-items: center; justify-content: center;
            padding: 8px 12px; border-radius: 8px; font-size: 13px; font-weight: 600;
            transition: all 0.3s ease; white-space: nowrap;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .card-header { background-color: #f4f4f4; padding: 20px; }
        label { font-weight: 600; }
    </style>
</head>
<body background="../images/tuv-login-background1.jpg">

<section class="pt-5">
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <h3>TÜV Austria BIC CVS | Edit Calibration Certificate</h3>
                <div class="mt-3 d-flex justify-content-center">
                    <a href="../dashboard" class="btn btn-primary me-2">
                        <i class="fa-solid fa-arrow-left me-1"></i> Go back to Dashboard
                    </a>
                    <a href="../delete-certificate/{{ $certificate->id }}" class="btn btn-danger">
                        <i class="fa-solid fa-trash me-1"></i> Delete Certificate
                    </a>
                </div>
                <p class="text-end mt-2 mb-0" style="font-style: italic;">* Required fields</p>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('certificate.update') }}">
                    @csrf

                    <input type="hidden" name="id" value="{{ $certificate->id }}">

                    <div class="mb-3">
                        <label for="certificate_number">Certificate Number *</label>
                        @error('certificate_number') <div class="text-danger">{{ $message }}</div> @enderror
                        <input type="text" name="certificate_number" class="form-control" value="{{ $certificate->certificate_number }}">
                    </div>

                    <div class="mb-3">
                        <label for="calibrator">Calibration Engineer *</label>
                        @error('calibrator') <div class="text-danger">{{ $message }}</div> @enderror
                        <input type="text" name="calibrator" class="form-control" value="{{ $certificate->calibrator }}">
                    </div>

                    <div class="mb-3">
                        <label for="client_name">Client Name *</label>
                        @error('client_name') <div class="text-danger">{{ $message }}</div> @enderror
                        <input type="text" name="client_name" class="form-control" value="{{ $certificate->client_name }}">
                    </div>

                    <div class="mb-3">
                        <label for="location">Location *</label>
                        @error('location') <div class="text-danger">{{ $message }}</div> @enderror
                        <textarea name="location" class="form-control">{{ $certificate->location }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="equipment_name">Equipment Name *</label>
                        @error('equipment_name') <div class="text-danger">{{ $message }}</div> @enderror
                        <input type="text" name="equipment_name" class="form-control" value="{{ $certificate->equipment_name }}">
                    </div>

                    <div class="mb-3">
                        <label for="equipment_brand">Equipment Brand *</label>
                        @error('equipment_brand') <div class="text-danger">{{ $message }}</div> @enderror
                        <input type="text" name="equipment_brand" class="form-control" value="{{ $certificate->equipment_brand }}">
                    </div>

                    <div class="mb-3">
                        <label for="equipment_id">Equipment ID *</label>
                        @error('equipment_id') <div class="text-danger">{{ $message }}</div> @enderror
                        <input type="text" name="equipment_id" class="form-control" value="{{ $certificate->equipment_id }}">
                    </div>

                    <div class="mb-3">
                        <label for="calibration_date">Calibration Date *</label>
                        @error('calibration_date') <div class="text-danger">{{ $message }}</div> @enderror
                        <input type="date" name="calibration_date" class="form-control" value="{{ $certificate->calibration_date }}">
                    </div>

                    <div class="mb-3">
                        <label for="report_issue_date">Report Issue Date *</label>
                        @error('report_issue_date') <div class="text-danger">{{ $message }}</div> @enderror
                        <input type="date" name="report_issue_date" class="form-control" value="{{ $certificate->report_issue_date }}">
                    </div>

                    <div class="mb-3">
                        <label for="validity_date">Validity Date</label>
                        @error('validity_date') <div class="text-danger">{{ $message }}</div> @enderror
                        <input type="date" name="validity_date" class="form-control" value="{{ $certificate->validity_date }}">
                    </div>

                    <div class="mb-3">
                        <label for="calibration_remarks">Calibration Remarks</label>
                        @error('calibration_remarks') <div class="text-danger">{{ $message }}</div> @enderror
                        <textarea name="calibration_remarks" class="form-control">{{ $certificate->calibration_remarks }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="calibration_internal_notes">Internal Notes</label>
                        @error('calibration_internal_notes') <div class="text-danger">{{ $message }}</div> @enderror
                        <textarea name="calibration_internal_notes" class="form-control">{{ $certificate->calibration_internal_notes }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="review_by">Review by *</label>
                        @error('review_by') <div class="text-danger">{{ $message }}</div> @enderror
                        <select name="review_by" class="form-control">
                            <option value="">Select Reviewer</option>
                            @foreach($users as $user)
                                <option value="{{ $user->name }}" {{ $certificate->review_by == $user->name ? 'selected' : '' }}>
                                    {{ $user->name }} | {{ $user->designation }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="approval_by">Approval by *</label>
                        @error('approval_by') <div class="text-danger">{{ $message }}</div> @enderror
                        <select name="approval_by" class="form-control">
                            <option value="">Select Approver</option>
                            @foreach($users as $user)
                                <option value="{{ $user->name }}" {{ $certificate->approval_by == $user->name ? 'selected' : '' }}>
                                    {{ $user->name }} | {{ $user->designation }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-check me-1"></i> Update Details
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@include('layouts.footer')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>