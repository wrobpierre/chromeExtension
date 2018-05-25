$(document).ready(function(){

     $("#submit").click(function(e){
        e.preventDefault();
        if($("#email").val() == $("#emailCheck").val() && $("#password").val() == $("#passwordCheck").val() && $("#password").val().length >= 8 && validateEmail($("#email").val())){
            $.post(
                '../connexion/register.php',
                {
                    email : $("#email").val(),
                    password : md5($("#password").val()),
                    job : $("#job").val(),
                    name : $("#name").val()
                },

                function(data){
                    console.log(data);
                    if(data == 'Success'){
                       alert("Your account is create !")
                       document.location.href="../index.php";
                   }
                   else if(data == 'Failed'){
                       $("#resultat").html("<p>Bad email or password.</p>");
                   }
                   else if(data == 'Exist'){
                    $("#resultat").html("<p>Your mail address is already used.</p>");
                   }

               },
               'text'
               );
        }
        else{
            if($("#email").val() != $("#emailCheck").val()){
                $("#resultat").html("<p>The two email are not the same.</p>");
            }
            else if($("#password").val() != $("#passwordCheck").val()){
                $("#resultat").html("<p>The two password are not the same.</p>");
            }
            else if(!validateEmail($("#email").val())){
                $("#resultat").html("<p>Your email is not valid.</p>");
            }
            else if($("#password").val().length < 8){
                $("#resultat").html("<p>Your password must be at least 8 characters.</p>");
            }
            else{
                $("#resultat").html("<p>A problem accured please try later.</p>");
            }
        }
    });
});

function validateEmail(email) 
{
  var re = /^(?:[a-z0-9!#$%&amp;'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&amp;'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$/;
  return re.test(email);
}