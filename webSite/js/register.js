$(document).ready(function(){
 
    $("#submit").click(function(e){
        e.preventDefault();
 
        $.post(
            '../connexion/register.php',
            {
                email : $("#email").val(),
                password : md5($("#password").val()),
                job : $("#job").val(),
                name : $("#name").val()
            },
 
            function(data){
                if(data == 'Success'){
                     alert("Your account is create !")
                     document.location.href="../index.php";
                }
                else{
                     $("#resultat").html("<p>Erreur lors de la connexion...</p>");
                }
         
            },
            'text'
         );
    });
});