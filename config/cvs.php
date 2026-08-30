<?php

return [
    'app_key' => env('CVS_APP_KEY', 'reports'),

    'registration_enabled' => env('CVS_REGISTRATION_ENABLED', false),

    'apps' => [
        'training' => 'Training CVS',
        'inspection' => 'Inspection CVS',
        'calibration' => 'Calibration CVS',
        'reports' => 'Reports CVS',
        'certification' => 'BA Certification',
    ],

    'access_levels' => [
        'view' => 'View only',
        'full' => 'Full access',
    ],

    'shared_activity_subject_types' => ['auth', 'user', 'department'],

    'cache_ttl' => [
        'dashboard' => (int) env('CVS_DASHBOARD_CACHE_TTL', 300),
        'permissions' => (int) env('CVS_PERMISSIONS_CACHE_TTL', 900),
    ],

    'certificate_search' => [
        'like' => [
            'certificate_number',
            'client_name',
            'location',
            'team_members',
            'report_prepared_by',
            'report_approved_by',
        ],
        'exact' => [],
        'date_like' => [
            'report_issue_date',
            'report_validity_date',
        ],
    ],
];
