<?php

return [
    'enabled' => env('SHEETS_ENABLED', false),
    'spreadsheet_id' => env('SHEETS_SPREADSHEET_ID'),
    'range' => env('SHEETS_RANGE', 'Presensi!A:Z'),
    'service_account_json' => env('GOOGLE_SERVICE_ACCOUNT_JSON'), // path storage/app/.. atau inline JSON
];
