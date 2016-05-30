<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">


    <title>Sistema de Administracion de Clubes DATACLUB</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(). "webApp/"; ?>assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="<?php echo base_url(). "webApp/"; ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(). "webApp/"; ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url(). "webApp/"; ?>assets/css/style-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
    var base_img = "<?php echo base_url(). "webApp/img/"; ?>";

        
    </script>

    <style type="text/css">
.form-login h2.form-login-heading {
margin: 0;
padding: 25px 20px;
text-align: center;
/* background: -webkit-linear-gradient(right, #525FB8, #183D6F); */
background: -moz-linear-gradient(right, #76b852, #8DC26F);
background: -o-linear-gradient(right, #76b852, #8DC26F);
background: linear-gradient(to left, #525FB8, #183D6F);
border-radius: 5px 5px 0 0;
-webkit-border-radius: 5px 5px 0 0;
color: #fff;
font-size: 20px;
text-transform: uppercase;
font-weight: 300;
background: #183D6F;
background-color: #848990;
}

.btn-theme:hover, .btn-theme:focus, .btn-theme:active, .btn-theme.active, .open .dropdown-toggle.btn-theme {
color: #fff;
background-color: #091141;
border-color: #48bcb4;
}

.btn:hover, .btn:focus {
color: #333;
text-decoration: none;
}

.btn-theme {
color: #FFFFFF;
background-color: #C8AB33;
border-color: #FFFFFF;
}

.container {
padding-right: 15px;
padding-left: 15px;
margin-right: auto;
margin-left: auto;
background: linear-gradient(to left, rgba(82, 95, 184, 0.97), #183D6F);
height: 71%;
position: absolute;
/* right: 0px; */
width: 100% !important;
top: 15%;
box-shadow: rgba(51, 51, 51, 0.45) 3px 1px 2px 3px;
}
    </style>
  </head>

  <body>

      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->

	  <div id="login-page">
	  	<div class="container">
	  	
		      <form class="form-login"  action="#" method="post">
		        <h2 class="form-login-heading"> 
				<img src="<?php echo base_url(). "webApp/"; ?>img/GestionVista_LocoCompleto.png" style="
    height: 79px;
    margin-top: -24px;
">
Bienvenido</h2>
		        <div class="login-wrap">
		            <input name="login[usuario]" type="text" class="form-control" placeholder="Usuario" autofocus>
		            <br>
		            <input type="password" name= "login[clave]" class="form-control" placeholder="Contraseña">
		            <label class="checkbox">
		               
		            </label>
		            <button id="submitLog" class="btn btn-theme btn-block" type="button"><i class="fa fa-lock"></i> Entrar</button>
		            <hr>            	            
		            <?php echo $mensaje;  ?>
                    <div id="msgServices"></div>
		
		        </div>
		
		          <!-- Modal -->
		          <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
		              <div class="modal-dialog">
		                  <div class="modal-content">
		                      <div class="modal-header">
		                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		                          <h4 class="modal-title">Olvidaste tu contraseña ?</h4>
		                      </div>
		                      <div class="modal-body">
		                          <p>  Entra tu dirección de correo para restablecer tu contraseña.</p>
		                          <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
		
		                      </div>
		                      <div class="modal-footer">
		                          <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
		                          <button class="btn btn-theme" type="button">Submit</button>
		                      </div>
		                  </div>
		              </div>
		          </div>
		          <!-- modal -->
		
		      </form>	  	
	  	<p style="
    position: fixed;
    bottom: 144px;
    right: 540px;
    color: rgb(255, 255, 255);
    font-weight: bold;
">@copyright 2016 , BIS TEAM</p>
	  	</div>
	  </div>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url(). "webApp/"; ?>js/jquery.js"></script>
    <script src="<?php echo base_url(). "webApp/"; ?>js/bootstrap.min.js"></script>

    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
    <script type="text/javascript" src="<?php echo base_url(). "webApp/"; ?>js/jquery.backstretch.min.js"></script>
    <script>
        $.backstretch("<?php echo base_url(). "webApp/"; ?>assets/img/Fondo00.jpg", {speed: 800});
var it = 0; 
        function cambioBG(){

        	if(it == 0){
        		$.backstretch("<?php echo base_url(). "webApp/"; ?>assets/img/Fondo01.jpg", {speed: 800});
        	} 

        	if(it == 1){

        		$.backstretch("<?php echo base_url(). "webApp/"; ?>assets/img/Fondo02.jpg", {speed: 800});        	
        	}

        	if(it == 2){
        		$.backstretch("<?php echo base_url(). "webApp/"; ?>assets/img/Fondo03.jpg", {speed: 800});	
        	}

        	if(it == 4){
        		it = -1;
        	}

        	it++; 
        setTimeout(function(){ cambioBG(); }, 9000);
        }

$(function() {
	
        setTimeout(function(){ cambioBG(); }, 9000);
    });


function EsperaLogin(){    
     $("#msgServices").html('<div style="color: black; font-weight: bold;  text-align: center;"> <img src="'+ base_img + 'ajax-loader-small.gif"> Espere un momento.</div>'); 

     // Read-only input
     $(".form-control").each(
        function(index){
            $(this).attr("readonly", true);            
        }
        ); 
}

function EsperaLoginClose(){    
     $("#msgServices").html(''); 

     // Read-only input
     $(".form-control").each(
        function(index){
            $(this).removeAttr("readonly", true);            
        }
        ); 
}

// Jquery Auth Server.
$(function() {

    $(".form-control").focus(
        function(){
            $("#msgServices").html(''); 

        }
        );

    $("#submitLog").click(
       function(){ 
      
      EsperaLogin(); 

        var form = {
        keyToken:"<?php echo $services["key_token"]; ?>",
        userName: $(document.getElementsByName("login[usuario]")).val(),
        userPw:$(document.getElementsByName("login[clave]")).val(),
        dispositivo:"PC",
        plataforma:"web",
        version:1
    }; 

        $.post("<?php echo $services["url"]; ?>", form, function(res){            

            if(res.IsOk){
                // Si es OK envio de Informacion al Login Local
                form.Data = res.Data;
                form.IsOk = true;
                $.post(base_url + "index.php/portal/login/", {login: form} , function(rs){

                    EsperaLoginClose(); 

                    if(rs.data == true){
                        document.location.reload(true);
                    } else {
                        $("#msgServices").html('<div style="color: red; font-weight: bold;  text-align: center;">  Nombre de usuario o clave incorrecta. </div>'); 

                    }

                }, "json");

            } else {

                EsperaLoginClose(); 
                
                $("#msgServices").html('<div style="color: red; font-weight: bold;  text-align: center;">  Nombre de usuario o clave incorrecta. </div>'); 

                


            } 

                },"json" ); 

            }
        ); 

}); 



    </script>


  </body>
</html>
