<div  ng-controller="ContenidoController" ng-init= "initt(); vCrud.setHash('<?=$csrf["name"];?>', '<?=$csrf["hash"];?>' );">
  <style type="text/css"> 

  .node:hover {
    opacity: 0.8;
    background-color: #68dff0;
  }


  #blog-bg {
background: #000; /* url(../img/blog-bg.jpg) no-repeat center top; */
margin-top: -15px;
background-attachment: relative;
background-position: center center;
min-height: 160px;
width: 100%;
-webkit-background-size: 100%;
-moz-background-size: 100%;
-o-background-size: 100%;
background-size: 100%;
-webkit-background-size: cover;
-moz-background-size: cover;
-o-background-size: cover;
background-size: cover;
}

.Full, .tbltv {
width: 618px;
height: 378px;
background-color: rgb(160, 160, 160);
}

.tbltv td, .tbltv th {
padding: 0;
border: 1px rgb(107, 98, 98) solid;
}

.tbltv td:hover {
opacity: 0.3;
background-color: #000;
}

  </style>
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
                                      <li ng-repeat="item in listaContenido|filter:buscarLista:strict" class="list-primary">
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
            


          		<div class="col-lg-12">
                  <div class="form-panel" ng-form="vCrud.$Form.Main" >

  <div id="rootwizard" class="tabbable tabs-left">
  <ul>
      <li ><a ng-click="wizard.setPosicion(1);" href="#tab1" data-toggle="tab">Título: <strong>"{{vCrud.form.Nombre}}"</strong> </a></li>
    <li><a ng-click="wizard.setPosicion(2);" href="#tab2" data-toggle="tab">Slider Templetes</a></li>
    <li><a  ng-click="wizard.setPosicion(3);" href="#tab3" data-toggle="tab">Seccion Fuentes</a></li>    
  </ul>
  <div class="tab-content">

  <div class="pager row">
  <div style="float:right">

      <input type='button' id="btn-next" ng-click="nextForm()" class='fa fa-arrow-right btn-success btn button-next' name='next' value='Siguiente' />            

    </div>
    <div style="float:left">      
      <span class="fa fa-sign-out btn" type="reset"  ng-click="vCrud.reset();">Cancelar</span>
      <input type='button' class='btn button-previous' name='previous'  ng-click="anteriorCursor()" value='Anterior' />
    </div>
</div>


      <div class="tab-pane" id="tab1">

         <div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">Nombre</label>
      <div class="col-sm-10">
              <input type="text" ng-model="vCrud.form.Nombre" class="form-control" required>
           </div>
</div>
<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">Descripcion</label>
      <div class="col-sm-10">
              <input type="text" ng-model="vCrud.form.Descripcion" class="form-control" required>
           </div>
</div>


<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">Estado</label>
      <div class="col-sm-10">

         <?php  Text::renderOptions('<select ng-model="vCrud.form.Estado" class="form-control" required>', $listEstadoForm); ?>                                    
                  
           </div>
</div>

       
      </div>
      <div class="tab-pane" id="tab2">      

      <div class="notContenido" ng-if="wizard.validado == false">
        <br><h3> No se he encontrado Titulo.</h3>
        <hr><p> Debes ir al tabs de títulos y configurar y clickear en <strong>"Siguiente"</strong> </p>
      </div>

      <div id="galeria_template" ng-if="wizard.validado" class="row">

      
      <div class="col-md-4 col-sm-4 mb">

        <div data-toggle="modal" data-target="#myModal" class="darkblue-panel pn node" ng-click="AgregarTemplate()">
                            <div class="darkblue-header">
                    <h5>AGREGAR SLIDE</h5>
                            </div>
                            <h1 class="mt"><i class="fa  fa-plus-circle fa-3x"></i></h1>                
                <footer>
                  <div class="centered">
                    <h5><i class="fa fa-television"></i>  </h5>
                  </div>
                </footer>
                          </div><!-- -- /darkblue panel ---->
      </div>

      <div id="content-TemplatePages">
      

            <div class="col-lg-4 col-md-4 col-sm-4 mb" ng-repeat="(k, tmppg) in listaTemplatePages">
              <div class="content-panel pn node">
                <div id="blog-bg" >
                  <div class="badge badge-popular">{{wizard.ObtenerEsquemaPorID(tmppg, k)}}</div>
                  <div class="blog-title">Slider # {{tmppg.Posicion}}  &nbsp; {{tmppg.Duracion}}</div>
                </div>
                <div class="blog-text wizard">
                <p> Duracion: {{tmppg.Duracion}}</p>


          <button data-toggle="modal" data-target="#myModal" class="btn fa-pencil" ng-click="wizard.llenar(tmppg, k);">Editar</button>   

          <span ng-click="wizard.CargarSeccionesFuentes(tmppg)" class='btn btn-default button-next' ><a href="#tab3" data-toggle="tab" >Configurar</a></span> 
          
          
                </div>
              </div>
            </div>

      </div>

      </div>
      </div>

    <div class="tab-pane" id="tab3">
      <div class="notContenido" ng-if="wizard.validado == false" >
        <br><h3> No se he encontrado Titulo.</h3><hr>        
        <p> Debes ir al tabs de títulos y configurar y clickear en <strong>"Siguiente"</strong> </p>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <h3> Secciones y Fuentes</h3>            

          <div compile="esquemaHtml">

          </div>        

        </div>
      </div>




    </div>   
          		</div><!-- col-lg-12-->      	
          	</div><!-- /row -->         	
          	  </form>
			</div>
    </div>


       <div id="divDialogEdit">
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Configuración Pages Template </h4>
      </div>
      <div class="modal-body">

      <div class="col-lg-12">
                  <div class="" ng-form="wizard.$Form.Main"  >                      
                   
<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">EsquemaTipo</label>
      <div class="col-sm-10">
              <?php  Text::renderOptions('<select ng-model="wizard.form.EsquemaTipo" class="form-control" required>', $listEsquemaTipo); ?>
<br>
              <div id="muestraEquema" class="centered" ng-bind-html="wizard.mostrarEsquema()">

              </div>
      </div>
</div>

<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">MostrarHeader</label>
      <div class="col-sm-10"> 
      <select ng-model="wizard.form.MostrarHeader" class="form-control" required>
      <option value="0"> Ocultar </option>
      <option value="1"> Mostrar </option>
      </select>
      
              
           </div>
</div>
<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">Duracion</label>
      <div class="col-sm-10">
              <input type="text" id="datetimepicker2" ng-model="wizard.form.Duracion" class="form-control" required>
           </div>
</div>
<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">TransicionTipoIni</label>
      <div class="col-sm-10">
              <?php  Text::renderOptions('<select ng-model="wizard.form.TransicionTipoIni" class="form-control" required>', $listTransicionTipoIni); ?>
           </div>
</div>
<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">TransicionTipoFin</label>
      <div class="col-sm-10">
              <?php  Text::renderOptions('<select ng-model="wizard.form.TransicionTipoFin" class="form-control" required>', $listTransicionTipoFin); ?>
           </div>
</div>

<div class="form-group">
      <label class="col-sm-2 col-sm-2 control-label">Estado</label>
      <div class="col-sm-10">              
              <?php  Text::renderOptions('<select ng-model="wizard.form.Estado" class="form-control" required>', $listEstadoForm); ?>   
           </div>
</div>   
  </div>  
</div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" ng-click= "wizard.guardarTemplatePages()" >Guardar cambios</button>
      </div>

    </div>
  </div>
</div>
               
                  </div>


<script type="text/javascript" src="<?php echo base_url(). "webApp/"; ?>js/jquery.bootstrap.wizard.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {

/*
    $('#rootwizard').bootstrapWizard({'tabClass': 'nav nav-tabs', onTabClick: function(tab, navigation, index) {
 //     return false;
    }});
    */

    $(document).ready(function() {
    $('#rootwizard').bootstrapWizard({'tabClass': 'nav nav-tabs', 'nextSelector': '.button-next', 'previousSelector': '.button-previous', 'firstSelector': '.button-first', 'lastSelector': '.button-last'

  });
}); 


 jQuery('#datetimepicker2').datetimepicker({
  datepicker:false,
  mask: true,
  format:'H:i:s',
  formatTime: 'H:i:s'
});

});


var vw_listEsquemaTipo = <?php echo json_encode($listEsquemaTipo);  ?>;

    
    </script>