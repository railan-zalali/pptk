<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$routes = app('router')->getRoutes()->getRoutesByName();
$validRouteNames = array_keys($routes);

function checkFiles($dir, $validRouteNames) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    $errors = [];
    foreach ($iterator as $file) {
        if ($file->isDir()) continue;
        $ext = $file->getExtension();
        if (!in_array($ext, ['php'])) continue;

        $content = file_get_contents($file->getPathname());
        
        // Match route('some.name'
        // Match route("some.name"
        preg_match_all("/route\(\s*['\"]([^'\"]+)['\"]/i", $content, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $routeName) {
                // Ignore dynamic routes if they contain $ or other variables
                if (strpos($routeName, '$') !== false || strpos($routeName, '{') !== false) continue;
                
                if (!in_array($routeName, $validRouteNames)) {
                    $errors[] = [
                        'file' => $file->getPathname(),
                        'route' => $routeName
                    ];
                }
            }
        }
    }
    return $errors;
}

$errors = [];
$errors = array_merge($errors, checkFiles(__DIR__.'/resources/views', $validRouteNames));
$errors = array_merge($errors, checkFiles(__DIR__.'/app/Http/Controllers', $validRouteNames));

if (empty($errors)) {
    echo "All routes are valid!\n";
} else {
    echo "Found undefined routes:\n";
    foreach ($errors as $error) {
        echo "- File: " . $error['file'] . " => Route: " . $error['route'] . "\n";
    }
}
