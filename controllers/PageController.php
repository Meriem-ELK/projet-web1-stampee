<?php
namespace App\Controllers;
use App\Providers\View;


class PageController {
    public function about() {
        return View::render('about');
    }


    public function contact() {
        return View::render('contact');
    }
}
?>