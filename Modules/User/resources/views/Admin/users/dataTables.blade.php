@forelse($users as $user)
    <tr>
        <td>
            <div class="user-info">
                <div class="user-avatar">{{ substr($user->name, 0, 1) }}</div>
                <div class="user-details">
                    <h6>{{ $user->name }}</h6>
                    <p>{{ __('ID') }}: #{{ $user->id }}</p>
                </div>
            </div>
        </td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->phone ?? __('No phone') }}</td>
        <td>{{ $user->created_at->format('Y-m-d') }}</td>
        <td>
            @if ($user->email_verified_at)
                <span class="status-badge active">{{ __('Active') }}</span>
            @else
                <span class="status-badge pending">{{ __('Pending') }}</span>
            @endif
        </td>
        <td>
            <span class="role-badge">{{ $user->getRoleNames()->first() ?? __('Customer') }}</span>
        </td>
        <td>
            <div class="action-buttons">
                <button class="action-btn view" onclick="viewUser({{ $user->id }})">
                    <i class="fas fa-eye"></i>
                    {{ __('View') }}
                </button>
                <button class="action-btn edit" onclick="editUser({{ $user->id }})">
                    <i class="fas fa-edit"></i>
                    {{ __('Edit') }}
                </button>
                <button class="action-btn delete" onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                    <i class="fas fa-trash"></i>
                    {{ __('Delete') }}
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">{{ __('No users found') }}</h5>
            <p class="text-muted">{{ __('No users match your search criteria') }}</p>
        </td>
    </tr>
@endforelse
