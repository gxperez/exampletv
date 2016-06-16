<div  ng-controller="FuerzaVentaDispositivoController" ng-init= "panel(); vCrud.setHash('<?=$csrf["name"];?>', '<?=$csrf["hash"];?>' );">
  <style type="text/css">

  h1, .h1, h2, .h2, h3, .h3 {
	margin-top: -2px;
	margin-bottom: 8px;
	}
   </style>

  <div class="row mt mb">
  		<div class="col-md-12">

			<h3><i class="fa fa-angle-right"></i> Dispositivo Fuerza Venta</h3>

			<section class="task-panel tasks-widget">
		<div class="panel-body">
      		<div class="task-content">

    	  <fieldset>
      		<legend>Fitro</legend>

      		<div class="form-group col-sm-6">
      			<div class="col-sm-12">
					<label class="col-sm-2 col-sm-2 control-label">Nivel Dispositivo</label>
					<div class="col-sm-12">
	            		<?php  Text::renderOptions('<select ng-model="vCrud.form.FrecuenciaTipo" class="form-control" required>', $nivelTipos); ?>
	           		</div>
      			</div>

      			<div class="col-sm-12">
      			   <label class="col-sm-2 col-sm-2 control-label">Buscar Dispositivo </label>
					<div class="col-sm-12">
	            	<input type="text" ng-model="buscarLista" class="form-control"></input>
	           		</div>
           		</div>
			</div>

			<div class="form-group col-sm-6">
				<label class="col-sm-2 col-sm-2 control-label">Buscar</label>
				<div class="col-sm-12">
            	<input type="text" class="form-control"></input>
           		</div>
			</div>

	</fieldset>

     

<div class="col-sm-12">
<hr>

<div class="col-sm-7">
<h5> Dispositivos</h5>	

<div>
	<table class="table table-striped table-advance table-hover">
	                  	  	  
	                  	  	  
                              <thead>
                              <tr>
                                  <th><i class="fa fa-bullhorn"></i> Dipositivo</th>
                                  <th class="hidden-phone"><i class="fa fa-question-circle"></i> Descripcion</th>
                                  <th><i class=" fa fa-edit"></i> Estado</th>
                                  <th><i class="fa fa-bookmark"></i> FuerzaVenta</th>
                                  <th></th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr ng-repeat="item in listaDispositivo| filter:buscarLista:strict">
                                  <td><a href="basic_table.html#">{{item.Mac}}</a></td>
                                  <td class="hidden-phone">{{item.Nombre}}</td>
                                  <td><span class="label label-info label-mini">Due</span></td>
                                  <td> {{item.FuerzaVenta}} </td>
                                  
                                  <td>
                                      <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>
                                      <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                      <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td>
                                      <a href="basic_table.html#">
                                          Dashgum co
                                      </a>
                                  </td>
                                  <td class="hidden-phone">Lorem Ipsum dolor</td>
                                  <td>17900.00$ </td>
                                  <td><span class="label label-warning label-mini">Due</span></td>
                                  <td>
                                      <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>
                                      <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                      <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td>
                                      <a href="basic_table.html#">
                                          Another Co
                                      </a>
                                  </td>
                                  <td class="hidden-phone">Lorem Ipsum dolor</td>
                                  <td>14400.00$ </td>
                                  <td><span class="label label-success label-mini">Paid</span></td>
                                  <td>
                                      <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>
                                      <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                      <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td>
                                      <a href="basic_table.html#">
                                          Dashgum ext
                                      </a>
                                  </td>
                                  <td class="hidden-phone">Lorem Ipsum dolor</td>
                                  <td>22000.50$ </td>
                                  <td><span class="label label-success label-mini">Paid</span></td>
                                  <td>
                                      <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>
                                      <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                      <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td><a href="basic_table.html#">Total Ltd</a></td>
                                  <td class="hidden-phone">Lorem Ipsum dolor</td>
                                  <td>12120.00$ </td>
                                  <td><span class="label label-warning label-mini">Due</span></td>
                                  <td>
                                      <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>
                                      <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                      <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              </tbody>
                          </table>	
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
	
</script>