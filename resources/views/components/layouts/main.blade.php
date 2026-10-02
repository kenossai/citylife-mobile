<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $title ?? 'City Life' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', Roboto, sans-serif; background: #f4f4f5; color: #1c1c1e; }
        a { color: inherit; text-decoration: none; }
        .screen { min-height: 100dvh; padding-bottom: calc(80px + env(safe-area-inset-bottom)); }
        .page-title { padding: calc(env(safe-area-inset-top) + 24px) 20px 12px; font-size: 22px; font-weight: 700; }

        .hero { position: relative; height: 320px; padding: calc(env(safe-area-inset-top) + 20px) 20px 0; color: #fff;
            background: linear-gradient(to bottom, rgba(0,0,0,.25), rgba(0,0,0,.55)), url('/images/home-hero.jpg') center / cover no-repeat, linear-gradient(135deg, #3b1d5e, #b3263e 60%, #0e7490); }
        .hero-row { display: flex; align-items: center; gap: 12px; }
        .avatar { width: 56px; height: 56px; border-radius: 50%; background: #e5e5e5; color: #333; display: grid; place-items: center; font-size: 10px; font-weight: 700; flex: none; }
        .hero small { display: block; font-size: 13px; opacity: .95; }
        .hero strong { font-size: 19px; letter-spacing: -.3px; }
        .bell { margin-left: auto; position: relative; width: 52px; height: 52px; border-radius: 16px; background: rgba(255,255,255,.2); display: grid; place-items: center; }
        .bell i { position: absolute; top: 8px; right: 8px; width: 16px; height: 16px; border-radius: 50%; background: #ef4444; font: 600 9px/16px sans-serif; text-align: center; font-style: normal; }

        .content { margin-top: -110px; position: relative; padding: 0 20px; }
        .card { background: #fff; border-radius: 24px; box-shadow: 0 4px 16px rgba(0,0,0,.06); }
        .quick { display: grid; grid-template-columns: repeat(4, 1fr); padding: 16px 8px; text-align: center; font-size: 11px; font-weight: 600; }
        .quick span.ico { display: grid; place-items: center; width: 64px; height: 64px; margin: 0 auto 8px; border-radius: 18px; color: #fff; }

        .live { margin-top: 20px; padding: 24px; border-radius: 24px; color: #fff; display: flex; align-items: center; justify-content: space-between;
            background: linear-gradient(135deg, #ff6b6b, #ff4d4f); }
        .live .tag { font-size: 10px; font-weight: 700; letter-spacing: 1px; display: flex; align-items: center; gap: 8px; }
        .live .tag::before { content: ''; width: 9px; height: 9px; border-radius: 50%; background: #fff; }
        .live h2 { font-size: 21px; margin: 8px 0 6px; }
        .live p { opacity: .9; }
        .watch { background: #fff; color: #ef4444; font-weight: 600; padding: 14px 24px; border-radius: 14px; box-shadow: 0 4px 10px rgba(0,0,0,.15); }

        .section-head { display: flex; justify-content: space-between; align-items: center; margin: 28px 0 14px; }
        .section-head h3 { font-size: 19px; }
        .section-head a { color: #2f5d9e; font-size: 13px; font-weight: 500; }
        .event { display: flex; align-items: center; gap: 16px; padding: 14px; margin-bottom: 14px; border: 1px solid #e5e5e5; box-shadow: none; }
        .date { width: 66px; height: 66px; border-radius: 14px; background: #e3f0fd; color: #1e6fd0; text-align: center; padding-top: 8px; flex: none; }
        .date small { display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .date b { font-size: 22px; line-height: 1.1; }
        .event h4 { font-size: 14px; margin-bottom: 4px; }
        .event p { color: #6b7280; }


        .profile-head { display: flex; align-items: center; gap: 20px; padding: calc(env(safe-area-inset-top) + 28px) 24px 28px; color: #fff; background: linear-gradient(135deg, #5b8def, #7c4dff); }
        .profile-head .badge { width: 84px; height: 84px; border-radius: 50%; background: #fff; color: #5b8def; display: grid; place-items: center; font-size: 26px; font-weight: 700; flex: none; }
        .profile-head h1 { font-size: 22px; }
        .profile-head p { opacity: .85; font-size: 14px; margin-top: 4px; }
        .profile-head .chev { margin-left: auto; }
        .menu-title { margin: 24px 20px 12px; color: #6b7280; font-size: 14px; font-weight: 500; }
        .menu { margin: 0 20px; padding: 0 12px; }
        .menu a { display: flex; align-items: center; gap: 14px; padding: 15px 4px; font-size: 16px; font-weight: 500; color: #333; }
        .menu a + a { border-top: 1px solid #ececec; }
        .menu a svg { width: 22px; height: 22px; color: #555; flex: none; }
        .menu a .chev { margin-left: auto; width: 20px; height: 20px; color: #aaa; }
        .count { margin-left: auto; min-width: 36px; height: 32px; padding: 0 14px; border-radius: 20px; background: #5b8def; color: #fff; display: grid; place-items: center; font-weight: 600; }
        .count + .chev { margin-left: 0 !important; }

        .signout { display: flex; align-items: center; justify-content: center; gap: 12px; width: calc(100% - 40px); margin: 28px 20px 0; padding: 16px; background: #fff; color: #ef4444;
            border: 1px solid #fbc5c0; border-radius: 20px; font-size: 16px; font-weight: 600; font-family: inherit; }
        .app-version { text-align: center; color: #8a8a8f; font-size: 14px; line-height: 1.7; margin: 28px 0 32px; }
        .chips { display: flex; gap: 8px; overflow-x: auto; padding: 4px 20px 16px; scrollbar-width: none; }
        .chip { flex: none; padding: 8px 16px; border-radius: 999px; border: 1px solid #e0e0e5; background: #fff; color: #555; font: 500 14px inherit; font-family: inherit; }
        .chip.active { background: #4f7df3; border-color: #4f7df3; color: #fff; }
        .tag-pill { font-size: 11px; font-weight: 600; color: #4f7df3; background: #eaf0ff; padding: 4px 10px; border-radius: 999px; }
        .detail-hero { height: 220px; padding: calc(env(safe-area-inset-top) + 16px) 20px 0; display: flex; justify-content: space-between; align-items: flex-start;
            background: linear-gradient(to bottom, rgba(0,0,0,.2), rgba(0,0,0,.45)), url('/images/event-hero.jpg') center / cover no-repeat, linear-gradient(135deg, #5b8def, #7c4dff); }
        .back { width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,.25); display: grid; place-items: center; }
        .detail-body { position: relative; margin-top: -24px; background: #f4f4f5; border-radius: 24px 24px 0 0; padding: 24px 20px; }
        .detail-body h1 { font-size: 22px; margin-bottom: 16px; }
        .detail-body h3 { font-size: 17px; margin: 24px 0 8px; }
        .info { padding: 8px 16px; }
        .info .row { display: flex; gap: 14px; align-items: center; padding: 12px 0; }
        .info .row + .row { border-top: 1px solid #ececec; }
        .info .ico { width: 44px; height: 44px; border-radius: 12px; background: #e3f0fd; color: #1e6fd0; display: grid; place-items: center; flex: none; }
        .info .ico svg { width: 22px; height: 22px; }
        .info b { font-size: 15px; } .info p { color: #6b7280; font-size: 14px; margin-top: 2px; }
        .about { color: #555; font-size: 15px; line-height: 1.6; }
        .cta { width: 100%; margin-top: 28px; padding: 16px; border: 0; border-radius: 16px; background: #4f7df3; color: #fff; font: 600 16px inherit; font-family: inherit; }
        .cta.done { background: #22c55e; }
        [x-cloak] { visibility: hidden; }
        .more-panel { position: fixed; top: 0; left: 0; right: 0; bottom: calc(66px + env(safe-area-inset-bottom)); z-index: 5; overflow-y: auto; background: #f4f4f5;
            transform: translateX(100%); visibility: hidden; transition: transform .35s cubic-bezier(.32,.72,0,1), visibility 0s .35s; box-shadow: -8px 0 24px rgba(0,0,0,.12); }
        .more-panel.open { transform: translateX(0); visibility: visible; transition: transform .35s cubic-bezier(.32,.72,0,1), visibility 0s; }

        .navbar { position: fixed; left: 0; right: 0; bottom: 0; display: flex; justify-content: space-around; background: #f5f6ff;
            padding: 10px 0 calc(env(safe-area-inset-bottom) + 8px); border-top: 1px solid #ececf3; z-index: 10; }
        .navbar a { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; font-size: 11px; color: #9a9aa2; }
        .navbar a.active { color: #4f7df3; }
        .navbar svg { width: 28px; height: 28px; }
    </style>
</head>
<body x-data="{ more: false }" @keydown.escape.window="more = false">
    <div class="screen">{{ $slot }}</div>

    <aside class="more-panel" :class="{ open: more }" :aria-hidden="!more" x-cloak>
        <livewire:more />
    </aside>

    <x-navbar />
</body>
</html>
