<?php

/**
 * Native UI — Theme Tokens
 *
 * Published via `php artisan vendor:publish --tag=native-ui-config`.
 * Edit to customize your app's visual identity in one place.
 *
 * For dynamic per-tenant theming, use Native\Mobile\UI\Theme::merge([...])
 * from a service provider. Runtime merges deep-merge on top of these values.
 *
 * Decision log: /docs/NATIVE-UI-REWRITE-PLAN.md (D — theme layer)
 */

return [

    /*
    |---------------------------------------------------------------------------
    | Theme
    |---------------------------------------------------------------------------
    |
    | Color tokens (open-ended map), 4 radii, 4 font sizes, font family.
    |
    | "on-X" means "color of content placed ON a surface of color X"
    |   — i.e., text/icons on that background.
    |
    | The token map is OPEN-ENDED: add any key your design needs (e.g. a
    | `warning` pair) to both blocks and `bg-theme-warning` /
    | `text-theme-on-warning` / `border-theme-warning` resolve immediately.
    | Theme classes also accept opacity modifiers — `bg-theme-primary/15`
    | is the tonal-fill idiom (the alpha applies to the dark companion
    | too). In PHP (layout chrome builders, dynamic styling) read tokens
    | with the appearance-aware `theme()` helper: `theme('primary')`.
    |
    | Color tokens accept:
    |   - CSS hex: '#B91C1C', '#F00', or with alpha '#8B5CF680' (#RRGGBBAA)
    |   - Tailwind palette names: 'red-300', 'orange-800'
    |   - Opacity modifiers on either: 'red-300/20', '#8B5CF6/50'
    |
    | Dark mode is auto-derived from `light` when `dark` is not set. To opt
    | into explicit dark tokens, fill out the `dark` block.
    |
    | The default pairs meet WCAG AA (4.5:1) — if you customize, keep each
    | `on-*` color at 4.5:1 contrast against its background token.
    |
    */

    'theme' => [

        'light' => [
            // City Life palette (matches the web screens).
            'primary' => '#B7441A',
            'on-primary' => '#FFFFFF',

            'secondary' => '#292929',
            'on-secondary' => '#FFFFFF',

            'surface' => '#FFFFFF',
            'on-surface' => '#191919',
            'background' => '#F2F2F2',
            'on-background' => '#191919',

            'surface-variant' => '#F4F4F5',
            'on-surface-variant' => '#6B6B6B',

            'input-fill' => '#F4F4F5',
            'on-input' => '#191919',

            'outline' => '#D4D4D8',
            'outline-variant' => '#E5E5E5',

            'destructive' => '#B91C1C',
            'on-destructive' => '#FFFFFF',

            'success' => '#15803D',
            'on-success' => '#FFFFFF',

            'accent' => '#E85D26',
            'on-accent' => '#FFFFFF',
        ],

        'dark' => [
            'primary' => '#E0693A',
            'on-primary' => '#FFFFFF',

            'secondary' => '#A1A1AA',
            'on-secondary' => '#0B0B0C',

            'surface' => '#1C1C1E',
            'on-surface' => '#F2F2F7',
            'background' => '#0B0B0C',
            'on-background' => '#F2F2F7',

            'surface-variant' => '#2C2C2E',
            'on-surface-variant' => '#A1A1AA',

            'input-fill' => '#2C2C2E',
            'on-input' => '#F2F2F7',

            'outline' => '#48484A',
            'outline-variant' => '#38383A',

            'destructive' => '#F87171',
            'on-destructive' => '#0B0B0C',

            'success' => '#4ADE80',
            'on-success' => '#052E16',

            'accent' => '#F4A27B',
            'on-accent' => '#0B0B0C',
        ],

        // Corner radii (points / dp).
        'radius-sm' => 4,
        'radius-md' => 8,
        'radius-lg' => 16,
        'radius-full' => 9999,

        // Font size scale (points / sp).
        'font-sm' => 14,
        'font-md' => 16,
        'font-lg' => 20,
        'font-xl' => 24,

    ],

    /*
    |---------------------------------------------------------------------------
    | Fonts
    |---------------------------------------------------------------------------
    |
    | Semantic names for bundled fonts (resources/fonts/ file tokens, minus
    | the extension). Use an alias anywhere a font token works — the `font`
    | attribute (`font="accent"`), chrome ->font() builders, or the layout
    | $font property. The `default` alias is the app-wide default font:
    | 'System' resolves to the platform face (San Francisco on iOS, Roboto
    | on Android); set a bundled token to apply it everywhere. Download one
    | with `php artisan native:font Inter --default`. Per-element `font`
    | attributes and font-serif / font-mono classes still win over the default.
    |
    | Weights: a family's `-Regular` token (or its bare family name, e.g.
    | 'Inter') follows the font-medium / font-semibold / font-bold classes by
    | drawing the sibling file bundled for that weight — Inter-Medium,
    | Inter-SemiBold, Inter-Bold — the names `native:font` downloads. A missing
    | weight uses the nearest bundled one; a variable font moves along its
    | weight axis instead. Any other face ('Inter-Bold') is drawn as named.
    |
    |   'fonts' => [
    |       'default' => 'Inter-Regular',
    |       'accent'  => 'DynaPuff-Regular',
    |   ],
    |
    */

    'fonts' => [
        'default' => 'System',
    ],

];
