<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Report / Certificate</title>
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
<body background="images/tuv-login-background1.jpg">

<section class="pt-5">
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <h3>TÜV Austria BIC CVS | Add New Report / Certificate</h3>
                <div class="mt-3 d-flex justify-content-center">
                    <a href="./dashboard" class="btn btn-primary me-2">
                        <i class="fa-solid fa-arrow-left me-1"></i> Go back to Dashboard
                    </a>
                </div>
                <p class="text-end mt-2 mb-0" style="font-style: italic;">* Required fields</p>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('certificate.create') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="certificate_number">Certificate Number *</label>
                        @error('certificate_number') <div class="text-danger">{{ $message }}</div> @enderror
                        <input type="text" name="certificate_number" class="form-control"
                               value="RPT-TUVAT-{{ $currentYear }}-{{ $currentMonthDay }}-">
                    </div>

                    <div class="mb-3">
                        <label for="client_name">Client Name *</label>
                        @error('client_name') <div class="text-danger">{{ $message }}</div> @enderror
                        <input type="text" name="client_name" class="form-control" value="{{ old('client_name') }}">
                    </div>

                    <div class="mb-3">
                        <label for="location">Location</label>
                        @error('location') <div class="text-danger">{{ $message }}</div> @enderror
                        <textarea name="location" class="form-control">{{ old('location') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="team_members">Team Members</label>
                        @error('team_members') <div class="text-danger">{{ $message }}</div> @enderror
                        <input type="text" name="team_members" class="form-control" placeholder="Comma-separated"
                               value="{{ old('team_members') }}">
                    </div>

                    <div class="mb-3">
                        <label for="report_prepared_by">Report Prepared By *</label>
                        @error('report_prepared_by') <div class="text-danger">{{ $message }}</div> @enderror
                        <input type="text" name="report_prepared_by" class="form-control" value="{{ old('report_prepared_by') }}">
                    </div>

                    <div class="mb-3">
                        <label for="report_approved_by">Report Approved By *</label>
                        @error('report_approved_by') <div class="text-danger">{{ $message }}</div> @enderror
                        <input type="text" name="report_approved_by" class="form-control" value="{{ old('report_approved_by') }}">
                    </div>

                    <div class="mb-3">
                        <label for="report_issue_date">Report Issue Date *</label>
                        @error('report_issue_date') <div class="text-danger">{{ $message }}</div> @enderror
                        <input type="date" name="report_issue_date" class="form-control" value="{{ old('report_issue_date') }}">
                    </div>

                    <div class="mb-3">
                        <label for="report_validity_date">Report Validity Date</label>
                        @error('report_validity_date') <div class="text-danger">{{ $message }}</div> @enderror
                        <input type="date" name="report_validity_date" class="form-control" value="{{ old('report_validity_date') }}">
                    </div>

                    <div class="mb-3">
                        <label for="report_revision">Report Revision</label>
                        @error('report_revision') <div class="text-danger">{{ $message }}</div> @enderror
                        <input type="text" name="report_revision" class="form-control" value="{{ old('report_revision') }}">
                    </div>

                    <div class="mb-3">
                        <label for="report_remarks">Report Remarks</label>
                        @error('report_remarks') <div class="text-danger">{{ $message }}</div> @enderror
                        <textarea name="report_remarks" class="form-control">{{ old('report_remarks') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="report_internal_notes">Internal Notes</label>
                        @error('report_internal_notes') <div class="text-danger">{{ $message }}</div> @enderror
                        <textarea name="report_internal_notes" class="form-control">{{ old('report_internal_notes') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="review_by">Review by *</label>
                        @error('review_by') <div class="text-danger">{{ $message }}</div> @enderror
                        <select name="review_by" class="form-control">
                            <option value="">Select Reviewer</option>
                            @foreach($users as $user)
                                <option value="{{ $user->name }}" {{ old('review_by') == $user->name ? 'selected' : '' }}>
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
                                <option value="{{ $user->name }}" {{ old('approval_by') == $user->name ? 'selected' : '' }}>
                                    {{ $user->name }} | {{ $user->designation }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-check me-1"></i> Add Details
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
