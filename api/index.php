<?php

declare(strict_types=1);

// Siapkan folder sementara yang dapat ditulis di Vercel.
$temporaryPath = sys_get_temp_dir() . '/logbook-magang';

$directories = [
    $temporaryPath,
    $temporaryPath . '/views',
    $temporaryPath . '/bootstrap',
];

foreach ($directories as $directory) {
    if (
        ! is_dir($directory)
        && ! mkdir($directory, 0755, true)
        && ! is_dir($directory)
    ) {
        http_response_code(500);

        exit('Folder sementara aplikasi tidak dapat dibuat.');
    }
}

// Atur lokasi cache sebelum Laravel dimuat.
$environment = [
    'VIEW_COMPILED_PATH' => $temporaryPath . '/views',
    'APP_PACKAGES_CACHE' => $temporaryPath . '/bootstrap/packages.php',
    'APP_SERVICES_CACHE' => $temporaryPath . '/bootstrap/services.php',
    'APP_CONFIG_CACHE' => $temporaryPath . '/bootstrap/config.php',
    'APP_EVENTS_CACHE' => $temporaryPath . '/bootstrap/events.php',
    'APP_ROUTES_CACHE' => $temporaryPath . '/bootstrap/routes.php',
];

foreach ($environment as $name => $value) {
    putenv($name . '=' . $value);

    $_ENV[$name] = $value;
    $_SERVER[$name] = $value;
}

require dirname(__DIR__) . '/public/index.php';