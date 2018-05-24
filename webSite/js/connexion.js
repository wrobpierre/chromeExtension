$(document).ready(function(){
 
    $("#submit").click(function(e){
        e.preventDefault();
 
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
                     $("#resultat").html("<p>Erreur lors de la connexion...</p>");
                }
         
            },
            'text'
         );
    });
});