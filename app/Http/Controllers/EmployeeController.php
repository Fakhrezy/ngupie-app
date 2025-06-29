<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = [
            ['id' => 1, 'name' => 'John Doe', 'position' => 'Head Barista', 'department' => 'Coffee Bar', 'salary' => 5500000, 'join_date' => '2023-01-15', 'status' => 'Active'],
            ['id' => 2, 'name' => 'Jane Smith', 'position' => 'Barista', 'department' => 'Coffee Bar', 'salary' => 4200000, 'join_date' => '2023-02-20', 'status' => 'Active'],
            ['id' => 3, 'name' => 'Mike Johnson', 'position' => 'Cashier', 'department' => 'Front Office', 'salary' => 3800000, 'join_date' => '2023-03-10', 'status' => 'Active'],
            ['id' => 4, 'name' => 'Sarah Wilson', 'position' => 'Assistant Barista', 'department' => 'Coffee Bar', 'salary' => 3500000, 'join_date' => '2023-04-05', 'status' => 'Active'],
            ['id' => 5, 'name' => 'David Brown', 'position' => 'Store Manager', 'department' => 'Management', 'salary' => 7000000, 'join_date' => '2023-05-12', 'status' => 'Active'],
        ];

        return view('employees.index', compact('employees'));
    }
}
