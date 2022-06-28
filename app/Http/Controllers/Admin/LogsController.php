<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Models\Log;
use App\Models\Gallery;


class LogsController extends Controller
{
    public function index()
    {
        $logs = Log::orderBy('id', 'desc')->get();

        return view('admin.logs.index', compact('logs'));
    }

    public function edit($id)
    {
        $log = Log::findOrFail($id);

        return view('admin.logs.edit', compact('log'));
    }

    public function destroy($id)
    {
        $log = Log::findOrFail($id);
        $log->delete();

        session()->flash('message', 'Item has been deleted!');

        return redirect()->route('logs.index');
    }

}
