@extends('core::layouts.app')

@section('title', __('E-Commerce Platform') . ' - ' . __('The Best Solution for E-Commerce'))

@section('content')
    <!-- Hero Section -->
    <section class="hero" id="home">
        <!-- Animated Background -->
        <div class="hero-background">
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
                <div class="shape shape-4"></div>
                <div class="shape shape-5"></div>
            </div>
        </div>

        <div class="hero-container">
            <!-- Brand Logos Section -->
            <div class="brands-section">
                <div class="brands-header">
                    <p class="brands-title">{{ __('Join thousands of successful merchants') }}</p>
                    <div class="brands-divider"></div>
                </div>

                <div class="brands-carousel">
                    <div class="brands-track" id="brandsTrack">
                        <!-- Logos will be dynamically added here -->
                    </div>
                </div>
            </div>

            <!-- Hero Content -->
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ __('Join over 68,000 active merchants') }}</span>
                </div>

                <h1 class="hero-title">
                    <span class="title-line">{{ __('MyStore') }}</span>
                    <span class="title-line highlight">{{ __('Smart Commerce Platform') }}</span>
                    <span class="title-line">{{ __('for the future') }}</span>
                </h1>

                <p class="hero-description">
                    {{ __('Create your online store in minutes and connect your products with a complete set of smart digital solutions for payments, shipping, inventory management, and marketing easily; because your success doesn\'t need complexity.') }}
                </p>

                <div class="hero-actions">
                    <button class="btn btn-primary btn-large">
                        <i class="fas fa-rocket"></i>
                        <span>{{ __('Create Your Store for Free') }}</span>
                    </button>
                    <button class="btn btn-outline btn-large">
                        <i class="fas fa-play"></i>
                        <span>{{ __('Watch Demo') }}</span>
                    </button>
                </div>

                <!-- Stats Section -->
                <div class="hero-stats">
                    <div class="stats-header">
                        <h3 class="stats-title">{{ __('Numbers speak for themselves') }}</h3>
                        <p class="stats-subtitle">{{ __('Join thousands of successful merchants') }}</p>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number" data-target="68000">0</div>
                                <div class="stat-label">{{ __('Active Merchants') }}</div>
                                <div class="stat-description">{{ __('Successful Online Store') }}</div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number" data-target="24">0</div>
                                <div class="stat-label">{{ __('Support Hours') }}</div>
                                <div class="stat-description">{{ __('Premium Customer Service') }}</div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number" data-target="50">0</div>
                                <div class="stat-label">{{ __('Countries') }}</div>
                                <div class="stat-description">{{ __('Global Reach') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="stats-footer">
                        <div class="trust-indicators">
                            <div class="trust-item">
                                <i class="fas fa-shield-alt"></i>
                                <span>{{ __('SSL Security') }}</span>
                            </div>
                            <div class="trust-item">
                                <i class="fas fa-lock"></i>
                                <span>{{ __('Data Protection') }}</span>
                            </div>
                            <div class="trust-item">
                                <i class="fas fa-certificate"></i>
                                <span>{{ __('Certified') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hero Visual - Separate Section -->
        <div class="hero-visual-section">
            <div class="hero-visual">
                <div class="dashboard-preview">
                    <div class="dashboard-header">
                        <div class="dashboard-dots">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <div class="dashboard-content">
                        <div class="dashboard-sidebar">
                            <div class="sidebar-item active">
                                <i class="fas fa-chart-line"></i>
                                <span>{{ __('Dashboard') }}</span>
                            </div>
                            <div class="sidebar-item">
                                <i class="fas fa-box"></i>
                                <span>{{ __('Products') }}</span>
                            </div>
                            <div class="sidebar-item">
                                <i class="fas fa-shopping-cart"></i>
                                <span>{{ __('Orders') }}</span>
                            </div>
                            <div class="sidebar-item">
                                <i class="fas fa-users"></i>
                                <span>{{ __('Customers') }}</span>
                            </div>
                        </div>
                        <div class="dashboard-main">
                            <div class="chart-container">
                                <div class="chart-bars">
                                    <div class="bar" style="height: 60%"></div>
                                    <div class="bar" style="height: 80%"></div>
                                    <div class="bar" style="height: 45%"></div>
                                    <div class="bar" style="height: 90%"></div>
                                    <div class="bar" style="height: 70%"></div>
                                    <div class="bar" style="height: 85%"></div>
                                </div>
                            </div>
                            <div class="dashboard-cards">
                                <div class="card">
                                    <i class="fas fa-dollar-sign"></i>
                                    <div class="card-content">
                                        <span class="card-value">$12,450</span>
                                        <span class="card-label">{{ __("Today's Sales") }}</span>
                                    </div>
                                </div>
                                <div class="card">
                                    <i class="fas fa-shopping-bag"></i>
                                    <div class="card-content">
                                        <span class="card-value">156</span>
                                        <span class="card-label">{{ __('New Orders') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>


    <!-- Store Types Section -->
    <section class="store-types" id="services">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">{{ __('Store Types') }}</h2>
                <p class="section-subtitle">{{ __('We Provide Complete Solutions for All Types of E-Commerce') }}</p>
            </div>
            <div class="store-types-grid">
                <div class="store-type-card">
                    <div class="store-type-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <h3>{{ __('Traditional Stores') }}</h3>
                    <p>{{ __('Stores selling physical products and traditional goods') }}</p>
                    <ul class="store-features">
                        <li><i class="fas fa-check"></i> {{ __('Inventory Management') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('Order Tracking') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('Shipping Management') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('Sales Reports') }}</li>
                    </ul>
                    <div class="store-examples">
                        <span class="example-tag">{{ __('Clothing') }}</span>
                        <span class="example-tag">{{ __('Electronics') }}</span>
                        <span class="example-tag">{{ __('Home') }}</span>
                    </div>
                </div>

                <div class="store-type-card">
                    <div class="store-type-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>{{ __('Digital Stores') }}</h3>
                    <p>{{ __('Stores selling apps, games, and digital gift cards') }}</p>
                    <ul class="store-features">
                        <li><i class="fas fa-check"></i> {{ __('Instant App Delivery') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('Digital Gift Cards') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('Game Credits') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('Balance Management') }}</li>
                    </ul>
                    <div class="store-examples">
                        <span class="example-tag">{{ __('Apps') }}</span>
                        <span class="example-tag">{{ __('Games') }}</span>
                        <span class="example-tag">{{ __('Gift Cards') }}</span>
                    </div>
                </div>

                <div class="store-type-card">
                    <div class="store-type-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>{{ __('Educational Stores') }}</h3>
                    <p>{{ __('Platforms selling courses and educational content') }}</p>
                    <ul class="store-features">
                        <li><i class="fas fa-check"></i> {{ __('Student Management') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('Progress Tracking') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('Certificates') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('Discussion Forum') }}</li>
                    </ul>
                    <div class="store-examples">
                        <span class="example-tag">{{ __('Training Courses') }}</span>
                        <span class="example-tag">{{ __('Workshops') }}</span>
                        <span class="example-tag">{{ __('Certificates') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trusted Partners Section -->
    <section class="trusted-partners">
        <div class="container">
            <div class="partners-header">
                <h2 class="partners-title">{{ __('Trusted Partners') }}</h2>
                <p class="partners-subtitle">{{ __('We Work with the Best Companies to Ensure Your Success') }}</p>
            </div>
            <div class="partners-grid">
                <div class="partner-item">
                    <div class="partner-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3>{{ __('Payment Solutions') }}</h3>
                    <p>{{ __('Secure and Advanced Payment System') }}</p>
                </div>
                <div class="partner-item">
                    <div class="partner-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3>{{ __('Shipping Services') }}</h3>
                    <p>{{ __('Fast and Reliable Shipping') }}</p>
                </div>
                <div class="partner-item">
                    <div class="partner-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>{{ __('Advanced Analytics') }}</h3>
                    <p>{{ __('Detailed Reports on Your Store Performance') }}</p>
                </div>
                <div class="partner-item">
                    <div class="partner-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>{{ __('Mobile App') }}</h3>
                    <p>{{ __('Manage Your Store From Anywhere') }}</p>
                </div>
            </div>
        </div>
    </section>


    <!-- How It Works Section -->
    <section class="how-it-works" id="demo">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">{{ __('How MyStore Works?') }}</h2>
                <p class="section-subtitle">{{ __('3 Simple Steps to Create Your Online Store') }}</p>
            </div>
            <div class="steps-container">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>{{ __('Sign Up Free') }}</h3>
                        <p>{{ __('Create Your Account in Minutes and Start Your Business Journey') }}</p>
                    </div>
                    <div class="step-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>{{ __('Design Your Store') }}</h3>
                        <p>{{ __('Choose from hundreds of ready-made templates or design your own store') }}</p>
                    </div>
                    <div class="step-icon">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>{{ __('Start Selling') }}</h3>
                        <p>{{ __('Add your products and start selling instantly') }}</p>
                    </div>
                    <div class="step-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Pricing Section -->
    <section class="pricing-preview" id="pricing">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">{{ __('Plans That Suit Your Needs') }}</h2>
                <p class="section-subtitle">{{ __('Start Free and Scale With Your Business Growth') }}</p>
            </div>
            <div class="pricing-cards">
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3>{{ __('Free Plan') }}</h3>
                        <div class="price">
                            <span class="currency">ر.س</span>
                            <span class="amount">0</span>
                            <span class="period">/شهر</span>
                        </div>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> {{ __('Basic Online Store') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('10 Products') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('Technical Support') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('Basic Reports') }}</li>
                    </ul>
                    <button class="btn btn-outline btn-full">{{ __('Start Free') }}</button>
                </div>

                <div class="pricing-card featured">
                    <div class="pricing-badge">{{ __('Most Popular') }}</div>
                    <div class="pricing-header">
                        <h3>{{ __('Advanced Plan') }}</h3>
                        <div class="price">
                            <span class="currency">ر.س</span>
                            <span class="amount">99</span>
                            <span class="period">/شهر</span>
                        </div>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> {{ __('Advanced Online Store') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('Unlimited Products') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('24/7 Support') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('Advanced Reports') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('Mobile App') }}</li>
                    </ul>
                    <button class="btn btn-primary btn-full">{{ __('Choose This Plan') }}</button>
                </div>

                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3>{{ __('Enterprise Plan') }}</h3>
                        <div class="price">
                            <span class="currency">ر.س</span>
                            <span class="amount">299</span>
                            <span class="period">/شهر</span>
                        </div>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> {{ __('Custom Online Store') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('Unlimited Products') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('Dedicated Support') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('Custom Reports') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('Custom Mobile App') }}</li>
                        <li><i class="fas fa-check"></i> {{ __('Integration with External Systems') }}</li>
                    </ul>
                    <button class="btn btn-outline btn-full">{{ __('Contact Us') }}</button>
                </div>
            </div>
        </div>
    </section>


    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <h2 class="section-title">{{ __('Why Choose MyStore?') }}</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3>{{ __('Super Fast') }}</h3>
                    <p>{{ __('Fast and optimized performance to ensure an ideal user experience') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>{{ __('Advanced Security') }}</h3>
                    <p>{{ __('Comprehensive protection for your data and your customers\' data') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>{{ __('Fully Responsive') }}</h3>
                    <p>{{ __('Works perfectly on all devices') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>{{ __('24/7 Support') }}</h3>
                    <p>{{ __('Support team available around the clock to assist you') }}</p>
                </div>
            </div>
        </div>
    </section>
    @include('core::layouts.footer')

@endsection
