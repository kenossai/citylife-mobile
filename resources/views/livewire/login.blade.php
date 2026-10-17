<div class="login-page">
    <header class="login-head">
        <a href="{{ route('home') }}" wire:navigate data-app-back class="prayer-back" aria-label="Back">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        </a>
        <h1>Sign in</h1>
        <p>Use your City Life member account</p>
    </header>

    <form class="card login-card" wire:submit="signIn">
        @if ($error)<p class="prayer-error" role="alert">{{ $error }}</p>@endif

        <label for="login-email">Email</label>
        <input id="login-email" type="email" wire:model="email" autocomplete="email" inputmode="email" autocapitalize="off" placeholder="you@example.com">
        @error('email')<p class="prayer-error" role="alert">{{ $message }}</p>@enderror

        <label for="login-password">Password</label>
        <input id="login-password" type="password" wire:model="password" autocomplete="current-password" placeholder="Your password">
        @error('password')<p class="prayer-error" role="alert">{{ $message }}</p>@enderror

        <button type="submit" class="prayer-submit" wire:loading.attr="disabled">
            <span wire:loading.remove>Sign in</span>
            <span wire:loading>Signing in…</span>
        </button>

        <button type="button" class="login-link" wire:click="openForgotPassword">Forgot your password?</button>

        <small>Use the same email and password as on the City Life website. Your account is created when you enrol on a course, and the email we send you has a link to set your password. You can still browse and send prayer requests without signing in.</small>
    </form>
</div>
