<?php
require_once __DIR__ . '/../models/Package.php';
require_once __DIR__ . '/../lib/helpers.php';

class PackageController
{
    public function index()
    {
        $packages = Package::allActiveWithMeta();
        $packageGroups = Package::splitByType($packages);
        $popularPackages = Package::popular(4);
        $whatsappNumber = preg_replace('/\D+/', '', (string)config_value('contact.whatsapp_number', ''));

        view('packages/index', [
            'packages' => $packages,
            'packageGroups' => $packageGroups,
            'popularPackages' => $popularPackages,
            'whatsappNumber' => $whatsappNumber,
        ]);
    }

    public function view($slug = null)
    {
        $slug = $slug ?: ($_GET['slug'] ?? null);
        if (!$slug) {
            http_response_code(404);
            echo 'Package not found';
            return;
        }
        $package = Package::findBySlug($slug);
        if (!$package) {
            http_response_code(404);
            echo 'Package not found';
            return;
        }
        view('packages/view', ['package' => $package]);
    }
}
