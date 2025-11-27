<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            margin: 0;
            background-color: #f4f7f6;
            padding-top: 110px;
        }

        /* --- FLASH MESSAGE (Centered) --- */
        .alert-container {
            position: fixed;
            top: 120px;
            /* Adjust this if you want it lower/higher */
            left: 50%;
            /* Move to middle of screen horizontally */
            transform: translateX(-50%);
            /* Center align exactly */
            z-index: 9999;
            width: auto;
            min-width: 300px;
            max-width: 600px;
            /* Increased max-width slightly for center look */
            text-align: center;
            /* Center text inside */
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Center icon and text */
            gap: 12px;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            /* Changed animation to slide from top */
            animation: slideInDown 0.5s ease-out forwards;
        }

        /* SUCCESS: Light Green Background */
        .alert-success {
            color: #0f5132;
            background-color: #d1e7dd;
            border-color: #badbcc;
        }

        /* ERROR: Light Red Background */
        .alert-danger {
            color: #842029;
            background-color: #f8d7da;
            border-color: #f5c2c7;
        }

        .alert i {
            font-size: 1.1rem;
        }

        /* New Animation: Slide Down and Fade In */
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                visibility: hidden;
            }
        }

        /* ----------------------------------- */

        /* Styles Global */
        .main-container {
            max-width: 1000px;
            margin: 3rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .main-container h2 {
            font-size: 2.2rem;
            color: #3ea2c7;
            margin: 0.5rem auto;
            padding: 0.5rem;
            font-weight: 700;
        }

        .action-header {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1.2rem;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.2s ease;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            white-space: nowrap;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background-color: #3ea2c7;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2e8aa8;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 1rem;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        .data-table th,
        .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
        }

        .data-table th {
            background-color: #3ea2c7;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .data-table th:first-child,
        .data-table td:first-child {
            text-align: center;
            width: 50px;
        }

        .data-table th:last-child,
        .data-table td.action-buttons {
            text-align: center;
            width: 180px;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .action-buttons form {
            margin: 0;
            padding: 0;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .data-table tbody tr:hover {
            background-color: #eaf6fc;
        }

        .data-table td {
            font-size: 0.95rem;
            color: #555;
        }

        .empty-message {
            font-size: 1.1rem;
            color: #777;
            margin-top: 2rem;
            padding: 1.5rem;
            background-color: #ffe0b2;
            border: 1px solid #ffb726;
            border-radius: 8px;
        }

        .empty-state-actions {
            margin-top: 1.5rem;
        }

        .form-layout {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 1rem;
            box-sizing: border-box;
            transition: 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #3ea2c7;
            box-shadow: 0 0 0 3px rgba(62, 162, 199, 0.2);
        }

        /* Kelas khusus untuk container form agar lebih kecil dari tabel */
        .main-container.container-sm {
            max-width: 600px;
            text-align: left;
            /* Override text-center dari main-container utama */
        }

        @media (max-width: 768px) {
            .data-table {
                min-width: 600px;
            }

            /* Agar tabel bisa discroll ke samping di HP */
            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
</head>

<body>

    {{-- NAVBAR --}}
    <x-navbar />

    {{-- FLASH MESSAGES (CENTERED) --}}
    <div class="alert-container">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Menangani session 'error' ATAU 'danger' --}}
        @if(session('error') || session('danger'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ session('error') ?? session('danger') }}</span>
            </div>
        @endif
    </div>

    {{-- CONTENT --}}
    @yield('content')

    {{-- Script: Remove alert after 4 seconds --}}
    <script>
        setTimeout(function () {
            let alerts = document.querySelectorAll('.alert');
            alerts.forEach(function (alert) {
                alert.style.animation = "fadeOut 0.5s forwards";
                setTimeout(function () { alert.remove(); }, 500);
            });
        }, 4000); 
    </script>
</body>

</html>