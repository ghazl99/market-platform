@extends('themes.app')
@section('title', __('My Notifications'))
@push('styles')
    <style>
         :root {
            --primary-color: #7C3AED;
            /* Purple 600 */
            --primary-dark: #5B21B6;
            /* Purple 800 */
            --primary-light: #C4B5FD;
            /* Purple 300 */
            --secondary-color: #8B5CF6;
            --secondary-dark: #6D28D9;
            --success-color: #10B981;
            --error-color: #EF4444;
            --warning-color: #F59E0B;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
            --text-light: #9CA3AF;
            --bg-primary: #F9FAFB;
            --bg-secondary: #FFFFFF;
            --bg-accent: #F3E8FF;
            --border-color: #E5E7EB;
            --border-light: #EDE9FE;
            --gradient-primary: linear-gradient(135deg, #8B5CF6, #7C3AED);
        }
        /* Notifications Page Specific Styles */
        .notifications-section {
            padding: 2rem 0;
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
            min-height: calc(100vh - 80px);
            position: relative;
        }

        .notifications-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
        }

        /* Page Header */
        .notifications-header {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .notifications-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .notifications-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin: 0;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Notifications List */
        .notifications-list {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
        }

        .notification-item {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    margin-bottom: 1rem;
    background: var(--bg-secondary);
    border-radius: 16px;
    border: 2px solid var(--border-color);
    transition: all 0.3s ease;
    position: relative;
}

/* الإشعار غير المقروء */
.notification-item.unread {
    border-left: 4px solid var(--primary-color);
    background: rgba(59, 130, 246, 0.08); /* خلفية زرقاء خفيفة */
}

/* الإشعار المقروء */
.notification-item:not(.unread) {
    background: rgba(255, 255, 255, 0.6); /* 🔹 خلفية فاتحة جدًا */
    opacity: 0.85;
}


        .notification-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .notification-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .notification-message {
            font-size: 1rem;
            color: var(--text-secondary);
        }

        .notification-time {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .empty-state p {
            font-size: 1rem;
            margin: 0;
        }
    </style>
@endpush
@section('content')
    <div class="notifications-section">
        <div class="notifications-container">
            <!-- Page Header -->
            <div class="notifications-header">
                <h2 class="notifications-title">{{ __('My Notifications') }}</h2>
            </div>

            <!-- Notifications List -->
            <div class="notifications-list">
                @php
                    $locale = app()->getLocale(); // 'ar' أو 'en'
                @endphp
                @forelse(auth()->user()->notifications as $notification)
                    @php
                        $data = is_array($notification->data)
                            ? $notification->data
                            : json_decode($notification->data, true);
                    @endphp
                    <a href="{{ route('notification.read', $notification->id) }}" style="text-decoration: none" class="notification-item {{ $notification->read_at ? '' : 'unread' }}">
                        <div class="notification-info">
                            <h4 class="notification-title">{{ $data['title'][$locale] ?? $data['title']['en'] }}</h4>
                            <p class="notification-message">{{ $data['message'][$locale] ?? $data['message']['en'] }}</p>
                            <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                    </a>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-bell"></i>
                        <h3>{{ __('No notifications') }}</h3>
                        <p>{{ __('You have no notifications at the moment.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
