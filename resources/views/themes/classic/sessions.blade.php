@extends('themes.app')
@section('title', __('Active Sessions'))
@push('styles')
    <style>
        /* استخدم نفس ستايل orders-page لكن مع تعديل بسيط للجلسات */
        .sessions-section {
            padding: 2rem;
            min-height: calc(100vh - 80px);
        }

        .sessions-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .session-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            border-radius: 16px;
            border: 2px solid #e5e7eb;
            background: var(--bg-secondary);
            transition: all 0.3s ease;
        }

        .session-item:hover {
            border-color: var(--primary-color);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .session-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .session-device {
            font-weight: 600;
            color: var(--text-primary);
        }

        .session-ip,
        .session-last {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .logout-btn {
            padding: 0.5rem 1rem;
            border-radius: 12px;
            background: #ef4444;
            color: white;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #dc2626;
        }
    </style>
@endpush

@section('content')
    <div class="sessions-section">
        <div class="orders-container">
            <h2 class="orders-title">{{ __('Active Sessions') }}</h2>
            <div class="sessions-list">
                @forelse($sessions as $session)
                    <div class="session-item">
                        <div class="session-info">

                            <div class="session-info">
                                <strong>{{ $session->os }} - {{ $session->browser }}</strong>
                                <small>({{ $session->location }})</small>
                            </div>

                            {{-- <div class="session-ip">{{ $session->ip_address }}</div> --}}
                            <div class="session-last">
                                {{$session->last_activity}}</div>
                        </div>
                        <form action="{{ route('auth.sessions.destroy', $session->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="logout-btn">{{ __('Logout') }}</button>
                        </form>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-user-clock"></i>
                        <h3>{{ __('No active sessions') }}</h3>
                        <p>{{ __('You are not logged in on any other devices.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
