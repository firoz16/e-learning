@component('mail::message')
# Congratulations {{ $user->name }}!

You’ve completed the course: **{{ $course->title }}** 🎓

Your certificate is attached with this email.

@component('mail::button', ['url' => url('/storage/' . $certificatePath)])
Download Certificate
@endcomponent

Thanks,  
{{ config('app.name') }}
@endcomponent
