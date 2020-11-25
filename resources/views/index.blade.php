<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="description" content="鯊鯊">
    <meta property="og:type" content="website" />
    <meta property="og:title" content="GuraeAt" />
    <meta property="og:description" content="鯊鯊" />
    <meta property="og:image" content="https://media1.tenor.com/images/bef61d5742b957cb5330cb738e6f7329/tenor.gif" />
    <link rel="manifest" href="./manifest.webmanifest"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GuraeAt</title>
    <link rel="stylesheet" type="text/css" href="./css/app.css">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css" rel="stylesheet">
    <script src="https://unpkg.com/marked@0.3.6"></script>
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;900&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('favicon.ico')}}" type="image/x-icon">
</head>
<body>
<div id="app">
</div>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
