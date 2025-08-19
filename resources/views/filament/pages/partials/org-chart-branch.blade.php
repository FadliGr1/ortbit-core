@props(['branch'])

<li>
    <div class="node">
        <div class="department-name">{{ $branch['name'] }}</div>
        @if (!empty($branch['employees']))
            <div class="employees">
                @foreach ($branch['employees'] as $employee)
                    <div class="employee">
                        {{ $employee['name'] }}
                        <div class="employee-title">{{ $employee['title'] }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @if (!empty($branch['children']))
        <ul>
            @foreach ($branch['children'] as $childBranch)
                {{-- Pastikan pemanggilan rekursif menggunakan @include --}}
                @include('filament.pages.partials.org-chart-branch', ['branch' => $childBranch])
            @endforeach
        </ul>
    @endif
</li>
