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
    </style>
</head>

<body>

    {{-- NAVBAR --}}
    <x-navbar />

    {{-- CONTENT --}}
    @yield('content')

</body>

</html>
