<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class FineTestController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function index()
    {
        return view('fine-test', ['name' => 'Ariful Islam']);
    }

    public function jsontest($id)
    {
        $json = ['a' => '123', 'b' => ['c' => '23', 'd' => 10 * $id]];
        return $json;
    }

    public function fileUpload()
    {
        $file = request()->file('file1');
        //$file->store('public/files');
        $file->move('public/files', $file->getClientOriginalName());
        return 'File uploaded successfully';
    }

    public function dbTest()
    {
        $users=DB::table('users')->get();
        dd($users);
    }
}
