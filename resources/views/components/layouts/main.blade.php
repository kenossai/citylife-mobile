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
            background: #f2f2f2;
            color: #1c1c1e;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        input,
        select,
        textarea,
        [contenteditable="true"] {
            -webkit-user-select: text;
            user-select: text;
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
            height: 278px;
            padding: calc(env(safe-area-inset-top) + 20px) 20px 0;
            color: #fff;
            background: linear-gradient(to bottom, rgba(0, 0, 0, .18), rgba(0, 0, 0, .78)), url('https://images.unsplash.com/photo-1687834303092-585075b56885?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjaHVyY2glMjB3b3JzaGlwJTIwc2VydmljZXxlbnwxfHx8fDE3NTk0MDk5NDF8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral') center / cover no-repeat, #171717;
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
            --ink: #191919;
            --muted: #6b6b6b;
            --paper: #ffffff;
            --line: #e5e5e5;
            --pine: #b7441a;
            --moss: #292929;
            --clay: #e85d26;
            --gold: #f4a27b;
        }

        body {
            background: #f2f2f2;
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
            outline: 3px solid rgba(232, 93, 38, .7);
            outline-offset: 3px;
        }

        .screen {
            width: min(100%, 520px);
            margin: 0 auto;
            background: var(--paper);
            box-shadow: 0 16px 42px rgba(0, 0, 0, .16);
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
            box-shadow: 0 6px 18px rgba(0, 0, 0, .05);
        }

        .hero {
            height: 278px;
        }

        .home-content {
            margin-top: -40px;
        }

        .avatar {
            width: 52px;
            height: 52px;
            border: 2px solid rgba(255, 255, 255, .5);
            background: #fff;
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

        .live {
            margin-top: 18px;
            padding: 21px;
            border-radius: 16px;
            background: var(--ink);
            box-shadow: 0 10px 20px rgba(0, 0, 0, .18);
        }

        .live h2 {
            font-size: 20px;
            letter-spacing: 0;
        }

        .live .tag {
            color: #ffb18f;
        }

        .live .tag::before {
            background: var(--clay);
            box-shadow: 0 0 0 4px rgba(232, 93, 38, .15);
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
            background: #fff0e9;
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
            background: linear-gradient(to bottom, rgba(0, 0, 0, .14), rgba(0, 0, 0, .7)), #292929;
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
            box-shadow: 0 8px 16px rgba(0, 0, 0, .16);
        }

        .cta.done {
            background: var(--moss);
        }

        .more-panel {
            width: min(100%, 520px);
            margin: auto;
            background: var(--paper);
            box-shadow: -10px 0 34px rgba(0, 0, 0, .17);
        }

        .navbar {
            width: min(100%, 520px);
            margin: auto;
            border: 1px solid var(--line);
            border-bottom: 0;
            border-radius: 18px 18px 0 0;
            background: rgba(250, 250, 246, .96);
            box-shadow: 0 -8px 24px rgba(0, 0, 0, .08);
        }

        .navbar a {
            color: #88938b;
            font-weight: 600;
        }

        .navbar a.active {
            color: var(--pine);
        }

        .navbar a.active svg {
            filter: drop-shadow(0 3px 3px rgba(0, 0, 0, .16));
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
            box-shadow: 0 8px 16px rgba(0, 0, 0, .16);
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
            box-shadow: 0 3px 9px rgba(0, 0, 0, .08);
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

        .feature-page {
            padding: calc(env(safe-area-inset-top) + 16px) 16px 32px;
            color: var(--ink);
        }

        .feature-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 22px;
        }

        .feature-back {
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            flex: none;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: var(--paper);
            color: var(--ink);
        }

        .feature-back svg,
        .feature-primary svg,
        .feature-link-row > svg,
        .feature-contact-link > svg,
        .faq-item button svg {
            width: 20px;
            height: 20px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .feature-kicker,
        .feature-eyebrow {
            display: block;
            color: var(--muted);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.15px;
        }

        .feature-header h1 {
            margin-top: 2px;
            font-size: 24px;
            line-height: 1.15;
            letter-spacing: -.5px;
        }

        .feature-hero {
            overflow: hidden;
            padding: 22px;
            border-radius: 22px;
            background: var(--clay);
            color: #fff;
        }

        .feature-hero .feature-eyebrow {
            color: rgba(255, 255, 255, .76);
        }

        .feature-hero h2,
        .invite-card h2,
        .feature-contact-card h2 {
            margin-top: 8px;
            font-size: 24px;
            line-height: 1.16;
            letter-spacing: -.5px;
        }

        .feature-hero > p,
        .invite-card > p,
        .feature-contact-card > p {
            margin-top: 8px;
            color: inherit;
            font-size: 14px;
            line-height: 1.5;
            opacity: .86;
        }

        .feature-progress {
            height: 7px;
            overflow: hidden;
            margin-top: 22px;
            border-radius: 99px;
            background: rgba(255, 255, 255, .3);
        }

        .feature-progress span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: #fff;
            transition: width .2s ease;
        }

        .feature-progress-meta {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-top: 9px;
            color: rgba(255, 255, 255, .86);
            font-size: 12px;
            font-weight: 600;
        }

        .feature-section {
            margin-top: 26px;
        }

        .feature-section-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }

        .feature-section-heading h2,
        .feature-intro h2 {
            margin-top: 4px;
            font-size: 20px;
            line-height: 1.2;
            letter-spacing: -.3px;
        }

        .feature-count {
            color: var(--muted);
            font-size: 12px;
            white-space: nowrap;
        }

        .feature-list,
        .feature-contact-card,
        .invite-card,
        .feature-link-row {
            border: 1px solid var(--line);
            border-radius: 18px;
            background: var(--paper);
            box-shadow: 0 5px 18px rgba(0, 0, 0, .045);
        }

        .feature-list {
            overflow: hidden;
        }

        .feature-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 13px;
        }

        .feature-row + .feature-row {
            border-top: 1px solid var(--line);
        }

        .feature-icon {
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            flex: none;
            border-radius: 14px;
            background: #fff0e9;
            color: var(--clay);
        }

        .feature-icon svg,
        .feature-icon-button svg,
        .invite-art svg {
            width: 22px;
            height: 22px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .feature-icon.is-complete,
        .reading-day.is-complete {
            background: #e9f5ee;
            color: #21804e;
        }

        .feature-row-copy {
            min-width: 0;
            flex: 1;
        }

        .feature-row-copy h3,
        .notification-title h2,
        .setting-row h2 {
            font-size: 14px;
            font-weight: 750;
            line-height: 1.3;
        }

        .feature-row-copy p,
        .setting-row p {
            margin-top: 4px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.4;
        }

        .feature-row-copy small {
            display: block;
            margin-top: 6px;
            color: var(--muted);
            font-size: 11px;
        }

        .feature-check {
            flex: none;
            padding: 8px 10px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #fff;
            color: var(--ink);
            font: inherit;
            font-size: 11px;
            font-weight: 700;
        }

        .feature-check:disabled {
            opacity: .45;
        }

        .feature-check.is-selected {
            border-color: #f2c6b4;
            background: #fff0e9;
            color: #a83e18;
        }

        .feature-primary,
        .feature-light-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            min-height: 50px;
            margin-top: 16px;
            padding: 12px 16px;
            border: 0;
            border-radius: 14px;
            background: var(--clay);
            color: #fff;
            font: inherit;
            font-size: 14px;
            font-weight: 750;
            text-align: center;
            cursor: pointer;
        }

        .feature-hero .feature-light-button {
            width: fit-content;
            min-height: 42px;
            margin-top: 17px;
            background: #fff;
            color: var(--ink);
        }

        .feature-footnote {
            margin-top: 11px;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.45;
            text-align: center;
        }

        .feature-intro {
            padding: 4px 2px 16px;
        }

        .feature-intro > p {
            margin-top: 6px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.45;
        }

        .feature-text-button {
            display: block;
            margin: -4px 0 12px auto;
            padding: 8px 0 8px 12px;
            border: 0;
            background: transparent;
            color: var(--pine);
            font: inherit;
            font-size: 12px;
            font-weight: 700;
        }

        .notification-row {
            align-items: flex-start;
        }

        .notification-title {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .notification-title h2 {
            font-size: 13px;
        }

        .notification-row .feature-row-copy > p {
            font-size: 12px;
        }

        .notification-dot {
            width: 7px;
            height: 7px;
            flex: none;
            border-radius: 50%;
            background: var(--clay);
        }

        .feature-icon-button {
            display: grid;
            place-items: center;
            width: 32px;
            height: 32px;
            flex: none;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #fff;
            color: var(--muted);
        }

        .feature-icon-button svg {
            width: 17px;
            height: 17px;
        }

        .reading-row {
            gap: 10px;
        }

        .reading-day {
            display: grid;
            place-items: center;
            width: 36px;
            height: 36px;
            flex: none;
            border-radius: 12px;
            background: #f2f2f2;
            color: var(--muted);
            font-size: 12px;
            font-weight: 800;
        }

        .reading-row .feature-row-copy > small {
            margin: 0 0 3px;
            color: var(--clay);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .7px;
        }

        .reading-row .feature-check {
            padding-inline: 9px;
        }

        .invite-card {
            padding: 26px 21px;
            text-align: center;
        }

        .invite-card .feature-kicker,
        .feature-contact-card .feature-kicker {
            margin-top: 15px;
        }

        .invite-card > p,
        .feature-contact-card > p {
            color: var(--muted);
            opacity: 1;
        }

        .invite-art {
            display: grid;
            place-items: center;
            width: 68px;
            height: 68px;
            margin: 0 auto;
            border-radius: 22px;
            background: #fff0e9;
            color: var(--clay);
        }

        .invite-art svg {
            width: 31px;
            height: 31px;
        }

        .invite-card .feature-primary {
            margin-top: 22px;
        }

        .feature-feedback,
        .feature-error {
            margin-top: 12px;
            color: #21804e;
            font-size: 12px;
        }

        .feature-error {
            color: #b42318;
        }

        .location-hero {
            background: var(--ink);
        }

        .location-hero h2 {
            font-size: 27px;
        }

        .feature-contact-card {
            padding: 20px;
        }

        .feature-contact-card .feature-icon {
            margin-bottom: 13px;
        }

        .feature-contact-card h2 {
            font-size: 20px;
        }

        .feature-contact-card .feature-primary {
            margin-top: 18px;
        }

        .feature-contact-card .feature-primary svg {
            width: 17px;
            height: 17px;
        }

        .service-card {
            margin-top: 12px;
            background: #fff8f4;
        }

        .service-card .feature-kicker {
            margin-top: 0;
            color: var(--clay);
        }

        .service-card > p {
            color: var(--ink);
            font-size: 15px;
            font-weight: 700;
        }

        .service-card > small {
            display: block;
            margin-top: 6px;
            color: var(--muted);
            font-size: 12px;
        }

        .service-times {
            margin-top: 12px;
        }

        .service-time-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            padding: 11px 0;
            border-top: 1px solid var(--line);
            font-size: 12px;
        }

        .service-time-row b {
            color: var(--muted);
            font-weight: 600;
        }

        .service-time-row span {
            display: grid;
            gap: 3px;
            text-align: right;
        }

        .service-time-row strong {
            color: var(--ink);
            font-size: 12px;
        }

        .contact-page-card {
            padding: 28px 22px;
            text-align: center;
        }

        .contact-page-card .invite-art {
            margin-bottom: 18px;
        }

        .contact-page-card .feature-kicker {
            margin-top: 0;
        }

        .contact-page-card .feature-secondary {
            display: inline-block;
            margin-top: 16px;
            color: var(--pine);
            font-size: 13px;
            font-weight: 700;
        }

        .settings-list {
            padding-inline: 15px;
        }

        .setting-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 17px 0;
        }

        .setting-row + .setting-row {
            border-top: 1px solid var(--line);
        }

        .setting-row p {
            max-width: 235px;
        }

        .feature-switch {
            position: relative;
            width: 48px;
            height: 28px;
            flex: none;
            border: 0;
            border-radius: 99px;
            background: #d5d5d5;
            transition: background .18s ease;
        }

        .feature-switch span {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .18);
            transition: transform .18s ease;
        }

        .feature-switch.is-on {
            background: var(--clay);
        }

        .feature-switch.is-on span {
            transform: translateX(20px);
        }

        .feature-link-row,
        .feature-contact-link {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 18px;
            padding: 15px;
        }

        .feature-link-row > span,
        .feature-contact-link > span:not(.feature-icon) {
            display: grid;
            flex: 1;
            gap: 4px;
        }

        .feature-link-row b,
        .feature-contact-link b {
            font-size: 13px;
        }

        .feature-link-row small,
        .feature-contact-link small {
            color: var(--muted);
            font-size: 11px;
        }

        .faq-section {
            margin-top: 5px;
        }

        .faq-item + .faq-item {
            border-top: 1px solid var(--line);
        }

        .faq-item > button {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            width: 100%;
            padding: 16px 14px;
            border: 0;
            background: transparent;
            color: var(--ink);
            font: inherit;
            font-size: 13px;
            font-weight: 700;
            text-align: left;
        }

        .faq-item > button[aria-expanded="true"] svg {
            transform: rotate(180deg);
        }

        .faq-item > p {
            padding: 0 14px 16px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.55;
        }

        .faq-item > p a {
            color: var(--pine);
            font-weight: 700;
            white-space: nowrap;
        }

        .feature-contact-link {
            border: 1px solid var(--line);
            border-radius: 16px;
            background: var(--paper);
            box-shadow: 0 5px 18px rgba(0, 0, 0, .045);
        }

        .feature-contact-link .feature-icon {
            width: 38px;
            height: 38px;
        }

        .feature-contact-link .feature-chevron {
            width: 18px;
            height: 18px;
            flex: none;
            fill: none;
            stroke: var(--muted);
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .feature-page button:focus-visible,
        .feature-page a:focus-visible {
            outline-color: rgba(232, 93, 38, .7);
        }

        .navbar a,
        .feature-page a,
        .feature-page button,
        .card a,
        button {
            -webkit-tap-highlight-color: transparent;
            transition: transform .12s ease, opacity .12s ease;
        }

        .navbar a:active,
        .card a:active,
        .feature-page a:active,
        .feature-page button:active,
        button:active {
            transform: scale(.96);
            opacity: .75;
        }

        @media (prefers-reduced-motion: reduce) {
            .navbar a,
            .feature-page a,
            .feature-page button,
            .card a,
            button {
                transition: none;
            }
        }

        .home-verse {
            margin-top: 16px;
            padding: 20px;
            border-radius: 18px;
            color: #fff;
            background: linear-gradient(135deg, var(--clay), var(--pine));
            box-shadow: 0 10px 22px rgba(183, 68, 26, .28);
        }

        .home-verse span {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            opacity: .85;
        }

        .home-verse blockquote {
            margin: 10px 0 8px;
            font-size: 17px;
            line-height: 1.4;
            font-weight: 600;
        }

        .home-verse cite {
            font-size: 13px;
            font-style: normal;
            opacity: .9;
        }

        .home-shortcuts {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 18px;
        }

        .home-shortcuts a {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 14px 4px 12px;
            border-radius: 16px;
            background: #fff;
            border: 1px solid var(--line);
            box-shadow: 0 6px 16px rgba(0, 0, 0, .05);
            font-size: 12px;
            font-weight: 600;
        }

        .home-shortcuts i {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: #fff0e9;
            color: var(--pine);
        }

        .home-series {
            display: flex;
            gap: 12px;
            margin: 0 -16px;
            padding: 2px 16px 8px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
        }

        .home-series::-webkit-scrollbar {
            display: none;
        }

        .home-series a {
            flex: none;
            width: 220px;
            min-height: 150px;
            padding: 16px;
            border-radius: 18px;
            color: #fff;
            scroll-snap-align: start;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            background: linear-gradient(to top, rgba(0, 0, 0, .55), rgba(0, 0, 0, .05)), var(--series);
            box-shadow: 0 8px 18px rgba(0, 0, 0, .15);
        }

        .home-series small {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .6px;
            text-transform: uppercase;
            opacity: .85;
        }

        .home-series strong {
            font-size: 18px;
            margin: 4px 0;
        }

        .home-series p {
            font-size: 12px;
            line-height: 1.35;
            opacity: .9;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .live .tag::before {
            animation: citylife-pulse 1.6s ease-out infinite;
        }

        @keyframes citylife-pulse {
            0% { box-shadow: 0 0 0 0 rgba(232, 93, 38, .5); }
            100% { box-shadow: 0 0 0 10px rgba(232, 93, 38, 0); }
        }

        @media (prefers-reduced-motion: reduce) {
            .live .tag::before {
                animation: none;
            }
        }

        .cta.pay-alt {
            margin-top: 10px;
            background: #003087;
        }

        .player-hero[data-media] {
            height: 120px;
        }

        .player-media {
            margin: 4px 0 16px;
            border-radius: 16px;
            overflow: hidden;
            background: #000;
        }

        .player-media iframe,
        .player-media video {
            display: block;
            width: 100%;
            aspect-ratio: 16 / 9;
            border: 0;
        }

        .player-media audio {
            display: block;
            width: 100%;
        }

        .player-link {
            display: block;
            padding: 16px;
            text-align: center;
            color: #fff;
            font-weight: 600;
        }

        .login-head {
            padding: calc(env(safe-area-inset-top) + 20px) 20px 28px;
            color: #fff;
            background: linear-gradient(135deg, var(--clay), var(--pine));
        }

        .login-head h1 {
            margin-top: 14px;
            font-size: 26px;
        }

        .login-head p {
            margin-top: 4px;
            opacity: .9;
            font-size: 14px;
        }

        .login-card {
            margin: -16px 16px 24px;
            padding: 20px;
            position: relative;
            background: #fff;
        }

        .login-card label {
            display: block;
            margin: 14px 0 8px;
            font-size: 14px;
            font-weight: 600;
        }

        .login-card input {
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

        .login-card .prayer-submit {
            margin-top: 20px;
        }

        .login-link {
            display: block;
            margin: 14px auto 0;
            border: 0;
            background: none;
            color: var(--pine);
            font: inherit;
            font-size: 14px;
            font-weight: 600;
        }

        .login-card small {
            display: block;
            margin-top: 14px;
            color: #6b7280;
            font-size: 12px;
            line-height: 1.4;
        }

        .prayer-check {
            display: flex !important;
            align-items: flex-start;
            gap: 10px;
            margin-top: 16px !important;
            font-weight: 500 !important;
            font-size: 14px !important;
        }

        .prayer-check input {
            width: 20px !important;
            height: 20px;
            flex: none;
            margin-top: 1px;
        }

        .prayer-notice-banner {
            margin-bottom: 14px;
            padding: 12px 14px;
            border-radius: 12px;
            background: #fff0e9;
            color: var(--pine);
            font-size: 14px;
            font-weight: 600;
        }

        .prayer-status {
            display: inline-block;
            margin: 10px 0 0;
            padding: 3px 10px;
            border-radius: 999px;
            background: #f4f4f5;
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
        }

        .course-tabs {
            margin: 4px 16px 0;
        }

        .course-row {
            align-items: center;
            text-decoration: none;
        }

        .course-row .course-chev {
            width: 20px;
            height: 20px;
            flex: none;
            fill: none;
            stroke: #9ca3af;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .course-pill {
            display: inline-block;
            margin-bottom: 6px;
            padding: 3px 10px;
            border-radius: 999px;
            background: #fff0e9;
            color: var(--pine);
            font-size: 12px;
            font-weight: 600;
        }

        .course-group {
            margin: 18px 4px 6px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .6px;
            text-transform: uppercase;
            color: #6b7280;
        }

        .lesson-row.is-locked {
            opacity: .55;
        }

        .course-callout {
            margin: 14px 0;
            padding: 14px 16px;
            border-radius: 14px;
            background: #fff0e9;
            color: var(--pine);
            font-size: 14px;
            font-weight: 600;
            line-height: 1.4;
        }

        .course-banner {
            margin: 0 16px 12px;
        }

        .course-enrol,
        .course-review,
        .course-quiz,
        .course-notes {
            margin: 16px 0;
            padding: 18px;
        }

        .course-enrol label,
        .course-review label {
            display: block;
            margin: 14px 0 8px;
            font-size: 14px;
            font-weight: 600;
        }

        .course-quiz fieldset {
            border: 0;
            margin: 16px 0;
        }

        .course-quiz legend {
            margin-bottom: 8px;
            font-weight: 600;
            line-height: 1.4;
        }

        .course-quiz .prayer-check {
            margin-top: 10px !important;
        }

        .course-stars {
            display: flex;
            gap: 6px;
            margin: 12px 0;
        }

        .course-stars button {
            border: 0;
            background: none;
            font-size: 34px;
            line-height: 1;
            color: #d4d4d8;
        }

        .course-stars button.on {
            color: #f59e0b;
        }

        .course-content {
            padding: 18px;
            font-size: 16px;
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .course-content h1,
        .course-content h2,
        .course-content h3 {
            margin: 18px 0 8px;
            line-height: 1.25;
        }

        .course-content p,
        .course-content ul,
        .course-content ol,
        .course-content blockquote {
            margin: 0 0 12px;
        }

        .course-content ul,
        .course-content ol {
            padding-left: 22px;
        }

        .course-content blockquote {
            padding-left: 14px;
            border-left: 3px solid var(--clay);
            color: #52525b;
        }

        .course-content img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
        }

        .course-content a {
            color: var(--pine);
            text-decoration: underline;
        }

        .course-action {
            width: 100%;
            margin: 14px 0 0;
        }

        .course-nav {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-top: 18px;
            font-size: 14px;
            font-weight: 600;
            color: var(--pine);
        }

        .course-nav span {
            color: #6b7280;
            font-weight: 500;
        }

        .sermon-views {
            display: block;
            margin-top: 8px;
            color: #6b7280;
            font-size: 12px;
        }

        .sermon-scripture {
            margin: 4px 0 8px;
            color: var(--pine);
            font-size: 14px;
            font-weight: 600;
        }

        .sermon-description {
            margin: 4px 0 12px;
            line-height: 1.55;
            font-size: 15px;
        }

        .group-card .course-callout {
            margin-top: 12px;
        }

        .group-form {
            display: grid;
            gap: 10px;
            margin-top: 12px;
        }

        .group-form input,
        .group-form textarea,
        .contact-form select {
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

        .contact-form {
            margin: 12px 16px 24px;
            padding: 18px;
        }

        .contact-form label {
            display: block;
            margin: 14px 0 8px;
            font-size: 14px;
            font-weight: 600;
        }

        .contact-form select {
            -webkit-appearance: none;
            appearance: none;
        }

        .give-methods {
            display: grid;
            gap: 10px;
        }

        .give-method {
            display: block;
            width: 100%;
            padding: 16px;
            border: 1px solid var(--line);
            border-radius: 16px;
            background: #fff;
            color: var(--ink);
            text-align: left;
            font: inherit;
        }

        .give-method b {
            display: block;
            font-size: 16px;
        }

        .give-method span {
            display: block;
            margin-top: 4px;
            font-size: 13px;
            opacity: .75;
        }

        .give-method.primary {
            border: 0;
            color: #fff;
            background: linear-gradient(135deg, var(--clay), var(--pine));
        }

        .give-bank {
            display: grid;
            gap: 0;
            padding: 4px 16px;
        }

        .give-bank div {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 12px 0;
            border-bottom: 1px solid var(--line);
            font-size: 14px;
        }

        .give-bank div:last-child {
            border-bottom: 0;
        }

        .give-bank span {
            color: #6b7280;
        }

        .give-bank b {
            text-align: right;
            user-select: text;
        }

        .give-giftaid {
            padding: 18px;
        }

        .give-giftaid p {
            font-size: 14px;
            line-height: 1.5;
        }

        .give-giftaid form {
            display: grid;
            gap: 10px;
            margin-top: 14px;
        }

        .give-giftaid input[type="text"],
        .give-giftaid input[type="tel"],
        .give-giftaid input[type="email"],
        .give-giftaid input[type="date"] {
            display: block;
            width: 100%;
            padding: 12px 14px;
            border: 0;
            border-radius: 12px;
            background: #f4f4f5;
            font: inherit;
            font-size: 16px;
            outline: none;
        }

        .give-giftaid small {
            color: #6b7280;
            font-size: 12px;
        }

        .give-date span {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
        }

        @media (min-width: 521px) {
            .screen {
                min-height: 100vh;
            }
        }

        body.keyboard-visible .screen {
            padding-bottom: calc(16px + env(safe-area-inset-bottom));
        }

        body.keyboard-visible .navbar {
            display: none;
        }
    </style>
</head>

<body x-data="{ more: false }" @keydown.escape.window="more = false">
    <div class="screen">{{ $slot }}</div>

    <aside class="more-panel" :class="{ open: more }" :aria-hidden="!more" x-cloak>
        <livewire:more />
    </aside>

    <x-navbar />

    <script>
        (() => {
            if (window.cityLifeNavigationInitialized) {
                return;
            }

            window.cityLifeNavigationInitialized = true;

            const currentPath = () => `${window.location.pathname}${window.location.search}`;
            const storageKey = 'cityLifeNavigationStack';
            let reopenMore = false;
            let direction = 'forward';

            const readStack = () => {
                try {
                    const stack = JSON.parse(window.sessionStorage.getItem(storageKey) ?? '[]');

                    return Array.isArray(stack) ? stack : [];
                } catch (error) {
                    return [];
                }
            };

            const writeStack = (stack) => {
                try {
                    window.sessionStorage.setItem(storageKey, JSON.stringify(stack.slice(-50)));
                } catch (error) {
                    //
                }
            };

            const recordVisit = () => {
                const path = currentPath();
                const stack = readStack();
                const top = stack[stack.length - 1];

                if (top?.path === path) {
                    return;
                }

                const earlier = stack.findLastIndex((entry) => entry.path === path);

                if (earlier !== -1) {
                    stack.length = earlier + 1;
                } else {
                    stack.push({ path, more: false });
                }

                writeStack(stack);
            };

            const animateScreen = () => {
                const screen = document.querySelector('.screen');

                if (!screen || !screen.animate || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    return;
                }

                const frames = {
                    forward: [{ opacity: 0, transform: 'translateX(36px)' }, { opacity: 1, transform: 'translateX(0)' }],
                    back: [{ opacity: 0, transform: 'translateX(-36px)' }, { opacity: 1, transform: 'translateX(0)' }],
                    tab: [{ opacity: 0 }, { opacity: 1 }],
                }[direction];

                screen.animate(frames, { duration: direction === 'tab' ? 180 : 300, easing: 'cubic-bezier(.32, .72, 0, 1)' });
            };

            document.addEventListener('livewire:navigate', (event) => {
                if (event.detail.history) {
                    direction = 'back';
                }
            });

            document.addEventListener('livewire:navigated', () => {
                recordVisit();
                animateScreen();
                direction = 'forward';

                if (reopenMore) {
                    reopenMore = false;

                    const appData = window.Alpine?.$data(document.body);

                    if (appData) {
                        appData.more = true;
                    }
                }
            });

            document.addEventListener('click', (event) => {
                if (!(event.target instanceof Element)) {
                    return;
                }

                direction = event.target.closest('.navbar a') ? 'tab' : 'forward';

                const moreLink = event.target.closest('a[data-app-more-navigation]');

                if (moreLink) {
                    const stack = readStack();

                    if (stack.length) {
                        stack[stack.length - 1].more = true;
                        writeStack(stack);
                    }

                    return;
                }

                const backLink = event.target.closest('a[data-app-back]');

                if (!backLink) {
                    return;
                }

                const stack = readStack();
                const path = currentPath();

                if (stack[stack.length - 1]?.path === path) {
                    stack.pop();
                }

                const previous = stack[stack.length - 1];

                if (!previous || previous.path === path) {
                    return;
                }

                event.preventDefault();
                event.stopImmediatePropagation();

                direction = 'back';
                reopenMore = previous.more === true;
                previous.more = false;
                writeStack(stack);

                window.Livewire.navigate(previous.path);
            }, true);

            recordVisit();
        })();
    </script>
</body>

</html>
