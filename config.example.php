<?php
// Copy this file to config.php and fill in your settings
return [
    'db' => [
        'host' => 'localhost',
        'dbname' => 'storeuploads',
        'user' => 'dbuser',
        'pass' => 'dbpass',
    ],
    'admin_password' => 'changeme',
    'service_account_json' => __DIR__.'/service-account.json',
    'drive_base_folder' => '',
    'calendar_media_dir' => __DIR__ . '/public/calendar_media',
    // Base directory for storing local copies of uploads
    'local_upload_dir' => __DIR__ . '/public/uploads',
    'notification_email' => 'admin@example.com',
    'google_oauth' => [
        'client_id' => '',
        'client_secret' => '',
        // URL to google_callback.php in your installation
        'redirect_uri' => 'http://yourdomain.com/admin/google_callback.php',
    ],
    'timezone' => 'America/New_York',
];
