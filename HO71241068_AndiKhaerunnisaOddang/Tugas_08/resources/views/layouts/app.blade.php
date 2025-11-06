<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Fish It Simulator')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --light-bg: #ecf0f1;
            --border-radius: 12px;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        .navbar {
            background-color: var(--primary-color);
            padding: 1rem 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }
        
        .nav-link {
            font-weight: 500;
            transition: color 0.3s ease;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
        }
        
        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
        }
        
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 2px solid var(--light-bg);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }
        
        .btn {
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-primary {
            background-color: var(--accent-color);
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }
        
        .btn-outline-secondary {
            border: 2px solid #dee2e6;
            color: #495057;
        }
        
        .btn-outline-secondary:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
            color: #495057;
        }
        
        .table {
            background-color: white;
            border-radius: var(--border-radius);
            overflow: hidden;
        }
        
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 1rem;
        }
        
        .table tbody tr {
            transition: background-color 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 0.625rem 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.15);
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .alert {
            border-radius: var(--border-radius);
            border: none;
            padding: 1rem 1.25rem;
        }
        
        .btn-group-sm > .btn {
            border-radius: 6px;
            margin: 0 2px;
        }
        
        .pagination {
            margin-top: 2rem;
        }
        
        .page-link {
            border-radius: 8px;
            margin: 0 4px;
            border: 2px solid #e9ecef;
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .page-link:hover {
            background-color: var(--light-bg);
            border-color: #dee2e6;
        }
        
        .page-item.active .page-link {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .container {
            max-width: 1200px;
        }
        
        h2, h3, h4 {
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .filter-section {
            background-color: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('fishes.index') }}">Fish It Simulator</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('fishes.index') }}">Fish Database</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>