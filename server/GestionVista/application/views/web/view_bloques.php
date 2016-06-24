<link href="<?php echo base_url(). "webApp/"; ?>js/fullcalendar/bootstrap-fullcalendar.css" rel="stylesheet">

<style type="text/css">

.Diario {
background-color: rgb(220, 239, 239);
}

.Lunes {}
.Martes {}
.Miercoles {}
.Jueves {}
.Viernes {}
.Sabado {}
.Domingo {}
.Lu-Vi {
background-color: rgb(243, 243, 243);
}

.L-M-V {
background-color: rgb(229, 253, 229);

}

.K-J-S {
background: rgb(255, 238, 238);
}

.Vi-Sa-Do {
	background-color: #E8EAE3;
}

.k-J {
background-color: rgb(236, 236, 214);
}


	
</style>

<div  ng-controller="MasterBloquesController" ng-init= "master(); vCrud.setHash('<?=$csrf["name"];?>', '<?=$csrf["hash"];?>' );">


		

<div id="CanalBloque">        	
          	<!-- BASIC FORM ELELEMNTS -->
    <div class="row mt">              


    <div class="col-lg-12">
                  
                  	  

       <div class="col-sm-3">
                  	   <div class="showback" >
                  	   <h4><i class="fa fa-angle-right"></i> Bloques <i ng-click="AgregarBloque()" class="fa fa-plus"> </i></h4>

                  	  <div class="col-sm-12">

                  	  <span class="fa fa-search" style="
    position: absolute;
    right: 21px;
    top: 11px;
"></span>
                  	  <input type="text" ng-model="bBloque" class="form-control"></input>
                  	   </div>

                  	   
                  	   <div style="min-height: 500px;">
                  	   <hr>                  	   
                  	   <div> &nbsp; </div>

                  	    <div class="btn-group" ng-repeat="item in bloques|filter:bBloque">
						  <button type="button" class="btn btn-theme dropdown-toggle" data-toggle="dropdown">
						   <span>blq_{{item.FrecuenciaTipoDesc}}-{{item.BloqueID}}</span>  <span class="caret"></span>
						  </button>
						  <ul class="dropdown-menu" role="menu">
						    <li><a href="#">Editar</a></li>
						    <li><a href="#">Filtrar</a></li>						    
						    <li class="divider"></li>
						    <li><a href="#">Eliminar</a></li>
						  </ul>
						</div>	


                  	   	
                  	   </div>
                  	   </div>                  	   
                  	   	
                  	   </div>

                   <div class="col-sm-9">
                  	   <div class="showback">
                  	   <div style="min-height: 530px;">
                  	   <h4><i class="fa fa-angle-right"></i> Bloques Semanal </h4>

                  	   <div class="fc-view fc-view-basicWeek fc-grid" style="position: relative; display: block;" unselectable="on">

                  	  <table class="fc-border-separate" style="width:100%" cellspacing="0">
                  	   <thead>
                  	   <tr class="fc-first fc-last">
                  	   <th class="fc-sun fc-widget-header fc-first" style="width: 104px;">Domingo</th>
                  	   <th class="fc-mon fc-widget-header" style="width: 104px;">Lunes</th>
                  	   <th class="fc-tue fc-widget-header" style="width: 104px;">Martes</th>
                  	   <th class="fc-wed fc-widget-header" style="width: 104px;">Miercoles</th>
                  	   <th class="fc-thu fc-widget-header" style="width: 104px;">Jueves</th>
                  	   <th class="fc-fri fc-widget-header" style="width: 104px;">Viernes</th>
                  	   <th class="fc-sat fc-widget-header fc-last">Sabado</th>
                  	   </tr>
                  	   </thead>

                  	   <tbody>

                  	 <tr class="fc-week0 fc-first fc-last">
                  	 <td class="fc-sun fc-widget-content fc-day0 fc-first">

                  	   <div style="min-height: 528px;">

                  	   <div class="fc-day-content">
                  	   <div ng-repeat="dia1 in listaBloques[1]" class="{{dia1.FrecuenciaTipoDesc}}" style="min-height: 88px; border-bottom: 1px solid #D8DAD4;"><div class="fc-day-number">{{dia1.Horario}}</div>
                  	   <div class="fc-day-content">
                  	   <div style="position: relative; height: 18px;">&nbsp;</div>
                  	   </div>
                  	   </div>
                  	   
                  	   </div>
                  	   </div>

                  	 </td>
                  	 <td class="fc-mon fc-widget-content fc-first">

                  	 <div style="min-height: 528px;">
                  	 <div class="fc-day-content">

                  	 <div ng-repeat="dia in listaBloques[2]"  class="{{dia.FrecuenciaTipoDesc}}" style="min-height: 88px; border-bottom: 1px solid #D8DAD4;"><div class="fc-day-number">{{dia.Horario}}</div>
                  	   <div class="fc-day-content">
                  	   <div style="position: relative; height: 18px;">&nbsp;</div>                  	   
                  	   </div>
                  	 </div>

                  	   
                  	  </div>
                  	   </div>

                  	 </td>
                  	 <td class="fc-tue fc-widget-content fc-day2">

                  	 <div style="min-height: 528px;">
                  	 <div class="fc-day-content">

                  	 
                  	 <div ng-repeat="dia in listaBloques[3]"  class="{{dia.FrecuenciaTipoDesc}}" style="min-height: 88px; border-bottom: 1px solid #D8DAD4;"><div class="fc-day-number">{{dia.Horario}}</div>
                  	   <div class="fc-day-content">
                  	   <div style="position: relative; height: 18px;">&nbsp;</div>                  	   
                  	   </div>
                  	 </div>

                  	 </div>
                  	 </div>
                  	 </td>

                  	 <td class="fc-wed fc-widget-content fc-day3">

                  	 <div style="min-height: 528px;" ><div class="fc-day-content">

                  	 
                  	 <div ng-repeat="dia in listaBloques[4]"  class="{{dia.FrecuenciaTipoDesc}}" style="min-height: 88px; border-bottom: 1px solid #D8DAD4;"><div class="fc-day-number">{{dia.Horario}}</div>
                  	   <div class="fc-day-content">
                  	   <div style="position: relative; height: 18px;">&nbsp;</div>                  	   
                  	   </div>
                  	 </div>


                  	 </div></div>
                  	 </td>

                  	 <td class="fc-thu fc-widget-content fc-day4 fc-state-highlight fc-today">

                  	 <div style="min-height: 528px;">
                  	 <div class="fc-day-content">

                  	 
                  	 <div ng-repeat="dia in listaBloques[5]"  class="{{dia.FrecuenciaTipoDesc}}" style="min-height: 88px; border-bottom: 1px solid #D8DAD4;"><div class="fc-day-number">{{dia.Horario}}</div>
                  	   <div class="fc-day-content">
                  	   <div style="position: relative; height: 18px;">&nbsp;</div>                  	   
                  	   </div>
                  	 </div>
                  	 </div>

                  	 </div>
                  	 </td>

                  	 <td class="fc-fri fc-widget-content fc-day5">

                  	 <div style="min-height: 528px;" >
                  	 <div class="fc-day-content">


                  	 <div ng-repeat="dia in listaBloques[6]"  class="{{dia.FrecuenciaTipoDesc}}" style="min-height: 88px; border-bottom: 1px solid #D8DAD4;"><div class="fc-day-number">{{dia.Horario}}</div>
                  	   <div class="fc-day-content">
                  	   <div style="position: relative; height: 18px;">&nbsp;</div>                  	   
                  	   </div>
                  	 </div>

                  	 
                  	 </div></div>
                  	 </td>

                  	 <td class="fc-sat fc-widget-content fc-day6 fc-last">
                  	 <div style="min-height: 528px;">

                  	 <div class="fc-day-content">


                  	 <div ng-repeat="descanso in listaBloques[7]"  class="{{descanso.FrecuenciaTipoDesc}}" style="min-height: 88px; border-bottom: 1px solid #D8DAD4;"><div class="fc-day-number">{{descanso.Horario}}</div>
                  	   <div class="fc-day-content">
                  	   <div style="position: relative; height: 18px;">&nbsp;</div>                  	   
                  	   </div>
                  	 </div>

                  	 
                  	 </div>
                  	 </div>
                  	 </td>

                  	 </tr>

                  	   </tbody>
                  	   </table>

                  	   </div>

                  	   
						
                  	   </div>                  	   	
                  	   </div>
                  	 </div>
                        
					</div><!-- col-lg-12-->      	
          		</div> <!-- /row -->  
          	</div>


<div id="myModal" class="" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">        
        <h4 class="modal-title">Seleccione la Programación</h4>
      </div>
      <div class="modal-body">
        
        <div class="task-content">
            <table class="table table-bordered table-striped table-condensed cf">

            <thead class="cf">
                                  <tr>
                                  	  <th></th>
                                      <th>Programa</th>
                                      <th>Fecha Inicio</th>
                                      <th>Fecha Fin</th>                                     
                                  </tr>
                                  </thead>

<tbody>

<?php
 if(count($listaProgramacion) == 0) { ?>
<tr> <td></td> <td colspan="3"> <h4> No hay programaciones Activias </h4> </td> </tr>

<?php } ?>
            <?php foreach ($listaProgramacion as $key => $value) {        	            ?>
            <tr>

            <td>
            <button type="button" class="btn btn-round btn-info" ng-click="AbrirPrograma(<?php echo $value->ProgramacionID;  ?>); "> <span class="fa fa-folder-open-o"></span> Abrir</button>
            	
            </td>

                <td>
                   <div class="task-title">
                       <span class="task-title-sp"> <?php echo $value->Descripcion; ?> </span>
                   </div>
                </td>

                <td>
                   <div class="task-title">
                       <span class="task-title-sp"> <?php echo $value->FechaEjecutaInicio; ?> </span>
                   </div>
                </td>
                <td>
                   <div class="task-title">
                       <span class="task-title-sp"> <?php echo $value->FechaEjecutaFin; ?> </span>
                   </div>
                </td>

               </tr>
                <?php }  ?>
                </tbody>
            </table>
         </div>
        
      </div>
      <div class="modal-footer">              
      </div>
    </div>

  </div>
</div>


<div id="bloqueform" title="Crear Bloque">
<div>        
			 <form id="vform" class="form-horizontal style-form" method="get" data-toggle="validator"  >
          	<!-- BASIC FORM ELELEMNTS -->
          		<div class="col-lg-12">
                  
                  	  <h4 class="mb"><i class="fa fa-angle-right"></i> {{Pantalla.nombre}}</h4>
                  	 
<div class="form-group">
			<label class="col-sm-2 col-sm-2 control-label">FrecuenciaTipo</label>
			<div class="col-sm-10">
            	<?php  Text::renderOptions('<select ng-model="frmBloque.form.FrecuenciaTipo" class="form-control" required>', $listFrecuenciaTipo); ?>
           </div>
</div>

<div class="form-group">
			<label class="col-sm-2 col-sm-2 control-label">Estado</label>
			<div class="col-sm-10">
			<?php  Text::renderOptions('<select ng-model="frmBloque.form.Estado" class="form-control" required>', $listEstadoForm); ?>              	
           </div>
</div>

<div class="form-group">
			<label class="col-sm-2 col-sm-2 control-label">HoraInicio</label>
			<div class="col-sm-10">
            	<input id="HoraInicio" type="text" ng-model="frmBloque.form.HoraInicio" class="form-control" required>
           </div>
</div>
<div class="form-group">
			<label  class="col-sm-2 col-sm-2 control-label">HoraFin</label>
			<div class="col-sm-10">
            	<input id="HoraFin" type="text" ng-model="frmBloque.form.HoraFin" class="form-control" required>
           </div>
</div>   

<div class="modal-footer">              
<button class="btn btn-success" type="button" ng-click="frmBloque.guardar()"> Guardar </button>
<button class="btn btn-danger" type="button" ng-click="frmBloque.cancel()"> Cancelar </button>
      </div>            
          		</div> <!-- col-lg-12-->      	
          	      	
          	  </form>
			</div>
 </div>



</div>




<script type="text/javascript">


var vw_listaProgramas = <?php echo json_encode($listaProgramacion);  ?>;


/*


	jQuery('#HoraInicio').datetimepicker({
		format:'Y-m-d'
// inputFormat: 'H:m'
});

	jQuery('#HoraFin').datetimepicker({
		format:'Y-m-d'
// inputFormat: 'H:m'
});

*/





jQuery(function(){

 jQuery('#HoraInicio').datetimepicker({
datepicker:false,
	mask:'29:59:59',
  format:'H:i:s', 	
  // format:'Y-m-d',  
  onShow:function( ct ){
  },

  onSelectTime: function(){
  	myDate  = new Date();
  	myDate2 = new Date();
  	tim = jQuery('#HoraInicio').val()?jQuery('#HoraInicio').val().split(":"):false;   	
  	tim2 = jQuery('#HoraFin').val()?jQuery('#HoraFin').val().split(":"):false;

  	if(tim !== false && tim2 !== false){
  		myDate.setHours(tim[0], tim[1], tim[2]);
  		myDate2.setHours(tim2[0], tim2[1], tim2[2]);
  		if(myDate2 <= myDate) {return jQuery('#HoraInicio').val(''); }
  	}
  }
 // timepicker:false
 });
 jQuery('#HoraFin').datetimepicker({
 	datepicker:false,
 	mask:'29:59:59',
  	format:'H:i:s',  
  onShow:function( ct ){

   this.setOptions({
    minTime:jQuery('#HoraInicio').val()?jQuery('#HoraInicio').val():false
   })
  },

  onSelectTime: function(){
  	myDate  = new Date();
  	myDate2 = new Date();
  	tim = jQuery('#HoraInicio').val()?jQuery('#HoraInicio').val().split(":"):false;   	
  	tim2 = jQuery('#HoraFin').val()?jQuery('#HoraFin').val().split(":"):false; 

  	if(tim !== false && tim2 !== false){
  		myDate.setHours(tim[0], tim[1], tim[2]);
  		myDate2.setHours(tim2[0], tim2[1], tim2[2]);
  		if(myDate2 <= myDate) {return jQuery('#HoraFin').val(''); }
  	}
  }
  
 });
});


// 605 *450

	
</script>




</div>