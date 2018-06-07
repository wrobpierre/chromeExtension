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
        <div class="w3-content w3-center" style="width:300px;">

            <h1>Sign In</h1>

            <form>
                <p>
                    <div class="w3-padding-16">
                        <div>
                            Email :
                        </div>
                        <div >
                         <input class="w3-input" type="email" id="email" />
                     </div>

                 </div>
                 <div class="w3-padding-16">
                    <div>
                        Password :
                    </div>
                    <div>
                     <input class="w3-input" type="password" id="password" />
                 </div>

             </div>
             <div class="w3-row-padding w3-row-padding w3-light-grey w3-padding-16">
                <input type="submit" id="submit" value="Sign in !" class="w3-button w3-green" />
            </div>
            <div id="resultat" class="w3-container w3-red"></div>
            <div class="w3-padding-16">
                Not register yet ? <a href="./regist.php">Click here !</a>
            </div>
        </p>
    </form>
</div>
</div>

<?php include '../layout/footer.php'; ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="../js/md5.min.js" type="text/javascript"></script>
<script src="../js/connexion.js" type="text/javascript"></script>
</body>
</html>
