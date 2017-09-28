<div  ng-controller="FuerzaVentaController" ng-init= "master(); vCrud.setHash('<?=$csrf["name"];?>', '<?=$csrf["hash"];?>' );">
  <style type="text/css"> </style>
<div class="styleCrud">
<div id="ListMantenimiento">
              <div class="row mt mb">

    <div id="header-crudTools" class="crudTools col-md-12">     
                  <div>
                    <div class="btn-group">                      

                    <button id="btnSubir" type="button" class="btn btn-default" ng-click="SubirBackup(0)"><span class="fa fa-plus"></span> Restaurar Backup </button>

                  </div>
                  </div>
      </div> 

     <div class="col-md-12">
                      <section class="task-panel tasks-widget">
                        <div class="panel-heading">
                          <div class="pull-left"> 
                            <h5><i class="fa fa-tasks"></i> {{Pantalla.nombre}}</h5>
                           </div>
                          <br>
                    </div>


                      <div class="panel-body">
                              <div class="task-content">
                                  <ul id="sortable" class="task-list">
                                      <li class="list-primary">
                                          <i class=" fa fa-ellipsis-v"></i>                                         
                                          <div class="task-title">
                                              <span class="task-title-sp">Actualizada Ultima vez: {{resumenFuerzaVenta.FechaCrea}}</span>
                                              <div class="pull-right hidden-phone">                                                  
                                            <button class="btn btn-success btn-xs fa fa-search-plus" ng-click="inittCrud();"> Ver </button>
                                            

                                                  <button class="btn btn-primary btn-xs fa fa-refresh" ng-click="ActualizarHoja(item, $index)">Actualizar </button>
                                              </div>
                                          </div>
                                      </li>

                                  </ul>
                              </div>                              
                          </div>

<div id="fvOculto">
<hr>

     <div class="panel-heading">

            <div class="pull-right">                                  

                            <input type="text" ng-model="buscarLista"  ng-keypress="Buscar($event)" class="round-form" >                            
                            <button type="button" class="btn btn-round btn-default" ng-click="Buscar($event)"><i class="fa fa-search"></i>  </button>
                      </div>
                    </div>


                          <div class="panel-body">

                          <div class="content-panel">
                            <h4> <i class="fa fa-tasks"></i> {{Pantalla.nombre}}</h4><hr>
                            <table class="table table-hover">                            
                            
                                <thead>
                                <tr>                                    
                                    <th>Nivel</th>
                                    <th>Nombre</th>
                                    <th>Descripcion</th>
                                    <th>Codigo/ <br> Nombre</th>
                                    <th>Perfil</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="item in listaFuerzaVenta|filter:buscarLista:strict">
                                    
                                    <td>{{item.Nivel}}</td>
                                    <td>{{item.Nombre}}</td>
                                    <td>{{item.Descripcion}}</td>
                                    <td>{{item.CodigoEmpleado}} <br>{{item.Persona}}</td>
                                    <td><img src="{{getImagenPersona(item)}}" width="45" >  </td>
                                </tr>                                
                                </tbody>
                            </table>
                        </div>
                              <div class=" add-task-row">   
                               <div id="page-selection-APP"></div>                                   
                              </div>
                          </div>                          


</div>
               
                      </section>
                  </div><!--/col-md-12 -->
             </div><!-- /row -->
             </div> 

    </div>

    <script type="text/javascript">

    $('#fvOculto').hide(); 
      
    </script>