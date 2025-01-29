<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Repository\Task\DBMenuInterface;

class MasterMenuController extends Controller
{
    private $mastermenuRepository;

    public function __construct(DBMenuInterface $mastermenuRepository)
    {
        $this->mastermenuRepository = $mastermenuRepository->model('dbmenu');
    }

    public function index()
    {
        $mastermenu = $this->mastermenuRepository->firstOrNew();
        //$menu = $this->mastermenuRepository->model('dbmenu')->firstOrNew();

       // return view('berkas.mastermenu', compact('mastermenu', 'kodemenu'));
       return view('berkas.mastermenu', compact('mastermenu', 'kodemenu'));
    }

}
