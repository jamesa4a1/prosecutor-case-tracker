<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview - AProsecutor Case Tracker</title>
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
        
        /* Header */
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
        
        /* Main Content */
        .main-content {
            padding: 8rem 2rem 4rem;
            max-width: 1200px;
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
            max-width: 700px;
            line-height: 1.7;
            margin-bottom: 3rem;
        }
        
        .content-section {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 2.5rem;
            margin-bottom: 2rem;
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--color-white);
        }
        
        .section-text {
            color: var(--color-text-muted);
            line-height: 1.8;
            margin-bottom: 1rem;
        }
        
        .role-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .role-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
        }
        
        .role-card h4 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--color-accent-light);
            margin-bottom: 0.75rem;
        }
        
        .role-card p {
            color: var(--color-text-muted);
            font-size: 0.9375rem;
            line-height: 1.6;
        }
        
        .cta-section {
            text-align: center;
            margin-top: 4rem;
        }
        
        .btn-primary {
            display: inline-block;
            padding: 1rem 2.5rem;
            background: linear-gradient(135deg, var(--color-accent) 0%, #1d4ed8 100%);
            color: var(--color-white);
            font-size: 1rem;
            font-weight: 600;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.4);
        }
        
        @media (max-width: 768px) {
            .nav-links { display: none; }
            .page-title { font-size: 2rem; }
            .main-content { padding: 6rem 1rem 2rem; }
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
                <li><a href="{{ route('overview') }}" class="nav-link active">Overview</a></li>
                <li><a href="{{ route('features') }}" class="nav-link">Features</a></li>
                <li><a href="{{ route('support') }}" class="nav-link">Support</a></li>
            </ul>
            <a href="{{ route('login') }}" class="btn-demo">Sign In</a>
        </nav>
    </header>

    <main class="main-content">
        <h1 class="page-title">System Overview</h1>
        <p class="page-subtitle">
            AProsecutor Case Tracker is a centralized digital platform designed to streamline and modernize case management for prosecutors' offices.
        </p>

        <div class="content-section">
            <h2 class="section-title">About the Platform</h2>
            <p class="section-text">
                The system serves as a single repository for tracking criminal, civil, and special cases from filing through resolution, enabling prosecutors to maintain real-time visibility into case status, scheduled hearings, involved parties, and case notes.
            </p>
            <p class="section-text">
                Built with Laravel, MySQL, and Tailwind CSS, the system combines backend robustness with frontend accessibility, supporting features such as hearing scheduling, party management, comprehensive case documentation, and full audit trails—all designed to reduce administrative burden, improve case coordination, and enhance accountability across the prosecution workflow.
            </p>
        </div>

        <div class="content-section">
            <h2 class="section-title">System Roles</h2>
            <p class="section-text">
                The system is built around three primary roles, each with specific responsibilities:
            </p>
            <div class="role-cards">
                <div class="role-card">
                    <h4>👨‍💼 Administrator</h4>
                    <p>Manages user accounts, system configuration, and overall platform maintenance with full system access. Administrators oversee user permissions and ensure system integrity.</p>
                </div>
                <div class="role-card">
                    <h4>⚖️ Prosecutor</h4>
                    <p>Primary case managers responsible for tracking assigned cases, making status updates, overseeing case progression, managing hearings, organizing case parties, and directing the investigation workflow.</p>
                </div>
                <div class="role-card">
                    <h4>📋 Clerk</h4>
                    <p>Provides essential support by adding case notes, managing documentation, organizing hearing schedules, and assisting prosecutors in maintaining comprehensive case records.</p>
                </div>
            </div>
        </div>

        <div class="cta-section">
            <a href="{{ route('login') }}" class="btn-primary">Get Started Now</a>
        </div>
    </main>
</body>
</html>
