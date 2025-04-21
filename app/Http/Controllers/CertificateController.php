<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Certificate;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;
use App\Jobs\SendCertificateEmail;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function download($courseId)
{
    $course = Course::find($courseId);

    if (!$course) {
        return response()->json([
            'message' => 'Course not found.'
        ], 404);
    }

    // Optional: Check if user is enrolled
        $isEnrolled = $course->enrollments()
                    ->where('user_id', auth()->id())
                     ->exists();

        if (!$isEnrolled) {
            return response()->json([
                'message' => 'You are not enrolled in this course.'
             ], 403);
        }
    
    $pdf = PDF::loadView('certificates.template', [
        'user' => auth()->user(),
        'course' => $course
    ]);

    $filePath = 'certificates/' . Str::uuid() . '.pdf';
    Storage::disk('public')->put($filePath, $pdf->output());

    Certificate::firstOrCreate([
        'user_id' => auth()->id(),
        'course_id' => $course->id,
    ], ['file_path' => $filePath]);

    $url= asset('storage/' . $filePath);

    //  Dispatch the email job
        SendCertificateEmail::dispatch(auth()->user(), $course, $filePath);

    return response()->json([
        'message' => 'Certificate generated!',
        'download_url' => $url
    ]);
}
}
