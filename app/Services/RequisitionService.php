<?php

namespace App\Services;

use App\Models\PurchaseRequisitionForm;
use Carbon\Carbon;

class RequisitionService
{
  public function getStatusCounters()
  {
    $month = request('month', Carbon::now()->month);
    $year = request('year', Carbon::now()->year);

    $start = Carbon::create($year, $month, 1)->startOfMonth();
    $end = Carbon::create($year, $month, 1)->endOfMonth();

    $user = auth()->user();
    $userId = $user->id;
    $userRole = $user->role_id;

    $pending = PurchaseRequisitionForm::where('status', '!=', 1)
      ->whereBetween('created_at', [$start, $end])
      ->when($userRole != 1, fn($q) => $q->where('assign_employee', $userId))
      ->count();

    $inProgress = PurchaseRequisitionForm::where('status', '!=', 1)
      ->whereBetween('created_at', [$start, $end])
      ->when($userRole != 1, fn($q) => $q->where('request_by', $userId))
      ->count();

    $completed = PurchaseRequisitionForm::where('status', 1)
      ->whereBetween('created_at', [$start, $end])
      ->when($userRole != 1, fn($q) => $q->where('request_by', $userId))
      ->count();

    $total = $inProgress + $completed;

    return compact('pending', 'inProgress', 'completed', 'total');
  }
}

