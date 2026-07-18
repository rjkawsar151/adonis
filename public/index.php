<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Never use a config cache generated on another machine. Laravel config
// caches contain absolute paths, so uploading a local cache breaks sessions.
$cachedConfigFile = __DIR__ . '/../bootstrap/cache/config.php';
if (is_file($cachedConfigFile)) {
    $cachedConfig = require $cachedConfigFile;
    $cachedSessionPath = $cachedConfig['session']['files'] ?? null;
    $currentProjectPath = realpath(__DIR__ . '/..');

    if ($cachedSessionPath && $currentProjectPath) {
        $normalizedSessionPath = str_replace('\\', '/', $cachedSessionPath);
        $normalizedProjectPath = str_replace('\\', '/', $currentProjectPath);

        if (!str_starts_with($normalizedSessionPath, $normalizedProjectPath . '/')) {
            @unlink($cachedConfigFile);
        }
    }
}

// Some deployment tools omit empty, dotfile-only Laravel runtime folders.
// Recreate them before the session middleware or compiled views are loaded.
$runtimeDirectories = [
    __DIR__ . '/../storage/framework/cache/data',
    __DIR__ . '/../storage/framework/sessions',
    __DIR__ . '/../storage/framework/testing',
    __DIR__ . '/../storage/framework/views',
    __DIR__ . '/../storage/logs',
    __DIR__ . '/../bootstrap/cache',
];

foreach ($runtimeDirectories as $runtimeDirectory) {
    if (!is_dir($runtimeDirectory)) {
        mkdir($runtimeDirectory, 0775, true);
    }
}

if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
