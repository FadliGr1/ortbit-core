<?php

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BrandPolicy
{
    /**
     * Menentukan apakah pengguna bisa melihat daftar brand.
     * Pengguna bisa melihat menu "Manajemen Brand" jika memiliki hak akses 'view_any_brand'.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_brand');
    }

    /**
     * Menentukan apakah pengguna bisa melihat detail brand (tidak digunakan di Filament).
     */
    public function view(User $user, Brand $brand): bool
    {
        return $user->can('view_brand');
    }

    /**
     * Menentukan apakah pengguna bisa membuat brand baru.
     */
    public function create(User $user): bool
    {
        return $user->can('create_brand');
    }

    /**
     * Menentukan apakah pengguna bisa mengedit brand.
     */
    public function update(User $user, Brand $brand): bool
    {
        return $user->can('update_brand');
    }

    /**
     * Menentukan apakah pengguna bisa menghapus brand.
     */
    public function delete(User $user, Brand $brand): bool
    {
        return $user->can('delete_brand');
    }

    /**
     * Menentukan apakah pengguna bisa menghapus beberapa brand sekaligus (bulk delete).
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_brand');
    }

    // Metode restore dan forceDelete tidak kita gunakan karena kita tidak pakai soft delete.
}
