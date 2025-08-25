<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TÜV Austria BIC CVS | Deleted Calibration Certificates</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <style>
        .container { max-width: 99%; }
        .table-container { overflow-x: auto; }
        .table-striped tbody td, .table-striped thead th { vertical-align: middle; }
        .table-striped thead th {
            text-align: left;
            position: sticky;
            top: 0;
            background-color: rgb(243, 243, 243);
            border-right: 1px solid #dee2e6;
        }
        .table-striped thead th:last-child { border-right: none; }
        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn i { font-size: 16px; }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .table-striped { font-size: 11px; }
    </style>
</head>
<body background="images/tuv-login-background1.jpg">
<section style="padding-top: 60px;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="text-end">Logged in User: <b>{{ auth()->user()->name }} ({{ auth()->user()->designation }})</b></h6>
                        <h3 class="text-center mb-3">TÜV Austria BIC - Calibration Certificate Verification System (CVS)</h3>
                        <table class="mx-auto mb-3" style="width: 80%;">
                            <tr>
                                <td><a href="add-certificate" class="btn btn-success"><i class="fa-solid fa-plus me-1"></i> Add New Certificate</a></td>
                                <td><a href="dashboard" class="btn btn-primary"><i class="fa-solid fa-arrow-left me-1"></i> Dashboard</a></td>
                                <td><a href="imports-exports" class="btn btn-warning"><i class="fa-solid fa-file-import me-1"></i> Import/Export</a></td>
                                <td><a href="all-users" class="btn btn-secondary"><i class="fa-solid fa-users me-1"></i> View All Users</a></td>
                                <td><a href="logout" class="btn btn-danger"><i class="fa-solid fa-right-from-bracket me-1"></i> Log Out</a></td>
                            </tr>
                        </table>
                        <table style="width:35%; margin: auto;">
                            <tr><td><input type="text" class="form-control my-1 search-input" placeholder="Search Certificates"/></td></tr>
                        </table>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped search-result">
                            <thead>
                                <tr><th colspan="12" class="text-center fs-5 fw-bold">Deleted Calibration Certificates</th></tr>
                                <tr>
                                    <th>Sl.</th>
                                    <th>Certificate No</th>
                                    <th>Calibration Engg</th>
                                    <th>Client</th>
                                    <th>Equipment</th>
                                    <th>Calibration Date</th>
                                    <th>Report Issue Date</th>
                                    <th>Validity</th>
                                    <th>Status</th>
                                    <th>QR Code</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="card-footer">{{ $certificates->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        function fetchCertificates(page = 1, userInput = '') {
            $.ajax({
                url: "{{ url('live-search-deleted') }}",
                data: { userInput: userInput, page: page },
                dataType: 'json',
                beforeSend: function() {
                    $(".search-result tbody").html('<tr><td colspan="11">Searching...</td></tr>');
                },
                success: function(res) {
                    let html = '';
                    $.each(res.data.data, function(index, data) {
                        const url = "{{ url('') }}" + "?search=" + data.certificate_number;
                        html += '<tr>' +
                            '<td>' + (index + 1 + (res.data.current_page - 1) * res.data.per_page) + '.</td>' +
                            '<td>' + (data.certificate_number ?? '') + '</td>' +
                            '<td>' + (data.calibrator ?? '') + '</td>' +
                            '<td>' + (data.client_name ?? '') + '</td>' +
                            '<td>' + (data.equipment_name ?? '') + '</td>' +
                            '<td>' + formatDate(data.calibration_date) + '</td>' +
                            '<td>' + formatDate(data.report_issue_date) + '</td>' +
                            '<td>' + (data.validity_date ? formatDate(data.validity_date) : 'N/A') + '</td>' +
                            '<td>' + (data.status ?? '') + '</td>' +
                            '<td><img src="' + generateQRCode(url) + '"/></td>' +
                            '<td><a href="view-certificate/' + data.id + '" target="_blank"><i class="fa-solid fa-circle-info" title="View"></i></a></td>' +
                            '</tr>';
                    });
                    if (html === '') {
                        html = '<tr><td colspan="11" class="text-center">No matching certificates found.</td></tr>';
                    }
                    $(".search-result tbody").html(html);
                    $('.pagination').remove();
                    $('.card-body').append(generatePaginationLinks(res.data));
                }
            });
        }

        function formatDate(date) {
            if (!date) return 'N/A';
            const d = new Date(date);
            if (isNaN(d)) return date; // keep original if parse fails
            return ('0' + d.getDate()).slice(-2) + '-' + ('0' + (d.getMonth() + 1)).slice(-2) + '-' + d.getFullYear();
        }

        function generateQRCode(url) {
            return 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' + encodeURIComponent(url);
        }

        function generatePaginationLinks(data) {
            let paginationLinks = '<nav class="pagination-container"><ul class="pagination">';
            if (data.current_page > 1) {
                paginationLinks += '<li class="page-item"><a class="page-link" href="#" data-page="' + (data.current_page - 1) + '">&laquo;</a></li>';
            }
            for (let i = 1; i <= data.last_page; i++) {
                paginationLinks += '<li class="page-item' + (i === data.current_page ? ' active' : '') + '"><a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>';
            }
            if (data.current_page < data.last_page) {
                paginationLinks += '<li class="page-item"><a class="page-link" href="#" data-page="' + (data.current_page + 1) + '">&raquo;</a></li>';
            }
            paginationLinks += '</ul></nav>';
            return paginationLinks;
        }

        $(".search-input").on('keyup', function() {
            fetchCertificates(1, $(this).val());
        });

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            fetchCertificates($(this).attr('data-page'), $('.search-input').val());
        });

        fetchCertificates();
    });
</script>
</body>
<footer>@include('layouts.footer')</footer>
</html>