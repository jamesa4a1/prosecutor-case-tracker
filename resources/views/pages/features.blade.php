<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Features - AProsecutor Case Tracker</title>
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
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
        }
        
        .feature-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 2rem;
            transition: all 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-4px);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(37, 99, 235, 0.3);
        }
        
        .feature-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            font-size: 1.5rem;
        }
        
        .feature-icon.green { background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); }
        .feature-icon.blue { background: rgba(37, 99, 235, 0.15); border: 1px solid rgba(37, 99, 235, 0.3); }
        .feature-icon.purple { background: rgba(139, 92, 246, 0.15); border: 1px solid rgba(139, 92, 246, 0.3); }
        .feature-icon.orange { background: rgba(249, 115, 22, 0.15); border: 1px solid rgba(249, 115, 22, 0.3); }
        .feature-icon.pink { background: rgba(236, 72, 153, 0.15); border: 1px solid rgba(236, 72, 153, 0.3); }
        .feature-icon.cyan { background: rgba(6, 182, 212, 0.15); border: 1px solid rgba(6, 182, 212, 0.3); }
        
        .feature-card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--color-white);
            margin-bottom: 0.75rem;
        }
        
        .feature-card p {
            color: var(--color-text-muted);
            line-height: 1.7;
            font-size: 0.9375rem;
        }
        
        .feature-list {
            margin-top: 1rem;
            list-style: none;
        }
        
        .feature-list li {
            color: var(--color-text-muted);
            font-size: 0.875rem;
            padding: 0.375rem 0;
            padding-left: 1.5rem;
            position: relative;
        }
        
        .feature-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #10b981;
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
            .features-grid { grid-template-columns: 1fr; }
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
                <li><a href="{{ route('features') }}" class="nav-link active">Features</a></li>
                <li><a href="{{ route('support') }}" class="nav-link">Support</a></li>
            </ul>
            <a href="{{ route('login') }}" class="btn-demo">Sign In</a>
        </nav>
    </header>

    <main class="main-content">
        <h1 class="page-title">Platform Features</h1>
        <p class="page-subtitle">
            Discover all the powerful features designed to streamline your prosecution workflow and improve case management efficiency.
        </p>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon green">📊</div>
                <h3>Real-time Case Tracking</h3>
                <p>Monitor case status, hearings, and critical deadlines in one unified dashboard.</p>
                <ul class="feature-list">
                    <li>Live status updates</li>
                    <li>Deadline notifications</li>
                    <li>Progress visualization</li>
                </ul>
            </div>

            <div class="feature-card">
                <div class="feature-icon blue">📁</div>
                <h3>Case Management</h3>
                <p>Complete CRUD operations for cases with comprehensive tracking capabilities.</p>
                <ul class="feature-list">
                    <li>Create, edit, archive cases</li>
                    <li>Track case number & title</li>
                    <li>Manage offense details</li>
                </ul>
            </div>

            <div class="feature-card">
                <div class="feature-icon purple">📅</div>
                <h3>Hearing Scheduler</h3>
                <p>Schedule and manage all court hearings with integrated calendar views.</p>
                <ul class="feature-list">
                    <li>Calendar integration</li>
                    <li>Hearing reminders</li>
                    <li>Location tracking</li>
                </ul>
            </div>

            <div class="feature-card">
                <div class="feature-icon orange">👥</div>
                <h3>Party Management</h3>
                <p>Track all parties involved in cases including defendants, witnesses, and plaintiffs.</p>
                <ul class="feature-list">
                    <li>Contact information</li>
                    <li>Role assignments</li>
                    <li>Party history</li>
                </ul>
            </div>

            <div class="feature-card">
                <div class="feature-icon pink">📝</div>
                <h3>Notes & Documentation</h3>
                <p>Add detailed notes and maintain comprehensive case documentation.</p>
                <ul class="feature-list">
                    <li>Case notes</li>
                    <li>Document attachments</li>
                    <li>Comment threads</li>
                </ul>
            </div>

            <div class="feature-card">
                <div class="feature-icon cyan">🔒</div>
                <h3>Security & Audit</h3>
                <p>Enterprise-grade security with complete audit trails for all activities.</p>
                <ul class="feature-list">
                    <li>Role-based access control</li>
                    <li>Status change history</li>
                    <li>Activity logging</li>
                </ul>
            </div>
        </div>

        <div class="cta-section">
            <a href="{{ route('login') }}" class="btn-primary">Start Using Now</a>
        </div>
    </main>
</body>
</html>
