<?php

namespace App\Policies;

use App\Models\Payslip;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PayslipPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Izinkan jika user punya permission
        return $user->can('view_any_payslip');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Payslip $payslip): bool
    {
        // Izinkan jika user punya permission ATAU jika itu adalah slip gajinya sendiri
        if ($user->can('view_payslip')) {
            return true;
        }

        return $user->employee?->id === $payslip->employee_id;
    }

    /**
     * Determine whether the user can create models.
     * (Biasanya false karena payslip di-generate, bukan dibuat manual)
     */
    public function create(User $user): bool
    {
        return $user->can('create_payslip');
    }

    /**
     * Determine whether the user can update the model.
     * (Biasanya false untuk menjaga integritas data)
     */
    public function update(User $user, Payslip $payslip): bool
    {
        return $user->can('update_payslip');
    }

    /**
     * Determine whether the user can delete the model.
     * (Biasanya false untuk menjaga integritas data)
     */
    public function delete(User $user, Payslip $payslip): bool
    {
        return $user->can('delete_payslip');
    }

    /**
     * Determine whether the user can download the payslip PDF.
     */
    public function downloadPdf(User $user, Payslip $payslip): bool
    {
        // Izinkan jika user punya permission ATAU jika itu adalah slip gajinya sendiri
        if ($user->can('download_payslip_pdf')) {
            return true;
        }

        return $user->employee?->id === $payslip->employee_id;
    }
}
