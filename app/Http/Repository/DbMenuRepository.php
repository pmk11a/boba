<?php


use App\Http\Repository\Task\DBMenuInterface;
use Illuminate\Support\Facades\Storage;

class DbMenuRepository extends BaseRepository implements DBMENUInterface
{
    public function __construct()
    {
        parent::__construct(['DbMenu', 'KODEMENU']);
    }
}
