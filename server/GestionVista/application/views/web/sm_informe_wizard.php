<style type="text/css">
.wizard {
    margin: 20px auto;
    background: #fff;
}

    .wizard .nav-tabs {
        position: relative;
        margin: 40px auto;
        margin-bottom: 0;
        border-bottom-color: #e0e0e0;
    }

    .wizard > div.wizard-inner {
        position: relative;
    }

.connecting-line {
    height: 2px;
    background: #e0e0e0;
    position: absolute;
    width: 80%;
    margin: 0 auto;
    left: 0;
    right: 0;
    top: 50%;
    z-index: 1;
}

.wizard .nav-tabs > li.active > a, .wizard .nav-tabs > li.active > a:hover, .wizard .nav-tabs > li.active > a:focus {
    color: #555555;
    cursor: default;
    border: 0;
    border-bottom-color: transparent;
}

span.round-tab {
    width: 70px;
    height: 70px;
    line-height: 70px;
    display: inline-block;
    border-radius: 100px;
    background: #fff;
    border: 2px solid #e0e0e0;
    z-index: 2;
    position: absolute;
    left: 0;
    text-align: center;
    font-size: 25px;
}
span.round-tab i{
    color:#555555;
}
.wizard li.active span.round-tab {
    background: #fff;
    border: 2px solid #5bc0de;
    
}
.wizard li.active span.round-tab i{
    color: #5bc0de;
}

span.round-tab:hover {
    color: #333;
    border: 2px solid #333;
}

.wizard .nav-tabs > li {
    width: 25%;
}

.wizard li:after {
    content: " ";
    position: absolute;
    left: 46%;
    opacity: 0;
    margin: 0 auto;
    bottom: 0px;
    border: 5px solid transparent;
    border-bottom-color: #5bc0de;
    transition: 0.1s ease-in-out;
}

.wizard li.active:after {
    content: " ";
    position: absolute;
    left: 46%;
    opacity: 1;
    margin: 0 auto;
    bottom: 0px;
    border: 10px solid transparent;
    border-bottom-color: #5bc0de;
}

.wizard .nav-tabs > li a {
    width: 70px;
    height: 70px;
    margin: 20px auto;
    border-radius: 100%;
    padding: 0;
}

    .wizard .nav-tabs > li a:hover {
        background: transparent;
    }

.wizard .tab-pane {
    position: relative;
    padding-top: 50px;
}

.wizard h3 {
    margin-top: 0;
}
.step1 .row {
    margin-bottom:10px;
}
.step_21 {
    border :1px solid #eee;
    border-radius:5px;
    padding:10px;
}
.step33 {
    border:1px solid #ccc;
    border-radius:5px;
    padding-left:10px;
    margin-bottom:10px;
}
.dropselectsec {
    width: 68%;
    padding: 6px 5px;
    border: 1px solid #ccc;
    border-radius: 3px;
    color: #333;
    margin-left: 10px;
    outline: none;
    font-weight: normal;
}
.dropselectsec1 {
    width: 74%;
    padding: 6px 5px;
    border: 1px solid #ccc;
    border-radius: 3px;
    color: #333;
    margin-left: 10px;
    outline: none;
    font-weight: normal;
}
.mar_ned {
    margin-bottom:10px;
}
.wdth {
    width:25%;
}
.birthdrop {
    padding: 6px 5px;
    border: 1px solid #ccc;
    border-radius: 3px;
    color: #333;
    margin-left: 10px;
    width: 16%;
    outline: 0;
    font-weight: normal;
}


/* according menu */
#accordion-container {
    font-size:13px
}
.accordion-header {
    font-size:13px;
    background:#ebebeb;
    margin:5px 0 0;
    padding:7px 20px;
    cursor:pointer;
    color:#fff;
    font-weight:400;
    -moz-border-radius:5px;
    -webkit-border-radius:5px;
    border-radius:5px
}
.unselect_img{
    width:18px;
    -webkit-user-select: none;  
    -moz-user-select: none;     
    -ms-user-select: none;      
    user-select: none; 
}
.active-header {
    -moz-border-radius:5px 5px 0 0;
    -webkit-border-radius:5px 5px 0 0;
    border-radius:5px 5px 0 0;
    background:#F53B27;
}
.active-header:after {
    content:"\f068";
    font-family:'FontAwesome';
    float:right;
    margin:5px;
    font-weight:400
}
.inactive-header {
    background:#333;
}
.inactive-header:after {
    content:"\f067";
    font-family:'FontAwesome';
    float:right;
    margin:4px 5px;
    font-weight:400
}
.accordion-content {
    display:none;
    padding:20px;
    background:#fff;
    border:1px solid #ccc;
    border-top:0;
    -moz-border-radius:0 0 5px 5px;
    -webkit-border-radius:0 0 5px 5px;
    border-radius:0 0 5px 5px
}
.accordion-content a{
    text-decoration:none;
    color:#333;
}
.accordion-content td{
    border-bottom:1px solid #dcdcdc;
}



@media( max-width : 585px ) {

    .wizard {
        width: 90%;
        height: auto !important;
    }

    span.round-tab {
        font-size: 16px;
        width: 50px;
        height: 50px;
        line-height: 50px;
    }

    .wizard .nav-tabs > li a {
        width: 50px;
        height: 50px;
        line-height: 50px;
    }

    .wizard li.active:after {
        content: " ";
        position: absolute;
        left: 35%;
    }
}
</style>

<div ng-controller="InformeMensualController" ng-init= "initt(); vCrud.setHash('<?=$csrf["name"];?>', '<?=$csrf["hash"];?>' );" >

<h3 style="margin-top: 14px; margin-bottom: -29px; color: #000;">Informe Mensual de <?php echo $mes;  ?></h3>
<div class="container">
    <div class="row">
        <section>
        <div class="wizard">
            <div class="wizard-inner">
                <div class="connecting-line"></div>
                <ul class="nav nav-tabs" role="tablist">

                    <li role="presentation" class="active">
                        <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-folder-open"></i>
                            </span>
                        </a>
                    </li>

                    <li role="presentation" class="disabled">
                        <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2">
                            <span class="round-tab">
                                <i class="glyphicon  glyphicon-pencil"></i>
                            </span>
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Step 3">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-calendar"></i>
                            </span>
                        </a>
                    </li>

                    <li role="presentation" class="disabled">
                        <a href="#complete" data-toggle="tab" aria-controls="complete" role="tab" title="Complete">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-ok"></i>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>

            <form role="form">
                <div class="tab-content">
                    <div class="tab-pane active" role="tabpanel" id="step1">
                        <div class="step1 form-panel">
                            <h4>Paso 1 - Datos Generales </h4>
                            <div class="row">

                            <div class="col-md-6">
                                <label for="exampleInputEmail1">Selecciona el Nombre del Club </label>
                                <select ng-change="enviarSelectedClub()" ng-model="clubSelected" class="form-control">
                                      <option ng-repeat="itm in  listaClubes" value="{{itm.OrganigramaID}}"> {{itm.NombreClub}} </option>
                                </select>                                
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputEmail1">Zona</label>
                                <input type="text" ng-model="infoClub.Zona" class="form-control" id="exampleInputEmail1" placeholder="Zona #" readonly >
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-md-6">
                                <label for="exampleInputEmail1">Iglesia</label>
                                <input type="text" ng-model="infoClub.Iglesia" class="form-control" id="exampleInputEmail1" placeholder="Mi Iglesia" readonly>
                            </div>

                            <div class="col-md-6">
                            <label for="exampleInputEmail1">¿Cual es el dia de la Semana y Horario de Reunión?</label>
                                <div class="row">
                                    <div class="col-md-3 col-xs-3">
                                <select class="form-control" ng-model="form.DiaReunion">
                                    <option></option>    
                                    <option value="7">Sábado</option>
                                    <option value="1">Domingo</option>
                                    <option value="2">Lunes</option>
                                    <option value="3">Martes</option>
                                    <option value="4">Miercoles</option>
                                    <option value="5">Jueves</option>
                                    <option value="6">Viernes</option>
                                </select>
                                        
                                    </div>
                                    <div class="col-md-9 col-xs-9">
                                        
                                <select class="form-control" ng-model="form.Hora">
                                    <option></option>    
                                    <option value="6:00 AM">6:00 AM</option>                                    
                                    <option value="7:00 AM">7:00 AM</option>
                                    <option value="8:00 AM">8:00 AM</option>
                                    <option value="9:00 AM">9:00 AM</option>
                                    <option value="10:00 AM">10:00 AM</option>
                                    <option value="11:00 AM">11:00 AM</option>
                                    <option value="12:00 PM">12:00 PM</option>
                                    <option value="1:00 PM">1:00 PM</option>
                                    <option value="2:00 PM">2:00 PM</option>
                                    <option value="3:00 PM">3:00 PM</option>
                                    <option value="4:00 PM">4:00 PM</option>
                                    <option value="5:00 PM">5:00 PM</option>
                                    <option value="6:00 PM">6:00 PM</option>
                                    <option value="7:00 PM">7:00 PM</option>
                                    <option value="8:00 PM">8:00 PM</option>
                                    <option value="9:00 PM">9:00 PM</option>
                                    <option value="10:00 PM">10:00 PM</option>
                                    <option value="11:00 PM">11:00 PM</option>                                    
                                </select>
                                        
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1">¿Está correcta la información de Zona e iglesia al que pertenece tu Club?</label>
                                <select class="form-control">
                                    <option value="SI">SI</option>                                    
                                    <option value="NO">NO</option>
                                </select>
                            </div>  

                            <div class="col-md-6">
                                <label for="exampleInputEmail1">¿Cuántas reuniones tuvieron este mes?</label>


                                <input ng-model="form.NumeroReunion" type="Number" class="form-control col-md-3 required"  required >
                            </div>                          
                        </div>

                        <div class="row">
                            <div class="col-md-6">

                                <label for="exampleInputEmail1">Valide esta información de sus Miembros.</label>
                              &nbsp;
                              <div class="form-group"> 
                              <table class="table">

                                <tr> 
                                      <th> </th>  <th> Registro Individual</th> <th>Confirmacion</th>
                                </tr>



                                <tr> 
                                      <td><strong>Total Miembros</strong>  </td>  <td> {{infoMiembro.TotalMiembros}} </td>  <td>  <input class="form-control" type="Number" ng-model="form.TotalMiembros"></td>
                                </tr>

                                <tr> 
                                      <td><strong>Total Uniformados</strong>  </td> <td> {{infoMiembro.TotalUniformado}} </td>  <td><input class="form-control" type="Number" ng-model="form.TotalUniformado"></td>
                                </tr>

                                <tr> 
                                      <td><strong>Total Bautizado</strong>  </td> <td> {{infoMiembro.TotalBautizado}} </td>  <td><input  class="form-control" type="Number" ng-model="form.TotalBautizado"></td>
                                </tr>
                              </table>
                          </div>
                            </div>  

                            <div class="col-md-6">
                                <div ng-if="(form.NumeroReunion == 0 || form.NumeroReunion =='')">
                                    <label for="exampleInputEmail1">Justifique, ¿Por qué no hubo reunión?</label>                                
                                <textarea ng-model="form.Justificacion" class="form-control"  ></textarea>
                                </div>
                            </div>                          
                        </div>
                        </div>
                        <ul class="list-inline pull-right">
                            <li><button type="button" class="btn btn-primary next-step">Guardar y continuar</button></li>
                        </ul>
                    </div>
                    <div class="tab-pane" role="tabpanel" id="step2">
                        <div class="step2 form-panel">
                            <div class="step_21">
                                <div class="row">                                     
                            <h4>Paso 2 - Administración & Finanzas.</h4>
                            <div class="row">

                            <div class="col-md-6">
                                <label for="exampleInputEmail1">Presupuesto Anual </label>                                
                                <input type="text" ng-model="form.Presupuesto" class="form-control" id="exampleInputEmail1" placeholder="0.00"  >
                            </div>
                            <div class="col-md-6">
                                  <label for="exampleInputEmail1">Cual es el Balance Disponible a la Fecha</label>
                                <input type="text" ng-model="infoClub.BalanceDisponible" class="form-control" id="exampleInputEmail1" placeholder="0.00">
                            </div>
                        </div>                        
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1">Registro de Entradas o Ingresos en el Mes</label>

                                <div class="form-group"> 
                                <table>
                                    <tr>   <th> Motivo </th>   <th> Monto </th>  </tr>
                                    <tr>   <td> <input ng-blur="calcularTotalEntradas()" ng-change="calcularTotalEntradas()" type="checkbox" ng-model="form.inInscripcion">  Inscripcion </td>   <td> <input type="text"  ng-blur="calcularTotalEntradas()"    class="form-control" ng-model="form.MontoInscripcion" placeholder="0.00" > </td>  </tr>
                                    <tr>   <td> <input ng-blur="calcularTotalEntradas()"  ng-change="calcularTotalEntradas()" type="checkbox" ng-model="form.inCuota">  Cuotas </td>   <td> <input type="text" ng-blur="calcularTotalEntradas()"    class="form-control" ng-model="form.MontoCuota" placeholder="0.00" > </td>  </tr>
                                    <tr>   <td> <input ng-blur="calcularTotalEntradas()" ng-change="calcularTotalEntradas()" type="checkbox" ng-model="form.inVentas">  Ventas / recolecciones & profondos </td>   <td> <input type="text" ng-blur="calcularTotalEntradas()"   class="form-control" ng-model="form.MontoVentas" placeholder="0.00" > </td>  </tr>
                                    <tr>   <td> <input ng-blur="calcularTotalEntradas()" ng-change="calcularTotalEntradas()" type="checkbox" ng-model="form.inDonacion">  Donación </td>   <td> <input type="text" class="form-control" ng-blur="calcularTotalEntradas()"  ng-model="form.MontoDonacion" placeholder="0.00" > </td>  </tr>
                                    <tr>   <td> <input ng-blur="calcularTotalEntradas()" ng-change="calcularTotalEntradas()"  type="checkbox" ng-model="form.inOtros">  Otros </td>   <td> <input type="text" class="form-control" ng-blur="calcularTotalEntradas()" ng-model="form.MontoOtros" placeholder="0.00" > </td>  </tr>
                                    <tr>   <td>  </td>   <td> <strong class="form-control"> $ {{TotalEntradas}} </strong>  </td>  </tr>
                                </table>                 
                                </div>              
                            </div>  

                            <div class="col-md-6">
                                <label for="exampleInputEmail1">Registro de Egresoso</label>
                                <div class="form-group"> 
                                 <table>
                                    <tr>   <td> <input ng-blur="calcularTotalSalidas()"  ng-change="calcularTotalSalidas()" type="checkbox" ng-model="form.outImpresion">  Impresión </td>   <td> <input type="text" class="form-control" ng-model="form.outMontoImpresion"  placeholder="0.00" ng-blur="calcularTotalSalidas()"> </td>  </tr>
                                    <tr>   <td> <input ng-blur="calcularTotalSalidas()"  ng-change="calcularTotalSalidas()" type="checkbox" ng-model="form.outMaterialG">  Material Gastable </td>   <td> <input type="text" class="form-control" ng-model="form.MontoMaterialG" placeholder="0.00"   ng-blur="calcularTotalSalidas()"> </td>  </tr>
                                    <tr>   <td> <input ng-blur="calcularTotalSalidas()"  ng-change="calcularTotalSalidas()" type="checkbox" ng-model="form.outMaterialClass">  Material de clases </td>   <td> <input type="text" class="form-control" ng-model="form.MontoMaterialClass" placeholder="0.00"   ng-blur="calcularTotalSalidas()"> </td>  </tr>
                                    <tr>   <td> <input ng-blur="calcularTotalSalidas()"  ng-change="calcularTotalSalidas()" type="checkbox" ng-model="form.outCuota">  Cuota Zona </td>   <td> <input type="text" class="form-control" ng-model="form.outMontoCuota" placeholder="0.00"   ng-blur="calcularTotalSalidas()"> </td>  </tr>
                                    <tr>   <td> <input ng-blur="calcularTotalSalidas()"  ng-change="calcularTotalSalidas()" type="checkbox" ng-model="form.outCuota">  Premiaciones  </td>   <td> <input type="text" class="form-control" ng-model="form.outMontoPremiacion" placeholder="0.00"  ng-blur="calcularTotalSalidas()"> </td>  </tr>
                                    <tr>   <td> <input ng-blur="calcularTotalSalidas()"  ng-change="calcularTotalSalidas()" type="checkbox" ng-model="form.outCompra">  Compra de utensilios </td>   <td> <input type="text" class="form-control" ng-model="form.MontoCompra" placeholder="0.00"  ng-blur="calcularTotalSalidas()"> </td>  </tr>
                                    <tr>   <td> <input ng-blur="calcularTotalSalidas()" ng-change="calcularTotalSalidas()" type="checkbox" ng-model="form.outOtro"> Otros  <input type="text" class="form-control" ng-model="form.outOtrosDesc" placeholder="Separados por (,)"  >  </td>   <td> <input type="text" class="form-control" ng-model="form.outMontoOtros" ng-blur="calcularTotalSalidas()"> </td>  </tr>
                                    
                                    <tr>   <td>  </td>   <td> <strong class="form-control"> $ {{TotalSalidas}} </strong>  </td>  </tr>
                                </table>
                            </div>
                                
                            </div>                          
                        </div>                                                                   
                                   
                                </div>
                            </div>
                            <div class="step-22">
                            
                            </div>
                        </div>
                        <ul class="list-inline pull-right">
                            <li><button type="button" class="btn btn-default prev-step">Atras</button></li>
                            <li><button type="button" class="btn btn-primary next-step">Guardar y continue</button></li>
                        </ul>
                    </div>
                    <div class="tab-pane" role="tabpanel" id="step3">
                        <div class="step33">
                        <h5><strong>Paso 3 - Planificación y Actividades </strong></h5>
                        <hr>
                        
                        <div ng-if="ListaActividades.length == 0">
                            <div class="panel panel-danger">
                            <div class="panel-heading"> <i style="font-size: 15px;" class="fa fa-info" aria-hidden="true"></i>  <strong style="font-size: 18px;">No Hamos encontrado Plan de Acción configurado para este club. </strong> 
                                <p> Puedas continuar y agregar actividades que no han sido planificadas, pero que se realizaron en este Mes. </p> 
                            </div>                                
                            </div>                            
                        </div>

                            <div class="row mar_ned">                               
                                
                            </div>
                            
                                <div class="col-md-6 col-xs-7" >
                                    <h5> En esta parte se muestras las actividades planificadas y diseñadas para cumplir los objetivos especificos del Plan de Acción en este mes.   </h5>


                                <div class="panel panel-default" ng-repeat="(k, vl) in ListaActividades">
                                      <div class="panel-heading"> <i class="fa fa-calendar"></i>  {{vl.FrecuenciaDesc}}{{GetFecha(vl)}}  </div>
                                      <div class="panel-body">

                                        <div class ="col-md-8">
                                            <b>{{vl.Categoria}} </b> <p > <strong> {{vl.Titulo}} </strong>:  {{vl.Descripcion}}<br></p>
                                           <p> <strong>Responsables: </strong> {{vl.Responsables}} <br></p>
                                           <p> <strong>Lugar: </strong> {{vl.Lugar}} {{vl.Direccion}} <br></p>

                                           <p class="ng-binding"><b>Presupuesto: </b>$ {{vl.Monto}}</p>
                                            <p class="ng-binding"><b>Cantidad de Dias: </b> {{vl.DiasAccion}}</p>
                                        </div>
                                        <div class ="col-md-4">

                                            <div class="row">
                                        
                                            <select name="visa_status" id="visa_status" class="dropselectsec1">
                                                <option value="0"> -- </option>
                                                <option value="1" style="background: green;" >Realizado</option>
                                                <option value="2" style="background: yellow;" >Aplazado</option>
                                                <option value="3" style="background: red;" >Cancelada</option>                                                
                                            </select>                                        
                                            </div>
                                            
                                        </div>
                                           
                                      </div>
                                </div>                
                                </div>
                                <div  class="col-md-6 col-xs-5" >


                                </div>                            
                            <div class="row mar_ned">
                                <div class="col-md-4 col-xs-3">
                                    <p align="right"><h5>Actividades Realizadas Fuera del Plan de Acción</h5> <button class="btn btn-defualt" ng-click="AgregarActividadProvicional()"> <i class="fa fa-plus"></i>  Agregar</button> </p>

                                    <div class="panel panel-default" ng-repeat="(ki, val) in ListaAdicionales">
                                      <div class="panel-heading"> 
                                       <i class="fa close" ng-click="EliminarAdicional(ki)">X</i>  <i class="fa fa-calendar"></i>  Nueva Actividad   </div>
                                      <div class="panel-body">
                                        <div class ="col-md-10">


                                            <b>Tipo Actividad: </b> 
                                                                 <select name="visa_status" id="visa_status" class="dropselectsec1">
                                                <option value="0"> -- </option>

                                                <?php
                                                foreach ($listaActividad as $key => $value) {
                                                    echo '<optgroup label="' .$key . '">';
                                                    foreach ($value as $ky => $val) {
                                                      echo " <option value='{$val->Nombre}'  >{$val->Nombre}</option>"; 
                                                    }
                                                    echo '</optgroup>'; 
                                                }
                                                 ?>                                             
                                            </select> 

                                            <b>Comentario: </b> 

                                            <textarea row="5" class="form-control"></textarea>                                      

                                        </div>
                                        <div class ="col-md-2">

                                            <div class="row">                                        
                                                              
                                            </div>
                                            
                                        </div>
                                           
                                      </div>
                                </div>


                                    

                                    
                                    

                                </div>                              
                            </div>
                                                       
                            <div class="row mar_ned">                             
                                
                            </div>
                            
                        </div>
                        <ul class="list-inline pull-right">
                            <li><button type="button" class="btn btn-default prev-step">Previous</button></li>
                            <li><button type="button" class="btn btn-primary btn-info-full next-step">Save and continue</button></li>
                        </ul>
                    </div>

                    <div class="tab-pane" role="tabpanel" id="complete">
                        <div class="step44">
                            <h5>Completed</h5>
                            
                          
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </section>
   </div>
</div>

</div>

<script type="text/javascript">

$(document).ready(function () {
    //Initialize tooltips
    $('.nav-tabs > li a[title]').tooltip();
    
    //Wizard
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

        var $target = $(e.target);
    
        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $(".next-step").click(function (e) {

        var $active = $('.wizard .nav-tabs li.active');
        $active.next().removeClass('disabled');
        nextTab($active);

    });
    $(".prev-step").click(function (e) {

        var $active = $('.wizard .nav-tabs li.active');
        prevTab($active);

    });
});

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}


//according menu

$(document).ready(function()
{
    //Add Inactive Class To All Accordion Headers
    $('.accordion-header').toggleClass('inactive-header');
    
    //Set The Accordion Content Width
    var contentwidth = $('.accordion-header').width();
    $('.accordion-content').css({});
    
    //Open The First Accordion Section When Page Loads
    $('.accordion-header').first().toggleClass('active-header').toggleClass('inactive-header');
    $('.accordion-content').first().slideDown().toggleClass('open-content');
    
    // The Accordion Effect
    $('.accordion-header').click(function () {
        if($(this).is('.inactive-header')) {
            $('.active-header').toggleClass('active-header').toggleClass('inactive-header').next().slideToggle().toggleClass('open-content');
            $(this).toggleClass('active-header').toggleClass('inactive-header');
            $(this).next().slideToggle().toggleClass('open-content');
        }
        
        else {
            $(this).toggleClass('active-header').toggleClass('inactive-header');
            $(this).next().slideToggle().toggleClass('open-content');
        }
    });
    
    return false;
});

var listaOrganigrama = <?php echo json_encode($listOrganigramaUsuario); ?>;

var resumenClub = <?php echo json_encode($resumenClub); ?>; 

</script>