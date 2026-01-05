<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep Dostępny - WCAG 2.1</title>
    <style>
        /* --- 1. ZMIENNE KOLORYSTYCZNE (Wysoki Kontrast) --- */
        :root {
            --bg-body: #f4f6f9;
            --bg-container: #ffffff;
            --text-main: #212529;       /* Prawie czarny dla kontrastu */
            --text-muted: #6c757d;
            --primary: #0056b3;         /* Ciemny niebieski (bezpieczny kontrast) */
            --primary-hover: #004494;
            --success: #198754;         /* Ciemny zielony */
            --danger: #dc3545;
            --warning: #ffc107;         /* Tekst na tym musi być czarny! */
            --border: #dee2e6;
            --focus-ring: #ffbf47;      /* Wyraźny żółty/pomarańczowy obrys dla klawiatury */
        }

        /* --- 2. RESET I BAZA --- */
        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-body);
            color: var(--text-main);
            line-height: 1.6;
            font-size: 16px; /* Bazowa wielkość czcionki */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* --- 3. DOSTĘPNOŚĆ (ACCESSIBILITY) --- */
        
        /* Skip Link - widoczny tylko po naciśnięciu TAB */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: var(--primary);
            color: white;
            padding: 8px;
            z-index: 100;
            transition: top 0.3s;
            text-decoration: none;
            font-weight: bold;
        }
        .skip-link:focus { top: 0; }

        /* Wyraźny Focus dla nawigacji klawiaturą */
        a:focus, button:focus, input:focus, select:focus, textarea:focus {
            outline: 3px solid var(--primary);
            outline-offset: 2px;
        }

        /* --- 4. UKŁAD (LAYOUT) --- */
        header {
            background-color: #343a40; /* Ciemny nagłówek */
            color: #fff;
            padding: 1rem 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap; /* Responsywność */
            gap: 15px;
        }

        main {
            flex: 1; /* Wypycha stopkę na dół */
            padding: 2rem 0;
        }

        .card {
            background: var(--bg-container);
            padding: 25px;
            border-radius: 8px;
            border: 1px solid var(--border);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        footer {
            background: #e9ecef;
            color: var(--text-main);
            text-align: center;
            padding: 1.5rem 0;
            margin-top: auto;
            border-top: 1px solid var(--border);
        }

        /* --- 5. NAWIGACJA --- */
        nav a {
            color: #fff;
            text-decoration: none;
            margin-left: 15px;
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 4px;
        }
        nav a:hover {
            background-color: rgba(255,255,255,0.2);
            text-decoration: underline;
        }

        .nav-group {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        /* --- 6. ELEMENTY FORMULARZY I TABEL --- */
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
            background-color: #e9ecef;
            color: var(--text-main);
            border: 1px solid #ced4da;
        }
        
        .btn:hover { background-color: #dde0e3; }
        .btn-primary { background-color: var(--primary); color: white; border: none; }
        .btn-primary:hover { background-color: var(--primary-hover); }
        .btn-red { background-color: var(--danger); color: white; border: none; }
        .btn-red:hover { background-color: #bb2d3b; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: 700;
            color: #495057;
        }

        /* Responsywne tabele (przewijanie na małych ekranach) */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .form-group { margin-bottom: 1rem; }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-main);
        }

        input[type="text"], input[type="number"], input[type="email"], input[type="password"], select, textarea {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            background-color: #fff;
        }

        /* --- 7. UTILITIES --- */
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            border: 1px solid transparent;
        }
        .alert-success { background-color: #d1e7dd; border-color: #badbcc; color: #0f5132; }
        .alert-error { background-color: #f8d7da; border-color: #f5c2c7; color: #842029; }

        .pagination { display: flex; justify-content: center; gap: 5px; margin-top: 20px; }
        /* Style dla domyślnej paginacji Laravela */
        .pagination li { list-style: none; }
        .pagination span, .pagination a {
            padding: 8px 12px;
            border: 1px solid var(--border);
            text-decoration: none;
            color: var(--primary);
        }
        .pagination .active span {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        /* Ukrycie SVG w paginacji jeśli są */
        .w-5 { width: 1em; }

        /* --- 8. MEDIA QUERIES (RESPONSYWNOŚĆ) --- */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                align-items: stretch;
            }
            nav {
                display: flex;
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
            nav a { margin: 0; display: block; background: rgba(255,255,255,0.1); }
            
            /* Tabele na mobilce */
            table, thead, tbody, th, td, tr { 
                display: block; 
            }
            thead tr { 
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            tr { margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px; overflow: hidden; }
            td { 
                border: none;
                border-bottom: 1px solid #eee; 
                position: relative;
                padding-left: 50%; 
            }
            td:before { 
                position: absolute;
                top: 12px;
                left: 10px;
                width: 45%; 
                padding-right: 10px; 
                white-space: nowrap;
                font-weight: bold;
                content: attr(data-label); /* Ważne dla dostępności tabel mobilnych */
            }
        }
        .visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Przejdź bezpośrednio do treści</a>

    <header>
        <div class="container header-content">
            <div style="font-size: 1.5rem; font-weight: bold;">
                <a href="{{ route('products.index') }}" style="color: white; text-decoration: none;">
                    Sklep Spożywczy
                </a>
            </div>

            <nav aria-label="Główna nawigacja">
                <div class="nav-group">
                    
                    
                    @auth
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'employee')
                            <a href="{{ route('admin.orders.index') }}" style="color: #ffc107;">Zamówienia</a>
                        @endif
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.users.index') }}" style="color: #ff9999;">Użytkownicy</a>
                        @endif
                    @endauth
                </div>

                <div class="nav-group" style="margin-left: 20px; border-left: 1px solid #555; padding-left: 20px;">
                    <a href="{{ route('cart.index') }}" aria-label="Przejdź do koszyka">
                        🛒 Koszyk 
                        @if(session('cart')) 
                            <span style="background: var(--warning); color: black; padding: 2px 6px; border-radius: 10px; font-size: 0.8em; font-weight: bold;">
                                {{ array_sum(session('cart')) }}
                            </span>
                        @endif
                    </a>

                    @auth
                        <span style="color: #ccc; font-size: 0.9em; margin-left: 10px;">
                            {{ Auth::user()->name }}
                        </span>
                        {{-- Formularz wylogowania - używamy tylko <input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}
                        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="btn" style="background: none; border: none; color: #adb5bd; padding: 5px 10px; font-size: 0.9em; text-decoration: underline;">
                                Wyloguj
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 5px 15px; font-size: 0.9em; margin-left: 10px;">Zaloguj</a>
                    @endauth
                </div>
            </nav>
        </div>
    </header>

    <main id="main-content">
        <div class="container">
            <div class="card">
                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-error" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Sklep Spożywczy. Aplikacja dostępna cyfrowo (WCAG 2.1).</p>
        </div>
    </footer>
</body>
</html>