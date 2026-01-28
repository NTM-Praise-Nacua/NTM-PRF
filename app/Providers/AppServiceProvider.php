<?php

namespace App\Providers;

use App\Models\PurchaseRequisitionForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        View::composer('layouts.include.sidebar', function ($view) {
            $userId = Auth::id();

            $pendingApproval = PurchaseRequisitionForm::where('status', 0)
                ->whereHas('requestBy', function ($query) use ($userId) {
                    $query->where('approver_id', $userId);
                })
                ->count();

            $assignedCounter = PurchaseRequisitionForm::where(function ($query) use ($userId) {
                $query->whereIn('status', [1, 2, 3])
                    ->where('assign_employee', $userId);
                })
                ->orWhere(function ($query) use ($userId) {
                    $query->where('status', 0)
                        ->where(function ($q) use ($userId) {
                            $q->whereHas('requestBy', fn($q) => $q->where('approver_id', $userId))
                                ->orWhereHas('departmentApprover', fn($q) => $q->where('approver', $userId));
                        });
                })
                ->count();

            $view->with([
                'sidebarCounters' => $pendingApproval,
                'requisitionCounter' => $assignedCounter,
            ]);
        });
    }
}
