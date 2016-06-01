<?php 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Siste,a de informacion de los clubes JA de la republica dominicana">
    <meta name="author" content="Dashboard">
    <title><?php echo $page->title;  ?></title>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(). "webApp/"; ?>assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="<?php echo base_url(). "webApp/"; ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(). "webApp/"; ?>js/gritter/css/jquery.gritter.css" />
        
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(). "webApp/"; ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url(). "webApp/"; ?>assets/css/main-style.css" rel="stylesheet">
    <link href="<?php echo base_url(). "webApp/"; ?>assets/css/style-responsive.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(). "webApp/"; ?>assets/css/to-do.css">

    <script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
      
    </script>

<style type="text/css">

ul.top-menu > li > .logout {
color: #f2f2f2;
font-size: 12px;
border-radius: 4px;
-webkit-border-radius: 4px;
border: 1px solid rgba(82, 95, 184, 0)!important;
padding: 4px 5px;
margin-right: 12px;
background: rgba(82, 95, 184, 0);
margin-top: 15px;
margin-left: 11px;
}

</style>



    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body ng-app="App" >

  <section id="container" ng-controller="AppController" >
      <!-- **********************************************************************************************************************************************************
      TOP BAR CONTENT & NOTIFICATIONS
      *********************************************************************************************************************************************************** -->
      <!--header start-->
      <header class="header black-bg">
              <div class="sidebar-toggle-box">
                  <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Ocultar/mostrar el menú"></div>
              </div>
            <!--logo start-->
            <a href="index.html" class="logo">			
			<img src="<?php echo base_url(). "webApp/";?>img/GestionVista_LocoCompleto.png" style="
    height: 79px;
    margin-top: -24px;
"></a>
            <!--logo end-->
            <div class="nav notify-row" id="top_menu">
                <!--  notification start -->
       
                <!--  notification end -->
            </div>
            <div class="top-menu">
            	<ul class="nav pull-right top-menu">
               
                  <li> 
					  <a class="logout" href="#">
					  <span class="fa fa-home" style="font-size: 17px;"></span>  </a>
				  </li>
                   <li> 
                  <a class="logout" href="#">
                  <span class="fa fa-envelope-o" style="font-size: 17px;"></span>  </a> </li>

                    <li><a class="logout" href="<?php echo base_url(). "";?>index.php/portal/cerrarSession"><span class="fa fa-cogs" style="font-size: 17px;"></span> Cerrar Session</a></li>
            	</ul>
            </div>
        </header>
      <!--header end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN SIDEBAR MENU
      *********************************************************************************************************************************************************** -->
      <!--sidebar start-->
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu" id="nav-accordion">
              
              	  <p class="centered"><a href="#"><img src="<?php echo base_url(). "webApp/";?>img/user.jpg" class="img-circle" width="60"></a></p>
              	  <h5 class="centered"><?php echo "Administrador";  ?></h5>             	  	
                  

      

                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="fa fa-cogs"></i>
                          <span>Seguridad</span>
                      </a>                     
                  </li>
                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="fa fa-book"></i>
                          <span>Solicitudes</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="blank.html">Blank Page</a></li>
                                                  </ul>
                  </li>
                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="fa fa-tasks"></i>
                          <span>Formularios</span>
                      </a>
                      <ul class="sub">
                          <li><a  ng-click="SetMain('dispositivo')">Dispositivos</a></li>
                           
                      </ul>
                  </li>
                
                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class=" fa fa-bar-chart-o"></i>
                          <span>Reportes</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="morris.html">Morris</a></li>
                          <li><a  href="chartjs.html">Chartjs</a></li>
                      </ul>
                  </li>
              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>
      <!--sidebar end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
      <!--main content start-->
      <section id="main-content">
          <div id="page-wrappaer" class="site-min-height">
            <section class="wrapper" compile="AppHtml">                     
              
             </section>     
          </div>
         
         <?php 
         // Contenido renderizado principal.
         		?>		 
          	

      </section><!-- /MAIN CONTENT -->

      <!--main content end-->
      <!--footer start-->
      <footer class="site-footer">
          <div class="text-center">
              2016 - @Movimiento JA
              <a href="general.html#" class="go-top">
                  <i class="fa fa-angle-up"></i>
              </a>
          </div>
      </footer>
      <!--footer end-->
  </section>

    <script src="<?php echo base_url(). "webApp/"; ?>js/jquery.js"></script>    
    <!-- js placed at the end of the document so the pages load faster -->    
    <script src="<?php echo base_url(). "webApp/"; ?>js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="<?php echo base_url(). "webApp/"; ?>js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="<?php echo base_url(). "webApp/"; ?>js/jquery.scrollTo.min.js"></script>
    <script src="<?php echo base_url(). "webApp/"; ?>js/jquery.nicescroll.js" type="text/javascript"></script>
    <!--common script for all pages-->
    <script src="<?php echo base_url(). "webApp/"; ?>js/common-scripts.js"></script>

<script src="<?php echo base_url(). "webApp/"; ?>js/jquery-ui.js"></script>    
    <script src="<?php echo base_url(). "webApp/"; ?>js/tasks.js" type="text/javascript"></script>

    <!--script for this page-->
    <script type="text/javascript" src="<?php echo base_url(). "webApp/"; ?>js/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript" src="<?php echo base_url(). "webApp/"; ?>js/gritter-conf.js"></script>
    <script type="text/javascript" src="<?php echo base_url(). "webApp/"; ?>js/angular-1.2.26.js"></script>    
    <script type="text/javascript" src="<?php echo base_url(). "webApp/"; ?>js/Jcs-auto-validate/jcs-auto-validate.js"></script>
    <script type="text/javascript" src="<?php echo base_url(). "webApp/"; ?>js/Oi.multiselect/multiselect-tpls.min.js"></script><!--angular-oi.multiselect-->

    <script type="text/javascript">
        var appAngularDependecies = [ "oi.multiselect"];
    </script>
    <!--[if lt IE 11]>
        <script type="text/javascript">
            appAngularDependecies =[];
        </script>
    <![endif]-->
    <script type="text/javascript">

    var CLUBCrud = {
        obj: {},       
        modo: 0,
        accion: 0,
        selectedIndex: 0,
        initt: function(){
          $("#formulario").hide();          
          $("#ListMantenimiento").show();          
        },

        Cancelar: function(){
          $("#formulario").hide();          
          $("#ListMantenimiento").show();          
        },

        Editar: function(modo){
          $("#formulario").show();          
          $("#ListMantenimiento").hide();     

               CLUBCrud.modo = modo; 

        },
        reset: function(){  
          $("#formulario").hide();          
          $("#ListMantenimiento").show();

          for(var i in CLUBCrud.obj){
            if(CLUBCrud.obj.hasOwnProperty(i) ){
              CLUBCrud.obj[i] = ""; 
            }
          } 
        }
}; 
    </script>


    <script type="text/javascript" src="<?php echo base_url(). "webApp/"; ?>js/App/App.js"></script>
    <script type="text/javascript" src="<?php echo base_url(). "webApp/"; ?>js/App/AppValidation.js"></script>
    <script type="text/javascript" src="<?php echo base_url(). "webApp/"; ?>js/App/AppFormat.js"></script>
    <script type="text/javascript" src=".<?php echo base_url(). "webApp/"; ?>js/App/AppTool.js"></script>
    <script type="text/javascript" src="<?php echo base_url(). "webApp/"; ?>js/App/AppSystem.js"></script>\

    <script type="text/javascript" src="<?php echo base_url(). "webApp/"; ?>js/angular-sanitize.js"></script>

    <script src="<?php echo base_url(). "webApp/"; ?>js/App/Filter/Filters.js"></script>
    <script src="<?php echo base_url(). "webApp/"; ?>js/App/Directive/Directives.js"></script>
    <script src="<?php echo base_url(). "webApp/"; ?>js/App/Factory/Factories.js"></script>
    <script src="<?php echo base_url(). "webApp/"; ?>js/App/Service/Services.js"></script>
    <script src="<?php echo base_url(). "webApp/"; ?>js/App/Controller/Controllers.js"></script>

    <script type="text/javascript">    
    </script>    
           
  <script type="text/javascript">
      //custom select box   

  </script>

  </body>
</html>