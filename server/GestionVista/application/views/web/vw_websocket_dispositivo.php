
<script src="<?php echo base_url(). "webApp/"; ?>js/fancywebsocket.js"></script>

<script type="text/javascript">
alert("Se ha Rednericoa"); 
  
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


<fieldset>
<legend>Dispositivos en Linea.  <span class="fa fa-wifi" ></span></legend> 
</fieldset>

<div class="col-sm-6">



<div class="col-sm-12">
               <label class="col-sm-7 "></label>
          <div class="col-sm-5">          
                <input type="text" class="form-control ng-pristine ng-valid" ng-model="buscarLista">
                </div>
  </div>

  <div style="height: 500px; overflow: scroll; width: 100%;">

  <!-- ngRepeat: item in listaDispositivo| filter:buscarLista:strict -->
<div class="row ng-scope" ng-repeat="item in listaDispositivo| filter:buscarLista:strict">
<div class="col-lg-12 ds">

<div class="desc">
<div class="col-sm-8">  


                        <div class="thumb">
                          <span id="fa-clock-o" ng-dblclick="SendDobleTocken(item); " class="badge bg-theme til-offline"><i class="fa fa-desktop"></i></span>
                        </div>

                        <div class="details">
                          <p class="ng-binding"> <br>
                          <strong>Mac: </strong> <span class="ng-binding"> 52:camre:25:tu:85no:am:246 </span> <br>
                          <strong>Nombre: </strong>
                        TV-SONY-smart-gen246     
                          </p>
                        </div>

   </div>

   <div class="col-sm-4">
      <ul id="sortable-261" class="sortable1-cont droptrue sortable1  ng-pristine ng-valid ui-sortable" ui-sortable="dropzone" ng-model="dropzoneFields[item.DispositivoID]">   
      <!-- ngRepeat: t in dropzoneFields[item.DispositivoID] -->         
    </ul>    
      
  </div>
</div>
</div>
</div>


<div class="row ng-scope" ng-repeat="item in listaDispositivo| filter:buscarLista:strict">
<div class="col-lg-12 ds">

<div class="desc">
<div class="col-sm-8">  


                        <div class="thumb">
                          <span id="fa-clock-o" ng-dblclick="SendDobleTocken(item); " class="badge bg-theme til-offline"><i class="fa fa-desktop"></i></span>
                        </div>

                        <div class="details">
                          <p class="ng-binding"> <br>
                          <strong>Mac: </strong> <span class="ng-binding"> 222-222-2222 </span> <br>
                          <strong>Nombre: </strong>
                        TV-222-222-2222     
                          </p>
                        </div>

   </div>

   <div class="col-sm-4">
      <ul id="sortable-345" class="sortable1-cont droptrue sortable1  ng-pristine ng-valid ui-sortable" ui-sortable="dropzone" ng-model="dropzoneFields[item.DispositivoID]">   
      <!-- ngRepeat: t in dropzoneFields[item.DispositivoID] -->         
    </ul>    
      
  </div>
</div>
</div>
</div><!-- end ngRepeat: item in listaDispositivo| filter:buscarLista:strict -->  

  </div>

</div>


<div class="col-sm-6">
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

    $('#fvOculto').hide(); 
      
    </script>