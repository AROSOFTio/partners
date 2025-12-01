<?php
require_once __DIR__ . '/../models/Package.php';
require_once __DIR__ . '/../lib/helpers.php';

class PackageController
{
    public function index()
    {
        $packages = Package::allActive();
        view('packages/index', ['packages' => $packages]);
    }

    public function view($slug)
    {
        $package = Package::findBySlug($slug);
        if (!$package) {
            http_response_code(404);
            echo 'Package not found';
            return;
        }
        view('packages/view', ['package' => $package]);
    }
}
