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

<div class="row" ng-repeat="item in listaDispositivo| filter:buscarLista:strict">
<div class="col-lg-12 ds">

<div class="desc">
<div class="col-sm-8">	


                      	<div class="thumb">
                      		<span class="badge bg-theme til-offline"><i class="fa fa-clock-o"></i></span>
                      	</div>

                      	<div class="details">
                      		<p><muted>2 Minutes Ago</muted><br>
                      		<strong>Mac: </strong> <span> {{item.Mac}} </span> <br>
                      		<strong>Nombre: </strong>
                      	{{item.Nombre}}	    
                      		</p>
                      	</div>

   </div>

   <div class="col-sm-4">
    	<ul id="sortable-1" class="sortable1-cont droptrue sortable1 " ui-sortable="dropzone" ng-model="dropzoneFields">   
    	<li ng-repeat="t in dropzoneFields" id="ruta_83" class="ui-state-default ng-binding ng-scope ui-sortable-handle"> {{t.FuerzaVenta}}</li>       	
                                            
    </ul>
		<strong>{{item.FuerzaVenta}}</strong>
		  
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

<div ng-repeat="(k, val) in listaFuerzaVenta| filter:buscarFV:strict"  ng-if="(vCrud.form.Nivel.toString() == k.toString() )">

<aside class="col-lg-12 mt">
<div class="row">
<div class="col-sm-7">
	<h4><i class="fa fa-angle-right"></i> Nivel {{k}}</h4>
</div>
<div class="col-sm-5">
	<input type="text" class="form-control" ng-model='buscarFV'>	
</div>
	
</div>
                      
                      

                      <div ui-sortable="sortableOptions" ng-model="listaFuerzaVentaCopy[k]" id="external-events">                          
                          <div ng-repeat="item in val| filter:buscarFV:strict" class="external-event label {{selectedClassNivel(item)}} ui-draggable" style="position: relative; z-index: auto; left: 0px; top: 0px;">{{item.FuerzaVenta}}</div>
                         
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

$(function() {


}); 

 

	
</script>