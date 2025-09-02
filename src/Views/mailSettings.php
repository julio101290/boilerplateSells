<?= $this->extend('julio101290\boilerplate\Views\layout\index') ?>

<!-- Section content -->
<?= $this->section('content') ?>



<section class="content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-12">

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Configuración Correo Electrónico</h3>
                    </div>



                    <form action="<?= base_url('admin/mailSettings') ?>/save" method="post">
                        <?= csrf_field() ?>

                        <div class="card-body">


                            <div class="form-group">
                                <label for="nombreEmpresa">E-Mail</label>
                                <input type="text" class="form-control" id="email" value="<?= $data["email"] ?>" name="email" placeholder="Inserte el Email">
                            </div>

                            <div class="form-group">
                                <label for="correoElectronico">Host</label>
                                <input type="text" class="form-control" value="<?= $data["host"] ?>" id="host" name="host" placeholder="Host">
                            </div>

                            <div class="form-group">
                                <label for="smtpDebug">Debug Client</label>
                                <select id="smtpDebug" name="smtpDebug">

                                    <option value="0">DEBUG_OFF</option>

                                    <option value="1">DEBUG_CLIENT</option>

                                    <option value="2">DEBUG_SERVER</option>

                                </select>

                            </div>

                            <div class="form-group">
                                <label for="SMTPAuth">SMTP Auth</label>
                                <select id="SMTPAuth" name="SMTPAuth">


                                    <option value=""></option>

                                    <option value="0">Sin autentificación</option>

                                    <option value="1">Con autentificación</option>


                                </select>

                            </div>


                            <div class="form-group">
                                <label for="smptSecurity">Seguridad</label>
                                <select id="smptSecurity" name="smptSecurity">


                                    <option value="">Sin Seguridad</option>
                                    <option value="ssl">Seguridad SSL</option>
                                    <option value="tls">Seguridad TLS</option>

                                </select>

                            </div>


                            <div class="form-group">
                                <label for="port">Puerto</label>
                                <input type="number" class="form-control" value="<?= $data["port"] ?>" id="port" name="port" placeholder="Puerto">
                            </div>

                            <div class="form-group">
                                <label for="port">Contraseña</label>
                                <input type="password" class="form-control" value="<?= $data["pass"] ?>" id="pass" name="pass" placeholder="Contraseña">
                            </div>


                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btnGuardar">Guardar</button>
                        </div>
                    </form>
                </div>





            </div>



        </div>

    </div>
</section>


<?= $this->endSection() ?>


<?= $this->section('js') ?>
<script>
    $("#smptSecurity").val("<?= $data["smptSecurity"] ?>");
    $("#SMTPAuth").val("<?= $data["SMTPAuth"] ?>");
    $("#smtpDebug").val("<?= $data["smtpDebug"] ?>");
</script>
<?= $this->endSection() ?>