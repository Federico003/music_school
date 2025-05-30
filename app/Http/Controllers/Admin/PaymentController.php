<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Student;
use App\Models\User;
use App\Models\Course;

class PaymentController extends AdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = User::role('student')->get();
        //$courses = User::with('course')->get();

        return view('admin.payment.index', compact('students'));
    }

    public function getStudentCourses($studentId)
    {
        $user = User::find($studentId);
        $corsi = $user->studentCourses; // qui ottieni i corsi dello studente
        return response()->json($corsi);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
