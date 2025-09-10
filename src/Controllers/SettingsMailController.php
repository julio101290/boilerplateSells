<?php

namespace julio101290\boilerplatesells\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;
use julio101290\boilerplatesells\Models\SettingsMailModel;
use julio101290\boilerplatelog\Models\LogModel;
use julio101290\boilerplate\Controllers\BaseController;
use julio101290\boilerplate\Entities\Collection;
use julio101290\boilerplate\Models\GroupModel;
use CodeIgniter\Config\Services;
use App\Models\RegisterModel;
use App\Controllers\RegisterController;
use PHPMailer\PHPMailer;
use julio101290\boilerplatequotes\Models\QuotesModel;
use julio101290\boilerplatecompanies\Models\EmpresasModel;
use julio101290\boilerplatequotes\Controllers\QuotesController;
use julio101290\boilerplatesells\Models\SellsModel;
use julio101290\boilerplatesells\Models\EnlacexmlModel;
use julio101290\boilerplatesells\Controllers\SellsController;
use julio101290\boilerplateCFDI\Models\XmlModel;
use julio101290\boilerplatecomplementopago\Models\PagosModel;

/**
 * Class UserController.
 */
class SettingsMailController extends BaseController {

    use ResponseTrait;

    protected $group;
    protected $settingsMail;
    protected $log;
    protected $quotes;
    protected $sells;
    protected $sellsModel;
    protected $users;
    protected $empresa;
    protected $quotesController;
    protected $enlaceXML;
    protected $xml;
    protected $pagos;
    

    public function __construct() {
        $this->group = new GroupModel();
        $this->log = new LogModel();
        $this->settingsMail = new SettingsMailModel();
        $this->quotes = new QuotesModel();
        $this->empresa = new EmpresasModel();
        $this->quotesController = new QuotesController();
        $autorize = $this->authorize = Services::authorization();
        $this->enlaceXML = new EnlacexmlModel();
        $this->sells = new SellsController();
        $this->sellsModel = new SellsModel();
        $this->xml = new XmlModel();
        $this->pagos = new PagosModel();

        helper('menu');
    }

    public function index() {

        $datos = $this->settingsMail->where("id", 1)->first();

        $data["title"] = "Correo Electronicos";
        $data["subtitle"] = "Configuraciones de Correo Electronico";
        $data["data"] = $datos;

        return view('mailSettings', $data);
    }

    public function guardar() {


        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;

        //GUARDA CONFIGURACIONES
        $this->settingsMail->update(1, $_POST);

        //  return redirect()->to("/admin/hospital");
        return redirect()->back()->with('sweet-success', 'Actualizado Correctamente');
        // return redirect()->back()->with('sweet-success','Guardado Correctamente');
    }

    public function sendMailCotizacionesPDF($uuid, $correos) {


        $arregloCorreos = explode(",", $correos);

        //DATOS  REGISTRO
        $cotizaciones = $this->quotes->select("*")->where("uuid", $uuid)->first();

        $datos = $this->empresa->find($cotizaciones["idEmpresa"]);

        $mailsTarjets = "";

        $correo = $datos["email"];
        $SMTPDebug = $datos["smtpDebug"];
        $host = $datos["host"];

        if ($datos["SMTPAuth"] == 1) {
            $SMTPAuth = true;
        } else {
            $SMTPAuth = false;
        }

        $puerto = $datos["port"];
        $clave = $datos["pass"];

        $SMTPSeguridad = $datos["smptSecurity"];

// Load Composer's autoloader
// Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer\PHPMailer();

        try {


            //Server settings
            $mail->SMTPDebug = $SMTPDebug;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host = $host;                    // Set the SMTP server to send through
            $mail->SMTPAuth = $SMTPAuth;                                   // Enable SMTP authentication
            $mail->Username = $correo;                     // SMTP username
            $mail->Password = $clave;                               // SMTP password
            $mail->SMTPSecure = $SMTPSeguridad;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port = $puerto;

            $nombreEmpresa = "";
            // TCP port to connect to
            //Recipients
            $mail->setFrom($correo, $nombreEmpresa);

            foreach ($arregloCorreos as $key => $value) {
                try {

                    $mailAddress = $mail->addAddress($value, '');

                    if (!$mailAddress) {

                        echo "Error con el correo Electronico";
                        return;
                    }
                } catch (Exception $ex) {

                    echo $ex->getMessage();
                }
            }


            // Add a recipient
            //$mail->addReplyTo('info@example.com', 'Information');
            //mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');
            // Attachments
            $attachment = $this->quotesController->report($uuid, 1);
            $mail->AddStringAttachment($attachment, 'cotizacion' . $cotizaciones["folio"] . '.pdf', 'base64', 'application/pdf');

// Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = "Envio de Cotización";
            $mail->Body = "Adjuntamos la cotizacion";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            try {
                $send = $mail->send();
            } catch (Exception $ex) {

                echo $ex->getMessage();
                return;
            }

            if ($send) {
                echo 'Correo Enviado Correctamente';
            } else {
                echo 'Error al enviar el correo';
            }
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$e->ErrorInfo}";
        }
    }

    public function sendMailVentasPDF() {
        // Recibir desde POST
        $uuid = $this->request->getPost('uuid');
        $correos = $this->request->getPost('correos');

        if (empty($uuid) || empty($correos)) {
            return $this->response->setBody('Faltan parámetros');
        }

        // Normalizar: quitar espacios y separar por comas
        //$correos = trim($correos);
        //$arregloCorreos = array_map('trim', explode(',', $correos));
        $arregloCorreos = $correos;

        // Validar emails (filtrar inválidos)
        $validEmails = [];
        foreach ($arregloCorreos as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $validEmails[] = $email;
            }
        }

        if (empty($validEmails)) {
            return $this->response->setBody('No hay correos válidos');
        }

        // DATOS REGISTRO
        $ventas = $this->sellsModel->select("*")->where("uuid", $uuid)->first();
        if (!$ventas) {
            return $this->response->setBody('Venta no encontrada');
        }

        $datos = $this->empresa->find($ventas["idEmpresa"]);
        if (!$datos) {
            return $this->response->setBody('Datos de empresa no encontrados');
        }

        // Configuración SMTP
        $correo = $datos["email"];
        $SMTPDebug = $datos["smtpDebug"];
        $host = $datos["host"];
        $SMTPAuth = ($datos["SMTPAuth"] == 1);
        $puerto = $datos["port"];
        $clave = $datos["pass"];
        $SMTPSeguridad = $datos["smptSecurity"];

        $mail = new PHPMailer\PHPMailer();

        try {
            // Server settings
            $mail->SMTPDebug = $SMTPDebug;
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = $SMTPAuth;
            $mail->Username = $correo;
            $mail->Password = $clave;
            $mail->SMTPSecure = $SMTPSeguridad;
            $mail->Port = $puerto;

            $nombreEmpresa = isset($datos['nombre']) ? $datos['nombre'] : '';
            $mail->setFrom($correo, $nombreEmpresa);

            // Agregar destinatarios validados
            foreach ($validEmails as $to) {
                try {
                    $mail->addAddress($to);
                } catch (\Exception $ex) {
                    // si quieres, loguea $ex->getMessage()
                }
            }

            // --- (Aquí sigue tu lógica para adjuntar archivos y generar PDFs) ---
            $enlaceFacturas = $this->enlaceXML->mdlGetEnlacexmlDatos($ventas["id"]);

            $enlaceFacturas = $enlaceFacturas->get()->getResultArray();

            foreach ($enlaceFacturas as $key => $value) {
                if ($value["status"] == "" || $value["status"] == "NULL" || $value["status"] == "vigente") {
                    $mail->AddStringAttachment($value["archivoXML"], 'ArchivoXML_' . $ventas["folio"] . '.xml');

                    $datosXML = $this->xml->where('uuidTimbre', $value["uuidXML"])->first();
                    $xml = \PhpCfdi\CfdiCleaner\Cleaner::staticClean($datosXML["archivoXML"]);
                    $comprobante = \CfdiUtils\Nodes\XmlNodeUtils::nodeFromXmlString($xml);

                    $cfdiData = (new \PhpCfdi\CfdiToPdf\CfdiDataBuilder())->build($comprobante);
                    $htmlTranslator = new \PhpCfdi\CfdiToPdf\Builders\HtmlTranslators\PlatesHtmlTranslator(
                            ROOTPATH . 'vendor/julio101290/boilerplatecfdi/src/Libraries/templatesCFDI/',
                            'generic'
                    );
                    $converter = new \PhpCfdi\CfdiToPdf\Converter(
                            new \PhpCfdi\CfdiToPdf\Builders\Html2PdfBuilder($htmlTranslator)
                    );
                    $archivo = $converter->createPdf($cfdiData);

                    $mail->addAttachment($archivo, 'ArchivoPDF_' . $ventas["folio"] . '.pdf');
                }
            }

            $mail->isHTML(true);
            $mail->Subject = "Envio de facturas y notas de venta";
            $mail->Body = "Adjuntamos la nota de ventas y facturas";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if ($mail->send()) {
                return $this->response->setBody('Correo Enviado Correctamente');
            } else {
                return $this->response->setBody('Error al enviar el correo');
            }
        } catch (\Exception $e) {
            return $this->response->setBody("Error al enviar el correo: {$mail->ErrorInfo}");
        }
    }
    
    
    
     public function sendMailPagosPDF() {
        // Recibir desde POST
        $uuid = $this->request->getPost('uuid');
        $correos = $this->request->getPost('correos');

        if (empty($uuid) || empty($correos)) {
            return $this->response->setBody('Faltan parámetros');
        }

        // Normalizar: quitar espacios y separar por comas
        //$correos = trim($correos);
        //$arregloCorreos = array_map('trim', explode(',', $correos));
        $arregloCorreos = $correos;

        // Validar emails (filtrar inválidos)
        $validEmails = [];
        foreach ($arregloCorreos as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $validEmails[] = $email;
            }
        }

        if (empty($validEmails)) {
            return $this->response->setBody('No hay correos válidos');
        }

        // DATOS REGISTRO
        $pagos = $this->pagos->select("*")->where("uuid", $uuid)->first();
        if (!$pagos) {
            return $this->response->setBody('Venta no encontrada');
        }

        $datos = $this->empresa->find($pagos["idEmpresa"]);
        if (!$datos) {
            return $this->response->setBody('Datos de empresa no encontrados');
        }

        // Configuración SMTP
        $correo = $datos["email"];
        $SMTPDebug = $datos["smtpDebug"];
        $host = $datos["host"];
        $SMTPAuth = ($datos["SMTPAuth"] == 1);
        $puerto = $datos["port"];
        $clave = $datos["pass"];
        $SMTPSeguridad = $datos["smptSecurity"];

        $mail = new PHPMailer\PHPMailer();

        try {
            // Server settings
            $mail->SMTPDebug = $SMTPDebug;
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = $SMTPAuth;
            $mail->Username = $correo;
            $mail->Password = $clave;
            $mail->SMTPSecure = $SMTPSeguridad;
            $mail->Port = $puerto;

            $nombreEmpresa = isset($datos['nombre']) ? $datos['nombre'] : '';
            $mail->setFrom($correo, $nombreEmpresa);

            // Agregar destinatarios validados
            foreach ($validEmails as $to) {
                try {
                    $mail->addAddress($to);
                } catch (\Exception $ex) {
                    // si quieres, loguea $ex->getMessage()
                }
            }

            // --- (Aquí sigue tu lógica para adjuntar archivos y generar PDFs) ---
            $enlaceFacturas = $this->enlaceXML->mdlGetEnlacexmlDatos($ventas["id"]);

            $enlaceFacturas = $enlaceFacturas->get()->getResultArray();

            foreach ($enlaceFacturas as $key => $value) {
                if ($value["status"] == "" || $value["status"] == "NULL" || $value["status"] == "vigente") {
                    $mail->AddStringAttachment($value["archivoXML"], 'ArchivoXML_' . $ventas["folio"] . '.xml');

                    $datosXML = $this->xml->where('uuidTimbre', $value["uuidXML"])->first();
                    $xml = \PhpCfdi\CfdiCleaner\Cleaner::staticClean($datosXML["archivoXML"]);
                    $comprobante = \CfdiUtils\Nodes\XmlNodeUtils::nodeFromXmlString($xml);

                    $cfdiData = (new \PhpCfdi\CfdiToPdf\CfdiDataBuilder())->build($comprobante);
                    $htmlTranslator = new \PhpCfdi\CfdiToPdf\Builders\HtmlTranslators\PlatesHtmlTranslator(
                            ROOTPATH . 'vendor/julio101290/boilerplatecfdi/src/Libraries/templatesCFDI/',
                            'generic'
                    );
                    $converter = new \PhpCfdi\CfdiToPdf\Converter(
                            new \PhpCfdi\CfdiToPdf\Builders\Html2PdfBuilder($htmlTranslator)
                    );
                    $archivo = $converter->createPdf($cfdiData);

                    $mail->addAttachment($archivo, 'ArchivoPDF_' . $ventas["folio"] . '.pdf');
                }
            }

            $mail->isHTML(true);
            $mail->Subject = "Envio de facturas y notas de venta";
            $mail->Body = "Adjuntamos la nota de ventas y facturas";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if ($mail->send()) {
                return $this->response->setBody('Correo Enviado Correctamente');
            } else {
                return $this->response->setBody('Error al enviar el correo');
            }
        } catch (\Exception $e) {
            return $this->response->setBody("Error al enviar el correo: {$mail->ErrorInfo}");
        }
    }
    
    
    
    
     public function sendMailComplementoPDF() {
        // Recibir desde POST
        $uuid = $this->request->getPost('uuid');
        $correos = $this->request->getPost('correos');

        if (empty($uuid) || empty($correos)) {
            return $this->response->setBody('Faltan parámetros');
        }

        // Normalizar: quitar espacios y separar por comas
        //$correos = trim($correos);
        //$arregloCorreos = array_map('trim', explode(',', $correos));
        $arregloCorreos = $correos;

        // Validar emails (filtrar inválidos)
        $validEmails = [];
        foreach ($arregloCorreos as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $validEmails[] = $email;
            }
        }

        if (empty($validEmails)) {
            return $this->response->setBody('No hay correos válidos');
        }

        // DATOS REGISTRO
        $pagos = $this->pagos->select("*")->where("uuid", $uuid)->first();
        if (!$pagos) {
            return $this->response->setBody('Venta no encontrada');
        }

        $datos = $this->empresa->find($pagos["idEmpresa"]);
        if (!$datos) {
            return $this->response->setBody('Datos de empresa no encontrados');
        }

        // Configuración SMTP
        $correo = $datos["email"];
        $SMTPDebug = $datos["smtpDebug"];
        $host = $datos["host"];
        $SMTPAuth = ($datos["SMTPAuth"] == 1);
        $puerto = $datos["port"];
        $clave = $datos["pass"];
        $SMTPSeguridad = $datos["smptSecurity"];

        $mail = new PHPMailer\PHPMailer();

        try {
            // Server settings
            $mail->SMTPDebug = $SMTPDebug;
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = $SMTPAuth;
            $mail->Username = $correo;
            $mail->Password = $clave;
            $mail->SMTPSecure = $SMTPSeguridad;
            $mail->Port = $puerto;

            $nombreEmpresa = isset($datos['nombre']) ? $datos['nombre'] : '';
            $mail->setFrom($correo, $nombreEmpresa);

            // Agregar destinatarios validados
            foreach ($validEmails as $to) {
                try {
                    $mail->addAddress($to);
                } catch (\Exception $ex) {
                    // si quieres, loguea $ex->getMessage()
                }
            }

            // --- (Aquí sigue tu lógica para adjuntar archivos y generar PDFs) ---
            $enlaceFacturas = $this->enlaceXML->mdlGetEnlacexmlDatos($pagos["id"],"pag");

            $enlaceFacturas = $enlaceFacturas->get()->getResultArray();

            foreach ($enlaceFacturas as $key => $value) {
                if ($value["status"] == "" || $value["status"] == "NULL" || $value["status"] == "vigente") {
                    $mail->AddStringAttachment($value["archivoXML"], 'ArchivoXML_' . $pagos["folio"] . '.xml');

                    $datosXML = $this->xml->where('uuidTimbre', $value["uuidXML"])->first();
                    $xml = \PhpCfdi\CfdiCleaner\Cleaner::staticClean($datosXML["archivoXML"]);
                    $comprobante = \CfdiUtils\Nodes\XmlNodeUtils::nodeFromXmlString($xml);

                    $cfdiData = (new \PhpCfdi\CfdiToPdf\CfdiDataBuilder())->build($comprobante);
                    $htmlTranslator = new \PhpCfdi\CfdiToPdf\Builders\HtmlTranslators\PlatesHtmlTranslator(
                            ROOTPATH . 'vendor/julio101290/boilerplatecfdi/src/Libraries/templatesCFDI/',
                            'generic'
                    );
                    $converter = new \PhpCfdi\CfdiToPdf\Converter(
                            new \PhpCfdi\CfdiToPdf\Builders\Html2PdfBuilder($htmlTranslator)
                    );
                    $archivo = $converter->createPdf($cfdiData);

                    $mail->addAttachment($archivo, 'ArchivoPDF_' . $pagos["folio"] . '.pdf');
                }
            }

            $mail->isHTML(true);
            $mail->Subject = "Envio de facturas y notas de venta";
            $mail->Body = "Adjuntamos la nota de ventas y facturas";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if ($mail->send()) {
                return $this->response->setBody('Correo Enviado Correctamente');
            } else {
                return $this->response->setBody('Error al enviar el correo');
            }
        } catch (\Exception $e) {
            return $this->response->setBody("Error al enviar el correo: {$mail->ErrorInfo}");
        }
    }
    
    
}
