<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>


    
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
        <h1>Register</h1>
  
  
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
  
  
  
           <form method="post" action="register" class="formRegister">
  
          <!-- CSRF token helps to ensure the data is legitim -->
          @csrf
  
          <label class="mb-10" for="Username">Username:</label>
          <input pattern="[A-Za-z0-9\-_.]{4,20}"
            title="at least 4 characters and maximum 20" required id="username"
            type="text" placeholder="Username" name="username">
  
  
          <label class="mb-10" for="email">Email:</label>
          <input title="example@example.com" required id="email" type="email" placeholder="example@example.com"
            name="email">
  
  
  
          <label class="mb-10" for="password">Password:</label>
          <input pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
          title="Must contain at least one number, one uppercase and lowercase letter, and at least 8 characters" required
            type="password" id="password" placeholder="******" name="password">
  
            <div class="g-recaptcha mt-20" data-sitekey={{ config('services.recaptcha.key') }} ></div>
  
  
          <button type="submit" id="buttonSubmit" class="mt-20"> Register </button>
  
        </form>
      </div>
    </div>
  </div>
  
  
    
</body>


 <!-- Using Javascript to prevent forms to refresh page's -->
 <script src="{{ asset('js/app.js') }}"></script> 
</html>