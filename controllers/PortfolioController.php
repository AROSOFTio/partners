<?php
require_once __DIR__ . '/../models/PortfolioItem.php';
require_once __DIR__ . '/../lib/helpers.php';

class PortfolioController
{
    public function index()
    {
        $items = PortfolioItem::all();
        view('portfolio/index', ['items' => $items]);
    }
}
