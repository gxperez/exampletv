<div  ng-controller="DispositivoController" ng-init="initt(); vCrud.setHash('<?=$csrf['name'];?>', '<?=$csrf['hash'];?>'); " >
  <style type="text/css">   
</style>


<div class="styleCrud">
<div id="ListMantenimiento">
	<!-- SIMPLE TO DO LIST -->
    <!-- SORTABLE TO DO LIST -->
      
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
                              <div class="task-content">
                                  <ul id="sortable" class="task-list">
                                      <li ng-repeat="item in listaDispositivo|filter:buscarLista:strict" class="list-primary">
                                          <i class=" fa fa-ellipsis-v"></i>
                                          <div class="task-checkbox">
                                              <input type="checkbox" class="list-child" value=""  />
                                          </div>
                                          <div class="task-title">
                                              <span class="task-title-sp">{{item.Descripcion}}</span>
                                              <div class="pull-right hidden-phone">                                                  
                                            <button class="btn btn-primary btn-xs fa fa-pencil" ng-click="Llenar(item, $index ); vCrud.Editar(1);"></button>
                                                  <button class="btn btn-danger btn-xs fa fa-trash-o" ng-click="Eliminar(item, $index)"></button>
                                              </div>
                                          </div>
                                      </li>

                                  </ul>
                              </div>
                              <div class=" add-task-row">   
                               <div id="page-selection-APP"></div> 

                                  <a class="btn btn-default btn-sm pull-right" ng-click="ListAll()">Ver Todo</a>
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
                              <label class="col-sm-2 col-sm-2 control-label">Nombre</label>
                              <div class="col-sm-10">
                                  <input type="text" ng-model="vCrud.form.Nombre" class="form-control" required>
                              </div>
                       </div>

                        <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Descripcion</label>
                              <div class="col-sm-10">
                                  <input type="text" ng-model="vCrud.form.Descripcion" class="form-control">
                              </div>
                          </div>

                      <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label" >DispositivoTipo</label>
                              <div class="col-sm-10">                              

                                  <?php  Text::renderOptions('<select ng-model="vCrud.form.DispositivoTipo" class="form-control" required>', $dispositivoTipos); ?>                                  
                              </div>
                       </div>

                       <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Marca</label>
                              <div class="col-sm-10">
                                  <input type="text" ng-model="vCrud.form.Marca" class="form-control">
                              </div>
                       </div>


                       <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label" >Estatus</label>
                              <div class="col-sm-10">                                

                                  <?php  Text::renderOptions('<select ng-model="vCrud.form.Estado" class="form-control" required>', $listEstadoForm); ?>                                    
                        </select>

                              </div>                              
                       </div>

                       <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Mac</label>
                              <div class="col-sm-10">
                                  <input type="text" ng-model="vCrud.form.Mac" class="form-control">
                              </div>                              
                       </div>
                  </div>
          		</div><!-- col-lg-12-->      	
          	</div><!-- /row -->         	
          	  </form>
			</div>
    </div>

      <script>
      jQuery(document).ready(function() {
          TaskList.initTaskWidget();
      });

      $(function() {
          $( "#sortable" ).sortable();
          $( "#sortable" ).disableSelection();
      });

    </script>



</div>