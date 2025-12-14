{{-- resources/views/layouts/navbar-admin.blade.php --}}

<style>
/* === GLOBAL STYLE === */
body {
    background-color: #f4f7f6;
    margin: 0;
    padding-top: 100px;
}

/* === NAVBAR STYLE (SAMA PERSIS DENGAN PEMILIK) === */
.nav-utama {
    background-color: #3ea2c7;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 2rem;
    height: 70px;
    position: fixed;
    width: 100%;
    top: 0;
    left: 0;
    z-index: 1000;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    box-sizing: border-box;
}

.nav-left,
.nav-right {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.nav-utama img { height: 45px; }
.text-bold { font-weight: 700; font-size: 0.9rem; line-height: 1.2; }
.divider { width: 2px; height: 40px; background-color: #000; }

/* === LINK NAVBAR === */
.dropdown-toggle,
.nav-utama a {
    color: white;
    text-decoration: none;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: 0.3s;
}

.dropdown-toggle:hover,
.nav-utama a:hover {
    color: #ffdd57;
}

/* === BUTTON KEMBALI === */
.btn-nav-kembali {
    padding: 5px 12px;
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 20px;
}

.btn-nav-kembali:hover {
    background: rgba(255,255,255,0.1);
    border-color: #ffdd57;
}

/* === DROPDOWN === */
.dropdown { position: relative; }

.dropdown-menu {
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all 0.3s ease;
    position: absolute;
    background-color: #ffffff;
    min-width: 240px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    z-index: 2000;
    border-radius: 10px;
    top: 100%;
    right: 0;
    margin-top: 15px;
    padding: 10px 0;
    border: 1px solid #f0f0f0;
}

.dropdown:hover .dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-menu a {
    color: #333;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 500;
    transition: 0.2s;
}

.dropdown-menu a:hover {
    background-color: #f0f9fc;
    color: #3ea2c7;
    padding-left: 25px;
}

.dropdown-menu i { color: #3ea2c7; width: 20px; text-align: center; }
</style>

<nav class="nav-utama">
    <div class="nav-left">
        <img src="{{ asset('images/logo.png') }}" alt="UNAIR">
        <span class="text-bold">UNIVERSITAS<br>AIRLANGGA</span>
        <div class="divider"></div>
        <span class="text-bold">RUMAH SAKIT HEWAN PENDIDIKAN</span>
        <img src="{{ asset('images/images.png') }}" alt="RSHP">
    </div>

    <div class="nav-right">
        <a href="{{ route('dashboard.admin.dashboard-admin') }}" class="btn-nav-kembali">
            <i class="fas fa-arrow-left"></i> Dashboard
        </a>

        <div class="dropdown">
            <a href="javascript:void(0)" class="dropdown-toggle">
                Menu Admin <i class="fas fa-chevron-down" style="font-size:0.7em"></i>
            </a>
                <div class="dropdown-menu">
                    <a href="{{ url('dashboard/admin/role-user') }}">Manajemen User</a>
                    <a href="{{ url('dashboard/admin/role') }}">Manajemen Role</a>
                    <a href="{{ url('dashboard/admin/jenis-hewan') }}">Jenis Hewan</a>
                    <a href="{{ url('dashboard/admin/ras-hewan') }}">Ras Hewan</a>
                    <a href="{{ url('dashboard/admin/kategori') }}">Data Kategori</a>
                    <a href="{{ url('dashboard/admin/kategori-klinis') }}">Data Kategori Klinis</a>
                    <a href="{{ url('dashboard/admin/kode-tindakan-terapi') }}">Data Kode Tindakan Terapi</a>
                    <a href="{{ url('dashboard/admin/rekam-medis') }}">Rekam Medis</a>
                    <a href="{{ url('dashboard/admin/pemilik') }}">Data Pemilik</a>
                    <a href="{{ url('dashboard/admin/pet') }}">Data Pet</a>
                </div>
        </div>

        <a href="{{ route('logout') }}" class="dropdown-toggle"
           onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>
</nav>
