<?php

namespace App\Http\Controllers\Student;

use Stripe\Charge;
use Stripe\Stripe;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EnrollmentController extends Controller
{
    public function enroll(Request $request)
{
       
    $request->validate([
        'course_id' => 'required|exists:courses,id',
        'payment_token' => 'required'
    ]);
    
    $user = auth()->user();
    $courseId = $request->course_id;

    $course = Course::findOrFail($courseId);

    
     // Check if already enrolled
     if (Enrollment::where('user_id', $user->id)->where('course_id', $courseId)->exists()) {
        return response()->json(['message' => 'Already enrolled in this course.'], 400);
    }

    // Set Stripe secret key
    Stripe::setApiKey(env('STRIPE_SECRET'));

    try {
        $charge = Charge::create([
            'amount' => $course->price * 100, // Stripe uses cents
            'currency' => 'usd',
            'description' => 'Payment for ' . $course->title,
            'source' => $request->payment_token,
            'metadata' => [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],
            'shipping' => [
                    'name' => $user->name,  // Customer's name
                    'address' => [
                        'line1' => 'test address',  // Address line 1 or add address in user table and write $user->address or pass through api body
                        'country' => 'IN',  // Country code for India
                    ],
                ],
        ]);

        // Create enrollment
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $courseId,
            'status' => 'paid',
        ]);

        return response()->json([
            'message' => 'Payment successful & enrolled!',
            'enrollment' => $enrollment,
            'charge_id' => $charge->id
        ]);

    } catch (\Exception $e) {
        return response()->json(['message' => 'Payment failed: ' . $e->getMessage()], 500);
    }

}
    
    
}
