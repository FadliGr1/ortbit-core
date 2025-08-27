<?php

namespace App\Policies;

use App\Models\PerformanceReview;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PerformanceReviewPolicy
{
    use HandlesAuthorization;

    /**
     * Metode 'before' dihapus agar semua hak akses, termasuk untuk Super Admin,
     * bisa diatur sepenuhnya melalui permission di dashboard.
     */

    /**
     * Tentukan apakah user bisa melihat daftar semua performance reviews.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_performance_review');
    }

    /**
     * Tentukan apakah user bisa melihat detail sebuah performance review.
     */
    public function view(User $user, PerformanceReview $performanceReview): bool
    {
        // Logika disederhanakan agar bergantung penuh pada permission.
        // Logika untuk menampilkan data khusus (misal: hanya untuk reviewer)
        // sebaiknya ditangani di getEloquentQuery() pada resource, bukan di policy.
        return $user->can('view_performance_review');
    }

    /**
     * Tentukan apakah user bisa membuat performance review baru.
     */
    public function create(User $user): bool
    {
        return $user->can('create_performance_review');
    }

    /**
     * Tentukan apakah user bisa mengedit sebuah performance review.
     */
    public function update(User $user, PerformanceReview $performanceReview): bool
    {
        return $user->can('update_performance_review');
    }

    /**
     * Tentukan apakah user bisa menghapus sebuah performance review.
     */
    public function delete(User $user, PerformanceReview $performanceReview): bool
    {
        return $user->can('delete_performance_review');
    }
}
