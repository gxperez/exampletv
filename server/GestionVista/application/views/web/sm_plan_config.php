<div  ng-controller="PlanConfigController" ng-init= "initt(); vCrud.setHash('<?=$csrf["name"];?>', '<?=$csrf["hash"];?>' );">
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
                              <div class="task-content">
                                  <ul id="sortable" class="task-list">
                                      <li ng-repeat="item in listaPlanConfig|filter:buscarLista:strict" class="list-primary">
                                          <i class=" fa fa-ellipsis-v"></i>
                                          <div class="task-checkbox">
                                              <input type="checkbox" class="list-child" value=""  />
                                          </div>
                                          <div class="task-title">
                                              <span class="task-title-sp">{{item.Titulo}}</span>
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
               <div class="col-sm-12">     
                  <div class="form-panel" ng-form="vCrud.$Form.Main" >
                  	  <h4 class="mb"><i class="fa fa-angle-right"></i> {{Pantalla.nombre}}</h4>

                  	  <div class="form-group">
			<label class="col-sm-2 col-sm-2 control-label">Calendario </label>
			<div class="col-sm-10">
            	<input type="int" ng-model="vCrud.form.CalendarioID" class="form-control" >
           </div>
</div>
<div class="form-group">
			<label class="col-sm-2 col-sm-2 control-label">Titulo</label>
			<div class="col-sm-10">
            	<input type="text" ng-model="vCrud.form.Titulo" class="form-control" >
           </div>
</div>
<div class="form-group">
			<label class="col-sm-2 col-sm-2 control-label">Vision</label>
			<div class="col-sm-10">
      <textarea ng-model="vCrud.form.Vision" class="form-control" rows="3"></textarea>            	
           </div>
</div>
<div class="form-group">
			<label class="col-sm-2 col-sm-2 control-label">Misión</label>
			     <div class="col-sm-10">
              <textarea ng-model="vCrud.form.Mision" class="form-control" rows="3"></textarea>            	
           </div>
</div>
<div class="form-group">
			<label class="col-sm-2 col-sm-2 control-label">Estado</label>
			<div class="col-sm-10">
      <?php  Text::renderOptions('<select ng-model="vCrud.form.Estado" class="form-control" required>', $listEstadoForm); ?>                 	
           </div>
</div>
                     
                        
                  </div>
                </div>

<div id="dialogbox" title="Editar Centro" class="col-sm-12">                    
                    <!-- Tabs -->                    
                    <div id="tabs">
                        <ul>
                            <li><a href="#tabs-1">Objetivos</a></li>

                            <li><a href="#tabs-2"> Configuracion Informes</a></li>
                        </ul>
                        <div id="tabs-1"> 

                        <button type="button" class="btn btn-success btn-xs" alt="agregar" ng-click="agregarobjetvo()" >
                        <span class="fa fa-plus"></span>                          
                        </button>                           
                            <hr />  

                            <table class="table table-hover">
                                <thead>
                                <tr>                                    
                                    <th>#</th>
                                    <th>Categoria</th>
                                    <th>Descripcion</th>
                                </tr>
                                </thead>
                                <tbody ng-repeat="obj in listaObjetivos" class="ng-scope">
                                <tr>
                                    
                                    <td>{{obj.Secuencia}}</td>
                                    <td> {{obj.Categoria}}</td>
                                    <td> {{obj.Descripcion}}</td>
                                 </tr>

                                 <tr ng-repeat="especifi in obj.especifico">
                                  <td> &nbsp; &nbsp; {{especifi.Secuencia}} </td>
                                  <td> &nbsp; &nbsp; {{especifi.Categoria}} </td>
                                  <td> {{especifi.Descripcion}} </td>
                                 </tr>
                                    </tbody>
                            </table>

                            <div id="objetivoFormulario">
         <div class="form-group">

        <label class="col-sm-2 col-sm-2 control-label">Tipo Objetivo</label>
                <div class="col-sm-10">
                        <select class="form-control" ng-model="objetivos.Tipo" ng-change="BuscarPendiente()">
                        <option value="1"> General </option>
                        <option value="2"> Especifico</option>
                         </select>
                     </div>
          </div>


          <div class="form-group" id="content-objs">
              <label class="col-sm-2 col-sm-2 control-label">DependenciaID</label>
              <div class="col-sm-10">

              <select ng-model="objetivos.dependencia" class="form-control">
                <option>---</option>                
                <option ng-repeat="dp in listaObjetivos" value="dp.PlanConfigObjetivoID"> {{dp.Descripcion}}      </option>                
              </select>
              </div>
          </div>

<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">Categoria</label>
      <div class="col-sm-10">
              <input type="text" ng-model="objetivos.Categoria" class="form-control" >
           </div>
</div>
<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">Descripcion</label>
      <div class="col-sm-10">
              <input type="text" ng-model="objetivos.Descripcion" class="form-control" >
           </div>
</div>
<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">Peso</label>
      <div class="col-sm-10">
              <input type="text" ng-model="objetivos.Peso" class="form-control" >
           </div>
</div>
<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">Secuencia</label>
      <div class="col-sm-10">
              <input type="int" ng-model="objetivos.Secuencia" class="form-control" >
           </div>
</div>
<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">OrganigramaTipo</label>
      <div class="col-sm-10">
              <?php  Text::renderOptions('<select ng-model="vCrud.form.OrganigramaTipo" class="form-control" required>', $listOrganigramaTipo); ?>
           </div>
</div>
<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">FechaLimite</label>
      <div class="col-sm-10">
              <input id="fechaLimite" type="text" ng-model="objetivos.FechaLimite" class="form-control" >
           </div>
</div>
<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">RequiereInforme</label>
      <div class="col-sm-10">      
                                      <input class="form-control" type="checkbox" ng-model="objetivos.RequiereInforme" data-toggle="switch"  />
                                  
              
           </div>
</div>
<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">FrecuenciaInforme</label>
      <div class="col-sm-10">
       <?php  Text::renderOptions('<select ng-model="objetivos.FrecuenciaInforme" class="form-control" required>', array('Semanal' =>1 , 'Mensual' =>2, 'Trimestral' =>3, 'Cuatrimestral'=> 4, "Semestral"=> 5, "Anual"=> 6  ) ); ?>

              
           </div>
</div>


<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">Estado</label>
      <div class="col-sm-10">
        <?php  Text::renderOptions('<select ng-model="objetivos.Estado" class="form-control" required>', $listEstadoForm); ?> 
       </div>

</div>

<div>
<button ng-click="guardarObjetivo()"> Guardar </button>
<button ng-click="cerrarDialog(); "> Cancerlar </button>
</div>

</div>


                            
                        </div>
                        
                        <div id="tabs-2">                            

                            <hr /> 

                            
                            <div class="col-sm-12">
                  
                
<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">Encabezado</label>
      <div class="col-sm-10">
              <input type="text" ng-model="vCrud.form.Encabezado" class="form-control" >
           </div>
</div>
<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">Descripcion</label>
      <div class="col-sm-10">
              <input type="text" ng-model="vCrud.form.Descripcion" class="form-control" >
           </div>
</div>
<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">FrecuenciaTipo</label>
      <div class="col-sm-10">
              <?php  Text::renderOptions('<select ng-model="vCrud.form.FrecuenciaTipo" class="form-control" required>', array()); ?>
           </div>
</div>
<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">FecuenciaTipoIni</label>
      <div class="col-sm-10">
              <?php  Text::renderOptions('<select ng-model="vCrud.form.FecuenciaTipoIni" class="form-control" required>', array()); ?>
           </div>
</div>
<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">FecuenciaTipoFin</label>
      <div class="col-sm-10">
              <?php  Text::renderOptions('<select ng-model="vCrud.form.FecuenciaTipoFin" class="form-control" required>', array()); ?>
           </div>
</div>

<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">Estado</label>
      <div class="col-sm-10">
              <input type="int" ng-model="vCrud.form.Estado" class="form-control" >
           </div>
</div>

                  
                        </div>


                        </div>
                    
                    </div>


          		</div><!-- col-lg-12-->      	
          	</div><!-- /row -->         	
          	  </form>
			</div>
    </div>

    <script type="text/javascript">
$(function() {

      $("#tabs").tabs();
       $( "#fechaLimite" ).datepicker();
       $("#objetivoFormulario").hide();        
       $("#content-objs").hide(); 


    }); 
      
    </script>