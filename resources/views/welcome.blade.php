<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <title>Laravel</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}"/>
    <!-- Fonts -->
    <link href="https://fonts.google.com/specimen/Spectral" rel="stylesheet">
</head>
<body>
<div id="app" class="mt-5">
    <flash-message class="flashCenter"></flash-message>
    <products></products>
</div>
</body>
<script src="{{ asset('js/app.js') }}"></script>
</html>

