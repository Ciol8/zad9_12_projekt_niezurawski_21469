<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jedzonko na zamówienie c:</title>
    
    {{-- 1. Import nowoczesnej czcionki 'Inter' z Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- ZMIENNE KOLORYSTYCZNE (Nowoczesna Paleta + WCAG) --- */
        :root {
            --bg-body: #f3f4f6;         /* Bardzo jasny szary (nie czysta biel) - mniej męczy oczy */
            --bg-card: #ffffff;
            --text-main: #1f2937;       /* Ciemny grafit zamiast czystej czerni (bardziej elegancki) */
            --text-muted: #6b7280;      /* Szary dla opisów */
            
            /* Kolory Akcji */
            --primary: #2563eb;         /* Nowoczesny niebieski (Royal Blue) */
            --primary-hover: #1d4ed8;
            --success: #059669;         /* Głęboka zieleń */
            --danger: #dc2626;          /* Czerwień */
            --warning: #fbbf24;         /* Żółty/Złoty */
            
            /* Obramowania i Cienie */
            --border: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --radius: 8px;              /* Zaokrąglenie rogów */
            --focus-ring: #60a5fa;      /* Kolor obwódki przy nawigacji klawiaturą */
        }

        /* --- BAZA --- */
        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif; /* Nowa czcionka */
            background-color: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* --- DOSTĘPNOŚĆ (Skip Link & Focus) --- */
        .skip-link {
            position: absolute;
            top: -50px; left: 0;
            background: var(--primary);
            color: white;
            padding: 10px 15px;
            z-index: 100;
            transition: top 0.3s;
            text-decoration: none;
            font-weight: 600;
            border-radius: 0 0 8px 0;
        }
        .skip-link:focus { top: 0; }

        /* Wyraźny Focus Ring (kluczowy dla dostępności) */
        :focus-visible {
            outline: 3px solid var(--focus-ring);
            outline-offset: 2px;
        }
        
        /* Ukrywanie labeli dla screen readerów */
        .visually-hidden {
            position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px;
            overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0;
        }

        /* --- UKŁAD (LAYOUT) --- */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* --- NAGŁÓWEK (HEADER) --- */
        header {
            background-color: #111827; /* Bardzo ciemny granat/czarny */
            color: white;
            padding: 1rem 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        nav a {
            color: #e5e7eb;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
            font-size: 0.95rem;
        }
        nav a:hover { color: white; text-decoration: underline; }

        /* --- TREŚĆ (MAIN) --- */
        main {
            flex: 1;
            padding: 40px 0; /* Więcej oddechu góra/dół */
        }

        /* Karta (Kontener treści) - Klucz do ładnego wyglądu */
        .card {
            background: var(--bg-card);
            border-radius: var(--radius);
            box-shadow: var(--shadow-md); /* Miękki cień zamiast grubej ramki */
            border: 1px solid var(--border); /* Cienka ramka dla kontrastu */
            padding: 30px;
            margin-bottom: 20px;
        }

        h1, h2, h3 {
            color: #111827;
            margin-top: 0;
            line-height: 1.2;
        }
        h1 { font-size: 1.8rem; font-weight: 700; margin-bottom: 1.5rem; }
        h2 { font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem; border-bottom: 2px solid var(--bg-body); padding-bottom: 10px; }

        /* --- FORMULARZE I PRZYCISKI --- */
        .form-group { margin-bottom: 1.2rem; }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px; /* Lekkie zaokrąglenie */
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: inherit;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); /* Delikatna poświata */
            outline: none;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.2s;
            font-size: 0.95rem;
            gap: 8px; /* Odstęp jeśli w przycisku jest ikona */
        }

        .btn:hover { transform: translateY(-1px); } /* Mikro-interakcja */
        .btn:active { transform: translateY(0); }

        .btn-default { background: white; border-color: #d1d5db; color: #374151; }
        .btn-default:hover { background: #f9fafb; border-color: #9ca3af; }

        .btn-primary { background-color: var(--primary); color: white; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .btn-primary:hover { background-color: var(--primary-hover); box-shadow: 0 2px 4px rgba(0,0,0,0.15); }

        .btn-red { background-color: white; color: var(--danger); border: 1px solid var(--danger); }
        .btn-red:hover { background-color: #fef2f2; }

        /* --- TABELE --- */
        .table-responsive {
            overflow-x: auto;
            border-radius: var(--radius);
            border: 1px solid var(--border);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background-color: #f9fafb;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #6b7280;
            font-weight: 700;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }

        td {
            padding: 16px;
            border-bottom: 1px solid var(--border);
            color: #374151;
            font-size: 0.95rem;
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background-color: #f9fafb; } /* Podświetlenie wiersza */

        /* --- ALERTY --- */
        .alert {
            padding: 15px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            border-left: 4px solid transparent;
            font-weight: 500;
        }
        .alert-success { background-color: #ecfdf5; color: #065f46; border-left-color: var(--success); }
        .alert-error { background-color: #fef2f2; color: #991b1b; border-left-color: var(--danger); }
        .alert-warning { background-color: #fffbeb; color: #92400e; border-left-color: var(--warning); }

        /* --- PAGINACJA --- */
        .pagination { display: flex; justify-content: center; margin-top: 2rem; }
        .pagination span, .pagination a {
            padding: 8px 14px;
            border: 1px solid var(--border);
            margin: 0 4px;
            border-radius: 6px;
            text-decoration: none;
            color: #374151;
            background: white;
        }
        .pagination .active span {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* --- RESPONSYWNOŚĆ (Mobile) --- */
        @media (max-width: 768px) {
            .header-content { flex-direction: column; gap: 15px; }
            nav { flex-direction: column; gap: 10px; width: 100%; text-align: center; }
            
            /* Karty tabeli na mobile */
            table, thead, tbody, th, td, tr { display: block; }
            thead tr { position: absolute; top: -9999px; left: -9999px; }
            tr { 
                margin-bottom: 15px; 
                border: 1px solid var(--border); 
                border-radius: var(--radius); 
                box-shadow: var(--shadow-sm);
                overflow: hidden;
            }
            td { 
                border: none;
                border-bottom: 1px solid #f3f4f6; 
                position: relative;
                padding-left: 40%; 
                text-align: right;
            }
            td:before { 
                position: absolute;
                top: 16px; left: 16px;
                width: 35%; 
                padding-right: 10px; 
                white-space: nowrap;
                text-align: left;
                font-weight: 700;
                color: #6b7280;
                text-transform: uppercase;
                font-size: 0.75rem;
                content: attr(data-label); 
            }
            td:last-child { border-bottom: none; }
        }

        /* --- STOPKA --- */
        footer {
            margin-top: auto;
            background: white;
            border-top: 1px solid var(--border);
            padding: 20px 0;
            text-align: center;
            color: #6b7280;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Przejdź bezpośrednio do treści</a>

    <header>
        <div class="container header-content">
            <div style="font-size: 1.25rem; font-weight: 800; letter-spacing: -0.025em;">
                <a href="{{ route('products.index') }}" style="color: white; text-decoration: none; display: flex; align-items: center; gap: 10px;">
                    🛒 Jedzonko na zamówienie c:
                </a>
            </div>

            <nav aria-label="Główna nawigacja">
                <a href="{{ route('products.index') }}">Produkty</a>
                
                @auth
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'employee')
                        <a href="{{ route('admin.orders.index') }}" style="color: #fbbf24;">Zamówienia</a>
                    @endif
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.users.index') }}" style="color: #fca5a5;">Użytkownicy</a>
                    @endif
                @endauth

                <div style="display: flex; align-items: center; gap: 15px; margin-left: 10px; padding-left: 20px; border-left: 1px solid #374151;">
                    <a href="{{ route('cart.index') }}" aria-label="Koszyk" style="position: relative;">
                        Koszyk
                        @if(session('cart')) 
                            <span style="position: absolute; top: -8px; right: -12px; background: var(--warning); color: #1f2937; padding: 1px 6px; border-radius: 10px; font-size: 0.7em; font-weight: 800;">
                                {{ array_sum(session('cart')) }}
                            </span>
                        @endif
                    </a>

                    @auth
                        <span style="font-size: 0.85rem; color: #9ca3af;">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" style="background: none; border: none; color: #9ca3af; cursor: pointer; font-size: 0.85rem; font-weight: 500; padding: 0;">Wyloguj</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" style="background: white; color: #111827; padding: 6px 14px; border-radius: 6px; font-size: 0.85rem;">Zaloguj</a>
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
            <p>&copy; {{ date('Y') }} Jedzonko na zamówienie c: <br> Aplikacja zgodna z WCAG 2.1 AA.</p>
        </div>
    </footer>
</body>
</html>