@extends('core::dashboard.traditional.layouts.app')

@section('title', __('Dashboard'))

@section('content')
    <!-- Dashboard Content -->
    <div class="dashboard-content">
        <div class="page-header">
            <h1 class="page-title">{{ __('Welcome to Dashboard') }}</h1>
            <p class="page-subtitle">{{ __('Here is an overview of your store performance today') }}</p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">{{ __('Total Sales') }}</h3>
                    <div class="stat-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
                <div class="stat-value">${{ number_format($totalSales, 2) }}</div>
                <div class="stat-change {{ $salesGrowth >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-arrow-{{ $salesGrowth >= 0 ? 'up' : 'down' }}"></i>
                    <span>{{ $salesGrowth >= 0 ? '+' : '' }}{{ number_format($salesGrowth, 1) }}%</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">{{ __('New Orders Today') }}</h3>
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($newOrders) }}</div>
                <div class="stat-change {{ $ordersGrowth >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-arrow-{{ $ordersGrowth >= 0 ? 'up' : 'down' }}"></i>
                    <span>{{ $ordersGrowth >= 0 ? '+' : '' }}{{ number_format($ordersGrowth, 1) }}%</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">{{ __('New Customers Today') }}</h3>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($newCustomers) }}</div>
                <div class="stat-change {{ $customersGrowth >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-arrow-{{ $customersGrowth >= 0 ? 'up' : 'down' }}"></i>
                    <span>{{ $customersGrowth >= 0 ? '+' : '' }}{{ number_format($customersGrowth, 1) }}%</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">{{ __('Average Orders per Customer') }}</h3>
                    <div class="stat-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($conversionRate, 2) }}</div>
                <div class="stat-change {{ $conversionGrowth >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-arrow-{{ $conversionGrowth >= 0 ? 'up' : 'down' }}"></i>
                    <span>{{ $conversionGrowth >= 0 ? '+' : '' }}{{ number_format($conversionGrowth, 1) }}%</span>
                </div>
            </div>
        </div>

        <!-- Additional Stats -->
        <div class="stats-grid" style="margin-top: 2rem;">
            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">{{ __('Total Customers') }}</h3>
                    <div class="stat-icon">
                        <i class="fas fa-user-friends"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($totalCustomersCount) }}</div>
                <div class="stat-change positive">
                    <i class="fas fa-info-circle"></i>
                    <span>{{ __('Active customers with orders') }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">{{ __('Average Order Value') }}</h3>
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div class="stat-value">${{ number_format($averageOrderValue, 2) }}</div>
                <div class="stat-change positive">
                    <i class="fas fa-chart-line"></i>
                    <span>{{ __('Per order') }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">{{ __('Active Products') }}</h3>
                    <div class="stat-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($activeProducts) }}</div>
                <div class="stat-change positive">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ __('Available for sale') }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">{{ __('Pending Orders') }}</h3>
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($pendingOrders) }}</div>
                <div class="stat-change {{ $pendingOrders > 0 ? 'negative' : 'positive' }}">
                    <i class="fas fa-{{ $pendingOrders > 0 ? 'exclamation-triangle' : 'check-circle' }}"></i>
                    <span>{{ __('Require attention') }}</span>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">{{ __('Monthly Sales') }}</h3>
                    <div class="chart-actions">
                        <button class="chart-btn active" data-period="7">{{ __('7 Days') }}</button>
                        <button class="chart-btn" data-period="30">{{ __('30 Days') }}</button>
                        <button class="chart-btn" data-period="90">{{ __('90 Days') }}</button>
                    </div>
                </div>
                <div class="chart-body" style="height: 300px; position: relative;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">{{ __('Recent Activities') }}</h3>
                </div>
                <div class="activity-list">
                    @forelse($activities as $activity)
                        <div class="activity-item {{ $activity['url'] ? 'clickable' : '' }}"
                            @if ($activity['url']) onclick="window.location.href='{{ $activity['url'] }}'" style="cursor: pointer;" @endif>
                            <div class="activity-icon {{ $activity['type'] }}">
                                <i class="fas fa-{{ $activity['icon'] }}"></i>
                            </div>
                            <div class="activity-content">
                                <h4 class="activity-title">{{ $activity['title'] }}</h4>
                                <p class="activity-desc">{{ $activity['description'] }}</p>
                            </div>
                            <div class="activity-time">{{ $activity['time']->diffForHumans() }}</div>
                        </div>
                    @empty
                        <div class="activity-item">
                            <div class="activity-content" style="width: 100%; text-align: center; padding: 2rem;">
                                <p style="color: var(--text-secondary);">{{ __('No recent activities') }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // التحقق من تحميل Chart.js
            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded!');
                return;
            }

            // بيانات المبيعات
            const salesData7Days = @json($salesData7Days);
            const salesData30Days = @json($salesData30Days);
            const salesData90Days = @json($salesData90Days);

            // التحقق من وجود البيانات
            console.log('Sales Data 7 Days:', salesData7Days);
            console.log('Sales Data 30 Days:', salesData30Days);
            console.log('Sales Data 90 Days:', salesData90Days);

            // الحصول على الثيم الحالي
            function getTheme() {
                return document.documentElement.getAttribute('data-theme') || 'dark';
            }

            const isDark = getTheme() === 'dark';
            const textColor = isDark ? '#ffffff' : '#111827';
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';
            const backgroundColor = isDark ? 'rgba(5, 150, 105, 0.1)' : 'rgba(5, 150, 105, 0.1)';
            const borderColor = isDark ? '#10b981' : '#059669';

            // إنشاء الرسم البياني
            const ctx = document.getElementById('salesChart');
            if (!ctx) {
                console.error('Chart canvas element not found!');
                return;
            }

            let salesChart = null;
            let currentData = salesData7Days;

            function createChart(data) {
                // التحقق من وجود البيانات
                if (!data || data.length === 0) {
                    console.warn('No data available for chart');
                    return;
                }

                if (salesChart) {
                    salesChart.destroy();
                }

                salesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(item => item.label),
                        datasets: [{
                            label: '{{ __('Sales') }} ($)',
                            data: data.map(item => item.sales),
                            borderColor: borderColor,
                            backgroundColor: backgroundColor,
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: borderColor,
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    color: textColor,
                                    font: {
                                        size: 12,
                                        weight: 'normal'
                                    },
                                    padding: 15,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' :
                                    'rgba(255, 255, 255, 0.95)',
                                titleColor: textColor,
                                bodyColor: textColor,
                                borderColor: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
                                borderWidth: 1,
                                padding: 12,
                                callbacks: {
                                    label: function(context) {
                                        return '{{ __('Sales') }}: $' + Number(context.parsed.y)
                                            .toFixed(2);
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: gridColor,
                                    drawBorder: false
                                },
                                ticks: {
                                    color: textColor,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: gridColor,
                                    drawBorder: false
                                },
                                ticks: {
                                    color: textColor,
                                    font: {
                                        size: 11
                                    },
                                    callback: function(value) {
                                        return '$' + Number(value).toFixed(0);
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // إنشاء الرسم البياني الأولي
            createChart(currentData);

            // تبديل الفترات
            document.querySelectorAll('.chart-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    // إزالة active من جميع الأزرار
                    document.querySelectorAll('.chart-btn').forEach(b => b.classList.remove(
                        'active'));
                    // إضافة active للزر المضغوط
                    this.classList.add('active');

                    const period = this.getAttribute('data-period');
                    if (period === '7') {
                        currentData = salesData7Days;
                    } else if (period === '30') {
                        currentData = salesData30Days;
                    } else if (period === '90') {
                        currentData = salesData90Days;
                    }

                    createChart(currentData);
                });
            });

            // تحديث الرسم البياني عند تغيير الثيم
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'data-theme') {
                        const newTheme = getTheme();
                        const newIsDark = newTheme === 'dark';
                        const newTextColor = newIsDark ? '#ffffff' : '#111827';
                        const newGridColor = newIsDark ? 'rgba(255, 255, 255, 0.1)' :
                            'rgba(0, 0, 0, 0.05)';

                        if (salesChart) {
                            salesChart.options.plugins.legend.labels.color = newTextColor;
                            salesChart.options.plugins.tooltip.backgroundColor = newIsDark ?
                                'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.95)';
                            salesChart.options.plugins.tooltip.titleColor = newTextColor;
                            salesChart.options.plugins.tooltip.bodyColor = newTextColor;
                            salesChart.options.scales.x.grid.color = newGridColor;
                            salesChart.options.scales.x.ticks.color = newTextColor;
                            salesChart.options.scales.y.grid.color = newGridColor;
                            salesChart.options.scales.y.ticks.color = newTextColor;
                            salesChart.update();
                        }
                    }
                });
            });

            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['data-theme']
            });
        });
    </script>
@endpush
