<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>{{ $pageTitle ?? "Books with reviews App." }}</title>
</head>
<body class="container mx-auto mt-10 mb-10 max-w-3xl">
{{ $slot }}
</body>
</html>
