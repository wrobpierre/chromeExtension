$(document).ready(function(){

    $("#submit").click(function(e){
        e.preventDefault();
        if(validateEmail($("#email").val())){
            $.post(
                '../connexion/connexion.php',
                {
                    email : $("#email").val(),
                    password : md5($("#password").val())
                },

                function(data){
                    console.log(data);
                    if(data == 'Success'){
                       document.location.href="../index.php";
                   }
                   else{ 
                       $("#resultat").html("<p>Your email or password is invalid.</p>");
                   }

               },
               'text'
               );
        }
        else{
            $("#resultat").html("<p>Email is invalid.</p>");
        }
    });
});
function validateEmail(email) 
{
  var re = /^(?:[a-z0-9!#$%&amp;'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&amp;'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$/;
  return re.test(email);
}