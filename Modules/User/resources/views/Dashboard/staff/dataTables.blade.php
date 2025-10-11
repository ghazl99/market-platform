@foreach ($users as $k => $user)
    @php
        $isActive = $user->stores->firstWhere('id', $store->id)?->pivot->is_active ?? false;
    @endphp
    <tr id="user-row-{{ $user->id }}">
        <td>
            <div class="staff-name">
                <i class="fas fa-user"></i>
                <span>{{ $user->name }}</span>
            </div>
        </td>
        <td>
            <span class="email-text">{{ $user->email }}</span>
        </td>
        <td>
            @if ($user->profile_photo_url ?? false)
                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="rounded-circle" width="40"
                    height="40">
            @else
                <div class="avatar-placeholder">
                    <i class="fas fa-user-circle"></i>
                </div>
            @endif
        </td>
        <td>
            @if ($isActive)
                <span class="status-badge verified">{{ __('متفعل') }}</span>
            @else
                <span class="status-badge unverified">{{ __('غير متفعل') }}</span>
            @endif
        </td>
        <td>
            <span class="date-text">{{ $user->created_at->format('Y-m-d') }}</span>
        </td>
        <td>
            <span class="date-text">{{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : '-' }}</span>
        </td>
        <td>
            <div class="action-buttons">
                <button class="action-btn toggle-btn {{ $isActive ? 'deactivate' : 'activate' }}"
                    onclick="toggleActivation({{ $user->id }})" id="toggle-btn-{{ $user->id }}"
                    data-user-id="{{ $user->id }}" data-is-active="{{ $isActive ? 'true' : 'false' }}"
                    title="{{ $isActive ? __('إلغاء التفعيل') : __('تفعيل') }}">
                    <i class="fas {{ $isActive ? 'fa-pause' : 'fa-play' }}"></i>
                    <span class="btn-text">{{ $isActive ? __('إلغاء التفعيل') : __('تفعيل') }}</span>
                </button>

                <a href="{{ route('dashboard.staff.edit', $user->id) }}" class="action-btn edit"
                    title="{{ __('إدارة الصلاحيات') }}">
                    <i class="fas fa-user-shield"></i>
                    <span class="btn-text">{{ __('الصلاحيات') }}</span>
                </a>
            </div>
        </td>
    </tr>
@endforeach
