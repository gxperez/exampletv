<style type="text/css">

.img-circle-op {
border-radius: 50%;
/* height: 85px; */
}
  
</style>


<script type="text/javascript" src="<?php echo base_url(). "webApp/"; ?>js/datatransformer/echarts-all.js"></script>
<script type="text/javascript" src="<?php echo base_url(). "webApp/"; ?>js/datatransformer/math.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(). "webApp/"; ?>js/datatransformer/datatransformer.js"></script>
<script type="text/javascript" src="<?php echo base_url(). "webApp/"; ?>js/datatransformer/datatransformer_echart.js"></script>  
  
  
     

 <div  ng-controller="FuentesController" ng-init= "initt(); vCrud.setHash('<?=$csrf["name"];?>', '<?=$csrf["hash"];?>' );">
  <style type="text/css"> </style>
<div class="styleCrud">
<div id="ListMantenimiento">
              <div class="row mt mb">

    <div id="header-crudTools" class="crudTools col-md-12">     
                  <div>
                    <div class="btn-group">                      

                    <button id="btnGuardar" type="submit" class="btn btn-default" ng-click="vCrud.Editar(0)"><span class="fa fa-plus"></span> Agregar </button>
                  </div>
                  </div>
                  </div> 

     <div class="col-md-12">
                      <section class="task-panel tasks-widget">

                    <div class="panel-heading">


            <div class="pull-right">                                  

                            <input type="text" ng-model="buscarLista"  ng-keypress="Buscar($event)" class="round-form" >                            
                            <button type="button" class="btn btn-round btn-default" ng-click="Buscar($event)"><i class="fa fa-search"></i>  </button>
                      </div>
                          
                    

                          <div class="pull-left"> 
                            <h5><i class="fa fa-tasks"></i> {{Pantalla.nombre}}</h5>
                           </div>
                          <br>
                          
                    </div>
                          <div class="panel-body">

                          <div class=" add-task-row">   
                               <div id="page-selection-APP"></div> 
                              </div>

                          
                         

                         

<div ng-repeat="item in listaFuentes|filter:buscarLista:strict" >

 <div class="col-md-3 col-sm-3 mb" ng-if="item.FuenteTipo == '3' " >
                          <div class="darkblue-panel pn">
                            <div class="darkblue-header">
                    <h5>{{item.Descripcion}}  </h5>
                            </div>
                
                <img src="../webApp/img/pie_chart.png" width= "120" height="120px" >
                               
                <footer>
                  <div class="btn pull-left">
                    <h5 ng-click="Llenar(item, $index ); vCrud.Editar(1);" ><i class="fa fa-pencil"></i> Editar</h5>
                  </div>
                  <div class="btn pull-right">
                    <h5 ng-click="Eliminar(item, $index)"> <i class="fa fa-trash-o"></i> Eliminar</h5>
                  </div>
                </footer>
                          </div><!-- -- /darkblue panel ---->
                        </div>


<div class="col-lg-3 col-md-3 col-sm-3 mb" ng-if="item.FuenteTipo !== '3'" >
              <div class="weather-2 pn">
                <div class="weather-2-header">
                  <div class="row">
                    <div class="col-sm-10 col-xs-10">
                      <p>{{item.Descripcion}}</p>
                    </div>
                    <div class="col-sm-2 col-xs-2 goright">
                      <p class="small"><button ng-click="Eliminar(item, $index)" class="close" type="button">×</button> </p>
                    </div>
                  </div>
                </div><!-- /weather-2 header -->
                <div class="row centered">                
                  <div ng-bind-html="PreviewHTMLElement(item)" ></div>                  
                </div>
                <div ng-click="Llenar(item, $index ); vCrud.Editar(1);" class="profile-01 centered">
                  <p>Editar Elemento <i class="fa fa-pencil"></i> </p>
                </div>
                <div class="summary">
                        <strong>18.3 M</strong> <span>Document</span>
                </div>
                
              </div>
            </div>

</div>
          
          
          
                        
                      </section>
                  </div><!--/col-md-12 -->
             </div><!-- /row -->
             </div>	

           <div id="formulario" >        
			 <form id="vform" class="form-horizontal style-form" method="get" data-toggle="validator"  >
          	<!-- BASIC FORM ELELEMNTS -->
          	<div class="row mt">
              <div id="header-crudTools" class="crudTools col-md-12">     
                  <div>
                    <div class="btn-group">                      
                      <button id="btnGuardar" type="submit" class="btn btn-default" ng-click="Guardar()" ><span class="fa fa-floppy-o"> </span> Guardar</button>
                      <button type="reset" class="btn btn-default" ng-click="vCrud.reset();"> <span class="fa fa-arrow-left" > </span> Cancelar</button>
                      <button type="reset" class="btn btn-default" ng-click="vCrud.reset()"> <span class="fa fa-times"> </span> Salir</button>
                  </div>
                  </div>
              </div>              


          		<div class="col-lg-12">
                  <div class="form-panel" ng-form="vCrud.$Form.Main" >
                  	  <h4 class="mb"><i class="fa fa-angle-right"></i> {{Pantalla.nombre}}</h4>

                  	  <div class="form-group">
			<label class="col-sm-2 col-sm-2 control-label">FuenteTipo</label>
			<div class="col-sm-10">
            	<?php  Text::renderOptions('<select ng-model="vCrud.form.FuenteTipo" class="form-control" required>', $listFuenteTipo); ?>
           </div>
</div>

<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">Descripcion</label>
      <div class="col-sm-10">
              <input type="text" ng-model="vCrud.form.Descripcion" class="form-control" required>
           </div>
</div>

<div id="dvSubidaImagenes" ng-if="vCrud.form.FuenteTipo == 1">
  <h4> Integracion con Subida de Documentos</h4>  
</div>
<div class="form-group">
			<label class="col-sm-2 col-sm-2 control-label">FuenteTipoID</label>
			<div class="col-sm-10">
            	<?php  Text::renderOptions('<select ng-model="vCrud.form.FuenteTipoID" class="form-control" >', array('N/A' => 0, )); ?>
           </div>
</div>

<div class="form-group">
			<label class="col-sm-2 col-sm-2 control-label">RepresentacionTipo</label>
			<div class="col-sm-10">
            	<?php  Text::renderOptions('<select ng-model="vCrud.form.RepresentacionTipo" class="form-control" required>', $listRepresentacionTipo); ?>
           </div>
</div>

<div id="tipoURL" ng-if="vCrud.form.FuenteTipo > 2">

  <div class="form-group">
        <label class="col-sm-2 col-sm-2 control-label">Url</label>
        <div class="col-sm-10">
                <input type="text" ng-model="vCrud.form.Url" class="form-control">

                <div ng-click="PreviewOfficeHTML()"  id="url_preview" class="col-sm-12" style="height: 240px; background-color: rgb(227, 227, 227); overflow-y: auto;">                
                  
                </div>
         </div>
  </div>
  
</div>

<div id="textto" ng-if="vCrud.form.FuenteTipo == 2">
    <div class="form-group">
          <label class="col-sm-2 col-sm-2 control-label">ContenidoTexto</label>
          <div class="col-sm-10">                  
                  <textarea ng-model="vCrud.form.ContenidoTexto" class="form-control" > 
                  </textarea>

               </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">GuidRelacionalJson</label>
      <div class="col-sm-10">
              <input type="text" ng-model="vCrud.form.GuidRelacionalJson" class="form-control">
      </div>
</div>
  
</div>

<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">Actualización</label>
      <div class="col-sm-10">

              <?php  Text::renderOptions('<select ng-model="vCrud.form.EsManual" class="form-control" required>', array('Manual' => 1, "Automática"=> 0 )); ?>
           </div>
</div>



<div class="form-group">
			<label class="col-sm-2 col-sm-2 control-label">ContentByID</label>
			<div class="col-sm-10">
            	<input type="text" ng-model="vCrud.form.ContentByID" class="form-control">
      </div>
</div>


<div class="form-group">
			<label class="col-sm-2 col-sm-2 control-label">Estado</label>
			<div class="col-sm-10">
			 <?php  Text::renderOptions('<select ng-model="vCrud.form.Estado" class="form-control" required>', $listEstadoForm); ?>  
           </div>
</div>
                     
                        
                  </div>
          		</div><!-- col-lg-12-->      	
          	</div><!-- /row -->         	
          	  </form>
			</div>
    </div>

    

    