<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToModel;

/*StudentImport class created to be use in Excel::import()
For ToModel method syntax
*/
class StudentImport implements ToModel
{
    public function model(array $row)
    {
        //old inefficient code
        // //update features
        // $student = Student::where([
        //     ['name', '=', $row[0]],
        //     ['email', '=', $row[1]]
        // ]);

        // if($student->exists())
        // {
        //     $student = Student::find($student->first()->id);
        //     $student->name = $row[0];
        //     $student->email = $row[1];
        //     $student->address = $row[2];
        //     $student->study_course = $row[3];
        //     $student->save();
        // } else {
        //     return new Student([
        //         'name'     => $row[0], //row 1 = data about name
        //         'email'    => $row[1], //row 2 = data about email
        //         'address'    => $row[2], //row 3 = data about address
        //         'study_course'    => $row[3], //row 4 = data about course
        //      ]);
        // }

        //update features
        $student = Student::firstOrNew([
            'name' => $row[0],
            'email' => $row[1]
        ]);

        $student->address = $row[2];
        $student->study_course = $row[3];
        $student->save();
    }
}

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
        if ($request->operation === 'refresh')
        {
            Student::truncate(); //remove all records
        }

        //add or update new records
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx'
        ]); //ensure excel file only.

        $file = $request->file('file');
        $data = Excel::import(new StudentImport, $file);

        return response()->json(['success' => 'Student data imported successfully']);
    }
}
