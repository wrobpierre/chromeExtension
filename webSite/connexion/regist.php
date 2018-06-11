<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>ChromeExtension</title>
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
                                <input type="text" id="job" class="w3-input" />
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
