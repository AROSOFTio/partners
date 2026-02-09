<?php
require_once __DIR__ . '/../models/Package.php';
require_once __DIR__ . '/../models/PortfolioItem.php';
require_once __DIR__ . '/../lib/helpers.php';

class HomeController
{
    public function index()
    {
        $packages = Package::allActiveWithMeta();
        $popularPackages = Package::popular(3);
        $packageGroups = Package::splitByType($packages);
        $whatsappNumber = preg_replace('/\D+/', '', (string)config_value('contact.whatsapp_number', ''));
        $featured = PortfolioItem::featured(6);
        view('home/index', [
            'packages' => $packages,
            'popularPackages' => $popularPackages,
            'packageGroups' => $packageGroups,
            'whatsappNumber' => $whatsappNumber,
            'featured' => $featured,
        ]);
    }
}
