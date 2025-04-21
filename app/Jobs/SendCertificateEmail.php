<?php

namespace App\Jobs;

use App\Mail\CertificateMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendCertificateEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user, $course, $filePath;
    /**
     * Create a new job instance.
     */
    public function __construct($user, $course, $filePath)
    {
        $this->user = $user;
        $this->course = $course;
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Mail::to($this->user->email)->send(
            new CertificateMail($this->user, $this->course, $this->filePath)
        );
    }
}
