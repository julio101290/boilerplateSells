<div class="row">

    <div class="col-12">

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Encabezado</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>

                </div>
            </div>



            <div class="card-body">


                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="generales-tab" data-toggle="tab" data-target="#generales" type="button" role="tab" aria-controls="generales" aria-selected="true">Generales</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#otrosDatos" type="button" role="tab" aria-controls="otrosDatos" aria-selected="false">Otros
                            Datos</button>
                    </li>



                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#facturacionMX" type="button" role="tab" aria-controls="facturacionMX" aria-selected="false">
                            Facturación MX
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="documentosRelacionados-tab" data-toggle="tab" data-target="#documentosRelacionados" type="button" role="tab" aria-controls="documentosRelacionados" aria-selected="false">
                            <?= lang('newCreditNote.relationDocuments') ?>
                        </button>
                    </li>

                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="generales" role="tabpanel" aria-labelledby="generales">

                        <?= $this->include('julio101290\boilerplatesells\Views/modulesNotasCredito/generalNotaCredito') ?>

                    </div>

                    <div class="tab-pane fade" id="otrosDatos" role="tabpanel" aria-labelledby="otrosDatos">

                        <?= $this->include('julio101290\boilerplatesells\Views/modulesNotasCredito/otrosDatos') ?>

                    </div>


                    <div class="tab-pane fade" id="facturacionMX" role="tabpanel" aria-labelledby="otrosDatos">

                        <?= $this->include('julio101290\boilerplatesells\Views/modulesNotasCredito/facturacionMX') ?>

                    </div>
                    <div class="tab-pane fade" id="documentosRelacionados" role="tabpanel" aria-labelledby="documentosRelacionados">

                        <?= $this->include('julio101290\boilerplatesells\Views\modulesNotasCredito/documentosRelacionados') ?>

                    </div>


                </div>
            </div>



        </div>

    </div>

</div>



<div class="row">
    <div class="col-12">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title"><?= lang('newCreditNote.creditNoteDetail') ?></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>

                </div>
            </div>

            <div class="card-body">
                <div class="row">


                    <div class="col-md-12">

                        <div class="box-body">

                            <div class="box" style="overflow-y: scroll; height:250px;">




                                <div class="row">

                                    <!--=====================================
                                ENCABEZADO
                                ======================================-->
                                    <div class="col-1"> <?= lang('newCreditNote.fields.row') ?> </div>
                                    <div class="d-none d-lg-table-cell col-1 "> <?= lang('newCreditNote.fields.code') ?> </div>
                                    <div class="col-7"> <?= lang('newCreditNote.fields.description') ?> </div>
                                    <div class="col-1"><?= lang('newCreditNote.fields.amount') ?> </div>
                                    <div class="col-1"><?= lang('newCreditNote.fields.price') ?> </div>
                                    <div class="col-1"><?= lang('newCreditNote.fields.total') ?> </div>


                                </div>
                                <hr class="hr" />
                                <!--=====================================
                            ENTRADA PARA AGREGAR PRODUCTO
                            ======================================-->
                                <div class="rowProducts">

                                    <?php
                                    if (isset($listProducts)) {

                                        $list = "";
                                        foreach ($listProducts as $key => $value) {


                                            if (!isset($value["porcentIVARetenido"])) {

                                                $value["porcentIVARetenido"] = "0.00";
                                            }


                                            if (!isset($value["IVARetenido"])) {

                                                $value["IVARetenido"] = "0.00";
                                            }

                                            if (!isset($value["porcentISRRetenido"])) {

                                                $value["porcentISRRetenido"] = "0.00";
                                            }

                                            if (!isset($value["ISRRetenido"])) {

                                                $value["ISRRetenido"] = "0.00";
                                            }

                                            if (!isset($value["claveProductoSAT"])) {

                                                $value["claveProductoSAT"] = "";
                                            }

                                            if (!isset($value["claveUnidadSAT"])) {

                                                $value["claveUnidadSAT"] = "";
                                            }


                                            if (!isset($value["unidad"])) {

                                                $value["unidad"] = "";
                                            }

                                            if (!isset($value["lote"])) {

                                                $value["lote"] = "";
                                            }

                                            if (!isset($value["idAlmacen"])) {

                                                $value["idAlmacen"] = "";
                                            }

                                            if (!isset($value["predial"])) {

                                                $value["predial"] = "";
                                            }

                                            if (!isset($value["valorTasaExenta"])) {

                                                $value["valorTasaExenta"] = "";
                                            }

                                            $list .= <<<EOF
                                                    
                                                <div class="form-group row nuevoProduct"><div class="col-1"> <button type="button" class="btn btn-danger quitProduct"><span class="far fa-trash-alt"></button>
                                                <button type="button"  data-toggle="modal" data-target="#modelMoreInfoRow" class="btn btn-primary  btnInfo" ><span class="fa fa-fw fa-pencil-alt"></span></button>
                                                </div>
                                                <div class="col-1"> <input type="text" id="codeProduct" class="form-control codeProduct" name="codeProduct" value="$value[codeProduct]" required=""> 
                                                <input type="hidden" id="claveProductoSATR" class="form-control claveProductoSATR"  name="claveProductoSATR" value="$value[claveProductoSAT]" required="">
                                                <input type="hidden" id="claveUnidadSatR" class="form-control claveUnidadSatR"  name="claveUnidadSatR" value="$value[claveUnidadSAT]" required="">
                                                <input type="hidden" id="unidad" class="form-control unidad"  name="unidad" value="$value[unidad]" required="">
                                                <input type="hidden" id="lote" class="form-control lote"  name="lote" value="$value[lote]" required="">    
                                                <input type="hidden" id="idAlmacen" class="form-control idAlmacen"  name="idAlmacen" value="$value[idAlmacen]" required="">
                                                    
                                                </div>
                                                <div class="col-7"> <input type="text" id="description" class="form-control description" idproducto="$value[idProduct]" name="description" value="$value[description]" required=""> </div>
                                                <div class="col-1"> <input type="number" id="cant" class="form-control cant" name="cant" value="$value[cant]" required=""> 
                                                
                                                <input type="hidden" id="porcentIVARetenido" class="form-control porcentIVARetenido" name="porcentIVARetenido" value="$value[porcentIVARetenido]" required="">
                                                <input type="hidden" id="porcentISRRetenido" class="form-control porcentISRRetenido" name="porcentISRRetenido" value="$value[porcentISRRetenido]" required="">
                                                <input type="hidden" id="porcentTax" class="form-control porcentTax" name="porcentTax" value="$value[porcentTax]" required=""></div>
                                                <div class="col-1"> <input type="number" id="price" class="form-control price" name="price" value="$value[price]" required="">
                                                
                                                <input type="hidden" id="IVARetenido" class="form-control IVARetenido" name="IVARetenido" value="$value[IVARetenido]" required="">
                                                <input type="hidden" id="ISRRetenido" class="form-control ISRRetenido" name="ISRRetenido" value="$value[ISRRetenido]" required="">
                                                        
                                                <input type="hidden" id="tasaExcenta" class="form-control tasaExcenta" name="tasaExcenta" value='$value[valorTasaExenta]' required="">        
                                                
                                                <input type="hidden" id="predialR" class="form-control predialR" name="predialR" value='$value[predial]' required="">
                                                        
                                                        
                                                <input type="hidden" id="tax" class="form-control tax" name="tax" value="$value[tax]" required=""> </div>
                                                <div class="col-1"> <input readonly="" type="number" id="total" class="form-control total" name="total" value="$value[total]" required="">
                                                <input type="hidden" id="neto" class="form-control neto" name="neto" value="$value[neto]" required="">
                                                </div></div>
                                                    
                                                    
                                            EOF;
                                        }

                                        echo $list;
                                    }
                                    ?>

                                </div>

                                <input type="hidden" id="listProducts" name="listProducts" value="[]">
                                <!--=====================================
                            BOTÓN PARA AGREGAR PRODUCTO
                            ======================================-->


                                <hr>
                            </div>
                        </div>



                        <div class="box-footer" style="
                             text-align: right;
                             ">
                            <div class="row form-group">

                                <div class="col-7">

                                </div>
                                <div class="col-3" style="
                                     vertical-align: middle;
                                     ">
                                    <label style="vertical-align: sub;margin-bottom: 0px;"><?= lang('newCreditNote.subTotal') ?>:</label>
                                </div>

                                <div class="col-2">
                                    <input readonly="" type="text" id="subTotal" class="form-control subTotal" name="subTotal" value="<?= $subTotal ?>" style="
                                           text-align: right;
                                           ">
                                </div>


                            </div>


                            <div class="row form-group">

                                <div class="col-7">

                                </div>
                                <div class="col-3" style="
                                     vertical-align: middle;
                                     ">
                                    <label style="
                                           vertical-align: sub;
                                           "><?= lang('newCreditNote.tax') ?>:</label>
                                </div>

                                <div class="col-2">
                                    <input readonly="" type="text" id="totalImpuesto" class="form-control totalImpuesto" name="totalImpuesto" value="<?= $taxes ?>" style="
                                           text-align: right;
                                           ">
                                </div>


                            </div>


                            <div class="row form-group grupoTotalRetencionIVA" hidden>

                                <div class="col-7">

                                </div>
                                <div class="col-3" style="
                                     vertical-align: middle;
                                     ">
                                    <label style="
                                           vertical-align: sub;
                                           "><?= lang('newCreditNote.VATWithholding') ?>:</label>
                                </div>

                                <div class="col-2">
                                    <input readonly="" type="text" id="totalRetencionIVA" class="form-control totalRetencionIVA" name="totalRetencionIVA" value="<?= $IVARetenido ?>" style="
                                           text-align: right;
                                           ">
                                </div>

                            </div>


                            <div class="row form-group grupoTotalTasaExcenta" hidden>

                                <div class="col-7">

                                </div>
                                <div class="col-3" style="
                                     vertical-align: middle;
                                     ">
                                    <label style="
                                           vertical-align: sub;
                                           "><?= lang('newCreditNote.exemptRate') ?>:</label>
                                </div>

                                <div class="col-2">
                                    <input readonly="" type="text" id="totalExento" class="form-control totalExento" name="totalExento" value="<?= $totalExento ?>" style="
                                           text-align: right;
                                           ">
                                </div>

                            </div>


                            <div class="row form-group grupoTotalRetencionISR" hidden>

                                <div class="col-7">

                                </div>
                                <div class="col-3" style="
                                     vertical-align: middle;
                                     ">
                                    <label style="
                                           vertical-align: sub;
                                           "><?= lang('newCreditNote.ISRWithholding') ?>:</label>
                                </div>


                                <div class="col-2">
                                    <input readonly="" type="text" id="totalRetencionISR" class="form-control totalRetencionISR" name="totalRetencionISR" value="<?= $ISRRetenido ?>" style="
                                           text-align: right;
                                           ">
                                </div>


                            </div>

                            <div class="row form-group">

                                <div class="col-7">

                                </div>
                                <div class="col-3" style="
                                     vertical-align: middle;
                                     ">
                                    <label style="
                                           vertical-align: sub;
                                           "><?= lang('newCreditNote.total') ?>:</label>
                                </div>

                                <div class="col-2">
                                    <input readonly="" type="text" id="granTotal" class="form-control granTotal" name="granTotal" value="<?= $total ?>" style="
                                           text-align: right;
                                           ">
                                </div>


                            </div>


                            <button type="button" class="btn btn-primary pull-right btnSaveNotaCredito" data-toggle="modal">
                                <i class="fa far fa-save"> </i><?= lang('newCreditNote.save') ?></button>

                            <button type="button" class="btn bg-maroon btnPrint" data-toggle="modal" required="" data-placement="top" title="Imprimir">
                                <i class="fa fa-print"> </i> <?= lang('newCreditNote.savePrintClose') ?>
                            </button>

                            <button type="button" class="btn bg-maroon btnTimbrar" data-toggle="modal" required="" data-placement="top" title="Timbrar">
                                <i class="fas fa-qrcode"> </i> <?= lang('newCreditNote.stamp') ?>
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>




<?= $this->section('js') ?>


<script>
    $("#metodoPagoVenta").select2();
    $("#usoCFDIVenta").select2();
    $("#formaPagoVenta").select2();
    $("#regimenFiscalReceptor").select2();





    /**
     * Obtiene el ultimo folio por almacen
     */

    $("#custumerPago").on("change", function () {


        var idEmpresa = $(this).val();
        var idSucursal = $("#idSucursal").val();

        var datos = new FormData();
        datos.append("idEmpresa", idEmpresa);

        // TRAE ULTIMO FOLIO
        $.ajax({

            url: "<?= base_url('admin/pagos/getLastCode') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {

                console.log(respuesta);

                $("#codeSell").val(respuesta["folio"]);


            }

        });



        //TRAE DATOS EMPRESA

        $.ajax({

            url: "<?= base_url('admin/empresas/obtenerEmpresa') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {

                console.log(respuesta);
                if (respuesta["facturacionRD"] == "on") {

                    $(".comprobantesRD").removeAttr("hidden");

                } else {

                    $(".comprobantesRD").attr("hidden", true);

                }





            }

        });



    });


    $("#idSucursal").on("change", function () {


        var idEmpresa = $("#idEmpresaSells").val();
        var idSucursal = $(this).val();

        console.log("idSucursal", idSucursal);

        var datos = new FormData();
        datos.append("idEmpresa", idEmpresa);
        datos.append("idSucursal", idSucursal);

        // TRAE ULTIMO FOLIO
        $.ajax({

            url: "<?= base_url('admin/pagos/getLastCode') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {

                console.log(respuesta);

                $("#codeSell").val(respuesta["folio"]);


            }

        });

    });


    $(document).on('click', '#btnSaveChoferes', function (e) {


        var idChoferes = $("#idChoferes").val();
        var idEmpresa = $("#idEmpresaChoferes").val();
        var nombre = $("#nombre").val();
        var Apellido = $("#Apellido").val();


        if (idEmpresa == 0 || idEmpresa == null) {

            Toast.fire({
                icon: 'error',
                title: "Tiene que seleccionar la empresa"
            });
            return;
        }

        $("#btnSaveChoferes").attr("disabled", true);

        var datos = new FormData();
        datos.append("idChoferes", idChoferes);
        datos.append("idEmpresa", idEmpresa);
        datos.append("nombre", nombre);
        datos.append("Apellido", Apellido);


        $.ajax({

            url: "<?= base_url('admin/choferes/save') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            success: function (respuesta) {
                if (respuesta.match(/Correctamente.*/)) {

                    Toast.fire({
                        icon: 'success',
                        title: "Guardado Correctamente"
                    });

                    tableChoferes.ajax.reload();
                    $("#btnSaveChoferes").removeAttr("disabled");


                    $('#modalAddChoferes').modal('hide');
                } else {

                    Toast.fire({
                        icon: 'error',
                        title: respuesta
                    });

                    $("#btnSaveChoferes").removeAttr("disabled");


                }

            }

        }

        )

    });

    /**
     * Obtiene el ultimo folio por almacen
     */

    $("#idEmpresaSells").on("change", function () {


        var idEmpresa = $(this).val();
        var idSucursal = $("#idSucursal").val();

        var datos = new FormData();
        datos.append("idEmpresa", idEmpresa);
        datos.append("idSucursal", idSucursal);

        // TRAE ULTIMO FOLIO
        $.ajax({

            url: "<?= base_url('admin/pagos/getLastCode') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {

                console.log(respuesta);

                $("#codeSell").val(respuesta["folio"]);


            }

        });



        $("#idSucursal").select2({
            ajax: {
                url: "<?= site_url('admin/sucursales/getSucursalesAjax') ?>",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    // CSRF Hash
                    var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                    var csrfHash = $('.txt_csrfname').val(); // CSRF hash
                    var idEmpresa = $('.idEmpresaSells').val(); // CSRF hash

                    return {
                        searchTerm: params.term, // search term
                        [csrfName]: csrfHash, // CSRF Token
                        idEmpresa: idEmpresa // search term
                    };
                },
                processResults: function (response) {

                    // Update CSRF Token
                    $('.txt_csrfname').val(response.token);
                    return {
                        results: response.data
                    };
                },
                cache: true
            }
        });




        //TRAE DATOS EMPRESA

        $.ajax({

            url: "<?= base_url('admin/empresas/obtenerEmpresa') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {

                console.log(respuesta);
                if (respuesta["facturacionRD"] == "on") {

                    $(".comprobantesRD").removeAttr("hidden");

                } else {

                    $(".comprobantesRD").attr("hidden", true);

                }





            }

        });



    });



    /**
     * Obtiene el ultimo folio por almacen
     */

    $("#tipoComprobanteRD").on("change", function () {


        var idComprobantes_rd = $(this).val();

        var datos = new FormData();
        datos.append("idComprobantes_rd", idComprobantes_rd);

        // TRAE ULTIMO FOLIO
        $.ajax({

            url: "<?= base_url('admin/comprobantes_rd/getComprobantes_rd') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {

                console.log(respuesta);

                $("#folioComprobanteRD").val(respuesta["folioActual"]);

            }

        });

    });


    $("#idEmpresaSells").select2();

    // Initialize select2 storages
    $("#custumerNotaCredito").select2({
        ajax: {
            url: "<?= site_url('admin/custumers/getCustumersAjax') ?>",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                // CSRF Hash
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF hash
                var idEmpresa = $('.idEmpresaSells').val(); // CSRF hash

                return {
                    searchTerm: params.term, // search term
                    [csrfName]: csrfHash, // CSRF Token
                    idEmpresa: idEmpresa // search term
                };
            },
            processResults: function (response) {

                // Update CSRF Token
                $('.txt_csrfname').val(response.token);

                return {
                    results: response.data
                };
            },
            cache: true
        }
    });



    // Initialize select2 storages
    $("#idSucursal").select2({
        ajax: {
            url: "<?= site_url('admin/sucursales/getSucursalAjax') ?>",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                // CSRF Hash
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF hash
                var idEmpresa = $('.idEmpresaSells').val(); // CSRF hash

                return {
                    searchTerm: params.term, // search term
                    [csrfName]: csrfHash, // CSRF Token
                    idEmpresa: idEmpresa // search term
                };
            },
            processResults: function (response) {

                // Update CSRF Token
                $('.txt_csrfname').val(response.token);

                return {
                    results: response.data
                };
            },
            cache: true
        }
    });




    /**
     * Get data Custumer on change
     */

    $("#custumerPago").on("change", function () {


        var idCustumer = $(this).val();

        var datos = new FormData();
        datos.append("idCustumers", idCustumer);

        // Trae los datos del cliente
        $.ajax({

            url: "<?= base_url('admin/custumers/getCustumers') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {


                console.log(respuesta);

                $("#RFCReceptor").val(respuesta["taxID"]);
                $("#razonSocialReceptor").val(respuesta["razonSocial"]);
                $("#codigoPostalReceptor").val(respuesta["codigoPostal"]);
                $("#usoCFDIVenta").val(respuesta["usoCFDI"]);
                $("#usoCFDIVenta").trigger("change");
                $("#metodoPagoVenta").val(respuesta["metodoPago"]);
                $("#metodoPagoVenta").trigger("change");
                $("#formaPagoVenta").val(respuesta["formaPago"]);
                $("#formaPagoVenta").trigger("change");
                $("#regimenFiscalReceptor").val(respuesta["regimenFiscal"]);
                $("#regimenFiscalReceptor").trigger("change");


            }

        });




        var idCustumer = $(this).val();
        var idEmpresa = $("#idEmpresaSells").val();
        var idSucursal = $("#idSucursal").val();

        var datos = new FormData();
        datos.append("idCustumers", idCustumer);
        datos.append("idEmpresa", idEmpresa);
        datos.append("idSucursal", idSucursal);

        // Traemos las facturas pendientes de pago
        $.ajax({

            url: "<?= base_url('admin/sells/obtenerVentasPendientes') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,

            success: function (respuesta) {

                $(".rowProducts").html(respuesta);


            }

        });

    });





    $("#idEmpresaVehiculos").select2();

    // Initialize select2 storages
    $("#idChoferSell").select2({
        ajax: {
            url: "<?= site_url('admin/choferes/getChoferesAjax') ?>",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                // CSRF Hash
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF hash
                var idEmpresa = $('.idEmpresaSells').val(); // CSRF hash

                return {
                    searchTerm: params.term, // search term
                    [csrfName]: csrfHash, // CSRF Token
                    idEmpresa: idEmpresa // search term
                };
            },
            processResults: function (response) {

                // Update CSRF Token
                $('.txt_csrfname').val(response.token);

                return {
                    results: response.data
                };
            },
            cache: true
        }
    });


    $("#idTipoVehiculo").select2({
        ajax: {
            url: "<?= site_url('admin/vehiculos/getTipoVehiculoAjax') ?>",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                // CSRF Hash
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF hash
                var idEmpresa = $('.idEmpresaVehiculos').val(); // CSRF hash

                return {
                    searchTerm: params.term, // search term
                    [csrfName]: csrfHash, // CSRF Token
                    idEmpresa: idEmpresa // search term
                };
            },
            processResults: function (response) {

                // Update CSRF Token
                $('.txt_csrfname').val(response.token);
                return {
                    results: response.data
                };
            },
            cache: true
        }
    });



    // Initialize select2 storages
    $("#idVehiculoSell").select2({
        ajax: {
            url: "<?= site_url('admin/vehiculos/getVehiculossAjax') ?>",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                // CSRF Hash
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF hash
                var idEmpresa = $('.idEmpresaSells').val(); // CSRF hash

                return {
                    searchTerm: params.term, // search term
                    [csrfName]: csrfHash, // CSRF Token
                    idEmpresa: idEmpresa // search term
                };
            },
            processResults: function (response) {

                // Update CSRF Token
                $('.txt_csrfname').val(response.token);

                return {
                    results: response.data
                };
            },
            cache: true
        }
    });



    /**
     * Get data Custumer on change
     */

    $("#idVehiculoSell").on("change", function () {


        var idVehiculo = $(this).val();

        var datos = new FormData();
        datos.append("idVehiculos", idVehiculo);

        // TRAE ULTIMO FOLIO
        $.ajax({

            url: "<?= base_url('admin/vehiculos/getVehiculos') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {


                console.log(respuesta);

                $("#tipoVehiculo").val(respuesta["descripcionTipo"]);



            }

        });

    });






    $(document).on('click', '#btnSaveVehiculos', function (e) {


        var idVehiculos = $("#idVehiculos").val();
        var idEmpresa = $("#idEmpresaVehiculos").val();
        var idTipoVehiculo = $("#idTipoVehiculo").val();
        var descripcion = $("#descripcion").val();
        var placas = $("#placas").val();



        if (idEmpresa == 0 || idEmpresa == null) {

            Toast.fire({
                icon: 'error',
                title: "Tiene que seleccionar la empresa"
            });
            return;
        }

        if (idTipoVehiculo == 0 || idTipoVehiculo == null) {

            Toast.fire({
                icon: 'error',
                title: "Tiene que seleccionar el tipo de vehiculo"
            });
            return;
        }



        $("#btnSaveVehiculos").attr("disabled", true);

        var datos = new FormData();
        datos.append("idVehiculos", idVehiculos);
        datos.append("idEmpresa", idEmpresa);
        datos.append("idTipoVehiculo", idTipoVehiculo);
        datos.append("descripcion", descripcion);
        datos.append("placas", placas);


        $.ajax({

            url: "<?= base_url('admin/vehiculos/save') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            success: function (respuesta) {
                if (respuesta.match(/Correctamente.*/)) {

                    Toast.fire({
                        icon: 'success',
                        title: "Guardado Correctamente"
                    });

                    tableVehiculos.ajax.reload();
                    $("#btnSaveVehiculos").removeAttr("disabled");


                    $('#modalAddVehiculos').modal('hide');
                } else {

                    Toast.fire({
                        icon: 'error',
                        title: respuesta
                    });

                    $("#btnSaveVehiculos").removeAttr("disabled");


                }

            }

        }

        )

    });

    // Initialize select2 storages
    $("#tipoComprobanteRD").select2({
        ajax: {
            url: "<?= site_url('admin/comprobantes_rd/getTiposComprobanteAjax') ?>",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                // CSRF Hash
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF hash
                var idEmpresa = $('.idEmpresaSells').val(); // CSRF hash

                return {
                    searchTerm: params.term, // search term
                    [csrfName]: csrfHash, // CSRF Token
                    idEmpresa: idEmpresa // search term
                };
            },
            processResults: function (response) {

                // Update CSRF Token
                $('.txt_csrfname').val(response.token);

                return {
                    results: response.data
                };
            },
            cache: true
        }
    });







    /**
     * Save Quote
     */

    $(".btnSaveNotaCredito").on("click", function () {


        listProducts();
        saveNotaCredito();

    });


    function timbrarNotaCredito() {


        var UUID = $("#uuid").val();


        $(".btnTimbrar").attr("disabled", true);



        $.ajax({

            url: "<?= base_url('admin/timbrarNotaCredito/') ?>" + UUID,
            method: "get",
            cache: false,
            contentType: false,
            processData: false,
            //dataType:"json",
            success: function (respuesta) {


                if (respuesta.match(/success.*/)) {


                    Toast.fire({
                        icon: 'success',
                        title: "Timbrada Correctamente"
                    });

                    $(".btnTimbrar").removeAttr("disabled");


                    window.open("<?= base_url('admin/xml/generarPDFNotaCredito') ?>" + "/" + UUID, "_blank");
                    return true;

                } else {

                    Toast.fire({
                        icon: 'error',
                        title: respuesta
                    });

                    $(".btnTimbrar").removeAttr("disabled");

                    return false;


                }

            }

        }

        ).fail(function (jqXHR, textStatus, errorThrown) {

            if (jqXHR.status === 0) {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "No hay conexión.!" + jqXHR.responseText
                });

                $(".btnTimbrar").removeAttr("disabled");


            } else if (jqXHR.status == 404) {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Requested page not found [404]" + jqXHR.responseText
                });

                $(".btnTimbrar").removeAttr("disabled");

            } else if (jqXHR.status == 500) {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Internal Server Error [500]." + jqXHR.responseText
                });


                $(".btnTimbrar").removeAttr("disabled");

            } else if (textStatus === 'parsererror') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Requested JSON parse failed." + jqXHR.responseText
                });

                $(".btnTimbrar").removeAttr("disabled");

            } else if (textStatus === 'timeout') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Time out error." + jqXHR.responseText
                });

                $(".btnTimbrar").removeAttr("disabled");

            } else if (textStatus === 'abort') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Ajax request aborted." + jqXHR.responseText
                });

                $(".btnTimbrar").removeAttr("disabled");

            } else {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: 'Uncaught Error: ' + jqXHR.responseText
                });


                $(".btnTimbrar").removeAttr("disabled");

            }
        })

        return true;
    }


    /**
     * Al cambiar el importe a pagar valirdar que no sobrepase el saldo
     */


    $(".rowProducts").on("change", ".importeAPagar", function () {


        var importeAPagar = Number($(this).val());

        var saldo = $(this).parent().parent().find(".saldo").val();

        if (importeAPagar > saldo) {

            $(this).val("0.00");

            Toast.fire({
                icon: 'error',
                title: "El importe a pagar es mayor al saldo"
            });

            return;
        }


        if (importeAPagar < 0) {

            $(this).val("0.00");

            Toast.fire({
                icon: 'error',
                title: "El importe a pagar no puede ser menor a cero"
            });

            return;
        }


        listarPagos();

    });


    function saveNotaCredito() {


        var UUID = $("#uuid").val();
        var folio = $("#folio").val();
        var idQuote = $("#idQuote").val();
        var idEmpresa = $("#idEmpresaSells").val();
        var custumerNotaCredito = $("#custumerNotaCredito").val();
        var date = $("#date").val();
        var dateVen = $("#dateVen").val()
        var idUser = $("#idUser").val();
        var generalObservations = $("#obsevations").val();
        var listProducts = $("#listProducts").val();
        var quoteTo = $("#quoteTo").val();
        var delivaryTime = $("#delivaryTime").val();

        var subTotal = $("#subTotal").val();
        var taxes = $("#totalImpuesto").val();
        var IVARetenido = $("#totalRetencionIVA").val();
        var ISRRetenido = $("#totalRetencionISR").val();
        var total = $("#granTotal").val();

        var datePayment = $("#datePayment").val();
        var metodoPago = $("#metodoPago").val();
        var pago = $("#pago").val();
        var cambio = $("#cambio").val();

        var tipoComprobanteRD = $("#tipoComprobanteRD").val();
        var folioComprobanteRD = $("#folioComprobanteRD").val() ?? "";
        folioComprobanteRD = (folioComprobanteRD === "null") ? "" : folioComprobanteRD;


        var RFCReceptor = $("#RFCReceptor").val();
        var usoCFDIVenta = $("#usoCFDIVenta").val();
        var metodoPagoVenta = $("#metodoPagoVenta").val() === "null" ? "" : $("#metodoPagoVenta").val();
        var formaPagoVenta = $("#formaPagoVenta").val();
        var razonSocialReceptor = $("#razonSocialReceptor").val();
        var codigoPostalReceptor = $("#codigoPostalReceptor").val();
        var regimenFiscalReceptor = $("#regimenFiscalReceptor").val();


        var idVehiculo = $("#idVehiculoSell").val();
        var idChofer = $("#idChoferSell").val();
        var tipoVehiculo = $("#tipoVehiculo").val();

        var idSucursal = $("#idSucursal").val();

        var importeExento = $("#totalExento").val();

        var observacionesPago = $("#observacionesPago").val();

        var tipoDocumentoRelacionado = $("#tipoDocumentoRelacionado").val();
        var UUIDRelacion = $("#UUIDRelacion").val();



        if ($("#esFacturaGlobal").is(':checked')) {

            var esFacturaGlobal = "on";

        } else {

            var esFacturaGlobal = "off";

        }


        var periodicidad = $("#periodicidad").val();
        var mes = $("#mes").val();
        var anio = $("#anio").val();


        var ajaxGuardarConsulta = "ajaxGuardarConsulta";


        /**
         * Validaciones
         * 
         */
        if (idEmpresa == 0 || idEmpresa == "") {

            Toast.fire({
                icon: 'error',
                title: "<?= lang('newCreditNote.selectCompanie') ?>"
            });

            return false;

        }

        if (custumerNotaCredito == 0 || custumerNotaCredito == "") {

            Toast.fire({
                icon: 'error',
                title: "<?= lang('newCreditNote.selectCustumer') ?>"
            });

            return false;

        }


        if (idSucursal == 0 || idSucursal == "") {

            Toast.fire({
                icon: 'error',
                title: "<?= lang('newCreditNote.selectBranchoffice') ?>"
            });

            return false;

        }


        if (listProducts == "[]") {

            Toast.fire({
                icon: 'error',
                title: "<?= lang('newCreditNote.selectBranchoffice') ?>"
            });

            return false;

        }



        $(".btnSaveSells").attr("disabled", true);


        var datos = new FormData();
        datos.append("idCustumer", custumerNotaCredito);
        datos.append("idEmpresa", idEmpresa);
        datos.append("idQuote", idQuote);
        datos.append("folio", folio);
        datos.append("date", date);
        datos.append("idUser", idUser);
        datos.append("listProducts", listProducts);
        datos.append("generalObservations", generalObservations);
        datos.append("dateVen", dateVen);
        datos.append("quoteTo", quoteTo);
        datos.append("delivaryTime", delivaryTime);

        datos.append("subTotal", subTotal);
        datos.append("taxes", taxes);
        datos.append("IVARetenido", IVARetenido);
        datos.append("ISRRetenido", ISRRetenido);
        datos.append("total", total);

        datos.append("importPayment", pago);
        datos.append("importBack", cambio);
        datos.append("datePayment", datePayment);
        datos.append("metodoPago", metodoPago);

        datos.append("tipoComprobanteRD", tipoComprobanteRD);
        datos.append("folioComprobanteRD", folioComprobanteRD);

        datos.append("RFCReceptor", RFCReceptor);
        datos.append("usoCFDI", usoCFDIVenta);
        datos.append("metodoPago", metodoPagoVenta);
        datos.append("formaPago", formaPagoVenta);
        datos.append("razonSocialReceptor", razonSocialReceptor);
        datos.append("codigoPostalReceptor", codigoPostalReceptor);
        datos.append("regimenFiscalReceptor", regimenFiscalReceptor);

        datos.append("idVehiculo", idVehiculo);
        datos.append("idChofer", idChofer);
        datos.append("tipoVehiculo", tipoVehiculo);
        datos.append("idSucursal", idSucursal);

        datos.append("tasaCero", importeExento);

        datos.append("esFacturaGlobal", esFacturaGlobal);
        datos.append("periodicidad", periodicidad);
        datos.append("mes", mes);
        datos.append("anio", anio);

        datos.append("observacionesPago", observacionesPago);

        datos.append("tipoDocumentoRelacionado", tipoDocumentoRelacionado);
        datos.append("UUIDRelacion", UUIDRelacion);




        datos.append("UUID", UUID);
        $.ajax({

            url: "<?= base_url('admin/notasCredito/save') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            //dataType:"json",
            success: function (respuesta) {


                if (respuesta.match(/Correctamente.*/)) {


                    Toast.fire({
                        icon: 'success',
                        title: "Guardado Correctamente"
                    });

                    $(".btnSaveSells").removeAttr("disabled");

                    return true;

                } else {

                    Toast.fire({
                        icon: 'error',
                        title: respuesta
                    });

                    $(".btnSaveSells").removeAttr("disabled");

                    return false;


                }

            }

        }

        ).fail(function (jqXHR, textStatus, errorThrown) {

            if (jqXHR.status === 0) {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "No hay conexión.!" + jqXHR.responseText
                });

                $(".btnSaveSells").removeAttr("disabled");


            } else if (jqXHR.status == 404) {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Requested page not found [404]" + jqXHR.responseText
                });

                $(".btnSaveSells").removeAttr("disabled");

            } else if (jqXHR.status == 500) {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Internal Server Error [500]." + jqXHR.responseText
                });


                $(".btnSaveSells").removeAttr("disabled");

            } else if (textStatus === 'parsererror') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Requested JSON parse failed." + jqXHR.responseText
                });

                $(".btnSaveSells").removeAttr("disabled");

            } else if (textStatus === 'timeout') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Time out error." + jqXHR.responseText
                });

                $(".btnSaveSells").removeAttr("disabled");

            } else if (textStatus === 'abort') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Ajax request aborted." + jqXHR.responseText
                });

                $(".btnSaveSells").removeAttr("disabled");

            } else {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: 'Uncaught Error: ' + jqXHR.responseText
                });


                $(".btnSaveSells").removeAttr("disabled");

            }
        })

        return true;
    }



    $(".btnAddArticle").on("click", function () {


        cargaProductos();

    });




    function listarPagos() {




        var listaPagos = [];

        var idSell = $(".idSell");
        var serie = $(".serie");
        var folio = $(".folio");
        var fecha = $(".fecha");
        var fechaVen = $(".fechaVen");
        var total = $(".total");
        var saldo = $(".saldo");
        var importeAPagar = $(".importeAPagar");

        var importeTotalAPagar = 0;


        for (var i = 0; i < serie.length; i++) {

            if ($(importeAPagar[i]).val() > 0) {
                listaPagos.push({
                    "idSell": $(idSell[i]).val(),
                    "serie": $(serie[i]).val(),
                    "folio": $(folio[i]).val(),
                    "fecha": $(fecha[i]).val(),
                    "fechaVen": $(fechaVen[i]).val(),
                    "saldo": $(saldo[i]).val(),
                    "total": $(total[i]).val(),
                    "importeAPagar": $(importeAPagar[i]).val()

                });


                importeTotalAPagar = Number(Number(importeTotalAPagar) + Number($(importeAPagar[i]).val())).toFixed(2);
            }
        }

        $("#granTotal").val(importeTotalAPagar);


        //Asignamos el JSON en el input

        $("#listProducts").val(JSON.stringify(listaPagos));

    }


    /*=============================================
     IMPRIMIR CONSULTA
     =============================================*/

    function cargaProductos() {

        var empresa = $("#idEmpresaSells").val();

        if (empresa == "") {

            empresa = 0;
        }
        console.log("empresa:", empresa);
        tableProducts.ajax.url(`<?= base_url('admin/products/getAllProducts') ?>/` + empresa).load();

    }


    $(".btnPrint").on("click", function () {


        var saved = saveSell();
        var uuid = $("#uuid").val();


        if (saved == true) {

            var uuid = $("#uuid").val();


            window.open("<?= base_url('admin/sells/report') ?>" + "/" + uuid, "_blank");

            window.location = "<?= base_url('admin/sells') ?>";

        }

    })


    $(".btnTimbrar").on("click", function () {


        timbrarNotaCredito();

    });

    function listProducts() {




        var listProducts = [];

        var description = $(".description");
        var codeProduct = $(".codeProduct");
        var cant = $(".cant");
        var price = $(".price");
        var total = $(".total");
        var porcentTax = $(".porcentTax");
        var tax = $(".tax");
        var IVARetenidoRenglon = $(".IVARetenido");
        var porcentIVARetenido = $(".porcentIVARetenido");
        var porcentISRRetenido = $(".porcentISRRetenido");

        var tasaExcenta = $(".tasaExcenta");

        var ISRRetenidoRenglon = $(".ISRRetenido");
        var neto = $(".neto");
        var unidad = $(".unidad");
        var lote = $(".lote");
        var idAlmacen = $(".idAlmacen");

        var claveProductoSATR = $(".claveProductoSATR");
        var claveUnidadSatR = $(".claveUnidadSatR");

        var predial = $(".predialR");

        var subTotal = 0;
        var impuesto = 0;
        var netoTotal = 0;
        var IVARetenido = 0;
        var ISRRetenido = 0;

        var totalExcento = 0;
        var importeExento = 0;
        var importeExentoDetalle = 0;
        var valorTasaExenta = "";




        for (var i = 0; i < description.length; i++) {



            console.log("Tasa Excenta", $(tasaExcenta[i]).val());

            if ($(tasaExcenta[i]).val() == "on") {

                console.log("Tasa Excenta 2", $(tasaExcenta[i]).val());

                $(".grupoTotalTasaExcenta").removeAttr("hidden");

                importeExentoDetalle = Number($(total[i]).val()).toFixed(2);

                $(".grupoTotalTasaExcenta").removeAttr("hidden");

                valorTasaExenta = "on";

            } else {

                importeExentoDetalle = 0;
                valorTasaExenta = "off";

            }


            listProducts.push({
                "idProduct": $(description[i]).attr("idProducto"),
                "description": $(description[i]).val(),
                "codeProduct": $(codeProduct[i]).val(),
                "cant": $(cant[i]).val(),
                "price": $(price[i]).val(),
                "porcentTax": $(porcentTax[i]).val(),
                "tax": $(tax[i]).val(),
                "porcentIVARetenido": $(porcentIVARetenido[i]).val(),
                "porcentISRRetenido": $(porcentISRRetenido[i]).val(),

                "IVARetenido": $(IVARetenidoRenglon[i]).val(),
                "ISRRetenido": $(ISRRetenidoRenglon[i]).val(),

                "claveProductoSAT": $(claveProductoSATR[i]).val(),
                "claveUnidadSAT": $(claveUnidadSatR[i]).val(),

                "lote": $(lote[i]).val(),
                "idAlmacen": $(idAlmacen[i]).val(),
                "importeExento": importeExentoDetalle,

                "valorTasaExenta": valorTasaExenta,

                "predial": $(predial[i]).val(),

                "neto": $(neto[i]).val(),
                "unidad": $(unidad[i]).val(),
                "total": $(total[i]).val()
            });

            subTotal = Number(Number(subTotal) + Number($(total[i]).val())).toFixed(2);
            impuesto = Number(Number(impuesto) + Number($(tax[i]).val())).toFixed(2);
            IVARetenido = Number(Number(IVARetenido) + Number($(IVARetenidoRenglon[i]).val())).toFixed(2);
            ISRRetenido = Number(Number(ISRRetenido) + Number($(ISRRetenidoRenglon[i]).val())).toFixed(2);
            netoTotal = Number(Number(netoTotal) + Number($(neto[i]).val())).toFixed(2);

            if ($(tasaExcenta[i]).val() == "on") {

                totalExcento = Number(Number(totalExcento) + Number($(total[i]).val())).toFixed(2);

                $(".grupoTotalTasaExcenta").removeAttr("hidden");

            }



            if (IVARetenido > 0) {



                $(".grupoTotalRetencionIVA").removeAttr("hidden");

            }

            if (ISRRetenido > 0) {

                $(".grupoTotalRetencionISR").removeAttr("hidden");

            }


        }


        $("#subTotal").val(subTotal);
        $("#totalImpuesto").val(impuesto);
        $("#granTotal").val(netoTotal);
        $("#totalRetencionIVA").val(IVARetenido);
        $("#totalRetencionISR").val(ISRRetenido);

        $("#totalExento").val(totalExcento);

        //Asignamos el JSON en el input

        $("#listProducts").val(JSON.stringify(listProducts));

    }

    $("#idSucursal").select2();
    $("#tipoDocumentoRelacionado").select2();
    
   

<?php
echo '$("#usoCFDIVenta").val("' . $usoCFDIReceptor . '"); ';
echo '$("#usoCFDIVenta").trigger("change"); ';
echo '$("#metodoPagoVenta").val("' . $metodoPagoReceptor . '"); ';
echo '$("#metodoPagoVenta").trigger("change"); ';
echo '$("#formaPagoVenta").val("' . $formaPagoReceptor . '"); ';
echo '$("#formaPagoVenta").trigger("change"); ';
echo '$("#regimenFiscalReceptor").val("' . $regimenFiscalReceptor . '"); ';
echo '$("#regimenFiscalReceptor").trigger("change"); ';

if(isset($tipoDocumentoRelacionado)){
    echo '$("#tipoDocumentoRelacionado").val("'.$tipoDocumentoRelacionado.'").trigger("change");';
}
?>
</script>


<?= $this->endSection() ?>