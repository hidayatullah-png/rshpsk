<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            margin: 0;
            background-color: #f4f7f6;
            padding-top: 110px;
        }

        /* =============================
           FLASH MESSAGE
        ============================= */
        .alert-container {
            position: fixed;
            top: 120px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            min-width: 300px;
            max-width: 600px;
            text-align: center;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(0,0,0,.1);
            animation: slideInDown .5s ease-out forwards;
        }

        .alert-success {
            color: #0f5132;
            background-color: #d1e7dd;
            border: 1px solid #badbcc;
        }

        .alert-danger {
            color: #842029;
            background-color: #f8d7da;
            border: 1px solid #f5c2c7;
        }

        @keyframes slideInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeOut {
            to { opacity: 0; visibility: hidden; }
        }

        /* =============================
           MAIN CONTAINER
        ============================= */
        .main-container {
            max-width: 1000px;
            margin: 3rem auto;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,.1);
            text-align: center;
        }

        .main-container h2 {
            font-size: 2.2rem;
            color: #3ea2c7;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        /* =============================
           ACTION HEADER (FIXED)
        ============================= */
        .action-header {
            display: flex;
            justify-content: space-between; /* ⬅️ PENTING */
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 1.2rem;
        }

        .action-left {
            margin-right: auto;
            display: flex;
            gap: 8px;
        }

        /* =============================
           BUTTON
        ============================= */
        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            font-size: .9rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            transition: .2s;
            cursor: pointer;
            border: none;
            white-space: nowrap;
        }

        .btn:hover { transform: translateY(-2px); }

        .btn-primary { background: #3ea2c7; color: #fff; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .btn-success { background: #28a745; color: #fff; }
        .btn-danger { background: #dc3545; color: #fff; }

        /* =============================
           BADGE
        ============================= */
        .badge {
            padding: 6px 10px;
            border-radius: 6px;
            font-size: .85rem;
            font-weight: 500;
        }

        .badge-success { background:#d1e7dd; color:#0f5132; border:1px solid #badbcc; }
        .badge-danger { background:#f8d7da; color:#842029; border:1px solid #f5c2c7; }
        .badge-secondary { background:#e2e3e5; color:#383d41; border:1px solid #d6d8db; }

        .ml-2 { margin-left:.5rem; }

        /* =============================
           EMPTY STATE
        ============================= */
        .empty-message {
            padding: 40px;
            background: #fff;
            border-radius: 8px;
            color: #777;
            box-shadow: 0 2px 5px rgba(0,0,0,.05);
        }

        .empty-state-actions { margin-top: 15px; }

        /* =============================
           ADMIN FORM
        ============================= */
        .form-container {
            max-width: 800px;
            margin: 2rem auto;
            background: #fff;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,.08);
            text-align: left;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            transition: .3s;
        }

        .form-control:focus {
            border-color: #3ea2c7;
            box-shadow: 0 0 0 3px rgba(62,162,199,.2);
            outline: none;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 2rem;
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

        /* =============================
           RESPONSIVE
        ============================= */
        @media (max-width: 768px) {
            .action-header {
                flex-direction: column;
                align-items: stretch;
            }

            .action-header .btn,
            .form-actions .btn {
                width: 100%;
                justify-content: center;
            }

            .form-actions {
                flex-direction: column-reverse;
            }
        }
    </style>
</head>

<body>

<x-navbar />

<div class="alert-container">
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error') || session('danger'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <span>{{ session('error') ?? session('danger') }}</span>
        </div>
    @endif
</div>

@yield('content')

<script>
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(alert => {
        alert.style.animation = "fadeOut .5s forwards";
        setTimeout(() => alert.remove(), 500);
    });
}, 4000);
</script>

</body>
</html>
