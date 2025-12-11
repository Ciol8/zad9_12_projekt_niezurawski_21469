<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Sklep Spożywczy - Win7 Aero</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 40px;
            min-height: 100vh;
            background:
                radial-gradient(circle at 50% 100%, rgba(5, 237, 255, 0.4) 0%, rgba(0, 163, 255, 0) 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0) 20%),
                linear-gradient(135deg, #2660a5 0%, #0099cc 100%);
            background-attachment: fixed;
            color: #1e1e1e;
        }


        .container {
            max-width: 1100px;
            margin: 0 auto;
            background: rgba(240, 248, 255, 0.65);
            backdrop-filter: blur(15px) saturate(120%);
            -webkit-backdrop-filter: blur(15px) saturate(120%);
            padding: 25px;
            border-radius: 12px;
            box-shadow:
                0 0 0 1px rgba(255, 255, 255, 0.5) inset,
                0 15px 35px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 30px;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.1));
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            pointer-events: none;
        }

        h1 {
            font-weight: normal;
            color: #1a3e6e;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
            margin-top: 10px;
            font-size: 28px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            background: white;
            border: 1px solid #aebfd1;
            border-radius: 3px;
        }

        th {
            background: linear-gradient(to bottom, #fcfcfc 0%, #f0f0f0 40%, #e2e2e2 100%);
            border-bottom: 1px solid #aebfd1;
            border-right: 1px solid #dcdcdc;
            padding: 10px 15px;
            text-align: left;
            color: #444;
            font-size: 13px;
            font-weight: 600;
        }

        td {
            padding: 10px 15px;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
            font-size: 14px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #eaf6fd;
            border-bottom-color: #b8d6fb;
        }

        .btn {
            display: inline-block;
            padding: 6px 16px;
            text-decoration: none;
            color: #1e1e1e;
            font-size: 13px;
            border: 1px solid #707070;
            border-radius: 3px;
            background: linear-gradient(to bottom, #f2f2f2 0%, #ebebeb 50%, #dddddd 50%, #cfcfcf 100%);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7), 0 1px 2px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.1s;
        }

        .btn:hover {
            background: linear-gradient(to bottom, #eaf6fd 0%, #d9f0fc 50%, #bee6fd 50%, #a7d9f5 100%);
            border-color: #3c7fb1;
            box-shadow: inset 0 0 2px rgba(255, 255, 255, 1), 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .btn:active {
            background: linear-gradient(to bottom, #a7d9f5 0%, #bee6fd 100%);
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .btn-red {
            color: #7a1e1e;
        }

        .btn-red:hover {
            background: linear-gradient(to bottom, #fdeae9 0%, #fbd5d2 50%, #f7b7b2 50%, #f29a92 100%);
            border-color: #b13c3c;
        }

        /* --- FORMULARZE --- */
        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 600;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 6px;
            border: 1px solid #abadb3;
            border-radius: 2px;
            font-family: 'Segoe UI', sans-serif;
            background: #fff;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #3399ff;
            box-shadow: 0 0 0 2px rgba(51, 153, 255, 0.3);
        }

        .checkbox-container {
            background: white;
            border: 1px solid #abadb3;
            padding: 10px;
            border-radius: 2px;
            max-height: 200px;
            overflow-y: auto;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .alert {
            background: rgba(223, 240, 216, 0.9);
            border: 1px solid #b2dba1;
            color: #3c763d;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(5px);
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }


        .w-5 {
            width: 1.25rem;
        }

        .h-5 {
            height: 1.25rem;
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>
</body>

</html>