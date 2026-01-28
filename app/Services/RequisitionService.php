<?php

namespace App\Services;

use App\Models\PurchaseRequisitionForm;
use Carbon\Carbon;

class RequisitionService
{
  public function getStatusCounters($monthYear)
  {
    [$month, $year] = explode('-', $monthYear);
    // $month = request('month', Carbon::now()->month);
    // $year = request('year', Carbon::now()->year);

    $start = Carbon::create($year, $month, 1)->startOfMonth();
    $end = Carbon::create($year, $month, 1)->endOfMonth();

    $user = auth()->user();
    $userId = $user->id;
    $userRole = $user->role_id;

    $pending = PurchaseRequisitionForm::where('status', 0)
      ->whereBetween('created_at', [$start, $end])
      ->when($userRole != 1, fn($q) => $q->where('request_by', $userId))
      ->count();

    $approved = PurchaseRequisitionForm::where('status', 1)
      ->whereBetween('created_at', [$start, $end])
      ->when($userRole != 1, fn($q) => $q->where('request_by', $userId))
      ->count();

    $rejected = PurchaseRequisitionForm::where('status', 2)
      ->whereBetween('created_at', [$start, $end])
      ->when($userRole != 1, fn($q) => $q->where('request_by', $userId))
      ->count();
    
    $inProgress = PurchaseRequisitionForm::where('status', 3)
      ->whereBetween('created_at', [$start, $end])
      ->when($userRole != 1, fn($q) => $q->where('request_by', $userId))
      ->count();

    $executed = PurchaseRequisitionForm::where('status', 4)
      ->whereBetween('created_at', [$start, $end])
      ->when($userRole != 1, fn($q) => $q->where('request_by', $userId))
      ->count();
    
    $completed = PurchaseRequisitionForm::where('status', 5)
      ->whereBetween('created_at', [$start, $end])
      ->when($userRole != 1, fn($q) => $q->where('request_by', $userId))
      ->count();

    $forApproval = PurchaseRequisitionForm::where('status', 0)
      ->whereBetween('created_at', [$start, $end])
      ->where(function ($query) use ($userId) {
          $query->whereHas('requestBy', function ($q) use ($userId) {
              $q->where('approver_id', $userId);
          })
          ->orWhere(function ($query) use ($userId) {
              $query->whereHas('requestBy', function ($q) {
                  $q->whereNull('approver_id');
              })
              ->whereHas('departmentApprover', function ($q) use ($userId) {
                  $q->where('approver', $userId);
              });
          });
      })
      ->count();

    $total = $pending + $approved + $rejected + $inProgress + $executed + $completed;

    return compact('pending', 'approved', 'rejected', 'inProgress', 'executed', 'completed', 'total', 'forApproval');
  }
}

