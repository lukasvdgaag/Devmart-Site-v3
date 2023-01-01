<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{$title}}</title>
</head>
<body>
	<div class="font-sans antialiased h-full" id="app"></div>

	@vite('resources/js/app.js')
    @vite('public/js/main.js')
</body>
</html>
