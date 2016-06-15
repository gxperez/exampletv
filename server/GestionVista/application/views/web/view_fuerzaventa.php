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

<hr>
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
                                      <li ng-repeat="item in listaFuerzaVenta|filter:buscarLista:strict" class="list-primary">
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

    </div>