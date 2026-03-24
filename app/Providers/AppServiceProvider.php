<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Policies\PatientPolicy;
use App\Policies\MedicalRecordPolicy;

class AppServiceProvider extends AuthServiceProvider 
{
    protected $policies = [
        Patient::class => PatientPolicy::class,
        MedicalRecord::class => MedicalRecordPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerPolicies(); 
    }
}