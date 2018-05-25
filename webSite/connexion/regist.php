<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>ChromeExtension</title>
</head>

<?php include '../layout/includes.php'; ?>
<body>
    <?php include '../layout/header.php'; ?>

    <div class="w3-row-padding w3-light-grey w3-padding-64 w3-container">
        <div class="w3-content w3-center">

            <h1>Sign Up</h1>

            <form id="form">
                <p>
                    <div class="w3-row-padding w3-row-padding w3-light-grey w3-margin w3-padding-16 w3-centered">
                        <div class="w3-padding-16">
                            <div>
                                Email :
                            </div>
                            <div >
                                <input type="email" id="email" />
                            </div>
                            
                        </div>
                        <div class="w3-padding-16">
                            <div>
                                Re-enter your email:
                            </div>
                            <div >
                                <input type="email" id="emailCheck" />
                            </div>
                            
                        </div>
                        <div class="w3-padding-16">
                            <div>
                                Password :
                            </div>
                            <div >
                                <input type="password" id="password" />
                            </div>
                            
                        </div>
                        <div class="w3-padding-16">
                            <div>
                                Re-enter your password :
                            </div>
                            <div>
                                <input type="password" id="passwordCheck" />
                            </div>
                            
                        </div>
                        <div class="w3-padding-16">
                            <div>
                                Name :
                            </div>
                            <div>
                                <input type="text" id="name" />
                            </div>
                            
                        </div>
                        <div class="w3-padding-16">
                            <div>
                                Job :
                            </div>
                            <div >
                                <input type="text" id="job" />
                            </div>
                            
                        </div>
                    </div>
                    <div class="w3-row-padding w3-row-padding w3-light-grey w3-padding-16">
                        <input type="submit" id="submit" value="Register !" />
                    </div>
                    <div id="resultat" class="w3-container w3-red"></div>

                </p>
            </form>
        </div>
    </div>

    <?php include '../layout/footer.php'; ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="../js/md5.min.js" type="text/javascript"></script>
    <script src="../js/register.js" type="text/javascript"></script>
</body>
</html>
