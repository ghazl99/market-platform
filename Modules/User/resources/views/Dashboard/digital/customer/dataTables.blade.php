@forelse($users as $k=>$user)
    <tr id="user-row-{{ $user->id }}">
        <td>
            <div class="user-info">
                <div class="user-avatar">
                    @if ($user->profile_photo_url ?? false)
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="rounded-circle"
                            width="40" height="40">
                    @else
                        {{ substr($user->name, 0, 1) }}
                    @endif
                </div>
                <div class="user-details">
                    <h6><a href="{{ route('dashboard.customer.show', $user->id) }}"
                            style="color: inherit; text-decoration: none;">{{ $user->name }}</a></h6>
                    <p>{{ __('ID') }}: #{{ $user->id }}</p>
                </div>
            </div>
        </td>
        <td>{{ $user->email }}</td>
        <td style="vertical-align: middle;">
            @if ($user->group)
                <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 0.25rem;">
                    <span class="group-badge">{{ $user->group->name }}</span>
                    <small class="text-muted">({{ $user->group->profit_percentage }}%)</small>
                </div>
            @else
                <span class="text-muted">{{ __('No Group') }}</span>
            @endif
        </td>
        <td>{{ $user->created_at->format('Y-m-d') }}</td>
        <td>
            @if ($user->hasVerifiedEmail())
                <span class="status-badge active">{{ __('Verified') }}</span>
            @else
                <span class="status-badge pending">{{ __('Unverified') }}</span>
            @endif
        </td>
        <td>
            @if ($user->last_login_at)
                <span class="role-badge">{{ $user->last_login_at->format('Y-m-d H:i') }}</span>
            @else
                <span class="role-badge">{{ __('Never') }}</span>
            @endif
        </td>
        <td>
            <span class="role-badge">{{ number_format((float) ($user->debt_limit ?? 0), 2) }}</span>
        </td>
        <td>
            <div class="action-buttons">
                <button class="action-btn view" onclick="viewCustomer({{ $user->id }})">
                    <i class="fas fa-eye"></i>
                    {{ __('View') }}
                </button>
                <button class="action-btn edit" onclick="editCustomer({{ $user->id }})">
                    <i class="fas fa-edit"></i>
                    {{ __('Edit') }}
                </button>
                <button class="action-btn delete delete-customer-btn" data-customer-id="{{ $user->id }}"
                    data-customer-name="{{ $user->name }}">
                    <i class="fas fa-trash"></i>
                    {{ __('Delete') }}
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">{{ __('No customers found') }}</h5>
            <p class="text-muted">{{ __('No customers match your search criteria') }}</p>
        </td>
    </tr>
@endforelse
