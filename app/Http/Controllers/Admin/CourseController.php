<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::latest()->get();

        if (request()->user()->role === 'student') {
            // Optionally hide internal fields for students
            $courses = $courses->makeHidden(['created_at', 'updated_at','deleted_at']);
        }
    
        return response()->json($courses);
       
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
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'thumbnail' => 'nullable|file|image',
            'price' => 'required|numeric',
            'materials.*' => 'nullable|array',
            'materials.*' => 'url',
        ]);
    
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }
    
        $course = Course::create($validated);
        return response()->json(['message'=>'Course Created!','course'=>$course], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return response()->json($course);
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
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'thumbnail' => 'nullable|file|image',
            'price' => 'sometimes|numeric',
            'materials' => 'nullable|array',
            'materials.*' => 'url',
        ]);
       
        // Replace thumbnail if uploaded
        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }

        $course->update($validated);

        return response()->json(['message'=>'Course Updated!','course'=>$course], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Course $course)
    {
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return response()->json(['message' => 'Course deleted']);
    }
}
