<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CityLife</title>
    <style>html,body{margin:0;height:100%;background:#0b0b0b}@media (prefers-color-scheme: light){html,body{background:#fff}}</style>
</head>
<body>
    <script>
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.replace('{{ route('home') }}');
        }
    </script>
</body>
</html>
