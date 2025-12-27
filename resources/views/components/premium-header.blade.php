{{--
    ============================================================
    Premium SaaS Header Component - AProsecutor Case Tracker
    ============================================================
    
    A world-class navigation bar with:
    - Deep navy/indigo gradient background
    - Smooth sliding underline animations on hover
    - Elevated "Request Demo" button with micro-interactions
    - Mobile hamburger menu with slide-in panel
    - Subtle fade-down animation on page load
    
    USAGE: @include('components.premium-header')
    
    CUSTOMIZATION POINTS:
    - Logo: Replace the SVG icon or add an <img> tag where indicated
    - Routes: Update href attributes to your actual route helpers
    - Colors: Modify gradient and accent colors as needed
    ============================================================
--}}

{{-- Inline styles for animations not available in default Tailwind --}}
<style>
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
    
    .animate-fade-in-down {
        animation: fadeInDown 0.6s ease-out forwards;
    }
    
    /* Sliding underline animation */
    .nav-link-underline::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 50%;
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, #3b82f6, #60a5fa);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translateX(-50%);
        border-radius: 1px;
    }
    
    .nav-link-underline:hover::after {
        width: 100%;
    }
    
    /* Mobile menu slide animation */
    .mobile-menu-enter {
        transform: translateX(100%);
        opacity: 0;
    }
    
    .mobile-menu-enter-active {
        transform: translateX(0);
        opacity: 1;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Gradient bottom glow */
    .header-glow::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.5), rgba(96, 165, 250, 0.3), transparent);
        filter: blur(1px);
    }
</style>

<header class="fixed top-0 left-0 right-0 z-50 animate-fade-in-down header-glow" 
        style="background: linear-gradient(135deg, #05081A 0%, #101B46 50%, #1a2d5a 100%);">
    
    {{-- Desktop Navigation --}}
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
            
            {{-- Left: Logo & Brand --}}
            <div class="flex items-center gap-3">
                {{-- 
                    LOGO PLACEHOLDER
                    Replace this SVG with your actual logo:
                    <img src="{{ asset('images/logo.svg') }}" alt="AProsecutor" class="h-10 w-auto">
                --}}
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg shadow-blue-500/25">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                    </svg>
                </div>
                <div class="flex flex-col">
                    {{-- CHANGE: Update brand name if needed --}}
                    <span class="text-white font-bold text-lg tracking-tight leading-tight">AProsecutor</span>
                    <span class="text-slate-400 text-xs font-normal tracking-wide">Case Tracker</span>
                </div>
            </div>
            
            {{-- Right: Desktop Nav Links --}}
            <div class="hidden md:flex items-center gap-8">
                {{-- Navigation Links with Sliding Underline --}}
                <div class="flex items-center gap-6">
                    {{-- CHANGE: Update href to your actual route, e.g., route('overview') --}}
                    <a href="#overview" 
                       class="relative text-slate-300 hover:text-white font-medium text-sm tracking-wide transition-colors duration-200 nav-link-underline py-1">
                        Overview
                    </a>
                    
                    {{-- CHANGE: Update href to your actual route, e.g., route('features') --}}
                    <a href="#features" 
                       class="relative text-slate-300 hover:text-white font-medium text-sm tracking-wide transition-colors duration-200 nav-link-underline py-1">
                        Features
                    </a>
                    
                    {{-- CHANGE: Update href to your actual route, e.g., route('support') --}}
                    <a href="#support" 
                       class="relative text-slate-300 hover:text-white font-medium text-sm tracking-wide transition-colors duration-200 nav-link-underline py-1">
                        Support
                    </a>
                </div>
                
                {{-- Request Demo Button with Hover Effects --}}
                {{-- CHANGE: Update href to your actual demo request route --}}
                <a href="#request-demo" 
                   class="inline-flex items-center px-5 py-2.5 
                          border border-slate-500/30 hover:border-slate-400/50
                          text-white font-semibold text-sm tracking-wide
                          rounded-lg
                          bg-transparent hover:bg-white/5
                          transform hover:-translate-y-0.5
                          shadow-sm hover:shadow-md hover:shadow-blue-500/10
                          transition-all duration-300 ease-out">
                    Request Demo
                </a>
            </div>
            
            {{-- Mobile Menu Button --}}
            <button type="button" 
                    class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/10 transition-colors duration-200"
                    onclick="toggleMobileMenu()"
                    aria-expanded="false"
                    aria-label="Toggle navigation menu">
                <span class="sr-only">Open main menu</span>
                {{-- Hamburger Icon --}}
                <svg id="hamburger-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                {{-- Close Icon (hidden by default) --}}
                <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </nav>
    
    {{-- Mobile Navigation Panel --}}
    <div id="mobile-menu" 
         class="md:hidden fixed inset-y-0 right-0 w-72 transform translate-x-full transition-transform duration-300 ease-out z-50"
         style="background: linear-gradient(180deg, #05081A 0%, #101B46 100%);">
        
        {{-- Mobile Menu Header --}}
        <div class="flex items-center justify-between p-4 border-b border-slate-700/50">
            <span class="text-white font-semibold">Menu</span>
            <button type="button" 
                    class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/10 transition-colors duration-200"
                    onclick="toggleMobileMenu()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        {{-- Mobile Menu Links --}}
        <nav class="p-4 space-y-2">
            {{-- CHANGE: Update href to your actual routes --}}
            <a href="#overview" 
               class="group flex items-center px-4 py-3 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition-all duration-200"
               onclick="toggleMobileMenu()">
                <span class="relative font-medium text-sm tracking-wide">
                    Overview
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-500 group-hover:w-full transition-all duration-300"></span>
                </span>
            </a>
            
            <a href="#features" 
               class="group flex items-center px-4 py-3 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition-all duration-200"
               onclick="toggleMobileMenu()">
                <span class="relative font-medium text-sm tracking-wide">
                    Features
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-500 group-hover:w-full transition-all duration-300"></span>
                </span>
            </a>
            
            <a href="#support" 
               class="group flex items-center px-4 py-3 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition-all duration-200"
               onclick="toggleMobileMenu()">
                <span class="relative font-medium text-sm tracking-wide">
                    Support
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-500 group-hover:w-full transition-all duration-300"></span>
                </span>
            </a>
            
            {{-- Mobile Request Demo Button --}}
            <div class="pt-4 mt-4 border-t border-slate-700/50">
                <a href="#request-demo" 
                   class="flex items-center justify-center w-full px-5 py-3 
                          border border-slate-500/30 hover:border-blue-500/50
                          text-white font-semibold text-sm tracking-wide
                          rounded-lg
                          bg-blue-600/10 hover:bg-blue-600/20
                          transition-all duration-300"
                   onclick="toggleMobileMenu()">
                    Request Demo
                </a>
            </div>
        </nav>
    </div>
    
    {{-- Mobile Menu Overlay --}}
    <div id="mobile-overlay" 
         class="md:hidden fixed inset-0 bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 z-40"
         onclick="toggleMobileMenu()">
    </div>
</header>

{{-- Spacer to prevent content from hiding under fixed header --}}
<div class="h-16 lg:h-20"></div>

{{-- Mobile Menu Toggle Script --}}
<script>
    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileOverlay = document.getElementById('mobile-overlay');
        const hamburgerIcon = document.getElementById('hamburger-icon');
        const closeIcon = document.getElementById('close-icon');
        
        const isOpen = !mobileMenu.classList.contains('translate-x-full');
        
        if (isOpen) {
            // Close menu
            mobileMenu.classList.add('translate-x-full');
            mobileOverlay.classList.add('opacity-0', 'pointer-events-none');
            mobileOverlay.classList.remove('opacity-100', 'pointer-events-auto');
            hamburgerIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
            document.body.style.overflow = '';
        } else {
            // Open menu
            mobileMenu.classList.remove('translate-x-full');
            mobileOverlay.classList.remove('opacity-0', 'pointer-events-none');
            mobileOverlay.classList.add('opacity-100', 'pointer-events-auto');
            hamburgerIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }
    
    // Close mobile menu on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const mobileMenu = document.getElementById('mobile-menu');
            if (!mobileMenu.classList.contains('translate-x-full')) {
                toggleMobileMenu();
            }
        }
    });
</script>
