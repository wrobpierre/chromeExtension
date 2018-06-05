  <!-- Navbar -->
  <div class="w3-top" style="z-index: 999;">
    <div class="w3-bar w3-red w3-card w3-left-align w3-large">
      <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-red" href="javascript:void(0);" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
      <a href="/webSite" class="w3-bar-item w3-button w3-padding-large w3-white w3-center">Home</a>
      <a href="/webSite/questionnaires/questionnaire.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">See graphics</a>
      <a href="/SharedOn.zip" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" download="SharedOn">Download extension</a>
      <?php
      if(!isset($_SESSION['user'])){
        ?>
        <a href="/webSite/connexion/connect.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white w3-right">Sign in</a>
        <a href="/webSite/connexion/regist.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white w3-right">Sign up</a>
        <?php
      }
      ?>
      <?php
      if(isset($_SESSION['user'])){
        ?>
      <a href="./connexion/disconnection.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white w3-right">Disconnection</a>
      <?php
    }
    ?>
    </div>

    <!-- Navbar on small screens -->
    <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium w3-large">
      <a href="./questionnaires/questionnaire.php" class="w3-bar-item w3-button w3-padding-large">See graphics</a>
      <a href="/SharedOn.zip" class="w3-bar-item w3-button w3-padding-large">Download extension</a>
      <a href="./connexion/connexion.html" class="w3-bar-item w3-button w3-padding-large">Sign in</a>
      <a href="./connexion/register.html" class="w3-bar-item w3-button w3-padding-large">Sign up</a>
    </div>
  </div>