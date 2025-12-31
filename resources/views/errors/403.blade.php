<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - AProsecutor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen flex items-center justify-center">
    <div class="text-center px-6">
        <div class="mb-8">
            <i class="fas fa-shield-halved text-8xl text-yellow-500 opacity-50"></i>
        </div>
        <h1 class="text-9xl font-bold text-white mb-4">403</h1>
        <h2 class="text-2xl font-semibold text-slate-300 mb-4">Access Denied</h2>
        <p class="text-slate-400 mb-8 max-w-md mx-auto">
            You don't have permission to access this resource. Please contact your administrator if you believe this is an error.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-th-large mr-2"></i>
                Dashboard
            </a>
            <a href="javascript:history.back()" class="inline-flex items-center px-6 py-3 border border-slate-600 text-slate-300 font-semibold rounded-lg hover:bg-slate-800 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Go Back
            </a>
        </div>
    </div>
</body>
</html>
