<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Department;
use Illuminate\Support\Collection;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class OrganizationalChart extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Fire;

    protected string $view = 'filament.pages.organizational-chart';
    protected static ?string $title = 'Struktur Organisasi';

    public array $chartData = [];

    public function mount(): void
    {
        $departments = Department::with('employees.user')->get();
        // Membangun struktur pohon dari data departemen yang flat
        $this->chartData = $this->buildTree($departments);
    }

    /**
     * Membangun struktur data pohon (tree) secara rekursif.
     */
    private function buildTree(Collection $elements, $parentId = null): array
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element->parent_id == $parentId) {
                // Menyiapkan node untuk departemen
                $departmentNode = [
                    'name' => $element->name,
                    'employees' => [],
                    'children' => []
                ];

                // Menambahkan karyawan ke dalam node departemen
                foreach ($element->employees as $employee) {
                    if ($employee->user) {
                        $departmentNode['employees'][] = [
                            'name' => $employee->user->name,
                            'title' => $employee->position ?? 'N/A',
                        ];
                    }
                }

                // Mencari dan menambahkan sub-departemen (anak) secara rekursif
                $children = $this->buildTree($elements, $element->id);
                if ($children) {
                    $departmentNode['children'] = $children;
                }
                
                $branch[] = $departmentNode;
            }
        }

        return $branch;
    }
}
