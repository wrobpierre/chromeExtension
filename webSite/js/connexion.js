$(document).ready(function(){
 
    $("#submit").click(function(e){
        e.preventDefault();
 
        $.post(
            '../connexion/connexion.php', // Un script PHP que l'on va créer juste après
            {
                username : $("#username").val(),  // Nous récupérons la valeur de nos input que l'on fait passer à connexion.php
                password : $("#password").val()
            },
 
            function(data){
                console.log(data);
                if(data == 'Success'){
                     // Le membre est connecté. Ajoutons lui un message dans la page HTML.
 
                     $("#resultat").html("<p>Vous avez été connecté avec succès !</p>");
                     console.log('sucess2');
                }
                else{
                     // Le membre n'a pas été connecté. (data vaut ici "failed")
 
                     $("#resultat").html("<p>Erreur lors de la connexion...</p>");
                     console.log('falied2');
                }
         
            },
            'text'
         );
    });
});