<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - AProsecutor Case Tracker</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* ============================================
           CSS Variables & Reset
           ============================================ */
        :root {
            --color-dark-navy: #05081A;
            --color-navy: #101B46;
            --color-navy-light: #1a2d5a;
            --color-accent: #2563EB;
            --color-accent-hover: #3b82f6;
            --color-accent-light: #60a5fa;
            --color-gold: #d4a853;
            --color-text-dark: #111827;
            --color-text-muted: #6b7280;
            --color-text-light: #9ca3af;
            --color-white: #ffffff;
            --color-card-bg: #ffffff;
            --color-input-border: #e5e7eb;
            --color-input-focus: #2563EB;
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --shadow-card: 0 25px 50px -12px rgb(0 0 0 / 0.25);
            --transition-base: all 0.2s ease-out;
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-family);
            background: linear-gradient(135deg, var(--color-dark-navy) 0%, var(--color-navy) 50%, var(--color-navy-light) 100%);
            min-height: 100vh;
            color: var(--color-text-dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* ============================================
           Animations
           ============================================ */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(2deg);
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 0.4;
            }
            50% {
                opacity: 0.8;
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }

        /* ============================================
           Header
           ============================================ */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: transparent;
            transition: var(--transition-smooth);
            animation: fadeInDown 0.6s ease-out;
        }

        .header.scrolled {
            background: rgba(5, 8, 26, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* REPLACE: Update this src with your actual logo path */
        .logo {
            height: 40px;
            width: auto;
            object-fit: contain;
        }

        .brand {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--color-white);
            letter-spacing: -0.02em;
        }

        .brand-subtitle {
            font-size: 0.75rem;
            color: var(--color-text-light);
            font-weight: 400;
        }

        .header-nav {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            list-style: none;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            position: relative;
            padding: 0.25rem 0;
            transition: var(--transition-base);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--color-accent);
            transition: var(--transition-smooth);
            transform: translateX(-50%);
        }

        .nav-link:hover {
            color: var(--color-white);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .btn-demo {
            padding: 0.625rem 1.25rem;
            background: transparent;
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            color: var(--color-white);
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition-base);
        }

        .btn-demo:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-1px);
        }

        /* ============================================
           Main Container
           ============================================ */
        .main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6rem 2rem 2rem;
        }

        .content-wrapper {
            width: 100%;
            max-width: 1280px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        /* ============================================
           Hero Section (Left)
           ============================================ */
        .hero-section {
            position: relative;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        /* Abstract floating shape */
        .floating-shape {
            position: absolute;
            top: -100px;
            right: -50px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
            pointer-events: none;
        }

        .floating-shape-2 {
            position: absolute;
            bottom: -50px;
            left: -100px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(212, 168, 83, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 10s ease-in-out infinite reverse;
            pointer-events: none;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 1rem;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            margin-bottom: 2rem;
        }

        .hero-badge-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-badge-icon svg {
            width: 16px;
            height: 16px;
            color: white;
        }

        .hero-badge-text {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }

        .hero-headline {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.03em;
            margin-bottom: 1.5rem;
        }

        .hero-headline-white {
            color: var(--color-white);
            display: block;
        }

        .hero-headline-accent {
            background: linear-gradient(135deg, var(--color-accent-light) 0%, var(--color-accent) 50%, #818cf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: block;
        }

        .hero-description {
            font-size: 1.125rem;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.7;
            margin-bottom: 2.5rem;
            max-width: 500px;
        }

        /* Features List */
        .features-list {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .feature-icon.green {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .feature-icon.blue {
            background: rgba(37, 99, 235, 0.15);
            border: 1px solid rgba(37, 99, 235, 0.3);
        }

        .feature-icon.purple {
            background: rgba(139, 92, 246, 0.15);
            border: 1px solid rgba(139, 92, 246, 0.3);
        }

        .feature-icon svg {
            width: 20px;
            height: 20px;
        }

        .feature-icon.green svg {
            color: #10b981;
        }

        .feature-icon.blue svg {
            color: #3b82f6;
        }

        .feature-icon.purple svg {
            color: #8b5cf6;
        }

        .feature-content h4 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-white);
            margin-bottom: 0.25rem;
        }

        .feature-content p {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.5;
        }

        /* ============================================
           Login Card (Right)
           ============================================ */
        .login-section {
            display: flex;
            justify-content: center;
            animation: scaleIn 0.6s ease-out 0.4s both;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: var(--color-card-bg);
            border-radius: 24px;
            box-shadow: var(--shadow-card);
            padding: 2.5rem;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--color-accent) 0%, var(--color-accent-light) 50%, #818cf8 100%);
        }

        .login-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.35);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--color-text-dark);
            margin-bottom: 0.5rem;
        }

        .login-header p {
            font-size: 0.9375rem;
            color: var(--color-text-muted);
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--color-text-dark);
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-text-light);
            pointer-events: none;
            transition: var(--transition-base);
        }

        .input-icon svg {
            width: 18px;
            height: 18px;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            font-size: 0.9375rem;
            font-family: inherit;
            color: var(--color-text-dark);
            background: #f9fafb;
            border: 2px solid var(--color-input-border);
            border-radius: 12px;
            transition: var(--transition-base);
            outline: none;
        }

        .form-input::placeholder {
            color: var(--color-text-light);
        }

        .form-input:hover,
        .form-select:hover {
            border-color: #d1d5db;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: var(--color-input-focus);
            background: var(--color-white);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .form-input:focus + .input-icon,
        .input-wrapper:focus-within .input-icon {
            color: var(--color-accent);
        }

        .form-select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        /* Password toggle */
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--color-text-light);
            cursor: pointer;
            padding: 0.25rem;
            transition: var(--transition-base);
        }

        .password-toggle:hover {
            color: var(--color-text-muted);
        }

        .password-toggle svg {
            width: 18px;
            height: 18px;
        }

        /* Remember & Forgot */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border: 2px solid var(--color-input-border);
            border-radius: 5px;
            cursor: pointer;
            accent-color: var(--color-accent);
        }

        .checkbox-wrapper span {
            font-size: 0.875rem;
            color: var(--color-text-muted);
        }

        .forgot-link {
            font-size: 0.875rem;
            color: var(--color-accent);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition-base);
        }

        .forgot-link:hover {
            color: var(--color-accent-hover);
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 600;
            font-family: inherit;
            color: var(--color-white);
            background: linear-gradient(135deg, var(--color-accent) 0%, #1d4ed8 100%);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.4);
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Register Link */
        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f3f4f6;
        }

        .register-link p {
            font-size: 0.9375rem;
            color: var(--color-text-muted);
        }

        .register-link a {
            color: var(--color-accent);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition-base);
        }

        .register-link a:hover {
            color: var(--color-accent-hover);
        }

        /* Security Notice */
        .security-notice {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
            padding: 0.75rem;
            background: #f0fdf4;
            border-radius: 8px;
        }

        .security-notice svg {
            width: 16px;
            height: 16px;
            color: #16a34a;
        }

        .security-notice span {
            font-size: 0.75rem;
            color: #166534;
        }

        /* Error Messages */
        .error-message {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            padding: 0.5rem 0.75rem;
            background: #fef2f2;
            border-radius: 6px;
            font-size: 0.8125rem;
            color: #dc2626;
        }

        .error-message svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
        }

        /* ============================================
           Responsive Design
           ============================================ */
        @media (max-width: 1024px) {
            .content-wrapper {
                gap: 3rem;
            }

            .hero-headline {
                font-size: 2.75rem;
            }

            .nav-links {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 1rem;
            }

            .btn-demo {
                display: none;
            }

            .main-container {
                padding: 5rem 1rem 2rem;
            }

            .content-wrapper {
                grid-template-columns: 1fr;
                gap: 2.5rem;
            }

            .hero-section {
                text-align: center;
                order: 2;
            }

            .login-section {
                order: 1;
            }

            .hero-badge {
                margin-left: auto;
                margin-right: auto;
            }

            .hero-headline {
                font-size: 2.25rem;
            }

            .hero-description {
                margin-left: auto;
                margin-right: auto;
            }

            .features-list {
                align-items: center;
            }

            .feature-item {
                max-width: 320px;
            }

            .floating-shape,
            .floating-shape-2 {
                display: none;
            }

            .login-card {
                padding: 2rem 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .logo {
                height: 32px;
            }

            .brand-name {
                font-size: 1rem;
            }

            .hero-headline {
                font-size: 1.875rem;
            }

            .hero-description {
                font-size: 1rem;
            }

            .login-card {
                border-radius: 20px;
            }

            .form-options {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }

        /* ============================================
           Mobile Menu (Optional Enhancement)
           ============================================ */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--color-white);
            padding: 0.5rem;
            cursor: pointer;
        }

        .mobile-menu-btn svg {
            width: 24px;
            height: 24px;
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- ============================================
         Header
         ============================================ -->
    <header class="header" id="header">
        <div class="header-left">
            <!-- REPLACE: Update this src with your actual logo path -->
            <img src="{{ asset('images/logo.png') }}" alt="AProsecutor Logo" class="logo" style="border-radius: 15px;">
            <div class="brand">
                <span class="brand-name">AProsecutor</span>
                <span class="brand-subtitle">Case Tracker</span>
            </div>
        </div>
        
        <nav class="header-nav">
            <ul class="nav-links">
                <li><a href="{{ route('overview') }}" class="nav-link">Overview</a></li>
                <li><a href="{{ route('features') }}" class="nav-link">Features</a></li>
                <li><a href="{{ route('support') }}" class="nav-link">Support</a></li>
            </ul>
            <button class="btn-demo" onclick="document.getElementById('login-section').scrollIntoView({behavior: 'smooth'})">Request Demo</button>
            <button class="mobile-menu-btn" aria-label="Menu">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </nav>
    </header>

    <!-- ============================================
         Main Container
         ============================================ -->
    <main class="main-container">
        <div class="content-wrapper">
            <!-- Hero Section (Left) -->
            <section class="hero-section" id="hero-section">
                <!-- Floating Shapes -->
                <div class="floating-shape"></div>
                <div class="floating-shape-2"></div>
                
                <!-- Badge -->
                <div class="hero-badge">
                    <div class="hero-badge-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span class="hero-badge-text">Secure Legal Platform</span>
                </div>

                <!-- Headline -->
                <h1 class="hero-headline">
                    <span class="hero-headline-white">Turn Case Files Into</span>
                    <span class="hero-headline-accent">Justice Delivered</span>
                </h1>

                <!-- Description -->
                <p class="hero-description">
                    Professional case management system designed for prosecutors and legal teams to streamline workflows, track progress, and deliver justice efficiently.
                </p>

                <!-- Features -->
                <div class="features-list" id="features-section">
                    <div class="feature-item">
                        <div class="feature-icon green">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="feature-content">
                            <h4>Real-time Case Tracking</h4>
                            <p>Monitor case status, hearings, and critical deadlines in one unified dashboard</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon blue">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div class="feature-content">
                            <h4>Intelligent Organization</h4>
                            <p>Automatically categorize cases, assign tasks, and manage evidence with smart workflows</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon purple">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div class="feature-content">
                            <h4>Enterprise Security</h4>
                            <p>Bank-grade encryption and role-based access control for sensitive legal data</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Login Section (Right) -->
            <section class="login-section" id="login-section">
                <div class="login-card">
                    <!-- Login Header -->
                    <div class="login-header">
                        <h2>Welcome Back</h2>
                        <p>Access your prosecutor case files</p>
                    </div>

                    <!-- HOOK: Login Form - Connect to your backend authentication -->
                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf

                        <!-- Role Selector -->
                        <div class="form-group">
                            <label class="form-label" for="role">Role</label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </span>
                                <select class="form-select form-input" id="role" name="role" required>
                                    <option value="" disabled selected>Select your role</option>
                                    <option value="Prosecutor">Prosecutor</option>
                                    <option value="Clerk">Clerk</option>
                                    <option value="Admin">Administrator</option>
                                </select>
                            </div>
                            @error('role')
                            <div class="error-message">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="form-group">
                            <label class="form-label" for="email">Email Address</label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                                <input 
                                    type="email" 
                                    class="form-input" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    placeholder="your.email@justice.gov" 
                                    required 
                                    autofocus
                                >
                            </div>
                            @error('email')
                            <div class="error-message">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="form-group">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </span>
                                <input 
                                    type="password" 
                                    class="form-input" 
                                    id="password" 
                                    name="password" 
                                    placeholder="••••••••" 
                                    required
                                    style="padding-right: 3rem;"
                                >
                                <button type="button" class="password-toggle" onclick="togglePassword()">
                                    <svg id="eyeIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                            <div class="error-message">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                            @enderror
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="form-options">
                            <label class="checkbox-wrapper">
                                <input type="checkbox" name="remember" id="remember">
                                <span>Remember me</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-submit">
                            Sign In Securely
                        </button>

                        <!-- Register Link -->
                        <div class="register-link">
                            <p>Don't have an account? <a href="{{ route('register') }}">Create one</a></p>
                        </div>

                        <!-- Security Notice -->
                        <div class="security-notice">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span>Protected by enterprise-grade security. All activities are logged.</span>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>

    <!-- ============================================
         JavaScript
         ============================================ -->
    <script>
        // Header scroll effect
        const header = document.getElementById('header');
        
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Password toggle
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                `;
            }
        }

        // Form validation enhancement (optional)
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    </script>
</body>
</html>
