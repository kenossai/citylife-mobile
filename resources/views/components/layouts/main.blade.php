<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover">
    <title>{{ $title ?? 'City Life' }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            -webkit-text-size-adjust: 100%;
            overflow-x: hidden;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', Roboto, sans-serif;
            background: #f4f4f5;
            color: #1c1c1e;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .screen {
            min-height: 100dvh;
            padding-bottom: calc(80px + env(safe-area-inset-bottom));
        }

        .page-title {
            padding: calc(env(safe-area-inset-top) + 24px) 20px 12px;
            font-size: 22px;
            font-weight: 700;
        }

        .hero {
            position: relative;
            height: 320px;
            padding: calc(env(safe-area-inset-top) + 20px) 20px 0;
            color: #fff;
            background: linear-gradient(to bottom, rgba(23, 35, 30, .18), rgba(23, 35, 30, .78)), url('https://images.unsplash.com/photo-1687834303092-585075b56885?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjaHVyY2glMjB3b3JzaGlwJTIwc2VydmljZXxlbnwxfHx8fDE3NTk0MDk5NDF8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral') center / cover no-repeat, #254b3b;
        }

        .hero-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #e5e5e5;
            color: #333;
            display: grid;
            place-items: center;
            font-size: 10px;
            font-weight: 700;
            flex: none;
        }

        .hero small {
            display: block;
            font-size: 13px;
            opacity: .95;
        }

        .hero strong {
            font-size: 19px;
            letter-spacing: -.3px;
        }

        .bell {
            margin-left: auto;
            position: relative;
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: rgba(255, 255, 255, .2);
            display: grid;
            place-items: center;
        }

        .bell i {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #ef4444;
            font: 600 9px/16px sans-serif;
            text-align: center;
            font-style: normal;
        }

        .content {
            margin-top: -110px;
            position: relative;
            padding: 0 20px;
        }

        .card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .06);
        }

        .quick {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            padding: 16px 8px;
            text-align: center;
            font-size: 11px;
            font-weight: 600;
        }

        .quick span.ico {
            display: grid;
            place-items: center;
            width: 64px;
            height: 64px;
            margin: 0 auto 8px;
            border-radius: 18px;
            color: #fff;
        }

        .live {
            margin-top: 20px;
            padding: 24px;
            border-radius: 24px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #ff6b6b, #ff4d4f);
        }

        .live .tag {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .live .tag::before {
            content: '';
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #fff;
        }

        .live h2 {
            font-size: 21px;
            margin: 8px 0 6px;
        }

        .live p {
            opacity: .9;
        }

        .watch {
            background: #fff;
            color: #ef4444;
            font-weight: 600;
            padding: 14px 24px;
            border-radius: 14px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .15);
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 28px 0 14px;
        }

        .section-head h3 {
            font-size: 19px;
        }

        .section-head a {
            color: #2f5d9e;
            font-size: 13px;
            font-weight: 500;
        }

        .event {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px;
            margin-bottom: 14px;
            border: 1px solid #e5e5e5;
            box-shadow: none;
        }

        .date {
            width: 66px;
            height: 66px;
            border-radius: 14px;
            background: #eef2ff;
            color: #4338ca;
            text-align: center;
            padding-top: 8px;
            flex: none;
        }

        .date small {
            display: block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .date b {
            font-size: 22px;
            line-height: 1.1;
        }

        .event h4 {
            font-size: 14px;
            margin-bottom: 4px;
        }

        .event p {
            color: #6b7280;
        }


        .profile-head {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: calc(env(safe-area-inset-top) + 28px) 24px 28px;
            color: #fff;
            background: linear-gradient(135deg, #4f46e5, #7c4dff);
        }

        .profile-head .badge {
            width: 84px;
            height: 84px;
            border-radius: 50%;
            background: #fff;
            color: #4f46e5;
            display: grid;
            place-items: center;
            font-size: 26px;
            font-weight: 700;
            flex: none;
        }

        .profile-head h1 {
            font-size: 22px;
        }

        .profile-head p {
            opacity: .85;
            font-size: 14px;
            margin-top: 4px;
        }

        .profile-head .chev {
            margin-left: auto;
        }

        .menu-title {
            margin: 24px 20px 12px;
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
        }

        .menu {
            margin: 0 20px;
            padding: 0 12px;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 15px 4px;
            font-size: 16px;
            font-weight: 500;
            color: #333;
        }

        .menu a+a {
            border-top: 1px solid #ececec;
        }

        .menu a svg {
            width: 22px;
            height: 22px;
            color: #555;
            flex: none;
        }

        .menu a .chev {
            margin-left: auto;
            width: 20px;
            height: 20px;
            color: #aaa;
        }

        .count {
            margin-left: auto;
            min-width: 36px;
            height: 32px;
            padding: 0 14px;
            border-radius: 20px;
            background: #4f46e5;
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 600;
        }

        .count+.chev {
            margin-left: 0 !important;
        }

        .signout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: calc(100% - 40px);
            margin: 28px 20px 0;
            padding: 16px;
            background: #fff;
            color: #ef4444;
            border: 1px solid #fbc5c0;
            border-radius: 20px;
            font-size: 16px;
            font-weight: 600;
            font-family: inherit;
        }

        .app-version {
            text-align: center;
            color: #8a8a8f;
            font-size: 14px;
            line-height: 1.7;
            margin: 28px 0 32px;
        }

        .chips {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 4px 20px 16px;
            scrollbar-width: none;
        }

        .chip {
            flex: none;
            padding: 8px 16px;
            border-radius: 999px;
            border: 1px solid #e0e0e5;
            background: #fff;
            color: #555;
            font: 500 14px inherit;
            font-family: inherit;
        }

        .chip.active {
            background: #4f46e5;
            border-color: #4f46e5;
            color: #fff;
        }

        .tag-pill {
            font-size: 11px;
            font-weight: 600;
            color: #4f46e5;
            background: #eaf0ff;
            padding: 4px 10px;
            border-radius: 999px;
        }

        .detail-hero {
            height: 220px;
            padding: calc(env(safe-area-inset-top) + 16px) 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            background: linear-gradient(to bottom, rgba(0, 0, 0, .2), rgba(0, 0, 0, .45)), url('/images/event-hero.jpg') center / cover no-repeat, linear-gradient(135deg, #4f46e5, #7c4dff);
        }

        .back {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .25);
            display: grid;
            place-items: center;
        }

        .detail-body {
            position: relative;
            margin-top: -24px;
            background: #f4f4f5;
            border-radius: 24px 24px 0 0;
            padding: 24px 20px;
        }

        .detail-body h1 {
            font-size: 22px;
            margin-bottom: 16px;
        }

        .detail-body h3 {
            font-size: 17px;
            margin: 24px 0 8px;
        }

        .info {
            padding: 8px 16px;
        }

        .info .row {
            display: flex;
            gap: 14px;
            align-items: center;
            padding: 12px 0;
        }

        .info .row+.row {
            border-top: 1px solid #ececec;
        }

        .info .ico {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #eef2ff;
            color: #4338ca;
            display: grid;
            place-items: center;
            flex: none;
        }

        .info .ico svg {
            width: 22px;
            height: 22px;
        }

        .info b {
            font-size: 15px;
        }

        .info p {
            color: #6b7280;
            font-size: 14px;
            margin-top: 2px;
        }

        .about {
            color: #555;
            font-size: 15px;
            line-height: 1.6;
        }

        .cta {
            width: 100%;
            margin-top: 28px;
            padding: 16px;
            border: 0;
            border-radius: 16px;
            background: #4f46e5;
            color: #fff;
            font: 600 16px inherit;
            font-family: inherit;
        }

        .cta.done {
            background: #22c55e;
        }

        [x-cloak] {
            visibility: hidden;
        }

        .more-panel {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: calc(66px + env(safe-area-inset-bottom));
            z-index: 5;
            overflow-y: auto;
            background: #f4f4f5;
            transform: translateX(100%);
            visibility: hidden;
            transition: transform .35s cubic-bezier(.32, .72, 0, 1), visibility 0s .35s;
            box-shadow: -8px 0 24px rgba(0, 0, 0, .12);
        }

        .more-panel.open {
            transform: translateX(0);
            visibility: visible;
            transition: transform .35s cubic-bezier(.32, .72, 0, 1), visibility 0s;
        }

        .navbar {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            justify-content: space-around;
            background: #f5f5ff;
            padding: 10px 0 calc(env(safe-area-inset-bottom) + 8px);
            border-top: 1px solid #ececf3;
            z-index: 10;
        }

        .navbar a {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            color: #9a9aa2;
        }

        .navbar a.active {
            color: #4f46e5;
        }

        .navbar svg {
            width: 28px;
            height: 28px;
        }

        .profile-page .p-head {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: calc(env(safe-area-inset-top) + 16px) 20px 28px;
            color: #fff;
            background: linear-gradient(135deg, #4f46e5, #7c4dff);
        }

        .profile-page .p-back {
            position: absolute;
            left: 20px;
            top: calc(env(safe-area-inset-top) + 16px);
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .25);
            display: grid;
            place-items: center;
        }

        .profile-page .p-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 18px;
            line-height: 40px;
        }

        .profile-page .p-avatar {
            width: 92px;
            height: 92px;
            border-radius: 50%;
            background: #fff;
            color: #4f46e5;
            display: grid;
            place-items: center;
            font-size: 30px;
            font-weight: 700;
        }

        .profile-page .p-head h1 {
            margin-top: 12px;
            font-size: 22px;
        }

        .profile-page .p-head p {
            margin-top: 4px;
            font-size: 14px;
            opacity: .85;
        }

        .profile-page .p-body {
            padding: 0 20px 24px;
        }

        .profile-page h2 {
            margin: 28px 0 14px;
            font-size: 20px;
            font-weight: 700;
        }

        .profile-page .p-card {
            padding: 4px 16px;
        }

        .profile-page .p-row {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 0;
        }

        .profile-page .p-row+.p-row {
            border-top: 1px solid #ececec;
        }

        .profile-page .p-ico {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            flex: none;
        }

        .profile-page .p-ico svg {
            width: 26px;
            height: 26px;
        }

        .profile-page .p-row small {
            display: block;
            color: #8a8a8f;
            font-size: 14px;
        }

        .profile-page .p-row b {
            display: block;
            margin-top: 3px;
            font-size: 17px;
            font-weight: 600;
            line-height: 1.35;
        }

        .profile-page .p-label {
            flex: 1;
            font-size: 17px;
            font-weight: 600;
        }

        .profile-page .p-switch {
            position: relative;
            width: 62px;
            height: 36px;
            border: 2px solid transparent;
            border-radius: 999px;
            background: #e0e0e5;
            flex: none;
            transition: background .2s;
        }

        .profile-page .p-switch::after {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #6b7280;
            transition: transform .2s, background .2s;
        }

        .profile-page .p-switch.on {
            background: #4f46e5;
        }

        .profile-page .p-switch.on::after {
            background: #fff;
            transform: translateX(26px);
        }

        .profile-page .p-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            margin-top: 14px;
            padding: 18px;
            border-radius: 16px;
            border: 2px solid #4f46e5;
            background: transparent;
            color: #4f46e5;
            font: inherit;
            font-size: 17px;
            font-weight: 600;
        }

        .profile-page .p-btn svg {
            width: 22px;
            height: 22px;
        }

        .profile-page .p-btn.primary {
            background: #4f46e5;
            color: #fff;
            box-shadow: 0 4px 10px rgba(79, 70, 229, .35);
            margin-top: 24px;
        }

        .profile-page .p-btn.danger {
            border-color: #f44336;
            color: #f44336;
        }

        .prayer-head {
            padding: calc(env(safe-area-inset-top) + 12px) 16px 14px;
            background: #fff;
        }

        .prayer-head-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .prayer-head h1 {
            font-size: 22px;
        }

        .prayer-head p {
            margin-top: 8px;
            color: #6b7280;
            font-size: 13px;
        }

        .prayer-back {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: #f4f4f5;
            display: grid;
            place-items: center;
        }

        .prayer-body {
            padding: 0 16px 20px;
        }

        .prayer-tabs {
            display: flex;
            gap: 2px;
            margin: 12px 0;
            padding: 3px;
            border-radius: 999px;
            background: #ececec;
        }

        .prayer-tabs button {
            flex: 1;
            padding: 9px 6px;
            border: 0;
            border-radius: 999px;
            background: transparent;
            color: #6b6b70;
            font: inherit;
            font-size: 13px;
            font-weight: 600;
        }

        .prayer-tabs button.active {
            background: #fff;
            color: #000;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .1);
        }

        .prayer-card {
            padding: 16px;
            margin-bottom: 14px;
        }

        .prayer-card h2 {
            font-size: 17px;
            margin-bottom: 14px;
        }

        .prayer-card label {
            display: block;
            margin: 14px 0 8px;
            font-size: 14px;
            font-weight: 600;
        }

        .prayer-card input,
        .prayer-card select,
        .prayer-card textarea {
            display: block;
            width: 100%;
            padding: 12px 14px;
            border: 0;
            border-radius: 12px;
            background: #f4f4f5;
            color: #1c1c1e;
            font: inherit;
            font-size: 16px;
            outline: none;
        }

        .prayer-card textarea {
            resize: vertical;
            min-height: 110px;
        }

        .prayer-card input::placeholder,
        .prayer-card textarea::placeholder {
            color: #bdbdc2;
        }

        .prayer-card small {
            display: block;
            margin-top: 8px;
            color: #6b7280;
            font-size: 12px;
        }

        .prayer-error {
            margin-top: 6px;
            color: #b42318;
            font-size: 12px;
        }

        .prayer-notice {
            margin-top: 16px;
            padding: 14px;
            border-radius: 12px;
            background: #eef2ff;
            color: #4338ca;
            font-size: 13px;
            line-height: 1.45;
        }

        .prayer-notice p {
            margin-top: 4px;
        }

        .prayer-submit {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            margin-top: 16px;
            padding: 14px;
            border: 0;
            border-radius: 12px;
            background: #000;
            color: #fff;
            font: inherit;
            font-size: 14px;
            font-weight: 600;
        }

        .prayer-submit svg {
            width: 18px;
            height: 18px;
        }

        .prayer-guidelines {
            list-style: none;
        }

        .prayer-guidelines li {
            position: relative;
            padding-left: 20px;
            margin-bottom: 10px;
            color: #6b7280;
            font-size: 14px;
            line-height: 1.45;
        }

        .prayer-guidelines li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 7px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #4f46e5;
        }

        .prayer-item-top {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .prayer-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #e3ebff;
            color: #4f46e5;
            display: grid;
            place-items: center;
            font-size: 15px;
            font-weight: 600;
            flex: none;
        }

        .prayer-who {
            flex: 1;
            min-width: 0;
        }

        .prayer-who b {
            display: block;
            font-size: 15px;
        }

        .prayer-who small {
            display: block;
            margin-top: 2px;
            color: #6b7280;
            font-size: 12px;
        }

        .prayer-tag {
            padding: 5px 12px;
            border-radius: 999px;
            background: #f0f0f1;
            color: #555;
            font-size: 12px;
            font-weight: 600;
        }

        .prayer-item p {
            margin: 12px 0 14px;
            color: #555;
            font-size: 14px;
            line-height: 1.5;
            max-width: none;
            opacity: 1;
        }

        .prayer-item-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .prayer-count {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #6b7280;
            font-size: 13px;
        }

        .prayer-count svg {
            width: 16px;
            height: 16px;
        }

        .prayer-pray {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border: 1.5px solid #4f46e5;
            border-radius: 999px;
            background: #fff;
            color: #4f46e5;
            font: inherit;
            font-size: 13px;
            font-weight: 600;
        }

        .prayer-pray svg {
            width: 16px;
            height: 16px;
        }

        .prayer-pray.done {
            background: #eaf0ff;
            opacity: .8;
        }

        .prayer-empty {
            color: #6b7280;
            text-align: center;
        }

        .sermon-sticky {
            position: sticky;
            top: 0;
            z-index: 2;
            background: #f4f4f5;
        }

        .sermon-head {
            padding: calc(env(safe-area-inset-top) + 20px) 16px 14px;
            background: #fff;
        }

        .sermon-head h1 {
            font-size: 24px;
            margin-bottom: 14px;
        }

        .sermon-search {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 14px;
            border-radius: 12px;
            background: #f4f4f5;
            color: #9a9aa2;
        }

        .sermon-search input {
            flex: 1;
            min-width: 0;
            padding: 12px 0;
            border: 0;
            background: transparent;
            color: #1c1c1e;
            font: inherit;
            font-size: 16px;
            outline: none;
        }

        .sermon-body {
            padding: 0 16px 20px;
        }

        .sermon-tabs {
            display: flex;
            padding: 12px 16px;
        }

        .sermon-tabs button {
            flex: 1;
            padding: 10px 6px;
            border: 0;
            border-radius: 12px;
            background: transparent;
            color: #6b6b70;
            font: inherit;
            font-size: 14px;
            font-weight: 600;
        }

        .sermon-tabs button.active {
            background: #fff;
            color: #000;
        }

        .sermon-card {
            display: flex;
            gap: 14px;
            padding: 12px;
            margin-bottom: 12px;
        }

        .sermon-thumb {
            position: relative;
            width: 104px;
            height: 104px;
            border-radius: 14px;
            flex: none;
            overflow: hidden;
        }

        .sermon-play {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 38px;
            height: 38px;
            margin: -23px 0 0 -19px;
            border-radius: 50%;
            background: #fff;
            display: grid;
            place-items: center;
        }

        .sermon-time {
            position: absolute;
            right: 6px;
            bottom: 6px;
            padding: 3px 8px;
            border-radius: 8px;
            background: rgba(0, 0, 0, .6);
            color: #fff;
            font-size: 11px;
            font-weight: 600;
        }

        .sermon-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            gap: 4px;
            min-width: 0;
        }

        .sermon-info h3 {
            font-size: 16px;
        }

        .sermon-info p {
            color: #6b7280;
            font-size: 13px;
        }

        .sermon-info small {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #9a9aa2;
            font-size: 12px;
        }

        .series-card {
            display: block;
            width: 100%;
            margin-bottom: 14px;
            padding: 0;
            overflow: hidden;
            border: 0;
            text-align: left;
            font: inherit;
            color: inherit;
        }

        .series-thumb {
            height: 150px;
        }

        .series-info {
            padding: 14px 16px;
        }

        .series-info h3 {
            font-size: 17px;
            margin-bottom: 4px;
        }

        .series-info p {
            color: #6b7280;
            font-size: 13px;
            line-height: 1.4;
        }

        .series-info small {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
            color: #6b7280;
            font-size: 13px;
        }

        .series-info small span {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .series-hero {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 260px;
            padding: calc(env(safe-area-inset-top) + 16px) 20px 24px;
            color: #fff;
        }

        .series-back {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .2);
            display: grid;
            place-items: center;
        }

        .series-hero h1 {
            font-size: 28px;
            margin-bottom: 6px;
        }

        .series-hero p {
            font-size: 15px;
            opacity: .85;
        }

        .series-body {
            padding: 20px 16px;
        }

        .series-body h2 {
            font-size: 18px;
            margin: 4px 0 12px;
        }

        .series-about {
            padding: 16px;
            margin-bottom: 24px;
        }

        .series-about p {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 14px;
        }

        .series-about>div {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .series-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
        }

        .series-pill.blue {
            background: #eef2ff;
            color: #4f46e5;
        }

        .series-pill.purple {
            background: #f8e8f8;
            color: #a21caf;
        }

        .series-message {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 16px;
        }

        .series-message>div {
            flex: 1;
            min-width: 0;
        }

        .series-num {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #4f46e5;
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 700;
            flex: none;
        }

        .series-message h3 {
            font-size: 15px;
            margin-bottom: 4px;
        }

        .series-message p {
            display: flex;
            flex-wrap: wrap;
            gap: 4px 12px;
            color: #6b7280;
            font-size: 12px;
        }

        .series-message p span {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .series-play {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #eef2ff;
            display: grid;
            place-items: center;
            flex: none;
        }

        .player-hero {
            position: relative;
            height: 280px;
            padding: calc(env(safe-area-inset-top) + 16px) 20px 0;
        }

        .player-big {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 76px;
            height: 76px;
            margin: -28px 0 0 -38px;
            border: 0;
            border-radius: 50%;
            background: #fff;
            display: grid;
            place-items: center;
        }

        .player-body {
            padding: 20px 16px;
        }

        .player-body h1 {
            font-size: 22px;
            margin: 12px 0 4px;
        }

        .player-pastor {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 18px;
        }

        .player-body h2 {
            font-size: 18px;
            margin: 24px 0 12px;
        }

        .player-bar {
            width: 100%;
            height: 6px;
            border-radius: 3px;
            appearance: none;
            -webkit-appearance: none;
            background: linear-gradient(to right, #4f46e5 var(--p, 0%), #dcdce2 var(--p, 0%));
        }

        .player-bar::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #4f46e5;
        }

        .player-times {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
            color: #6b7280;
            font-size: 12px;
        }

        .player-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 10px 0;
            color: #1c1c1e;
        }

        .player-controls button,
        .player-controls a,
        .player-controls .off {
            position: relative;
            display: grid;
            place-items: center;
            width: 44px;
            height: 44px;
            border: 0;
            background: transparent;
            color: inherit;
        }

        .player-controls .off {
            opacity: .25;
        }

        .player-controls small {
            position: absolute;
            font-size: 8px;
            font-weight: 700;
            top: 18px;
        }

        .player-controls .main {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #4f46e5;
        }

        .series-message.current {
            outline: 2px solid #4f46e5;
        }

        .give-head {
            padding: calc(env(safe-area-inset-top) + 20px) 16px 16px;
            background: #fff;
        }

        .give-head h1 {
            font-size: 24px;
            margin-bottom: 4px;
        }

        .give-head p {
            color: #6b7280;
            font-size: 13px;
        }

        .give-body {
            padding: 16px;
        }

        .give-body h2 {
            font-size: 16px;
            margin: 20px 0 10px;
        }

        .give-freq {
            display: flex;
            padding: 4px;
            border-radius: 14px;
            background: #e8e8ec;
        }

        .give-freq button {
            flex: 1;
            padding: 9px 6px;
            border: 0;
            border-radius: 11px;
            background: transparent;
            color: #6b6b70;
            font: inherit;
            font-size: 14px;
            font-weight: 600;
        }

        .give-freq button.active {
            background: #fff;
            color: #000;
        }

        .give-amounts {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .give-amounts button {
            padding: 14px 0;
            border: 2px solid transparent;
            border-radius: 14px;
            background: #fff;
            color: #1c1c1e;
            font: inherit;
            font-size: 16px;
            font-weight: 700;
        }

        .give-amounts button.active {
            border-color: #4f46e5;
            color: #4f46e5;
            background: #eef2ff;
        }

        .give-custom {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            padding: 0 14px;
            border-radius: 14px;
            background: #fff;
            color: #6b7280;
            font-weight: 700;
        }

        .give-custom input {
            flex: 1;
            min-width: 0;
            padding: 14px 0;
            border: 0;
            background: transparent;
            color: #1c1c1e;
            font: inherit;
            font-size: 16px;
            outline: none;
        }

        .give-error {
            margin-top: 6px;
            color: #ef4444;
            font-size: 13px;
        }

        .give-funds {
            padding: 4px 16px;
            border-radius: 18px;
        }

        .give-funds label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 0;
            border-bottom: 1px solid #efeff2;
            font-size: 15px;
        }

        .give-funds label:last-child {
            border-bottom: 0;
        }

        .give-funds input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .give-funds i {
            width: 22px;
            height: 22px;
            border: 2px solid #cfcfd6;
            border-radius: 50%;
        }

        .give-funds input:checked~i {
            border: 7px solid #4f46e5;
        }

        .give-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            margin-top: 22px;
            padding: 16px;
            border: 0;
            border-radius: 16px;
            background: #4f46e5;
            color: #fff;
            font: inherit;
            font-size: 16px;
            font-weight: 700;
        }

        .give-note {
            margin-top: 10px;
            text-align: center;
            color: #9a9aa2;
            font-size: 12px;
        }

        .give-thanks {
            padding: 32px 20px;
            text-align: center;
        }

        .give-thanks h2 {
            font-size: 22px;
            margin: 16px 0 8px;
        }

        .give-thanks p {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.5;
        }

        .give-check {
            display: inline-grid;
            place-items: center;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #22c55e;
        }

        .sermon-empty {
            margin-top: 40px;
            text-align: center;
            color: #6b7280;
        }

        :root {
            --ink: #17231e;
            --muted: #65726a;
            --paper: #f7f7f2;
            --line: #dce3da;
            --pine: #254b3b;
            --moss: #557a5c;
            --clay: #c9634d;
            --gold: #e9b44c;
        }

        body {
            background: #dfe8e1;
            color: var(--ink);
            letter-spacing: 0;
        }

        button,
        input,
        select,
        textarea {
            font-family: inherit;
        }

        button,
        a {
            -webkit-tap-highlight-color: transparent;
        }

        button:focus-visible,
        a:focus-visible,
        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible {
            outline: 3px solid rgba(233, 180, 76, .7);
            outline-offset: 3px;
        }

        .screen {
            width: min(100%, 520px);
            margin: 0 auto;
            background: var(--paper);
            box-shadow: 0 16px 42px rgba(23, 35, 30, .16);
        }

        .page-title {
            padding: calc(env(safe-area-inset-top) + 26px) 20px 14px;
            color: var(--ink);
            font-size: 25px;
            line-height: 1.1;
            letter-spacing: 0;
        }

        .card {
            border: 1px solid var(--line);
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(23, 35, 30, .05);
        }

        .hero {
            height: 326px;
        }

        .avatar {
            width: 52px;
            height: 52px;
            border: 2px solid rgba(255, 255, 255, .5);
            background: #f7f7f2;
            color: var(--pine);
            font-size: 9px;
            letter-spacing: .4px;
        }

        .hero strong {
            font-size: 20px;
            letter-spacing: 0;
        }

        .bell {
            width: 46px;
            height: 46px;
            border: 1px solid rgba(255, 255, 255, .32);
            border-radius: 14px;
            backdrop-filter: blur(8px);
        }

        .bell i {
            background: var(--clay);
        }

        .content {
            margin-top: -104px;
            padding: 0 16px 28px;
        }

        .quick {
            padding: 14px 6px 16px;
            font-size: 11px;
            color: var(--ink);
        }

        .quick span.ico {
            width: 54px;
            height: 54px;
            margin-bottom: 7px;
            border-radius: 15px;
            box-shadow: inset 0 1px rgba(255, 255, 255, .25);
        }

        .live {
            margin-top: 18px;
            padding: 21px;
            border-radius: 16px;
            background: var(--pine);
            box-shadow: 0 10px 20px rgba(37, 75, 59, .18);
        }

        .live h2 {
            font-size: 20px;
            letter-spacing: 0;
        }

        .live .tag {
            color: #f8d78f;
        }

        .live .tag::before {
            background: var(--gold);
            box-shadow: 0 0 0 4px rgba(233, 180, 76, .15);
        }

        .watch {
            border-radius: 11px;
            color: var(--pine);
            padding: 12px 17px;
        }

        .section-head {
            margin: 30px 2px 13px;
        }

        .section-head h3 {
            font-size: 18px;
            letter-spacing: 0;
        }

        .section-head a {
            color: var(--pine);
            font-weight: 700;
        }

        .event {
            gap: 13px;
            padding: 12px;
            margin-bottom: 10px;
        }

        .date {
            width: 58px;
            height: 58px;
            border-radius: 12px;
            padding-top: 7px;
            background: #eef3e9;
            color: var(--pine);
        }

        .date b {
            font-size: 20px;
        }

        .event h4 {
            font-size: 15px;
        }

        .event p,
        .info p,
        .about {
            color: var(--muted);
        }

        .chips {
            gap: 8px;
            padding: 5px 20px 17px;
        }

        .chip {
            border-color: var(--line);
            border-radius: 10px;
            color: var(--muted);
        }

        .chip.active {
            background: var(--pine);
            border-color: var(--pine);
        }

        .tag-pill {
            border-radius: 8px;
            color: var(--pine);
            background: #eaf2e9;
        }

        .detail-hero {
            background: linear-gradient(to bottom, rgba(23, 35, 30, .14), rgba(23, 35, 30, .7)), #557a5c;
        }

        .back,
        .profile-page .p-back,
        .series-back {
            border-radius: 12px;
        }

        .detail-body {
            background: var(--paper);
            border-radius: 20px 20px 0 0;
        }

        .cta,
        .give-btn,
        .prayer-submit {
            border-radius: 12px;
            background: var(--pine);
            box-shadow: 0 8px 16px rgba(37, 75, 59, .16);
        }

        .cta.done {
            background: var(--moss);
        }

        .more-panel {
            width: min(100%, 520px);
            margin: auto;
            background: var(--paper);
            box-shadow: -10px 0 34px rgba(23, 35, 30, .17);
        }

        .navbar {
            width: min(100%, 520px);
            margin: auto;
            border: 1px solid var(--line);
            border-bottom: 0;
            border-radius: 18px 18px 0 0;
            background: rgba(250, 250, 246, .96);
            box-shadow: 0 -8px 24px rgba(23, 35, 30, .08);
        }

        .navbar a {
            color: #88938b;
            font-weight: 600;
        }

        .navbar a.active {
            color: var(--pine);
        }

        .navbar a.active svg {
            filter: drop-shadow(0 3px 3px rgba(37, 75, 59, .16));
        }

        .profile-head,
        .profile-page .p-head {
            background: var(--pine);
        }

        .profile-head .badge,
        .profile-page .p-avatar {
            color: var(--pine);
            background: var(--paper);
        }

        .menu-title {
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .6px;
            text-transform: uppercase;
        }

        .menu {
            padding: 0 14px;
        }

        .menu a {
            color: var(--ink);
            font-size: 15px;
        }

        .count {
            height: 28px;
            border-radius: 9px;
            background: var(--pine);
        }

        .signout {
            border-radius: 12px;
            border-color: #e5c3bd;
            color: #a74536;
        }

        .profile-page h2 {
            font-size: 18px;
        }

        .profile-page .p-switch.on,
        .profile-page .p-btn.primary {
            background: var(--pine);
        }

        .profile-page .p-btn {
            border-color: var(--pine);
            border-radius: 12px;
            color: var(--pine);
        }

        .profile-page .p-btn.primary {
            box-shadow: 0 8px 16px rgba(37, 75, 59, .16);
        }

        .prayer-head,
        .sermon-head,
        .give-head {
            background: var(--paper);
            border-bottom: 1px solid var(--line);
        }

        .prayer-back {
            background: #edf1e9;
        }

        .prayer-tabs,
        .give-freq {
            background: #e6ece4;
            border-radius: 12px;
        }

        .prayer-tabs button,
        .give-freq button {
            border-radius: 9px;
        }

        .prayer-tabs button.active,
        .give-freq button.active,
        .sermon-tabs button.active {
            color: var(--pine);
            box-shadow: 0 3px 9px rgba(23, 35, 30, .08);
        }

        .prayer-card input,
        .prayer-card select,
        .prayer-card textarea,
        .sermon-search {
            background: #edf1e9;
            border-radius: 11px;
        }

        .prayer-notice,
        .series-pill.blue,
        .prayer-pray.done {
            background: #eaf2e9;
            color: var(--pine);
        }

        .prayer-guidelines li::before {
            background: var(--clay);
        }

        .prayer-avatar {
            background: #eaf2e9;
            color: var(--pine);
        }

        .prayer-pray {
            border-color: var(--pine);
            color: var(--pine);
        }

        .sermon-sticky {
            background: var(--paper);
        }

        .sermon-tabs {
            gap: 4px;
        }

        .sermon-tabs button {
            border-radius: 9px;
        }

        .sermon-thumb,
        .series-thumb {
            border-radius: 11px;
        }

        .series-pill.purple {
            background: #f9eadf;
            color: #a45239;
        }

        .series-num,
        .player-controls .main {
            background: var(--pine);
        }

        .series-message.current {
            outline-color: var(--moss);
        }

        .player-bar {
            background: linear-gradient(to right, var(--pine) var(--p, 0%), #d8dfd6 var(--p, 0%));
        }

        .player-bar::-webkit-slider-thumb {
            background: var(--pine);
        }

        .give-amounts button {
            border-color: var(--line);
            border-radius: 11px;
        }

        .give-amounts button.active {
            border-color: var(--pine);
            color: var(--pine);
            background: #eaf2e9;
        }

        .give-custom,
        .give-funds {
            border: 1px solid var(--line);
        }

        .give-funds input:checked~i {
            border-color: var(--pine);
        }

        .give-check {
            background: var(--moss);
        }

        @media (min-width: 521px) {
            .screen {
                min-height: 100vh;
            }
        }
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
