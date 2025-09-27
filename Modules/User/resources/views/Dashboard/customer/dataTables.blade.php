@foreach ($users as $k=>$user)
    <tr id="user-row-{{ $user->id }}">
        <td>{{ $k+1 }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>
            @if ($user->profile_photo_url ?? false)
                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="rounded-circle" width="40"
                    height="40">
            @else
                <i class="fas fa-user-circle fa-2x text-muted"></i>
            @endif
        </td>
        <td>
            @if ($user->hasVerifiedEmail())
                <span class="badge bg-success">{{ __('Verified') }}</span>
            @else
                <span class="badge bg-warning">{{ __('Unverified') }}</span>
            @endif
        </td>
        <td>{{ $user->created_at->format('Y-m-d') }}</td>
        <td>{{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : '-' }}</td>

    </tr>
@endforeach
