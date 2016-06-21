<div  ng-controller="FuerzaVentaDispositivoController" ng-init= "panel(); vCrud.setHash('<?=$csrf["name"];?>', '<?=$csrf["hash"];?>' );">
  <style type="text/css">

  h1, .h1, h2, .h2, h3, .h3 {
	margin-top: -2px;
	margin-bottom: 8px;
	}


.ds .desc {
border-bottom: 1px solid #eaeaea;
display: inline-block;
padding: 0px 0;
width: 100%;
}

.til-offline {
background-color: gray;
}
.til-online {
background-color: green;
}

.sortable1 {
list-style-type: none;
margin: 0;
padding: 0;
margin-right: 0px;
background: #eee;
padding: 5px;
width: 120px;
min-height: 50px;
}

.sortable1 li {
margin: 2px;
padding: 2px;
font-size: 12px;
width: 112px;
}

.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
border: 1px solid #d3d3d3;
background: #e6e6e6 url("../webApp/img/jqueryui/ui-bg_glass_75_e6e6e6_1x400.png") 50% 50% repeat-x !important;
font-weight: normal;
color: #555555;
}

   </style>

  <div class="row mt mb">
  		<div class="col-md-12">

			

			<section class="task-panel tasks-widget">
		<div class="panel-body">
      		<div class="task-content">

<div class="col-sm-12">


<fieldset>
<legend>Dispositivos | Fuerza Venta</legend>
</fieldset>
<div class="col-sm-6">



<div class="col-sm-12">
      			   <label class="col-sm-7 "></label>
					<div class="col-sm-5">					
	            	<input type="text" class="form-control" ng-model="buscarLista"></input>
	           		</div>
	</div>

  <div  style="height: 500px; overflow: scroll; width: 100%;">

  <div class="row" ng-repeat="item in listaDispositivo| filter:buscarLista:strict">
<div class="col-lg-12 ds">

<div class="desc">
<div class="col-sm-8">  


                        <div class="thumb">
                          <span id="fa-clock-o" class="badge bg-theme {{validateOnline(item.Mac)}}"><i class="fa fa-desktop"></i></span>
                        </div>

                        <div class="details">
                          <p> <br>
                          <strong>Mac: </strong> <span> {{item.Mac}} </span> <br>
                          <strong>Nombre: </strong>
                        {{item.Nombre}}     
                          </p>
                        </div>

   </div>

   <div class="col-sm-4">
      <ul id="sortable-{{item.DispositivoID}}" class="sortable1-cont droptrue sortable1 " ui-sortable="dropzone" ng-model="dropzoneFields[item.DispositivoID]">   
      <li ng-repeat="t in dropzoneFields[item.DispositivoID]" id="ruta_{{item.DispositivoID}}" class="ui-state-default ng-binding ng-scope ui-sortable-handle"> <div class="fa fa-times-circle" style="
    position: absolute;
    top: 2px;
    left: 4px;
    font-size: 14px;    
" ng-click="eliminarVinculoFV(item, t);"></div>  {{t.FuerzaVenta}}</li>         
                                            
    </ul>
    <strong>{{item.FuerzaVenta}}</strong>
      
  </div>
</div>
</div>
</div>  

  </div>

</div>


<div class="col-sm-6">
<h5> Fuerza Venta</h5>	

<div class="form-group ">
      			<div class="col-sm-12">
					<label class="col-sm-2 col-sm-2 control-label">Nivel</label>
					<div class="col-sm-12">
	            		<?php  Text::renderOptions('<select ng-model="vCrud.form.Nivel" class="form-control" required>', $nivelTipos); ?>
	           		</div>
      			</div>
 </div> 

<div ng-repeat="(k, val) in listaFuerzaVentaCopy| filter:buscarFV:strict"  ng-if="(vCrud.form.Nivel.toString() == k.toString() )">

<aside class="col-lg-12 mt">
<div class="row">
<div class="col-sm-7">
	<h4><i class="fa fa-angle-right"></i> Nivel {{k}}</h4>
</div>
<div class="col-sm-5">
	<input type="text" class="form-control" ng-model='buscarFV'>	
</div>
	
</div>
                      
                      <div id="taken-events">                          
                          <div ng-repeat="(kis, item2) in dropzoneFields| filter:buscarFV:strict" class="external-event label label-success" style="position: relative; z-index: auto; left: 0px; top: 0px;" ng-if="validarFvSelected(item2)" ng-click='clickAutoSearch(kis);'>{{item2[0].FuerzaVenta}}</div>
                          
                      </div>
                      

                      <div ui-sortable="sortableOptions" ng-model="listaFuerzaVentaCopy[k]" id="external-events">                          
                          <div ng-repeat="item in val| filter:buscarFV:strict" class="external-event label {{selectedClassNivel(item)}} ui-draggable" style="position: relative; z-index: auto; left: 0px; top: 0px;" id='GUID_FV;{{item.GUID_FV}}'  >{{item.FuerzaVenta}}</div>
                          <p class="drop-after">
                          </p>
                      </div>
                  </aside>

<div></div>

	
</div>


</div>


</div>

<div class="col-sm-5">
<h5> FV </h5>	
</div>


</div>

      	
      



      </div>
     </div>


	</section>






	</div>

  </div>
</div>

<script type="text/javascript">
var JSONData = <?php echo $dispositivosData; ?>; 

var JFData = <?php echo $fuerzaVentaData;  ?>

var JResentDis = <?php echo $resentDispositivo; ?>

$(function() {


}); 

 

	
</script>