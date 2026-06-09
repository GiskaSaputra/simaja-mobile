<?php
namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
        echo view('layout/header');
        echo view('/home');
        echo view('layout/footer');
    }
}
