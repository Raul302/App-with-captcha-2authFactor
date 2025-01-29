<div>
  <!-- Nothing in life is to be feared, it is only to be understood. Now is the time to understand more, so that we may fear less. - Marie Curie -->

  <!-- custom nav bar component to reuse -->
  <nav>
    <ul class="navigationbar">

    @auth
    <li><a href="logout">Logout</a></li>
    <li><a >{{ Auth::user()->username }}</a></li>
    @endauth

    @guest
    <li><a href="login">Login</a></li>
    <li><a href="register">Register</a></li>
    @endguest

      
    </ul>
  </nav>

</div>