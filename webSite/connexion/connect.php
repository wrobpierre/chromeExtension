<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>SharedOn</title>
</head>
<?php include '../layout/includes.php'; ?>
<link rel="stylesheet" href="../plugin/country-select-js-master/build/css/countrySelect.css">
<body>
    <?php include '../layout/header.php'; ?>

    <div class="w3-row-padding w3-light-grey w3-padding-64 w3-container">
        <div class="w3-content w3-center" style="width:300px;">

            <h1>Sign In</h1>

            <form>
                <div id="content">
                    <div class="w3-padding">
                        <div>
                            login :
                        </div>
                        <div >
                           <input class="w3-input" type="text" id="login" />
                       </div>

                   </div>
                   <div class="w3-padding">
                    <div>
                        Password :
                    </div>
                    <div id="connectionButton">
                       <input class="w3-input" type="password" id="password" />
                   </div>

               </div>
               <div id="register" style="display: none;" class="w3-row-padding w3-row-padding w3-light-grey w3-centered">
                <div>
                        Re-enter your password :
                    </div>
                    <div>
                       <input class="w3-input" type="password" id="passwordCheck" />
                   </div>
                <div>
                    <div>
                        Name :
                    </div>
                    <div>
                        <input type="text" id="name" class="w3-input" />
                    </div>

                </div>
                <div>
                    <div>
                        Job :
                    </div>
                    <div >
                        <select id="job" class="w3-select" name="option">
                          <option value="" disabled selected>Choose your option</option>
                          <option value="1">Administrative and support service activities</option>
                          <option value="2">Transportation and warehousing</option>
                          <option value="3">Extra-territorial activities</option>
                          <option value="4">Financial and insurance activities</option>
                          <option value="5">Real estate activities</option>
                          <option value="6">Specialized, scientific and technical activities</option>
                          <option value="7">Public administration</option>
                          <option value="8">Agriculture, forestry and fishing</option>
                          <option value="9">Arts, entertainment and recreation</option>
                          <option value="10">Other service activities</option>
                          <option value="11">Trade, repair of motor vehicles and motorcycles</option>
                          <option value="12">Construction</option>
                          <option value="13">Education</option>
                          <option value="14">Accommodation and catering</option>
                          <option value="15">Manufacturing industry</option>
                          <option value="16">Extractive industries</option>
                          <option value="17">Information and communication</option>
                          <option value="18">Human health and social action</option>
                          <option value="19">Student</option>
                      </select>
                  </div>

              </div>
              <div>
                <div>
                    Language :
                </div>
                <div id="registerButton">
                    <input class="w3-input" type="text" id="country">
                </div>
            </div>
        </div>
        <div class="w3-row-padding w3-row-padding w3-light-grey w3-padding-16">
            <input type="submit" id="submit" value="Sign in !" class="w3-button w3-blue" />
        </div>
        <div id="resultat" class="w3-container w3-red"></div>
        <div class="w3-padding-16">
            Not register yet ? <a href="./regist.php">Click here !</a>
        </div>
    </div>
</form>
</div>
</div>

<?php include '../layout/footer.php'; ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="../js/md5.min.js" type="text/javascript"></script>
<script src="../js/connexion.js" type="text/javascript"></script>
<script src="../plugin/country-select-js-master/build/js/countrySelect.min.js"></script>
</body>
</html>