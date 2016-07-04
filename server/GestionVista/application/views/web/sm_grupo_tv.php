<div  ng-controller="GrupoTVController" ng-init= "initt(); vCrud.setHash('<?=$csrf["name"];?>', '<?=$csrf["hash"];?>' );">
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
width: 135px;
min-height: 50px;
}

.sortable1 li {
margin: 0px;
padding: 0px;
font-size: 11px;
width: 120px;
}

.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
border: 1px solid #d3d3d3;
background: #e6e6e6 url("../webApp/img/jqueryui/ui-bg_glass_75_e6e6e6_1x400.png") 50% 50% repeat-x !important;
font-weight: normal;
color: #555555;
}

/**
Las aventuras de un Heroe
*/

   </style>

  <div class="row mt mb">
      <div class="col-md-12">

      

      <section class="task-panel tasks-widget">
    <div class="panel-body">
          <div class="task-content">

<div class="col-sm-12">


<fieldset>
<legend>Administrador de Grupo | Dispositivo</legend>
<div>
</div>
</fieldset>

<div class="col-sm-6">
<div class="col-sm-12">
  <div class="form-group">
        <label class="col-sm-2 col-sm-2 control-label">Grupo</label>
        <div class="col-sm-10">

        <select ng-model="masterGroup.GrupoID" class="form-control" required>
        <option value= "0" > seleccione... </option>
        <option ng-repeat = "gp in listaGrupos" value="{{gp.GrupoID}}"> {{gp.Descripcion}}</option>
        </select>

             </div>
  </div>
  <hr>

  <div ng-if="masterGroup.GrupoID > 0">
    

  </div>


           <label class="col-sm-7 "></label>
          <div class="col-sm-5">          
                <input type="text" class="form-control" ng-model="buscarLista"></input>
          </div>

          <div>
          <table id="todo" class="table table-hover custom-check">

            <tbody>
            <tr ng-repeat="grupoTV in listaGrupoTv[masterGroup.GrupoID] |filter:buscarLista:strict ">
              <td>{{grupoTV.FuerzaVenta}}
              <button ng-click="EliminarToList(grupoTV)" class="close" aria-hidden="true" data-dismiss="alert" type="button"> <i class="fa fa-trash-o "></i> </button>

              </td>
            </tr>
            </tbody>
          </table>
          </div>
  </div>
</div>

<div class="col-sm-6">

<div ng-if="masterGroup.GrupoID > 0">

<div class="form-group ">
            <div class="col-sm-12">
          <label class="col-sm-4 col-sm-4 control-label">Fuerza Venta</label>
              <div class="col-sm-8">
                      <?php  Text::renderOptions('<select ng-model="masterGroup.Nivel" class="form-control" required>', $nivelTipos); ?>
               </div>
            </div>
 </div> 

<div ng-repeat="(k, val) in listaFuerzaVentaCopy| filter:buscarFV:strict"  ng-if="(masterGroup.Nivel.toString() == k.toString() )">

<aside class="col-lg-12 mt">
<div class="row">
<div class="col-sm-7">
  <h4><i class="fa fa-angle-right"></i> Nivel {{k}}</h4>
</div>
<div class="col-sm-5">
  <input type="text" class="form-control" ng-model='buscarFV'>  
</div>  
</div>

<table id="todo" class="table table-hover custom-check">
                      <tbody ng-repeat="(k, val) in listaFuerzaVentaCopy| filter:buscarFV:strict"  ng-if="(masterGroup.Nivel.toString() == k.toString() )" >
                        <tr>                        
                        </tr>

                        <tr> 
                        <td>                          
                        <span class="check"> <input ng-model="masterGroup.AllFV" type="checkbox" class="checked"> Select All  {{getCantidadSelected() }} </span>
                        </td>                       
                        </tr>

                        <tr ng-repeat="item in val| filter:buscarFV:strict" ng-if="notChecked(item)" >
                          <td ng-click="addToList(item)">
                                  <span class="btn check glyphicon glyphicon-arrow-left" ng-if="hasTV(item)" > </span>  <span ng-if="!hasTV(item)"  data-toggle="tooltip" title="No tiene dispositivo asignado. &nbsp; Para poder asociarlo a un grupo esta fuerza de venta debe tener un dispositivo asignado. Favor ir al maestro de Dispositivo Fuerza Venta (FV)" class="glyphicon glyphicon-ban-circle" style="color: red;"></span>

                                  <a href="#">{{item.FuerzaVenta}}</a> <span ng-if="hasTV(item); "> ({{item.Mac}} ) <span>                       
                          </td>                        
                        </tr>
                        
                      </tbody>
</table>

                      
                  </aside>

<div></div>

  
</div>
  

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


<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>




  </div>

  </div>
</div>

<script type="text/javascript">

var JFData = <?php echo $fuerzaVentaData;  ?>;

var vw_listaGrupoTv = <?php echo json_encode($listaGrupoTv); ?>;



$(function() {
  $('[data-toggle="tooltip"]').tooltip();
}); 
  
</script>