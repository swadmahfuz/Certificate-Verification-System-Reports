@extends('layouts.admin')

@section('title', 'Import / Export')

@section('content')
<div class="page-heading">
    <div>
        <h1>Import / Export</h1>
        <p>Bulk import and export report certificate data.</p>
    </div>
    <a class="btn btn-outline-primary btn-sm" href="{{ route('certificates.index') }}">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Certificates
    </a>
</div>

<section class="admin-card">
    <div class="admin-card-header">
        <h2>Data Tools</h2>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('export') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-file-export me-1"></i> Export Database
            </a>
            <a href="./downloads/TUVAT CVS Reports - Data Import Template.xlsx" class="btn btn-info btn-sm">
                <i class="fa-solid fa-download me-1"></i> Download Blank Template
            </a>
            <a href="./downloads/TUVAT CVS Reports - Sample Data File.xlsx" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-file-lines me-1"></i> Download Sample Data
            </a>
        </div>
    </div>
    <div class="admin-card-body">
        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Upload Excel file</label>
                <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
            </div>
            <p class="text-muted small mb-1">Please upload an MS Excel sheet using the import template above.</p>
            <p class="text-danger small mb-3"><strong>Do not change template formatting. All dates must be YYYY-MM-DD (e.g. 2024-05-20).</strong></p>
            <button class="btn btn-success" type="submit">
                <i class="fa-solid fa-file-import me-1"></i> Import Data
            </button>
        </form>
    </div>
</section>
@endsection
