$(document).ready(function(){
  var register = false;
  $("#country").countrySelect();

  $("#submit").click(function(e){
    if(register){
      e.preventDefault();
      $job = "";
      alert($("#job").val());
      switch($("#job").val()) {
        case "1": $job ="Administrative and support service activities";
        break;
        case "2": $job ="Transportation and warehousing";
        break;
        case "3": $job ="Extra-territorial activities";
        break;
        case "4": $job ="Financial and insurance activities";
        break;
        case "5": $job ="Real estate activities";
        break;
        case "6": $job ="Specialized, scientific and technical activities";
        break;
        case "7": $job ="Public administration";
        break;
        case "8": $job ="Agriculture, forestry and fishing";
        break;
        case "9": $job ="Arts, entertainment and recreation";
        break;
        case "10": $job = "Other service activities";
        break;
        case "11": $job = "Trade, repair of motor vehicles and motorcycles";
        break;
        case "12": $job = "Construction";
        break;
        case "13": $job = "Education";
        break;
        case "14": $job = "Accommodation and catering";
        break;
        case "15": $job = "Manufacturing industry";
        break;
        case "16": $job = "Extractive industries";
        break;
        case "17": $job = "Information and communication";
        break;
        case "18": $job = "Human health and social action";
        break;
        case "19": $job = "Student";
        break;
        default:
        $job = null;
      }

      if($("#login").val() != "" && $("#password").val() == $("#passwordCheck").val() && $("#password").val().length >= 8){
        $.post(
          '../connexion/register.php',
          {
            login : $("#login").val(),
            password : md5($("#password").val()),
            job : $job,
            name : $("#name").val(),
            lang : $('#country').next().find('.selected-flag').find('.flag')[0].classList[1]
          },

          function(data){
            console.log(data);
            if(data == 'Success'){
             alert("Your account is created !")
             document.location.href="../index";
           }
           else if(data == 'Failed'){
             $("#resultat").html("<p>Bad login or password.</p>");
           }
           else if(data == 'Exist'){
            $("#resultat").html("<p>Your mail address is already used.</p>");
          }

        },
        'text'
        );
      }
      else{
        if($("#login").val() == ""){
          $("#resultat").html("<p>The login is empty.</p>");
        }
        else if($("#password").val() != $("#passwordCheck").val()){
          $("#resultat").html("<p>The two password are not the same.</p>");
        }
        else if($("#password").val().length < 8){
          $("#resultat").html("<p>Your password must be at least 8 characters.</p>");
        }
        else{
          $("#resultat").html("<p>A problem occured please try later.</p>");
        }
      }
    }
    else{
      e.preventDefault();
      $.post(
        '../connexion/connexion.php',
        {
          login : $("#login").val(),
          password : md5($("#password").val())
        },

        function(data){
          if(data == 'Success'){
            console.log('test');
            window.history.back();
          }
          else{ 
            register = true;
            $("#register").show();
          }
        },
        'text'
        );
    }
  });
});
