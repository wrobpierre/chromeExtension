<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>SharedOn</title>
</head>

<link rel="stylesheet" href="../plugin/country-select-js-master/build/css/countrySelect.css">

<?php include '../layout/includes.php'; ?>
<body>
    <?php include '../layout/header.php'; ?>

    <div class="w3-row-padding w3-light-grey w3-padding-64 w3-container">
        <div class="w3-content w3-center" style="width:300px;">

            <h1>Sign Up</h1>

            <form id="form">
                <p>
                    <div class="w3-row-padding w3-row-padding w3-light-grey w3-margin w3-padding-16 w3-centered">
                        <div class="w3-padding-16">
                            <div>
                                Email :
                            </div>
                            <div >
                                <input type="email" id="email" class="w3-input" />
                            </div>
                            
                        </div>
                        <div class="w3-padding-16">
                            <div>
                                Re-enter your email:
                            </div>
                            <div >
                                <input type="email" id="emailCheck" class="w3-input" />
                            </div>
                            
                        </div>
                        <div class="w3-padding-16">
                            <div>
                                Password :
                            </div>
                            <div >
                                <input type="password" id="password" class="w3-input" />
                            </div>
                            
                        </div>
                        <div class="w3-padding-16">
                            <div>
                                Re-enter your password :
                            </div>
                            <div>
                                <input type="password" id="passwordCheck" class="w3-input" />
                            </div>
                            
                        </div>
                        <div class="w3-padding-16">
                            <div>
                                Name :
                            </div>
                            <div>
                                <input type="text" id="name" class="w3-input" />
                            </div>
                            
                        </div>
                        <div class="w3-padding-16">
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
                      <div class="w3-padding-16">
                        <div>
                            Language :
                        </div>
                        <div>
                            <input class="w3-input" type="text" id="country">
                        </div>
                    </div>
                </div>
                <div class="w3-row-padding w3-row-padding w3-light-grey w3-padding-16">
                    <input type="submit" id="submit" class="w3-button w3-green" value="Register !" />
                </div>
                <div id="resultat" class="w3-container w3-red"></div>

            </p>
        </form>
    </div>
</div>

<?php include '../layout/footer.php'; ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="../js/md5.min.js" type="text/javascript"></script>
<script src="../plugin/country-select-js-master/build/js/countrySelect.min.js"></script>
<script src="../js/register.js" type="text/javascript"></script>
</body>
</html>
