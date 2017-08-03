<link rel="stylesheet" type="text/css" href="<?php echo base_url(). "webApp/"; ?>assets/css/zabuto_calendar.css">

<style type="text/css">

.green-panel {
text-align: center;
background: #424a5d;
}

.calendar-month-header {
    background: #776e42;
}
.ds h3 {
color: #ffffff;
font-size: 16px;
padding: 0 10px;
line-height: 60px;
height: 60px;
margin: 0;
background: #948646;
text-align: center;
}

</style>

<div ng-controller="HomeDashBoardController" ng-init= "initt();" >


<div class="row">
 <div class="col-lg-9 main-chart">

       <div class="panel panel-default">
             <div class="panel-heading">

<h3> <?php echo '<img src="'. base_url(). 'webApp/img/TV-wifi.png" style=" height: 28px; margin-top: -5px;">'; ?> Dispositivos conectados (Online) {{rpt.real}}
             </h3>                 
             </div>
<div class="panel-body">

<div class="col-md-4 mb">
                          <div class="grey-panel pn">
                            <div class="grey-header">
                    <h5>TV en Linea</h5>
                            </div>
                <canvas id="serverstatus02" height="120" width="120" style="width: 120px; height: 120px;"></canvas>
                
                <p>{{lastUpdate}}</p>
                <footer>
                  <div class="pull-left">
                    <h5><i class="fa fa-desktop"></i> {{rpt.real}} / {{rpt.total}}</h5>
                  </div>
                  <div class="pull-right">
                    <h5>{{rpt.alcance}}% Online</h5>
                  </div>
                </footer>
                          </div><!-- -- /darkblue panel ---->
            </div>

            <div class="col-md-4 mb">

            <table class="table">
                  <thead>
                    <tr><th>Registrados</th> <th>{{rpt.total}}</th></tr>
                    <tr><th>Conectados</th> <th>{{rpt.real}}</th></tr>
                    <tr><th></th> <th>{{rpt.alcance}}%</th></tr>
                  </thead>              
                  <tr><td colspan="2"> Actualizado: {{lastUpdate}} </td></tr>
            </table>
            </div>
      
    

      <?php 
      if($esZona){
        ?>

        <div class="panel panel-default">
              <div class="panel-heading">Informacion General <?php echo current($resumen)->Zona; ?></div>
              <div class="panel-body">

                <table class="table">
                  <thead>
                    <tr><th>Tipo Club</th> <th>Cantidad</th></tr>
                  </thead>               
                </table>
              </div>
            </div>

             <div class="panel panel-default">
              <div class="panel-heading">Detalle por Fuerza de Venta.</div>
              <div class="panel-body">
                <table class="table">
                  <thead>
                    <tr><th>Distrito</th> <th>Iglesia</th>  <th>Clubes</th> </tr>
                  </thead>               
                </table>
                
                  
              </div>
            </div>
      <?php 
      }
   ?>

   </div>   
</div>

<div class="panel panel-default">
             <div class="panel-heading"><h3><?php echo '<img src="'. base_url(). 'webApp/img/aventur.png" style=" height: 28px; margin-top: -5px;">'; ?>  Tutorial rápido</h3> 
             </div>
  <div class="panel-body">  
  </div>      
</div>

</div><!-- /col-lg-9 END SECTION MIDDLE -->                 
                  
      <!-- **********************************************************************************************************************************************************
      RIGHT SIDEBAR CONTENT
      *********************************************************************************************************************************************************** -->                  
                  
<div class="col-lg-3 ds">                    

                        <!-- CALENDAR-->
                        <div id="calendar" class="mb">
                            <div class="panel green-panel no-margin">
                                <div class="panel-body">
                                    <div id="date-popover" class="popover top" style="cursor: pointer; disadding: block; margin-left: 33%; margin-top: -50px; width: 175px;">
                                        <div class="arrow"></div>
                                        <h3 class="popover-title" style="disadding: none;"></h3>
                                        <div id="date-popover-content" class="popover-content"></div>
                                    </div>
                                    <div id="my-calendar"></div>
                                </div>
                            </div>
                        </div><!-- / calendar -->

                        <!--COMPLETED ACTIONS DONUTS CHART-->
            <h3>NOTIFICACION</h3>                                        
                      <!-- First Action -->                 
                      <div class="desc">
                        <div class="thumb">
                          <span class="badge bg-theme"><i class="fa fa-clock-o"></i></span>
                        </div>
                        <div class="details">
                          <p><muted> Hoy </muted><br/>
                             <strong>Videos tutoriales </strong> Se han Habilitado dos videos tutoriales nuevos. <br/>
                          </p>
                        </div>
                      </div>                     

                       <!-- USERS ONLINE SECTION -->
                      
</div><!-- /col-lg-3 -->

</div><! --/row -->

<script src="<?php echo base_url(). "webApp/"; ?>js/zabuto_calendar.js"></script>  

<script src="<?php echo base_url(). "webApp/"; ?>js/Chart.js"></script>


 <script type="application/javascript">
        $(document).ready(function () {
            $("#date-popover").popover({html: true, trigger: "manual"});
            $("#date-popover").hide();
            $("#date-popover").click(function (e) {
                $(this).hide();
            });
        
            $("#my-calendar").zabuto_calendar({
                action: function () {
                    return myDateFunction(this.id, false);
                },
                action_nav: function () {
                    return myNavFunction(this.id);
                },
                ajax: {
                    url:  base_url +"ReporteController/eventosCalendario?action=1",
                    modal: true
                },
                legend: [
                    {type: "text", label: "Special event", badge: "00"},
                    {type: "block", label: "Regular event", }
                ]
            });
        });
    
        
    function myDateFunction(id) {
      var date = $("#" + id).data("date");
      var hasEvent = $("#" + id).data("hasEvent");
    }
        
        function myNavFunction(id) {
            $("#date-popover").hide();
            var nav = $("#" + id).data("navigation");
            var to = $("#" + id).data("to");
            console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
        }
    </script>


</div>
