<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two auth</title>


    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">


    <!-- Recaptcha  -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
</head>
<body>

     <!-- {{-- Nav Bar we will use many times --}} -->

     <x-nav-bar.nav-custom />

     <!-- {{-- end Nav Bar reused --}} -->
 

    <!-- CSRF token is not necessary because we dont have an active session yet -->


<!--[A-Za-z0-9\-_.]{4,20} AND (?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,} are regular expresion to validate username and password input -->

<!-- Div Container main ( flexbox ) -->
<div class="flex-container">
    <div class="row">
      <div class="flex-item register">
        <h1>Two auth</h1>
  
  
        <!-- Box to display validation errors -->
        @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
  
           <!-- Box to display message from backend -->
           @if ( session()->get('message') )
           <div class="succesful">
             <ul>
               <li>{{ session()->get('message') }}</li>
             </ul>
           </div>
           @endif
  
  
  
           <form method="post" action="send-otp" class="formRegister">
  
          <!-- CSRF token helps to ensure the data is legitim -->
          @csrf
  
          
  
  
          <label class="mb-10" for="email">OTP:</label>
          <input  required id="otp" type="number" pattern="[0-9]"  title="Only numbers" name="otp">
  
  
  
        
  
  
          <button type="submit" id="buttonSubmit" class="mt-20"> Verify </button>
  
        </form>
      </div>
    </div>
  </div>
  
  
    
</body>
</html>