<x-filament-panels::page>
    <div class="org-chart-container">
        <div class="org-chart">
            {{-- Memulai render pohon dari level paling atas --}}
            @if (!empty($this->chartData))
                <ul>
                    <li>
                        <div class="node root-node">Orbit Core Team</div>
                        <ul>
                            @foreach ($this->chartData as $department)
                                {{-- Memanggil partial untuk setiap cabang --}}
                                @include('filament.pages.partials.org-chart-branch', [
                                    'branch' => $department,
                                ])
                            @endforeach
                        </ul>
                    </li>
                </ul>
            @else
                <p style="text-align: center; padding: 2rem;">Tidak ada data departemen untuk ditampilkan.</p>
            @endif
        </div>
    </div>

    {{-- CSS Kustom untuk Bagan Organisasi --}}
    <style>
        .org-chart-container {
            width: 100%;
            overflow-x: auto;
            padding: 1.5rem;
            background-color: #f9fafb;
        }

        html.dark .org-chart-container {
            background-color: #111827;
        }

        .org-chart {
            display: inline-block;
            min-width: 100%;
        }

        .org-chart ul {
            padding-top: 20px;
            position: relative;
            transition: all 0.5s;
            display: flex;
            justify-content: center;
        }

        /* Garis horizontal yang menghubungkan sibling */
        .org-chart ul:not(:first-child)::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            border-left: 2px solid #cbd5e1;
            width: 0;
            height: 20px;
        }

        .org-chart li {
            text-align: center;
            list-style-type: none;
            position: relative;
            padding: 20px 10px 0 10px;
            transition: all 0.5s;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Garis vertikal dari node ke garis horizontal */
        .org-chart li::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            border-left: 2px solid #cbd5e1;
            width: 0;
            height: 20px;
        }

        /* Garis horizontal dari node ke garis vertikal */
        .org-chart li::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            border-top: 2px solid #cbd5e1;
            width: 100%;
            height: 20px;
        }

        /* Menghapus garis yang tidak perlu */
        .org-chart li:only-child {
            padding-top: 0;
        }

        .org-chart li:only-child::before,
        .org-chart li:only-child::after {
            display: none;
        }

        .org-chart li:first-child::after {
            left: 50%;
            width: 50%;
            border-radius: 5px 0 0 0;
        }

        .org-chart li:last-child::after {
            width: 50%;
            border-radius: 0 5px 0 0;
        }

        .org-chart>ul>li>ul>li::after {
            border-top: none;
        }

        .org-chart>ul>li::before,
        .org-chart>ul>li::after {
            display: none;
            /* Menghapus garis di atas root node */
        }

        html.dark .org-chart li::before,
        html.dark .org-chart li::after,
        html.dark .org-chart ul:not(:first-child)::before {
            border-color: #4b5563;
        }

        /* Styling Node/Kotak */
        .org-chart .node {
            padding: 12px 16px;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            display: inline-block;
            min-width: 180px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        html.dark .org-chart .node {
            background-color: #1f2937;
            border-color: #374151;
        }

        .org-chart .node.root-node {
            font-weight: 600;
            font-size: 1.1rem;
            background-color: #dbeafe;
        }

        html.dark .org-chart .node.root-node {
            background-color: #1e40af;
        }

        .org-chart .department-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: #111827;
        }

        html.dark .org-chart .department-name {
            color: #f9fafb;
        }

        .org-chart .employees {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #f3f4f6;
            text-align: left;
            font-size: 0.8rem;
        }

        html.dark .org-chart .employees {
            border-top-color: #374151;
        }

        .org-chart .employee {
            padding: 2px 0;
            color: #374151;
        }

        html.dark .org-chart .employee {
            color: #d1d5db;
        }

        .org-chart .employee-title {
            color: #9ca3af;
            font-size: 0.75rem;
        }
    </style>
</x-filament-panels::page>
