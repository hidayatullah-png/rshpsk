<style>
    /* --- Layout Form Style (Reusable) --- */
    .main-container {
        max-width: 100%;
        margin: 2rem auto;
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.05);
    }

    h2 {
        text-align: left;
        margin-bottom: 2rem;
        color: #3ea2c7;
        font-weight: 700;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* --- Form Elements --- */
    .form-control { 
        width: 100%; 
        padding: 10px 15px; 
        font-size: 1rem; 
        border: 1px solid #ddd; 
        border-radius: 8px; 
        box-sizing: border-box; 
        transition: 0.3s; 
        background-color: #fff;
    }
    .form-control:focus { 
        outline: none; 
        border-color: #3ea2c7; 
        box-shadow: 0 0 0 3px rgba(62,162,199,0.2); 
    }

    /* Custom Select Arrow */
    select.form-control {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%233ea2c7' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1em;
        padding-right: 2.5rem;
    }

    /* --- Buttons --- */
    .btn { 
        padding: 10px 20px; 
        border-radius: 8px; 
        font-weight: 500; 
        border: none; 
        cursor: pointer; 
        text-decoration: none; 
        display: inline-flex; 
        align-items: center; 
        justify-content: center; 
        gap: 8px; 
        transition: transform 0.2s; 
        font-size: 0.9rem;
    }
    .btn:hover { transform: translateY(-2px); }
    .btn-primary { background-color: #3ea2c7; color: white; }
    .btn-secondary { background-color: #6c757d; color: white; }
    .btn-green { background-color: #28a745; color: white; }
    .btn-red { background-color: #dc3545; color: white; }
    .btn-sm { padding: 6px 12px; font-size: 0.8rem; }

    /* --- Table Style --- */
    .table-responsive { overflow-x: auto; border-radius: 8px; border: 1px solid #eee; }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background-color: #3ea2c7; color: white; padding: 12px; text-align: left; font-weight: 600; }
    .data-table td { padding: 12px; border-bottom: 1px solid #eee; color: #555; }

    /* --- Header Actions --- */
    .header-actions { display: flex; gap: 10px; align-items: center; }
    
    /* --- Form Input Section --- */
    .input-section {
        background-color: #f8f9fa; 
        padding: 20px; 
        border-radius: 10px; 
        margin-bottom: 30px; 
        border: 1px solid #eee;
    }
    .input-section h4 { margin-top: 0; color: #3ea2c7; margin-bottom: 15px; font-weight: 600; }
</style>