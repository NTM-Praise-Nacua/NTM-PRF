<?php

namespace App\Policies;

use App\Models\PurchaseRequisitionForm;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseRequisitionFormPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchaseRequisitionForm  $purchaseRequisitionForm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, PurchaseRequisitionForm $purchaseRequisitionForm)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchaseRequisitionForm  $purchaseRequisitionForm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, PurchaseRequisitionForm $purchaseRequisitionForm)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchaseRequisitionForm  $purchaseRequisitionForm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, PurchaseRequisitionForm $purchaseRequisitionForm)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchaseRequisitionForm  $purchaseRequisitionForm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, PurchaseRequisitionForm $purchaseRequisitionForm)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchaseRequisitionForm  $purchaseRequisitionForm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, PurchaseRequisitionForm $purchaseRequisitionForm)
    {
        //
    }

    public function edit(User $user, PurchaseRequisitionForm $requisition): bool
    {
        // 1. Assigned user
        if ($requisition->assign_employee === $user->id) {
            return true;
        }

        // 2. Requestor
        if ($requisition->request_by === $user->id) {
            return true;
        }

        $approver = $requisition->requestBy->approver_id ?? $requisition->departmentApprover->approver;
        // 3. Immediate head of the requestor
        if ($approver === $user->id) {
            return true;
        }

        // 4. Member of assigned department (if no assigned user)
        if (is_null($requisition->assign_employee)) {
            if ($requisition->next_department === $user->department_id) {
                return true;
            }
        }

        // 5. Super Admin
        if ($user->role_id === 1) {
            return true;
        }

        return false;
    }

}
