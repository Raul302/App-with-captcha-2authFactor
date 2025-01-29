<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Logged</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">



</head>

<body>

    <!-- {{-- Nav Bar we will use many times --}} -->

    <x-nav-bar.nav-custom />

    <!-- {{-- end Nav Bar reused --}} -->


      <!-- Div Container main ( flexbox ) -->
      <div class="flex-container">
        <div class="row">
            <div class="flex-item">
                <p>Welcome ,  You already are logged
                </p>
            </div>
        </div>
    </div>

    
    




</body>

 <!-- Using Javascript to prevent forms to refresh page's -->
<script src="{{ asset('js/app.js') }}"></script> 

</html>