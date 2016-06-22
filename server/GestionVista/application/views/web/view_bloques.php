<div  ng-controller="MasterBloquesController" ng-init= "master(); vCrud.setHash('<?=$csrf["name"];?>', '<?=$csrf["hash"];?>' );">


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
            <?php foreach ($listaProgramacion as $key => $value) {        	            ?>
            <tr>

            <td>
            <button type="button" class="btn btn-round btn-info"> <span class="fa fa-folder-open-o"></span> Abrir</button>
            	
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




</div>