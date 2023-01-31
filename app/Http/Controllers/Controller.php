<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Maatwebsite\Excel\Facades\Excel;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function search(Request $request)
    {
        $column = $request->name ? 'name' : 'email';
        $value = $request->name ?? $request->email;

        $student = Student::where($column, 'like', '%'. $value . '%')
            ->select('name', 'address')
            ->get();

        return response()->json($student);
    }

    public function bulkOperation(Request $request)
    {
        
    }
}
