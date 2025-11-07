<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I NEED A JOB</title>
    <link rel="stylesheet" href="style.css">
        <style>
            body {
                margin: 0;
                background-color: white;
            }
            nav {
                background-color: #3ea2c7;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1rem 2rem;
                height: 50px; 
            }
            nav img {
                height: 60px; 
                width: auto;
            }
            nav div {
                display: flex;
                gap: 2rem; 
            }
            nav div a {
                text-decoration: none; 
                color: white; 
                font-weight: 500;
                transition: color 0.3s ease, transform 0.2s ease;
            }
            nav div a:hover {
                color: #ffdd57ff; 
                transform: scale(1.05); 
            }
    </style>
</head>
<body>
    <nav>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <img src="../../RSHP/logo.png" alt="Logo UNAIR">
            <span class="text-bold">UNIVERSITAS<br>AIRLANGGA</span>
            <div class="divider"></div>
            <span class="text-bold">RUMAH SAKIT HEWAN PENDIDIKAN</span>
            <img src="../../RSHP/images.png" alt="Logo RSHP">
        </div>
        <div>
            <?php include 'menu/menu_1.php'; ?>
        </div>

        <style>
        .text-bold {
            font-weight: bold;
        }
        .divider {
            width: 2px;
            background-color: #000000ff; 
            height: 40px; 
        }     
        </style>
        
    </nav>
    <table>
        <div class="header">
            <h2>Selamat datang di halaman khusus Resepsionis</h2>
            <?php
                $nama_user = $_SESSION['username'] ?? 'User';
                $nama_role = $_SESSION['role_nama'] ?? 'Tidak diketahui';
                echo "<p>Halo <b>{$nama_user}</b>, anda login sebagai 
                <span class='badge'>{$nama_role}</span></p>";
            ?>
        </div>
        <style>
        /* Header box */
        .header {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem;
            color: black;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }
        .header h2 {
            margin: 0 0 0.5rem 0;
        }
        .header p {
            margin: 0;
        }
        /* Badge untuk role */
        .badge {
            display: inline-block;
            background: #3ea2c7;
            color: white;
            font-weight: bold;
            padding: 0.2rem 0.6rem;
            border-radius: 12px;
            margin-left: 0.3rem;
        }
        </style>

    </table>
</body>
</html>