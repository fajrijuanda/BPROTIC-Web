<?php

namespace App\Http\Controllers;

use App\Models\Major;
use Illuminate\Http\JsonResponse;

class MajorController extends Controller
{
    public function index(): JsonResponse
    {
        $majors = Major::select('id', 'name')->get();
        return response()->json($majors);
    }
}
