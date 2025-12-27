<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support - AProsecutor Case Tracker</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-dark-navy: #05081A;
            --color-navy: #101B46;
            --color-navy-light: #1a2d5a;
            --color-accent: #2563EB;
            --color-accent-hover: #3b82f6;
            --color-accent-light: #60a5fa;
            --color-white: #ffffff;
            --color-text-muted: #94a3b8;
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: var(--font-family);
            background: linear-gradient(135deg, var(--color-dark-navy) 0%, var(--color-navy) 50%, var(--color-navy-light) 100%);
            min-height: 100vh;
            color: var(--color-white);
        }
        
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
            background: rgba(5, 8, 26, 0.9);
            backdrop-filter: blur(20px);
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .brand-name {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--color-white);
        }
        
        .brand-subtitle {
            font-size: 0.75rem;
            color: var(--color-text-muted);
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
            padding: 0.25rem 0;
            transition: color 0.2s;
            position: relative;
        }
        
        .nav-link:hover, .nav-link.active {
            color: var(--color-white);
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--color-accent);
        }
        
        .btn-demo {
            padding: 0.625rem 1.25rem;
            background: var(--color-accent);
            border: none;
            color: var(--color-white);
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .btn-demo:hover {
            background: var(--color-accent-hover);
            transform: translateY(-1px);
        }
        
        .main-content {
            padding: 8rem 2rem 4rem;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .page-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--color-white) 0%, var(--color-accent-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .page-subtitle {
            font-size: 1.25rem;
            color: var(--color-text-muted);
            line-height: 1.7;
            margin-bottom: 3rem;
        }
        
        .support-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        .support-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s;
        }
        
        .support-card:hover {
            transform: translateY(-4px);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(37, 99, 235, 0.3);
        }
        
        .support-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .support-card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--color-white);
            margin-bottom: 0.75rem;
        }
        
        .support-card p {
            color: var(--color-text-muted);
            font-size: 0.9375rem;
            line-height: 1.6;
        }
        
        .support-card a {
            color: var(--color-accent-light);
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .support-card a:hover {
            color: var(--color-white);
        }
        
        .faq-section {
            margin-top: 3rem;
        }
        
        .faq-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--color-white);
        }
        
        .faq-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .faq-question {
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-white);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .faq-question::before {
            content: 'Q';
            background: var(--color-accent);
            color: var(--color-white);
            font-size: 0.75rem;
            font-weight: 700;
            width: 24px;
            height: 24px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .faq-answer {
            color: var(--color-text-muted);
            line-height: 1.7;
            padding-left: 2.25rem;
            font-size: 0.9375rem;
        }
        
        .contact-section {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.15) 0%, rgba(139, 92, 246, 0.1) 100%);
            border: 1px solid rgba(37, 99, 235, 0.2);
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            margin-top: 3rem;
        }
        
        .contact-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--color-white);
            margin-bottom: 1rem;
        }
        
        .contact-text {
            color: var(--color-text-muted);
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }
        
        .btn-contact {
            display: inline-block;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--color-accent) 0%, #1d4ed8 100%);
            color: var(--color-white);
            font-size: 1rem;
            font-weight: 600;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-contact:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.4);
        }
        
        @media (max-width: 768px) {
            .nav-links { display: none; }
            .page-title { font-size: 2rem; }
            .main-content { padding: 6rem 1rem 2rem; }
            .support-grid { grid-template-columns: 1fr; }
            .contact-section { padding: 2rem; }
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="{{ route('login') }}" class="header-left" style="text-decoration: none;">
            <div>
                <div class="brand-name">AProsecutor</div>
                <div class="brand-subtitle">Case Tracker</div>
            </div>
        </a>
        <nav class="header-nav">
            <ul class="nav-links">
                <li><a href="{{ route('overview') }}" class="nav-link">Overview</a></li>
                <li><a href="{{ route('features') }}" class="nav-link">Features</a></li>
                <li><a href="{{ route('support') }}" class="nav-link active">Support</a></li>
            </ul>
            <a href="{{ route('login') }}" class="btn-demo">Sign In</a>
        </nav>
    </header>

    <main class="main-content">
        <h1 class="page-title">Support Center</h1>
        <p class="page-subtitle">
            Get help and find answers to your questions. Our team is here to assist you with the AProsecutor Case Tracker.
        </p>

        <div class="support-grid">
            <div class="support-card">
                <div class="support-icon">📧</div>
                <h3>Email Support</h3>
                <p>Send us an email and we'll respond within 24 hours.</p>
                <p style="margin-top: 0.5rem;"><a href="mailto:support@aprosecutor.com">support@aprosecutor.com</a></p>
            </div>

            <div class="support-card">
                <div class="support-icon">📖</div>
                <h3>Documentation</h3>
                <p>Browse our comprehensive guides and tutorials to learn how to use the system.</p>
            </div>

            <div class="support-card">
                <div class="support-icon">💬</div>
                <h3>Live Chat</h3>
                <p>Connect with our support team during business hours for immediate assistance.</p>
            </div>
        </div>

        <section class="faq-section">
            <h2 class="faq-title">Frequently Asked Questions</h2>

            <div class="faq-item">
                <div class="faq-question">How do I create a new case?</div>
                <p class="faq-answer">Navigate to the Cases section from your dashboard and click the "Add New Case" button. Fill in the required fields including case number, title, and offense details, then save.</p>
            </div>

            <div class="faq-item">
                <div class="faq-question">What are the different user roles?</div>
                <p class="faq-answer">The system has three roles: <strong>Administrator</strong> (full system access and user management), <strong>Prosecutor</strong> (case management and hearing scheduling), and <strong>Clerk</strong> (documentation and administrative support).</p>
            </div>

            <div class="faq-item">
                <div class="faq-question">How do I schedule a hearing?</div>
                <p class="faq-answer">Open a case, navigate to the Hearings tab, and click "Schedule Hearing". Set the date, time, location, and type of hearing. The system will send reminders as the date approaches.</p>
            </div>

            <div class="faq-item">
                <div class="faq-question">Can I export case data?</div>
                <p class="faq-answer">Yes! Case data can be exported in multiple formats including PDF and Excel. Use the Export button available in the case view or reports section.</p>
            </div>

            <div class="faq-item">
                <div class="faq-question">How do I reset my password?</div>
                <p class="faq-answer">On the login page, click "Forgot Password" and enter your email address. You'll receive a link to reset your password within a few minutes.</p>
            </div>
        </section>

        <section class="contact-section">
            <h2 class="contact-title">Need More Help?</h2>
            <p class="contact-text">Our support team is available to assist you with any questions or issues you may have.</p>
            <a href="mailto:support@aprosecutor.com" class="btn-contact">Contact Support</a>
        </section>
    </main>
</body>
</html>
