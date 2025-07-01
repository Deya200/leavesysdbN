@php
<ul class="nav flex-column mt-4">
@foreach ($sidebarMenu as $item)
    @if (in_array(auth()->user()->role_id, $item['roles']))
        @if (isset($item['divider']) && $item['divider'])
            <li><hr class="sidebar-divider"></li>
        @elseif (isset($item['submenu']))
            <li class="nav-item">
                <a class="nav-link parent-toggle"
                   data-bs-toggle="collapse"
                   href="#submenu-{{ Str::slug($item['label']) }}"
                   aria-expanded="{{ request()->routeIs(collect($item['submenu'])->pluck('route')->toArray()) ? 'true' : 'false' }}"
                   aria-controls="submenu-{{ Str::slug($item['label']) }}">
                    <i class="{{ $item['icon'] }}"></i>
                    <span class="menu-text">{{ $item['label'] }}</span>
                    <i class="fas fa-angle-right dropdown-arrow"></i>
                </a>
                <div class="collapse {{ request()->routeIs(collect($item['submenu'])->pluck('route')->toArray()) ? 'show' : '' }}"
                     id="submenu-{{ Str::slug($item['label']) }}">
                    <ul class="submenu">
                        @foreach ($item['submenu'] as $sub)
                            @if(empty($sub['button']))
                                <li>
                                    <a class="nav-link {{ request()->routeIs($sub['route']) ? 'active' : '' }}"
                                       href="{{ route($sub['route']) }}">
                                        @if(!empty($sub['icon']))<i class="{{ $sub['icon'] }}"></i>@endif {{ $sub['label'] }}
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a class="nav-link btn btn-outline-primary text-white {{ request()->routeIs($sub['route']) ? 'active' : '' }}"
                                       href="{{ route($sub['route']) }}">
                                        {{ $sub['label'] }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}"
                   href="{{ route($item['route']) }}">
                    <i class="{{ $item['icon'] }}"></i>
                    <span class="menu-text">{{ $item['label'] }}</span>
                    @if(isset($item['badge']))
                        <span class="badge bg-danger">{{ $$item['badge'] ?? '' }}</span>
                    @endif
                </a>
            </li>
        @endif
    @endif
@endforeach
</ul>
@endphp
