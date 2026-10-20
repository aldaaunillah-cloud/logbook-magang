<?php

declare(strict_types=1);

// Blade membutuhkan folder yang dapat ditulis saat aplikasi berjalan.
$compiledViews = sys_get_temp_dir() . '/logbook-magang/views';

if (
    ! is_dir($compiledViews)
    && ! mkdir($compiledViews, 0755, true)
    && ! is_dir($compiledViews)
) {
    http_response_code(500);

    exit('Folder sementara aplikasi tidak dapat dibuat.');
}

putenv('VIEW_COMPILED_PATH=' . $compiledViews);

$_ENV['VIEW_COMPILED_PATH'] = $compiledViews;
$_SERVER['VIEW_COMPILED_PATH'] = $compiledViews;

// Jalankan aplikasi melalui entry point Laravel.
require dirname(__DIR__) . '/public/index.php';