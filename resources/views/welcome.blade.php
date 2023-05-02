<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$title ?? 'Devmart'}}</title>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>
<body>
<div class="font-sans antialiased h-full" id="app"></div>

@vite('resources/js/app.js')
</body>
</html>
