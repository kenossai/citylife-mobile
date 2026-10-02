<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $title ?? 'City Life' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', Roboto, sans-serif;
            color: #fff;
            background: #1e3a8a;
        }
        .splash {
            position: relative;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 24px 28px;
            padding-top: calc(env(safe-area-inset-top) + 24px);
            padding-bottom: calc(env(safe-area-inset-bottom) + 32px);
            overflow: hidden;
            background:
                linear-gradient(to bottom, rgba(15,23,42,.35) 0%, rgba(15,23,42,0) 35%, rgba(30,64,175,.55) 100%),
                url('/images/splash-bg.jpg') center / cover no-repeat,
                linear-gradient(to bottom, #1f2a3d 0%, #4a4a63 40%, #7f8fb0 65%, #1e40af 100%);
        }
        .skip {
            position: absolute;
            top: calc(env(safe-area-inset-top) + 20px);
            right: 28px;
            color: #fff;
            font-size: 14px;
            text-decoration: underline;
        }
        h1 { font-size: 27px; font-weight: 700; letter-spacing: -.5px; margin-bottom: 14px; }
        p { font-size: 14px; line-height: 1.5; opacity: .92; max-width: 330px; }
        .actions { display: flex; justify-content: flex-end; margin-top: 8px; }
        .btn {
            background: #fff;
            color: #1565c0;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            padding: 22px 48px;
            border-radius: 18px;
            box-shadow: 0 8px 20px rgba(0,0,0,.25);
        }
        .btn:active { transform: scale(.97); }
    </style>
</head>
<body>
    {{ $slot }}
</body>
</html>
