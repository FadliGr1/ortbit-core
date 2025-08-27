<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Models\Brand;                 
use App\Policies\BrandPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy; 
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\HttpException; 
use Filament\Notifications\Notification;  
use Illuminate\Http\RedirectResponse;
use App\Models\Attendance;
use App\Policies\AttendancePolicy;
use App\Models\Employee;
use App\Policies\EmployeePolicy;
use App\Models\Leave;
use App\Policies\LeavePolicy;
use App\Models\Department;
use App\Policies\DepartmentPolicy;
use App\Models\PerformanceReview;
use App\Policies\PerformanceReviewPolicy;

use Illuminate\Support\Facades\Schema;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (HttpException $e, $request) {
            if ($e->getStatusCode() === 403) {
                if ($request->is('orbit/*')) {
                    Notification::make()
                        ->title('Oww Oww!')
                        ->body('What are you doing? You do not have permission to access this page.')
                        ->danger()
                        ->send();

                    return new RedirectResponse(route('filament.orbit.pages.dashboard'));
                }
            }
        });
    })->create();

    Gate::policy(Brand::class, BrandPolicy::class);
    Gate::policy(Role::class, RolePolicy::class);  
    Gate::policy(User::class, UserPolicy::class);
    Gate::policy(Employee::class, EmployeePolicy::class);  
    Gate::policy(Attendance::class, AttendancePolicy::class);
    Gate::policy(Leave::class, LeavePolicy::class);
    Gate::policy(Department::class, DepartmentPolicy::class);
    Gate::policy(PerformanceReview::class, PerformanceReviewPolicy::class);


    Gate::define('approve_leave', [LeavePolicy::class, 'approve']);
    Gate::define('reject_leave', [LeavePolicy::class, 'reject']);
