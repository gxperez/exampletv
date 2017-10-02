
<script src="<?php echo base_url(). "webApp/"; ?>js/fancywebsocket.js"></script>

<script type="text/javascript">
  
</script>


<div  ng-controller="WebSocketController" ng-init= "initt(); vCrud.setHash('<?=$csrf["name"];?>', '<?=$csrf["hash"];?>' );">
  <style type="text/css"> </style>
<div class="styleCrud">
<div id="ListMantenimiento">
              <div class="row mt mb">

    <div class="col-md-12">

      

      <section class="task-panel tasks-widget">
    <div class="panel-body">
          <div class="task-content">

<div class="col-sm-12">



<div class="col-sm-6">

<div class="panel panel-default">
             <div class="panel-heading"><h4><img src="<?php echo base_url(); ?>webApp/img/aventur.png" style=" height: 15px; margin-top: -5px;">  TV en Linea  </h4> 
             </div>
  <div class="panel-body">  
  <input type="text" class="form-control ng-pristine ng-valid" ng-model="buscarLista">
  <br> 

  <div style="height: 500px; overflow: scroll; width: 100%;">

  <div class="row ng-scope" ng-repeat="item in liveDisp| filter:buscarLista:strict">
  <div class="col-lg-12 ds">
    <div class="desc" ng-click="setFvMsg(getFV(item.Mac))">

    <div class="col-sm-8">  


                        <div class="thumb">
                          <span id="fa-clock-o" ng-dblclick="SendDobleTocken(item); " class="badge bg-theme til-offline"><i class="fa fa-desktop"></i></span>
                        </div>

                        <div class="details">
                          <p class="ng-binding"> <br>
                          <strong>Mac: </strong> <span class="ng-binding"> {{item.Mac}} </span> <br>
                          <strong>Nombre: </strong>
                        TV-{{item.Mac}}     
                          </p>
                        </div>

   </div>

   <div class="col-sm-4">
      <ul id="sortable-261" class="sortable1-cont droptrue sortable1  ng-pristine ng-valid ui-sortable" >
      <img src="{{getImagenPersonaUrl()}}{{getFV(item.Mac).GUID_FV}} " width="40">
      <br>
      {{getFV(item.Mac).FuerzaVenta}}
      
    </ul>          
  </div>
</div>
</div>
  </div>


  </div> 

  
  
  </div>      
</div>



  
</div>


<div class="col-sm-6">

<div class="panel panel-default">
             <div class="panel-heading"><h4> <li class="fa fa-rss"></li> broadcast messanges</h4> 
             </div>
  <div class="panel-body"> 
  <div ng-if="fvSend == false">
  <p>Seleccione un TV en linea.</p>    
  <strong>Alcance: </strong> TODOS
  </div>

  <div ng-if="fvSend != false">
  <div class="col-md-8">
  <strong>Alcance: </strong> {{fvSend.FuerzaVenta}} <br>
  <strong>TV: </strong> {{fvSend.Mac}}
  </div>
  <div  class="col-md-4">
    <img src="{{getImagenPersonaUrl()}}{{fvSend.GUID_FV}} " width="65">
  </div>
  

  </div>

  
  <hr>

  <strong>Tipo Msg:</strong>  <select ng-model="TipoMsg">
   <option value="0"> Texto Cinta Marquee </option> 
   <option value="1"> Texto News Flash </option>
   <option value="2"> Twits FV </option>
   </select>
   <hr>
   <textarea id="log" name="log" readonly="readonly" style=" border: 1px solid #CCC; margin: 0px; padding: 0px; height: 80px; width: 100%">
  </textarea>

  <div ng-if="TipoMsg== 0 || TipoMsg== 1">
  
  <table>
  <tr>
      <td>Titulo-1: <input type="text" ng-model="tt-1"> </td> <td>Css: <input type="text" ng-model="tcss-1"></td>
  </tr>
  <tr>
      <td>Titulo-2: <input type="text" ng-model="tt-2"> </td> <td>Css: <input type="text" ng-model="tcss-2"></td>
  </tr>    
  </table>
  

</div>

   <input ng-model="Msgs" type="text" id="message" name="message" class="form-control" ng-keypress="enviarMensaje($event)" >

  </div>      
</div>

<h5> Fuerza Venta</h5>  

</div>
</div>
</div>

        
      



      </div>
     </section></div><!--/col-md-12 -->

            </div><!-- /row -->
             </div> 

    </div>

    <script type="text/javascript">

    var JFData = <?php echo $fuerzaVentaData;  ?>;

    var vw_listaGrupoTv = <?php echo json_encode($listaGrupoTv); ?>;

    $('#fvOculto').hide(); 
      
    </script>