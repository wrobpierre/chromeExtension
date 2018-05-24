<?php
session_start();
?>

<!DOCTYPE html>
<html>
<title>ChromeExtension</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="icon" type="image/gif" href="img/opu.ico" />
<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Lato", sans-serif}
.w3-bar,h1,button {font-family: "Montserrat", sans-serif}
.fa-anchor,.fa-coffee {font-size:200px}
</style>
<body>

  <!-- Navbar -->
  <div class="w3-top">
    <div class="w3-bar w3-red w3-card w3-left-align w3-large">
      <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-red" href="javascript:void(0);" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
      <a href="#" class="w3-bar-item w3-button w3-padding-large w3-white w3-center">Home</a>
      <a href="./questionnaires/questionnaire.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">See graphics</a>
      <a href="chromeExtension.zip" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" download="chromeExtension">Download extension</a>
      <?php
      if(!isset($_SESSION['user'])){
        ?>
        <a href="./connexion/connexion.html" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white w3-right">Sign in</a>
        <a href="./connexion/register.html" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white w3-right">Sign up</a>
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
      <a href="https://github.com/wrobpierre/chromeExtension.git" class="w3-bar-item w3-button w3-padding-large">Download extension</a>
      <a href="./connexion/connexion.html" class="w3-bar-item w3-button w3-padding-large">Connexion</a>
    </div>
  </div>

  <!-- Header -->
  <header class="w3-container w3-red w3-center" style="padding:128px 16px">
    <h1 class="w3-margin w3-jumbo">ChromeExtension</h1>
    <p class="w3-xlarge">Answer easier and faster.</p>
    <button class="w3-button w3-black w3-padding-large w3-large w3-margin-top" onclick="window:location.href='./questionnaires/questionnaire.php'">Get Started</button>
  </header>

  <!-- First Grid -->
  <div class="w3-row-padding w3-padding-64 w3-container">
    <div class="w3-content">
      <div class="w3-twothird">
        <h1>What is ChromeExtension ?</h1>

        <p class="w3-text-grey">
        Do you often use your internet to answer a question?</p>
        <p class="w3-text-grey">But you do not always understand the author's explanations and spend a lot of time learning about other sites.</p>
        <p class="w3-text-grey">So our extension is done for you !</p>
        <p class="w3-text-grey">With chromeExtension, you can use the experience of previous users to find the information you need as quickly as possible.
        If you activate the extension, you can also share your experience and future users can benefit from it.</p>
      </div>

      <div class="w3-third w3-center" style="font-size:15em;">
        <i class="fa fa-question w3-padding-64 w3-text-red"></i>
      </div>
    </div>
  </div>

  <!-- Second Grid -->
  <div class="w3-row-padding w3-light-grey w3-padding-64 w3-container">
    <div class="w3-content">
      <div class="w3-third w3-center" style="font-size:15em;">
        <i class="fa fa-edit w3-padding-64 w3-text-red w3-margin-right"></i>
      </div>

      <div class="w3-twothird">
        <h1>Help us with answer to questionnaire !</h1>

        <p class="w3-text-grey">You can help us to improve our algorithm while answering some questionnaire. The operation is the same than before exept you don't need to push the start and stop button. You have just to choose a questionnaire and answer the question.</p>
      </div>
    </div>
  </div>

  <div class="w3-row-padding w3-black w3-opacity w3-padding-64 w3-container">
    <div class="w3-content">
      <div class="w3-twothird">
        <h1 class="w3-margin w3-xlarge">How to use it ?</h1>
        <h5 class="w3-padding-32">Do your hown research !</h5>

        <p class="w3-text-white">When you are on a web page you don't understand, click on the extension's icon.</p>
        <p class="w3-text-white">You can "start" the extension, this button will search the url of the web site and check if others peoples have already done this search. If it find a result, the extension will show you a link. This link send you on a web page with all the search of previous users in order of relevance. </p>
        <p class="w3-text-white">When you click on "start" the extension begin to analize and save your navigation.</p>
        <p class="w3-text-white">When you finished you have to push the stop button of the extension. Then your navigation will analize and will be save to help the futur users.</p>

      </div>
      <div class="w3-third w3-center" style="font-size:15em;">
        <img src="img/extension.png" alt="" style="border-radius: 100%; width: 100%;">
      </div>
    </div>
  </div>

  <div class="w3-row-padding w3-black w3-opacity w3-padding-64 w3-container">
    <div class="w3-content">
      <div class="w3-third w3-center" style="font-size:15em;">
        <img src="img/bulle.png" alt="" style="border-radius: 100%; width: 100%;">
      </div>
      <div class="w3-twothird" style="padding-left: 50px;">

        <h5 class="w3-padding-32">See the revelant website</h5>

        <p class="w3-text-white">On clicking on the "get started" in the top of this page, you can access to the search's graphics of all the other users.</p>
        <p class="w3-text-white">When you are on the graphics page you can see the statistics done by our algorithm and click on the buble to go on the web page. </p>
        <p class="w3-text-white">The size of a circle represent the number of view made by all the users and the color represent the time spend on the page.</p>
      </div>
    </div>
  </div>

  <div class="w3-row-padding w3-light-grey w3-padding-64 w3-container">
    <div class="w3-content">
      <div class="w3-twothird">
        <h1>How to install the extension ?</h1>

        <ul>
          <li>Download the extension</li>
          <li>Locate the ZIP file on your computer and unzip it</li>
          <li>Go back to the chrome://extensions/ page and click the Load unpacked extension button and select the unzipped folder for your extension to install it</li>
        </ul>

        <h1>How to install as unpacked file ?</h1>
        <ul>
          <li>Go to chrome://extensions/ and check the box for Developer mode in the top right. </li>
          <li>Unzip the extension</li>
          <li>Install it in Google Chrome as an unpacked file</li>
          <li>Have fun !</li>
        </ul>

        <p>Thanks to MATT ELLIOTT For the tutoriel on : <a href="https://www.cnet.com/how-to/how-to-install-chrome-extensions-manually/">This link</a></p>

      </div>
      <div class="w3-third w3-center" style="font-size:15em;">
        <img src="https://c-8oqtgrjgwu46x24epgv6x2eedukuvcvkex2eeqo.g00.cnet.com/g00/3_c-8yyy.epgv.eqo_/c-8OQTGRJGWU46x24jvvrux3ax2fx2fepgv6.edukuvcvke.eqox2fkoix2fgfXKulUUHLtE6G82FyEwjwmR2Wux3dx2f842z2x2f4239x2f23x2f30x2fd1ef0e24-6c65-6e54-d7d1-87d7hc6g18dhx2ffgx78gnqrgt-oqfg-ejtqog.lrix3fk32e.octmx3dkocig_$/$/$/$/$/$/$/$/$" alt="" style="border-radius: 5%; width: 100%;">
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="w3-container w3-padding-64 w3-center w3-opacity">  
    <div class="w3-xlarge w3-padding-32">
    </div>
    <p>Our gitHub <a href="https://github.com/wrobpierre/chromeExtension/" target="_blank">Github</a></p>
    <p>Powered by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank">w3.css</a></p>
  </footer>

  <script>
// Used to toggle the menu on small screens when clicking on the menu button
function myFunction() {
  var x = document.getElementById("navDemo");
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
  } else { 
    x.className = x.className.replace(" w3-show", "");
  }
}
</script>

</body>
</html>