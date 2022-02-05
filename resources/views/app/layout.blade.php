<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    <script src="{{ mix('/js/manifest.js') }}" defer></script>
    <script src="{{ mix('/js/vendor.js') }}" defer></script>
    <script src="{{ mix('/js/app.js') }}" defer></script>
    <meta name="robots" content="{{ $pageRobots }}" />
    @if(!empty($metaDescription))
        <meta name="description" content="{{ $metaDescription }}">
    @endif
    <meta property="og:image" content="{{ asset('assets/og/og-image.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('assets/favicon/safari-pinned-tab.svg') }}" color="#6441a5">
    <link rel="shortcut icon" href="{{ asset('assets/favicon/favicon.ico') }}">
    <meta name="msapplication-TileColor" content="#6441a5">
    <meta name="msapplication-config" content="{{ asset('assets/favicon/browserconfig.xml') }}">
    <meta name="theme-color" content="#6441a5">
    @inertiaHead
</head>
<body>
@inertia
</body>
</html>
