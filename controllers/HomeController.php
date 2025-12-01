<?php
require_once __DIR__ . '/../models/Package.php';
require_once __DIR__ . '/../models/PortfolioItem.php';
require_once __DIR__ . '/../lib/helpers.php';

class HomeController
{
    public function index()
    {
        $packages = Package::allActive();
        $featured = PortfolioItem::featured(6);
        view('home/index', [
            'packages' => $packages,
            'featured' => $featured,
        ]);
    }
}
