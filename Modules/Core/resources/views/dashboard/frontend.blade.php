<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $store->name }} - متجر إلكتروني</title>
    
    <!-- Bootstrap 5 RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--success-color));
            color: white;
            padding: 4rem 0;
        }
        
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .category-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1;
        }
        
        .footer {
            background-color: #343a40;
            color: white;
            padding: 3rem 0 1rem;
        }
        
        .social-links a {
            color: white;
            margin: 0 10px;
            font-size: 1.5rem;
            transition: color 0.3s ease;
        }
        
        .social-links a:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-store me-2"></i>
                {{ $store->name }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#products">المنتجات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">من نحن</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">اتصل بنا</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#cart">
                            <i class="fas fa-shopping-cart me-1"></i>
                            السلة
                            <span class="badge bg-danger">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#search">
                            <i class="fas fa-search"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">مرحباً بكم في {{ $store->name }}</h1>
            <p class="lead mb-4">{{ $store->description ?: 'اكتشف تشكيلة واسعة من المنتجات عالية الجودة' }}</p>
            <div class="d-flex gap-3 justify-content-center">
                <a href="#products" class="btn btn-light btn-lg">
                    <i class="fas fa-shopping-bag me-2"></i>
                    تسوق الآن
                </a>
                <a href="#about" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-info-circle me-2"></i>
                    اعرف المزيد
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="bg-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-shipping-fast fa-2x text-white"></i>
                    </div>
                    <h5>شحن سريع</h5>
                    <p class="text-muted">شحن مجاني لجميع الطلبات فوق 200 ريال</p>
                </div>
                
                <div class="col-md-4 text-center">
                    <div class="bg-success rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-shield-alt fa-2x text-white"></i>
                    </div>
                    <h5>ضمان الجودة</h5>
                    <p class="text-muted">جميع المنتجات مضمونة الجودة</p>
                </div>
                
                <div class="col-md-4 text-center">
                    <div class="bg-warning rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-headset fa-2x text-white"></i>
                    </div>
                    <h5>دعم فني</h5>
                    <p class="text-muted">دعم فني متاح على مدار الساعة</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">منتجاتنا المميزة</h2>
                <p class="lead text-muted">اكتشف تشكيلة واسعة من المنتجات عالية الجودة</p>
            </div>
            
            <div class="row g-4">
                <!-- Sample Products (Placeholder) -->
                <div class="col-lg-3 col-md-6">
                    <div class="card product-card h-100">
                        <div class="position-relative">
                            <img src="https://via.placeholder.com/300x200/007bff/ffffff?text=منتج+1" class="card-img-top" alt="منتج 1">
                            <span class="badge bg-danger category-badge">جديد</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">منتج مميز</h5>
                            <p class="card-text text-muted">وصف مختصر للمنتج</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 text-primary mb-0">199 ريال</span>
                                <button class="btn btn-primary btn-sm">
                                    <i class="fas fa-cart-plus me-1"></i>
                                    أضف للسلة
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card product-card h-100">
                        <div class="position-relative">
                            <img src="https://via.placeholder.com/300x200/28a745/ffffff?text=منتج+2" class="card-img-top" alt="منتج 2">
                            <span class="badge bg-success category-badge">متوفر</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">منتج عادي</h5>
                            <p class="card-text text-muted">وصف مختصر للمنتج</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 text-primary mb-0">99 ريال</span>
                                <button class="btn btn-primary btn-sm">
                                    <i class="fas fa-cart-plus me-1"></i>
                                    أضف للسلة
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card product-card h-100">
                        <div class="position-relative">
                            <img src="https://via.placeholder.com/300x200/ffc107/ffffff?text=منتج+3" class="card-img-top" alt="منتج 3">
                            <span class="badge bg-warning category-badge">عرض</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">منتج مخفض</h5>
                            <p class="card-text text-muted">وصف مختصر للمنتج</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 text-primary mb-0">
                                    <del class="text-muted">299 ريال</del>
                                    <span class="text-danger">199 ريال</span>
                                </span>
                                <button class="btn btn-primary btn-sm">
                                    <i class="fas fa-cart-plus me-1"></i>
                                    أضف للسلة
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card product-card h-100">
                        <div class="position-relative">
                            <img src="https://via.placeholder.com/300x200/dc3545/ffffff?text=منتج+4" class="card-img-top" alt="منتج 4">
                            <span class="badge bg-info category-badge">مطلوب</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">منتج شائع</h5>
                            <p class="card-text text-muted">وصف مختصر للمنتج</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 text-primary mb-0">149 ريال</span>
                                <button class="btn btn-primary btn-sm">
                                    <i class="fas fa-cart-plus me-1"></i>
                                    أضف للسلة
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <a href="#" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-eye me-2"></i>
                    عرض جميع المنتجات
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-5 fw-bold mb-4">من نحن</h2>
                    <p class="lead mb-4">{{ $store->description ?: 'نحن متجر إلكتروني متخصص في تقديم أفضل المنتجات لعملائنا الكرام. نسعى دائماً لتوفير تجربة تسوق مميزة وجودة عالية.' }}</p>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>جودة عالية</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>أسعار منافسة</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>شحن سريع</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>دعم فني</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://via.placeholder.com/600x400/6c757d/ffffff?text=صورة+المتجر" class="img-fluid rounded" alt="صورة المتجر">
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">اتصل بنا</h2>
                <p class="lead text-muted">نحن هنا للإجابة على استفساراتكم</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow">
                        <div class="card-body p-4">
                            <form>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">الاسم</label>
                                            <input type="text" class="form-control" id="name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">البريد الإلكتروني</label>
                                            <input type="email" class="form-control" id="email" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">الموضوع</label>
                                    <input type="text" class="form-control" id="subject" required>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">الرسالة</label>
                                    <textarea class="form-control" id="message" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    إرسال الرسالة
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card border-0 shadow">
                        <div class="card-body p-4">
                            <h5 class="mb-4">معلومات التواصل</h5>
                            <div class="mb-3">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                <span>العنوان: الرياض، المملكة العربية السعودية</span>
                            </div>
                            <div class="mb-3">
                                <i class="fas fa-phone text-primary me-2"></i>
                                <span>الهاتف: +966 50 123 4567</span>
                            </div>
                            <div class="mb-3">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <span>البريد: info@{{ $store->domain }}.com</span>
                            </div>
                            <div class="mb-3">
                                <i class="fas fa-clock text-primary me-2"></i>
                                <span>ساعات العمل: 8:00 ص - 10:00 م</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="mb-3">{{ $store->name }}</h5>
                    <p class="text-muted">{{ $store->description ?: 'متجر إلكتروني متخصص في تقديم أفضل المنتجات لعملائنا الكرام.' }}</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2">
                    <h6 class="mb-3">روابط سريعة</h6>
                    <ul class="list-unstyled">
                        <li><a href="#home" class="text-muted text-decoration-none">الرئيسية</a></li>
                        <li><a href="#products" class="text-muted text-decoration-none">المنتجات</a></li>
                        <li><a href="#about" class="text-muted text-decoration-none">من نحن</a></li>
                        <li><a href="#contact" class="text-muted text-decoration-none">اتصل بنا</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3">
                    <h6 class="mb-3">خدماتنا</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">الشحن والتوصيل</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">سياسة الإرجاع</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">الشروط والأحكام</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">سياسة الخصوصية</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3">
                    <h6 class="mb-3">النشرة الإخبارية</h6>
                    <p class="text-muted">اشترك للحصول على آخر العروض والتحديثات</p>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="بريدك الإلكتروني">
                        <button class="btn btn-primary" type="button">اشتراك</button>
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            <div class="text-center">
                <p class="text-muted mb-0">
                    جميع الحقوق محفوظة &copy; {{ date('Y') }} {{ $store->name }}
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Add to cart functionality (placeholder)
        document.querySelectorAll('.btn-primary').forEach(button => {
            if (button.textContent.includes('أضف للسلة')) {
                button.addEventListener('click', function() {
                    const badge = document.querySelector('.badge.bg-danger');
                    let count = parseInt(badge.textContent) || 0;
                    badge.textContent = count + 1;
                    
                    // Show success message
                    this.innerHTML = '<i class="fas fa-check me-1"></i>تم الإضافة';
                    this.classList.remove('btn-primary');
                    this.classList.add('btn-success');
                    
                    setTimeout(() => {
                        this.innerHTML = '<i class="fas fa-cart-plus me-1"></i>أضف للسلة';
                        this.classList.remove('btn-success');
                        this.classList.add('btn-primary');
                    }, 2000);
                });
            }
        });
    </script>
</body>
</html>
