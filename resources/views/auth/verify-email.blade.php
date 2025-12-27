<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Email - AProsecutor Case Tracker</title>
    
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
            --color-success: #10b981;
            --color-error: #ef4444;
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: var(--font-family);
            background: linear-gradient(135deg, var(--color-dark-navy) 0%, var(--color-navy) 50%, var(--color-navy-light) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .verify-container {
            background: var(--color-white);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
        }
        
        .logo-section {
            margin-bottom: 2rem;
        }
        
        .brand-name {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--color-navy);
        }
        
        .brand-subtitle {
            font-size: 0.875rem;
            color: var(--color-text-muted);
        }
        
        .icon-wrapper {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .icon-wrapper svg {
            width: 40px;
            height: 40px;
            color: var(--color-white);
        }
        
        h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--color-navy);
            margin-bottom: 0.75rem;
        }
        
        .description {
            color: var(--color-text-muted);
            font-size: 0.9375rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        
        .email-display {
            font-weight: 600;
            color: var(--color-accent);
        }
        
        .code-inputs {
            display: flex;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .code-input {
            width: 70px;
            height: 70px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1.75rem;
            font-weight: 700;
            text-align: center;
            color: var(--color-navy);
            transition: all 0.2s;
            outline: none;
        }
        
        .code-input:focus {
            border-color: var(--color-accent);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }
        
        .code-input.error {
            border-color: var(--color-error);
            animation: shake 0.5s;
        }
        
        .code-input.success {
            border-color: var(--color-success);
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: var(--color-error);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            display: none;
        }
        
        .error-message.show {
            display: block;
        }
        
        .success-message {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: var(--color-success);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            display: none;
        }
        
        .success-message.show {
            display: block;
        }
        
        .btn-verify {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--color-accent) 0%, #1d4ed8 100%);
            border: none;
            border-radius: 12px;
            color: var(--color-white);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 1rem;
        }
        
        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
        }
        
        .btn-verify:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .resend-section {
            color: var(--color-text-muted);
            font-size: 0.875rem;
        }
        
        .resend-link {
            color: var(--color-accent);
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }
        
        .resend-link:hover {
            text-decoration: underline;
        }
        
        .resend-link.disabled {
            color: var(--color-text-muted);
            cursor: not-allowed;
        }
        
        .timer {
            font-weight: 600;
            color: var(--color-accent);
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
            color: var(--color-text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s;
        }
        
        .back-link:hover {
            color: var(--color-accent);
        }
        
        .expires-info {
            font-size: 0.8125rem;
            color: var(--color-text-muted);
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="logo-section">
            <div class="brand-name">AProsecutor</div>
            <div class="brand-subtitle">Case Tracker</div>
        </div>
        
        <div class="icon-wrapper">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        
        <h1>Verify Your Email</h1>
        <p class="description">
            We've sent a 4-digit verification code to<br>
            <span class="email-display">{{ Auth::user()->email }}</span>
        </p>
        
        <div class="error-message" id="errorMessage">
            <strong>Invalid code.</strong> The verification code you entered is not correct. Please try again.
        </div>
        
        <div class="success-message" id="successMessage">
            <strong>Success!</strong> Email verified. Redirecting to dashboard...
        </div>
        
        <form action="{{ route('verification.verify') }}" method="POST" id="verifyForm">
            @csrf
            <div class="code-inputs">
                <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autofocus>
                <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
            </div>
            <input type="hidden" name="code" id="codeInput">
            
            @if ($errors->has('code'))
                <script>document.getElementById('errorMessage').classList.add('show');</script>
            @endif
            
            <button type="submit" class="btn-verify" id="verifyBtn">
                Verify Email
            </button>
        </form>
        
        <div class="resend-section">
            <span>Didn't receive the code? </span>
            <form action="{{ route('verification.resend') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="resend-link" id="resendLink">Resend Code</button>
            </form>
        </div>
        
        <p class="expires-info">Code expires in 10 minutes</p>
        
        <a href="{{ route('logout') }}" class="back-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Login
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
    </div>
    
    <script>
        const inputs = document.querySelectorAll('.code-input');
        const codeInput = document.getElementById('codeInput');
        const form = document.getElementById('verifyForm');
        const errorMessage = document.getElementById('errorMessage');
        const successMessage = document.getElementById('successMessage');
        
        // Handle input
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                // Only allow numbers
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
                
                // Remove error styling
                input.classList.remove('error');
                errorMessage.classList.remove('show');
                
                // Move to next input
                if (e.target.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                
                updateHiddenInput();
            });
            
            // Handle backspace
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
            
            // Handle paste
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 4);
                
                pastedData.split('').forEach((char, i) => {
                    if (inputs[i]) {
                        inputs[i].value = char;
                    }
                });
                
                updateHiddenInput();
                
                if (pastedData.length === 4) {
                    inputs[3].focus();
                }
            });
        });
        
        function updateHiddenInput() {
            let code = '';
            inputs.forEach(input => {
                code += input.value;
            });
            codeInput.value = code;
        }
        
        // Form submission
        form.addEventListener('submit', (e) => {
            updateHiddenInput();
            
            if (codeInput.value.length !== 4) {
                e.preventDefault();
                inputs.forEach(input => input.classList.add('error'));
                errorMessage.textContent = 'Please enter all 4 digits of the verification code.';
                errorMessage.classList.add('show');
            }
        });
        
        // Show error from server
        @if(session('error'))
            errorMessage.innerHTML = '<strong>Error!</strong> {{ session("error") }}';
            errorMessage.classList.add('show');
            inputs.forEach(input => input.classList.add('error'));
        @endif
        
        // Show success message
        @if(session('success'))
            successMessage.innerHTML = '<strong>Success!</strong> {{ session("success") }}';
            successMessage.classList.add('show');
        @endif
        
        // Show resend success
        @if(session('resent'))
            successMessage.innerHTML = '<strong>Code Resent!</strong> A new verification code has been sent to your email.';
            successMessage.classList.add('show');
        @endif
    </script>
</body>
</html>
