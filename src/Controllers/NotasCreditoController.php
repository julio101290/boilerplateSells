<?php

namespace julio101290\boilerplatesells\Controllers;

use App\Controllers\BaseController;
use julio101290\boilerplateproducts\Models\ProductsModel;
use \App\Models\UserModel;
use julio101290\boilerplatelog\Models\LogModel;
use julio101290\boilerplatequotes\Models\QuotesModel;
use julio101290\boilerplatesells\Models\SellsModel;
use julio101290\boilerplatestorages\Models\StoragesModel;
use julio101290\boilerplatesells\Models\SellsDetailsModel;
use CodeIgniter\API\ResponseTrait;
use julio101290\boilerplatecompanies\Models\EmpresasModel;
use julio101290\boilerplatecustumers\Models\CustumersModel;
use julio101290\boilerplatesells\Models\PaymentsModel;
use julio101290\boilerplatecomprobanterd\Models\Comprobantes_rdModel;
use julio101290\boilerplatevehicles\Models\VehiculosModel;
use julio101290\boilerplatedrivers\Models\ChoferesModel;
use julio101290\boilerplatevehicles\Models\TipovehiculoModel;
use julio101290\boilerplatebranchoffice\Models\BranchofficesModel;
use julio101290\boilerplatecashtonnage\Models\ArqueoCajaModel;
use julio101290\boilerplateinventory\Models\SaldosModel;
use julio101290\boilerplatesells\Models\NotasCreditoModel;
use julio101290\boilerplateCFDI\Models\XmlModel;
use julio101290\boilerplatecomplementopago\Models\PagosModel;
use julio101290\boilerplatesells\Models\EnlacexmlModel;
use julio101290\boilerplatesells\Models\NotaCreditoDetailsModel;
use julio101290\boilerplateCFDI\Controllers\XmlController;

class NotasCreditoController extends BaseController {

    use ResponseTrait;

    protected $log;
    protected $sells;
    protected $storages;
    protected $sellsDetail;
    protected $sucursales;
    protected $empresa;
    protected $user;
    protected $custumer;
    protected $payments;
    protected $products;
    protected $quotes;
    protected $comprobantesRD;
    protected $vehiculos;
    protected $choferes;
    protected $tiposVehiculo;
    protected $arqueoCaja;
    protected $saldos;
    protected $pagos;
    protected $notasCredito;
    protected $notaCreditoDetalle;
    protected $enlace;
    protected $xmlController;

    public function __construct() {

        $this->log = new LogModel();

        $this->sells = new SellsModel();
        $this->sellsDetail = new SellsDetailsModel();
        $this->empresa = new EmpresasModel();
        $this->user = new UserModel();
        $this->custumer = new CustumersModel();
        $this->payments = new PaymentsModel();
        $this->products = new ProductsModel();
        $this->quotes = new QuotesModel();
        $this->comprobantesRD = new Comprobantes_rdModel();
        $this->vehiculos = new VehiculosModel();
        $this->choferes = new ChoferesModel();
        $this->tiposVehiculo = new TipovehiculoModel();
        $this->sucursales = new BranchofficesModel();
        $this->arqueoCaja = new ArqueoCajaModel();
        $this->saldos = new SaldosModel();
        $this->pagos = new PagosModel();
        $this->notasCredito = new NotasCreditoModel();
        $this->enlace = new EnlacexmlModel();
        $this->notaCreditoDetalle = new NotaCreditoDetailsModel();
        $this->xmlController = new XmlController;

        helper('menu');
        helper('utilerias');
    }

    public function index() {

        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('admin');
        }
        helper('auth');

        $idUser = user()->id;
        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }


        if ($this->request->isAJAX()) {


            $request = service('request');

            $draw = intval($request->getGet('draw'));
            $start = intval($request->getGet('start'));
            $length = intval($request->getGet('length'));
            $search = $request->getGet('search')['value'] ?? '';

            // Obtener el builder desde el modelo
            $builder = $this->notasCredito->mdlGetNotasCredito($empresasID);

            // Columnas completas en orden para mapear índice de ordenamiento
            $columns = [
                'a.UUID',
                'a.id',
                // nombre concatenado:
                'b.firstname',
                'b.lastname',
                'a.idCustumer',
                'a.folio',
                'a.date',
                'b.email',
                'a.dateVen',
                'a.total',
                'a.taxes',
                'a.subTotal',
                'a.balance',
                'a.delivaryTime',
                'a.generalObservations',
                'a.idQuote',
                'a.IVARetenido',
                'a.ISRRetenido',
                'a.idSucursal',
                'a.RFCReceptor',
                'a.usoCFDI',
                'a.metodoPago',
                'a.formaPago',
                'a.razonSocialReceptor',
                'a.codigoPostalReceptor',
                'a.regimenFiscalReceptor',
                'a.idVehiculo',
                'a.idChofer',
                'a.tipoVehiculo',
                'a.idArqueoCaja',
                'a.created_at',
                'a.updated_at',
                'a.deleted_at',
            ];

            // Filtro de búsqueda (buscando en campos relevantes)
            if (!empty($search)) {
                $builder->groupStart();
                $builder->like('b.firstname', $search)
                        ->orLike('b.lastname', $search)
                        ->orLike('a.folio', $search)
                        ->orLike('a.UUID', $search)
                        ->orLike('a.idCustumer', $search)
                        ->groupEnd();
            }

            // Contar total registros sin filtro
            $totalRecords = $builder->countAllResults(false);

            // Contar total registros con filtro
            $totalFiltered = $builder->countAllResults(false);

            // Orden y paginación
            $orderColumnIndex = $request->getGet('order')[0]['column'] ?? 0;
            $orderDir = $request->getGet('order')[0]['dir'] ?? 'asc';

            $orderColumn = $columns[$orderColumnIndex] ?? 'a.id';

            $builder->orderBy($orderColumn, $orderDir)
                    ->limit($length, $start);

            $data = $builder->get()->getResultArray();

            // Para devolver solo columnas esperadas (puedes mapear aquí si quieres)
            // En este ejemplo se devuelve todo el arreglo directo

            return $this->response->setJSON([
                        "draw" => $draw,
                        "recordsTotal" => $totalRecords,
                        "recordsFiltered" => $totalFiltered,
                        "data" => $data,
            ]);
        }


        $tiposVehiculo = $this->tiposVehiculo->mdlGetTipovehiculoArray($empresasID);

        $titulos["tiposVehiculo"] = $tiposVehiculo;
        $titulos["listaTitle"] = "Administracion de ventas";
        $titulos["listaSubtitle"] = "Muestra la lista de ventas";

        //$data["data"] = $datos;
        return view('julio101290\boilerplatesells\Views\notascredito', $titulos);
    }

    public function notasCreditoFilters($desdeFecha, $hastaFecha, $todas, $empresa, $sucursal, $cliente) {


        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('admin');
        }


        helper('auth');

        $idUser = user()->id;
        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }


        if ($this->request->isAJAX()) {

            $params = [
                'draw' => $this->request->getGet('draw'),
                'start' => $this->request->getGet('start'),
                'length' => $this->request->getGet('length'),
                'order' => $this->request->getGet('order'),
                'columns' => $this->request->getGet('columns'),
            ];

            $todas = $this->request->getGet('todas') ?? null;
            $empresa = intval($this->request->getGet('empresa') ?? 0);
            $sucursal = intval($this->request->getGet('sucursal') ?? 0);
            $cliente = intval($this->request->getGet('cliente') ?? 0);

            $datos = $this->notasCredito->mdlGetNotasCreditoFilters(
                    $empresasID,
                    $desdeFecha,
                    $hastaFecha,
                    $todas,
                    $empresa,
                    $sucursal,
                    $cliente,
                    $params
            );

            return $this->response->setJSON([
                        'draw' => intval($params['draw']),
                        'recordsTotal' => $datos['recordsTotal'],
                        'recordsFiltered' => $datos['recordsFiltered'],
                        'data' => $datos['data'],
            ]);
        }
    }

    /**
     * 
     * @return type
     */
    public function newNotaCredito() {
        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('admin');
        }

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;

        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }


        $fechaActual = fechaMySQLADateHTML5(fechaHoraActual());

        $idMax = "0";

        $titulos["idMax"] = $idMax;
        $titulos["idSell"] = $idMax;
        $titulos["folio"] = "0";
        $titulos["fecha"] = $fechaActual;
        $titulos["userName"] = $userName;
        $titulos["idUser"] = $idUser;
        $titulos["contact"] = "";
        $titulos["idQuote"] = "0";
        $titulos["codeCustumer"] = "";
        $titulos["observations"] = "";
        $titulos["taxes"] = "0.00";
        $titulos["IVARetenido"] = "0.00";
        $titulos["ISRRetenido"] = "0.00";
        $titulos["subTotal"] = "0.00";
        $titulos["total"] = "0.00";
        $titulos["formaPago"] = $this->catalogosSAT->formasDePago40()->searchByField("texto", "%%", 99999);
        $titulos["usoCFDI"] = $this->catalogosSAT->usosCfdi40()->searchByField("texto", "%%", 99999);
        $titulos["metodoPago"] = $this->catalogosSAT->metodosDePago40()->searchByField("texto", "%%", 99999);
        $titulos["regimenFiscal"] = $this->catalogosSAT->regimenesFiscales40()->searchByField("texto", "%%", 99999);

        $titulos["RFCReceptor"] = "";
        $titulos["regimenFiscalReceptor"] = "";
        $titulos["usoCFDIReceptor"] = "";
        $titulos["metodoPagoReceptor"] = "";
        $titulos["formaPagoReceptor"] = "";
        $titulos["razonSocialReceptor"] = "";
        $titulos["codigoPostalReceptor"] = "";

        $titulos["folioComprobanteRD"] = "0";
        $titulos["totalExento"] = "0";

        $titulos["uuid"] = generaUUID();

        $titulos["uuidRelacion"] = "";

        $tiposVehiculo = $this->tiposVehiculo->mdlGetTipovehiculoArray($empresasID);

        $titulos["title"] = "Notas de Crédito"; //lang('registerNew.title');
        $titulos["subtitle"] = "Captura de Notas de Crédito"; // lang('registerNew.subtitle');
        $titulos["tiposVehiculo"] = $tiposVehiculo;

        return view('julio101290\boilerplatesells\Views\newNotaCredito', $titulos);
    }

    /**
     * Get Last Code
     */
    public function getLastCode() {

        $idEmpresa = $this->request->getPost("idEmpresa");
        $idSucursal = $this->request->getPost("idSucursal");
        $result = $this->notasCredito->selectMax("folio")
                ->where("idEmpresa", $idEmpresa)
                ->where("idSucursal", $idSucursal)
                ->first();

        if ($result["folio"] == null) {

            $result["folio"] = 1;
        } else {

            $result["folio"] = $result["folio"] + 1;
        }

        echo json_encode($result);
    }

    /**
     * Get Last Code
     */
    public function getLastCodeInterno($idEmpresa, $idSucursal) {


        $result = $this->notasCredito->selectMax("folio")
                ->where("idEmpresa", $idEmpresa)
                ->where("idSucursal", $idSucursal)
                ->first();

        if ($result["folio"] == null) {

            $result["folio"] = 1;
        } else {

            $result["folio"] = $result["folio"] + 1;
        }

        return $result["folio"];
    }

    /*
     * Editar Cotizacion
     */

    public function editNotaCredito($uuid) {

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;

        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('admin');
        }


        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('admin');
        }

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;

        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }


        $notaCredito = $this->notasCredito->mdlGetNotascreditoUUID($uuid, $empresasID);

        $listProducts = json_decode($notaCredito["listProducts"], true);

        $titulos["idNotaCredito"] = $notaCredito["id"];
        $titulos["folio"] = $notaCredito["folio"];
        $titulos["idCustumer"] = $notaCredito["idCustumer"];
        $titulos["nameCustumer"] = $notaCredito["nameCustumer"];
        $titulos["idEmpresa"] = $notaCredito["idEmpresa"];
        $titulos["nombreEmpresa"] = $notaCredito["nombreEmpresa"];

        $titulos["idUser"] = $idUser;
        $titulos["userName"] = $userName;
        $titulos["listProducts"] = $listProducts;

        $titulos["subTotal"] = number_format($notaCredito["subTotal"], 2, ".");
        $titulos["total"] = number_format($notaCredito["total"], 2, ".");
        $titulos["taxes"] = number_format($notaCredito["taxes"], 2, ".");
        $titulos["IVARetenido"] = number_format($notaCredito["IVARetenido"], 2, ".");
        $titulos["totalExento"] = $notaCredito["tasaCero"];
        $titulos["ISRRetenido"] = number_format($notaCredito["ISRRetenido"], 2, ".");
        $titulos["fecha"] = $notaCredito["date"];
        $titulos["dateVen"] = $notaCredito["dateVen"];

        $titulos["observations"] = $notaCredito["generalObservations"];
        $titulos["uuid"] = $notaCredito["UUID"];
        $titulos["formaPago"] = $this->catalogosSAT->formasDePago40()->searchByField("texto", "%%", 99999);
        $titulos["usoCFDI"] = $this->catalogosSAT->usosCfdi40()->searchByField("texto", "%%", 99999);
        $titulos["metodoPago"] = $this->catalogosSAT->metodosDePago40()->searchByField("texto", "%%", 99999);
        $titulos["regimenFiscal"] = $this->catalogosSAT->regimenesFiscales40()->searchByField("texto", "%%", 99999);

        $titulos["RFCReceptor"] = $notaCredito["RFCReceptor"];
        $titulos["regimenFiscalReceptor"] = $notaCredito["regimenFiscalReceptor"];
        $titulos["usoCFDIReceptor"] = $notaCredito["usoCFDI"];
        $titulos["metodoPagoReceptor"] = $notaCredito["metodoPago"];
        $titulos["formaPagoReceptor"] = $notaCredito["formaPago"];
        $titulos["razonSocialReceptor"] = $notaCredito["razonSocialReceptor"];
        $titulos["codigoPostalReceptor"] = $notaCredito["codigoPostalReceptor"];
        $titulos["folioComprobanteRD"] = "";
        $titulos["idQuote"] = "";

        $titulos["idSucursal"] = $notaCredito["idSucursal"];
        $sucursal = $this->sucursales->select("*")->where("id", $titulos["idSucursal"])->first();
        $titulos["nombreSucursal"] = $sucursal["key"] . " " . $sucursal["name"];

        $titulos["uuidRelacion"] = $notaCredito["UUIDRelacion"];
        $titulos["tipoDocumentoRelacionado"] = $notaCredito["tipoDocumentoRelacionado"];

        $titulos["title"] = "Editar Nota de Credito";
        $titulos["subtitle"] = "Edición de Nota de Crédito";

        return view('julio101290\boilerplatesells\Views\newNotaCredito', $titulos);
    }

    /*
     * Save or Update
     */

    public function save() {

        $auth = service('authentication');

        if (!$auth->check()) {
            $this->session->set('redirect_url', current_url());
            return redirect()->route('admin');
        }

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;

        $datos = $this->request->getPost();

        $this->notasCredito->db->transBegin();

        $existsNotaCredito = $this->notasCredito->where("UUID", $datos["UUID"])->countAllResults();

        $listProducts = json_decode($datos["listProducts"], true);

        $datosSucursal = $this->sucursales->find($datos["idSucursal"]);

        $datos["idArqueoCaja"] = 0;

        if ($datosSucursal["arqueoCaja"] == "on") {


            $datosArqueoCaja = $this->arqueoCaja->mdlObtenerIdArqueo($datos["idEmpresa"], $datos["idSucursal"], $datos["date"]);

            if (!isset($datosArqueoCaja["id"])) {


                $this->notasCredito->db->transRollback();

                echo "No hay habilitado arqueo de caja";

                return;
            } else {


                $datos["idArqueoCaja"] = $datosArqueoCaja["id"];
            }
        }

        /**
         * if is new sell
         */
        if ($existsNotaCredito == 0) {


            $ultimoFolio = $this->getLastCodeInterno($datos["idEmpresa"], $datos["idSucursal"]);

            $empresa = $this->empresa->find($datos["idEmpresa"]);

            if ($datos["tipoComprobanteRD"] != "")
                $comprobante = $this->comprobantesRD->find($datos["tipoComprobanteRD"]);

            if ($empresa["facturacionRD"] == "on") {


                if ($datos["tipoComprobanteRD"] == "") {

                    $this->notasCredito->db->transRollback();

                    echo "No se selecciono tipo comprobante";
                    return;
                }


                if ($datos["folioComprobanteRD"] == "") {

                    $this->notasCredito->db->transRollback();

                    echo "No hay folio Comprobante";
                    return;
                }


                if ($datos["folioComprobanteRD"] > $comprobante["folioFinal"]) {

                    $this->notasCredito->db->transRollback();

                    echo "Se agotaron los folio son hasta  $comprobante[folioFinal] y van en $datos[folioComprobanteRD]";
                    return;
                }

                if ($datos["folioComprobanteRD"] < $comprobante["folioInicial"]) {

                    $this->notasCredito->db->transRollback();

                    echo "Folio fuera de rango  $comprobante[folioInicial] y van en $datos[folioComprobanteRD]";
                    return;
                }


                if ($datos["date"] < $comprobante["desdeFecha"]) {

                    $this->sells->notasCredito->transRollback();

                    echo "fecha fuera de rango limite inferior $comprobante[desdeFecha] fecha venta $datos[date]";
                    return;
                }


                if ($datos["date"] > $comprobante["hastaFecha"]) {

                    $this->sells->notasCredito->transRollback();

                    echo "fecha fuera de rango,  limite superior $comprobante[desdeFecha]  fecha venta $datos[date]";
                    return;
                }
            }


            $datos["folio"] = $ultimoFolio;

            $datos["balance"] = "0";
            $datos["balance"] = $datos["total"];

            try {

                $datos1 = array_intersect_key($datos, array_flip($this->notasCredito->allowedFields));
                $datos1["tipoComprobanteRD"] = "";
                if ($this->notasCredito->insert($datos1) === false) {


                    $errores = $this->notasCredito->errors();

                    $listErrors = "";

                    foreach ($errores as $field => $error) {

                        $listErrors .= $error . " ";
                    }

                    echo $listErrors;

                    return;
                }

                $idNotaCreditoInserted = $this->notasCredito->getInsertID();

                // save datail

                foreach ($listProducts as $key => $value) {

                    $datosDetalle["idNotaCredito"] = $idNotaCreditoInserted;
                    $datosDetalle["idProduct"] = $value["idProduct"];
                    $datosDetalle["description"] = $value["description"];
                    $datosDetalle["unidad"] = $value["unidad"];
                    $datosDetalle["codeProduct"] = $value["codeProduct"];
                    $datosDetalle["cant"] = $value["cant"];
                    $datosDetalle["price"] = $value["price"];
                    $datosDetalle["porcentTax"] = $value["porcentTax"];

                    $datosDetalle["porcentIVARetenido"] = $value["porcentIVARetenido"];
                    $datosDetalle["porcentISRRetenido"] = $value["porcentISRRetenido"];
                    $datosDetalle["IVARetenido"] = $value["IVARetenido"];
                    $datosDetalle["ISRRetenido"] = $value["ISRRetenido"];

                    $datosDetalle["claveProductoSAT"] = $value["claveProductoSAT"];
                    $datosDetalle["claveUnidadSAT"] = $value["claveUnidadSAT"];

                    $datosDetalle["lote"] = $value["lote"];
                    $datosDetalle["idAlmacen"] = $value["idAlmacen"];

                    $datosDetalle["tax"] = $value["tax"];
                    $datosDetalle["total"] = $value["total"];
                    $datosDetalle["importeExento"] = $value["importeExento"];
                    $datosDetalle["neto"] = $value["neto"];

                    $datosDetalle["predial"] = $value["predial"];

                    //Valida Stock
                    $products = $this->products->find($datosDetalle["idProduct"]);

                    if ($this->notaCreditoDetalle->save($datosDetalle) === false) {

                        echo "error al insertar el producto $datosDetalle[idProducto]";

                        $this->notasCredito->db->transRollback();
                        return;
                    }
                }

                /*
                  if ($datos["idQuote"] > 0) {

                  echo "Inserted" . $idSellInserted;
                  $newSellQuote["idSell"] = $idSellInserted;

                  if ($this->quotes->update($datos["idQuote"], $newSellQuote) === false) {

                  echo "error al actualizar el stock del producto $datosDetalle[idProducto]";

                  $this->sellsDetail->db->transRollback();

                  return;
                  }
                  }
                 */



                //ACTUALIZAMOS FOLIO ACTUAL COMPROBANTE

                if ($empresa["facturacionRD"] == "on") {

                    $comprobante = $this->comprobantesRD->find($datos["tipoComprobanteRD"]);

                    $folio = $comprobante["folioActual"] + 1;

                    $datosComprobante["folioActual"] = $folio;

                    if ($this->comprobantesRD->update($datos["tipoComprobanteRD"], $datosComprobante))
                        ;
                }


                $datosBitacora["description"] = "Se guardo la nota de credito con los siguientes datos" . json_encode($datos);
                $datosBitacora["user"] = $userName;

                $this->log->save($datosBitacora);

                $this->notasCredito->db->transCommit();
                echo "Guardado Correctamente";
            } catch (\PHPUnit\Framework\Exception $ex) {


                echo "Error al guardar " . $ex->getMessage();
            }
        } else {




            $backNotaCredito = $this->notasCredito->where("UUID", $datos["UUID"])->first();
            $listProductsBack = json_decode($backNotaCredito["listProducts"], true);

            $datos["folio"] = $backNotaCredito["folio"];

            if ($this->notasCredito->update($backNotaCredito["id"], $datos) == false) {

                $errores = $this->notasCredito->errors();
                $listError = "";
                foreach ($errores as $field => $error) {

                    $listError .= $error . " ";
                }

                echo $listError;

                return;
            } else {






                $this->notaCreditoDetalle->select("*")->where("idNotaCredito", $backNotaCredito["id"])->delete();
                $this->notaCreditoDetalle->purgeDeleted();
                foreach ($listProducts as $key => $value) {

                    $datosDetalle["idNotaCredito"] = $backNotaCredito["id"];
                    $datosDetalle["idProduct"] = $value["idProduct"];
                    $datosDetalle["description"] = $value["description"];
                    $datosDetalle["unidad"] = $value["unidad"];
                    $datosDetalle["codeProduct"] = $value["codeProduct"];
                    $datosDetalle["cant"] = $value["cant"];
                    $datosDetalle["price"] = $value["price"];
                    $datosDetalle["porcentTax"] = $value["porcentTax"];

                    $datosDetalle["porcentIVARetenido"] = $value["porcentIVARetenido"];
                    $datosDetalle["porcentISRRetenido"] = $value["porcentISRRetenido"];
                    $datosDetalle["IVARetenido"] = $value["IVARetenido"];
                    $datosDetalle["ISRRetenido"] = $value["ISRRetenido"];

                    $datosDetalle["claveProductoSAT"] = $value["claveProductoSAT"];
                    $datosDetalle["claveUnidadSAT"] = $value["claveUnidadSAT"];
                    $datosDetalle["lote"] = $value["lote"];
                    $datosDetalle["idAlmacen"] = $value["idAlmacen"];

                    $datosDetalle["tax"] = $value["tax"];
                    $datosDetalle["total"] = $value["total"];
                    $datosDetalle["neto"] = $value["neto"];

                    if ($this->notaCreditoDetalle->save($datosDetalle) === false) {

                        $errores = $this->notaCreditoDetalle->errors();
                        $listError = "";
                        foreach ($errores as $field => $error) {

                            $listError .= $error . " ";
                        }

                        echo "error al insertar el producto $datosDetalle[idProduct] $errores";

                        $this->notaCreditoDetalle->db->transRollback();
                        return;
                    }
                }


                $datosBitacora["description"] = "Se actualizo" . json_encode($datos) .
                        " Los datos anteriores son" . json_encode($backNotaCredito);
                $datosBitacora["user"] = $userName;
                $this->log->save($datosBitacora);

                echo "Actualizado Correctamente";
                $this->notaCreditoDetalle->db->transCommit();
                return;
            }
        }

        return;
    }

    public function delete($id) {
        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;

        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('admin');
        }


        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('admin');
        }

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;

        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }

        /**
         * Verificamos que no este timbrada
         * 
         */
        $enlace = $this->enlace
                        ->select("*")
                        ->where("idDocumento", $id)
                        ->where("tipo", "NCR")->countAllResults();

        if ($enlace > 0) {

            return $this->failNotFound('No se puede eliminar, la nota de credito tiene timbres enlazados');
        }


        /*
          if ($this->notasCredito->select("*")->whereIn("idEmpresa", $empresasID)->where("id", $id)->countAllResults() == 0) {

          return $this->failNotFound('Acceso Prohibido');
          }
         * 
         * */


        $this->notasCredito->db->transBegin();

        $infoNotaCredito = $this->notasCredito->find($id);

        if (!$found = $this->notasCredito->delete($id)) {
            $this->notasCredito->db->transRollback();
            return $this->failNotFound('Error al eliminar');
        }

        //Borramos los pagos generados

        if ($this->payments->select("*")
                        ->where("idNotaCredito", $id)->delete() === false) {

            $this->notasCredito->db->transRollback();
            return $this->failNotFound('Error al eliminar el detalle');
        }

        $this->notasCredito->purgeDeleted();

        $listPagos = json_decode($infoNotaCredito["listPagos"], true);
        $this->notasCredito->purgeDeleted();

        //Devolvemos el Stock

        foreach ($listPagos as $key => $value) {

            $sell = $this->sells->select("*")->where("id", $value["idSell"])->first();

            $nuevoSaldo["balance"] = $sell["balance"] + $value["importeAPagar"];

            if ($this->sells->update($sell["id"], $nuevoSaldo) === false) {

                $this->pagos->db->transRollback();
                return $this->failNotFound('Error al corregir saldo');
            }
        }


        $datosBitacora["description"] = 'Se elimino el pago' . json_encode($listPagos);

        $this->log->save($datosBitacora);

        $this->sells->db->transCommit();
        return $this->respondDeleted($found, 'Eliminado Correctamente');
    }

    /**
     * Reporte Consulta
     */
    public function report($uuid, $isMail = 0) {

        $pdf = new PDFLayout(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $dataSells = $this->sells->where("uuid", $uuid)->first();

        $listProducts = json_decode($dataSells["listProducts"], true);

        $user = $this->user->where("id", $dataSells["idUser"])->first()->toArray();

        $custumer = $this->custumer->where("id", $dataSells["idCustumer"])->where("deleted_at", null)->first();

        $datosEmpresa = $this->empresa->select("*")->where("id", $dataSells["idEmpresa"])->first();
        $datosEmpresaObj = $this->empresa->select("*")->where("id", $dataSells["idEmpresa"])->asObject()->first();

        $pdf->nombreDocumento = "Nota De Venta";
        $pdf->direccion = $datosEmpresaObj->direccion;

        if ($datosEmpresaObj->logo == NULL || $datosEmpresaObj->logo == "") {

            $pdf->logo = ROOTPATH . "public/images/logo/default.png";
        } else {

            $pdf->logo = ROOTPATH . "public/images/logo/" . $datosEmpresaObj->logo;
        }
        $pdf->folio = str_pad($dataSells["folio"], 5, "0", STR_PAD_LEFT);

        $folioConsulta = "Folio Consulta";
        $fecha = " Fecha: ";

        // set document information
        $pdf->nombreEmpresa = $datosEmpresa["nombre"];
        $pdf->direccion = $datosEmpresa["direccion"];
        $pdf->usuario = $user["firstname"] . " " . $user["lastname"];
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($user["username"]);
        $pdf->SetTitle('CI4JCPOS');
        $pdf->SetSubject('CI4JCPOS');
        $pdf->SetKeywords('CI4JCPOS, PDF, PHP, CodeIgniter, CESARSYSTEMS.COM.MX');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 35, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // ---------------------------------------------------------
        // add a page
        $pdf->AddPage();

        $pdf->SetY(45);
        //ETIQUETAS
        $cliente = "Cliente: ";
        $folioRegistro = " Folio: ";
        $fecha = " Fecha:";

        $pdf->SetY(45);
        //ETIQUETAS
        $cliente = "Cliente: ";
        $folioRegistro = " Folio: ";
        $fecha = " Fecha:";

        // set font
        //$pdf->SetFont('times', '', 12);

        if ($datosEmpresa["facturacionRD"] == "on" && $dataSells["folioComprobanteRD"] > 0) {


            $comprobante = $this->comprobantesRD->find($dataSells["tipoComprobanteRD"]);
            if ($comprobante["tipoDocumento"] == "COF") {
                $tipoDocumento = "FACTURA PARA CONSUMIDOR FINAL";
            }

            if ($comprobante["tipoDocumento"] == "CF") {
                $tipoDocumento = "FACTURA PARA CREDITO FISCAL";
            }

            $comprobanteFactura = $comprobante["prefijo"] . str_pad($dataSells["folioComprobanteRD"], 10, "0", STR_PAD_LEFT);
            $fechaVencimiento = "AUTORIZADO POR DGII :" . $comprobante["hastaFecha"];
        } else {

            $tipoDocumento = "";
            $comprobanteFactura = "";
            $fechaVencimiento = "";
        }

        $bloque2 = <<<EOF

    
        <table style="font-size:10px; padding:0px 10px;">
    
             <tr>
               <td style="width: 50%; background-color:#2c3e50; padding: 4px 4px 4px; font-weight:bold;  color:white;">ATENCION A
               </td>
               <td style="width: 50%; background-color:#2c3e50; padding: 4px 4px 4px; font-weight:bold;  color:white;">OBSERVACIONES
               </td>
            </tr>
            <tr>
    
                <td >
    
    
                Cliente: $custumer[firstname] $custumer[lastname] 
    
                    <br>
                    Telefono: 000
                    <br>
                    E-Mail: $custumer[email]
                    <br>
                </td>
                <td >
                    $dataSells[generalObservations]
                    $tipoDocumento  <br>
                    $comprobanteFactura  <br>
                    $fechaVencimiento <br>
                </td>
    
    
            </tr>
    
            <tr>
    
                <td style="width: 25%; background-color:#2c3e50; padding: 4px 4px 4px; font-weight:bold;  color:white;">VENDEDOR
                </td>
    
                <td style="width: 24%; background-color:#2c3e50; padding: 4px 4px 4px; font-weight:bold;  color:white;">FECHA
                </td>
                <td style="width: 30%; background-color:#2c3e50; padding: 4px 4px 4px; font-weight:bold;  color:white;">FECHA DE VENCIMIENTO
                </td>
    
    
                <td style="width: 21%; background-color:#2c3e50; padding: 4px 4px 4px; font-weight:bold;  color:white;">VIGENCIA
                </td>
    
            </tr>
            <tr>
                    <td>
                        $user[firstname] $user[lastname]
                    </td>
                    <td>
                    $dataSells[date]
                    </td>
                    <td>
                    $dataSells[dateVen]
                    </td>
                    <td>
                    $dataSells[delivaryTime]
                    </td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #666; background-color:white; width:640px"></td>
            </tr>
        </table>
    EOF;

        $pdf->writeHTML($bloque2, false, false, false, false, '');

        $bloque3 = <<<EOF

        <table style="font-size:10px; padding:5px 10px;">
    
            <tr>
    
            <td style="width: 100px; background-color:#2c3e50; padding: 4px 4px 4px; font-weight:bold;  color:white; text-align:center">Código</td>
            <td style="width: 200px; background-color:#2c3e50; padding: 4px 4px 4px; font-weight:bold;  color:white; text-align:center">Descripción</td>
                     <td style="width: 60px; background-color:#2c3e50; padding: 4px 4px 4px; font-weight:bold;  color:white; text-align:center">Cant</td>
    
            <td style="width: 80px; background-color:#2c3e50; padding: 4px 4px 4px; font-weight:bold;  color:white; text-align:center">Precio</td>
            <td style="width: 100px; background-color:#2c3e50; padding: 4px 4px 4px; font-weight:bold;  color:white; text-align:center">SubTotal</td>
            <td style="width: 100px; background-color:#2c3e50; padding: 4px 4px 4px; font-weight:bold;  color:white; text-align:center">Total</td>
    
            </tr>
    
        </table>
    
    EOF;

        $pdf->writeHTML($bloque3, false, false, false, false, '');

        $contador = 0;
        foreach ($listProducts as $key => $value) {



            if ($contador % 2 == 0) {
                $clase = 'style=" background-color:#ecf0f1; padding: 3px 4px 3px; ';
            } else {
                $clase = 'style="background-color:white; padding: 3px 4px 3px; ';
            }

            $precio = number_format($value["price"], 2, ".");
            $subTotal = number_format($value["total"], 2, ".");
            $total = number_format($value["neto"], 2, ".");
            $bloque4 = <<<EOF
    
        <table style="font-size:10px; padding:5px 10px;">
    
            <tr>
    
                <td  $clase width:100px; text-align:center">
                    $value[codeProduct]
                </td>
    
    
                <td  $clase width:200px; text-align:center">
                    $value[description]
                </td>
    
                <td $clase width:60px; text-align:center">
                    $value[cant]
                </td>
    
                <td $clase width:80px; text-align:right">
                    $precio
                </td>
    
                <td $clase width:100px; text-align:center">
                $subTotal
            </td>
    
                <td $clase width:100px; text-align:right">
                $total
                </td>
    
               
    
    
            </tr>
    
        </table>
    
    
    EOF;
            $contador++;
            $pdf->writeHTML($bloque4, false, false, false, false, '');
        }




        /**
         * TOTALES
         */
        $pdf->Setx(43);
        $subTotal = number_format($dataSells["subTotal"], 2, ".");
        $impuestos = number_format($dataSells["taxes"], 2, ".");
        $total = number_format($dataSells["total"], 2, ".");
        $IVARetenido = number_format($dataSells["IVARetenido"], 2, ".");
        $ISRRetenido = number_format($dataSells["ISRRetenido"], 2, ".");

        if ($IVARetenido > 0) {

            $bloqueIVARetenido = <<<EOF
                    <tr>
            
                    <td style="border-right: 0px solid #666; color:#333; background-color:white; width:340px; text-align:right"></td>
    
                    <td style="border: 0px solid #666; background-color:white; width:100px; text-align:right">
                    IVA Retenido:
                    </td>
    
                    <td style="border: 0px solid #666; color:#333; background-color:white; width:100px; text-align:right">
                        $IVARetenido
                    </td>
    
                </tr>
    
            EOF;
        } else {

            $bloqueIVARetenido = "";
        }


        if ($ISRRetenido > 0) {

            $bloqueISRRetenido = <<<EOF
                    <tr>
            
                    <td style="border-right: 0px solid #666; color:#333; background-color:white; width:340px; text-align:right"></td>
    
                    <td style="border: 0px solid #666; background-color:white; width:100px; text-align:right">
                    ISR Retenido:
                    </td>
    
                    <td style="border: 0px solid #666; color:#333; background-color:white; width:100px; text-align:right">
                        $ISRRetenido
                    </td>
    
                </tr>
    
            EOF;
        } else {

            $bloqueISRRetenido = "";
        }





        $bloque5 = <<<EOF

      <table style="font-size:10px; padding:5px 10px;">
  
          <tr>
  
              <td style="color:#333; background-color:white; width:340px; text-align:right"></td>
  
              <td style="border-bottom: 0px solid #666; background-color:white; width:100px; text-align:right"></td>
  
              <td style="border-bottom: 0px solid #666; color:#333; background-color:white; width:100px; text-align:right"></td>
  
          </tr>
  
          <tr>
  
              <td style="border-right: 0px solid #666; color:#333; background-color:white; width:340px; text-align:right"></td>
  
              <td style="border: 0px solid #666;  background-color:white; width:100px; text-align:right">
              Subtotal:
              </td>
  
              <td style="border: 0px solid #666; color:#333; background-color:white; width:100px; text-align:right">
                   $subTotal
              </td>
  
          </tr>
  
          <tr>
  
              <td style="border-right: 0px solid #666; color:#333; background-color:white; width:340px; text-align:right"></td>
  
              <td style="border: 0px solid #666; background-color:white; width:100px; text-align:right">
               IVA:
              </td>
  
              <td style="border: 0px solid #666; color:#333; background-color:white; width:100px; text-align:right">
                   $impuestos
              </td>
  
          </tr>
  
  
          $bloqueIVARetenido
          $bloqueISRRetenido
  
  
          <tr>
  
              <td style="border-right: 0px solid #666; color:#333; background-color:white; width:340px; text-align:right"></td>
  
              <td style="border: 0px solid #666; background-color:white; width:100px; text-align:right">
                  Total:
              </td>
  
              <td style="border: 0px solid #666; color:#333; background-color:white; width:100px; text-align:right">
                  $ $total
              </td>
  
          </tr>
  
  
      </table>
      <br>
      <div style="font-size:11pt;text-align:center;font-weight:bold">Gracias por su compra!</div>
  <br><br>
                  
          <div style="font-size:8.5pt;text-align:left;font-weight:ligth">UUID DOCUMENTO: $dataSells[UUID]</div>
          
     
      <div style="font-size:8.5pt;text-align:left;font-weight:ligth">ES RESPONSABILIDAD DEL CLIENTE REVISAR A DETALLE ESTA COTIZACION PARA SU POSTERIOR SURTIDO, UNA VEZ CONFIRMADA, NO HAY CAMBIOS NI DEVOLUCIONES.</div>
  
      
  
  
  EOF;

        $pdf->writeHTML($bloque5, false, false, false, false, 'R');

        if ($isMail == 0) {
            ob_end_clean();
            $this->response->setHeader("Content-Type", "application/pdf");
            $pdf->Output('notaVenta.pdf', 'I');
        } else {

            $attachment = $pdf->Output('notaVenta.pdf', 'S');

            return $attachment;
        }


        //============================================================+
        // END OF FILE
        //============================================================+
    }

    public function obtenerFacturasPendientes() {

        $datos = $this->request->getPost();

        $empresa = $datos["idEmpresa"];
        $sucursal = $datos["idSucursal"];
        $cliente = $datos["idCustumers"];

        $listaFacturas = $this->pagos->mdlObtenerVentasFacturadasPendientesDePago($empresa, $sucursal, $cliente);
        $facturas = "";

        foreach ($listaFacturas as $key => $value) {

            $facturas .= <<<EOF
                <div class="form-group row nuevoProduct\">
                <div class ="col-1"> <button type="button" class="btn btn-danger quitProduct" ><span class="far fa-trash-alt"></span></button>
                <button type="button"  data-toggle="modal" data-target="#modelMoreInfoRow" class="btn btn-primary  btnInfo" ><span class="fa fa-fw fa-pencil-alt"></span></button> </div>
                <div class ="col-1"> <input disabled type="text" id="serie" class="form-control serie"  name="serie" value="$value[serie]" required=""> 
                <input disabled type="hidden" id="idSell" class="form-control idSell"  name="idSell" value="$value[id]" required="">    </div>
                <div class ="col-5"> <input disabled type="text" id="folio" class="form-control folio"  name="folio" value="$value[folio]" required=""> </div>
                <div class ="col-1"> <input disabled type="text" id="fecha" class="form-control fecha" name="fecha" value="$value[date]" =""></div>
                <div class ="col-1"> <input disabled type="text" id="price" class="form-control fechaVen" name="fechaVen" value="$value[dateVen]" required="">  </div>
                <div class ="col-1"> <input disabled type="text" id="total" class="form-control total" name="total" value="$value[total]" required=""> </div>
                <div class ="col-1"> <input disabled type="text" id="saldo" class="form-control saldo" name="total" value="$value[balance]" required=""> </div>        
                <div class ="col-1"> <input  type="number" id="importeAPagar" class="form-control importeAPagar" name="importeAPagar" value="0.00" required=""> </div></div>
            EOF;
        }

        echo $facturas;
    }

    public function generaPDFDesdeNotaCredito($uuidNotaCredito) {

        // Search Id Credit Note

        $datosNotaCredito = $this->notasCredito->select("*")->where("UUID", $uuidNotaCredito)->first();

        //Search UUID in link XML

        $enlaceXML = $this->enlace->select("*")
                        ->where("idDocumento", $datosNotaCredito["id"])
                        ->where("tipo", "NCR")->first();

        $archivo = $this->xmlController->generarPDF($enlaceXML["uuidXML"], true);

        echo $archivo;
        $this->response->setHeader("Content-Type", "application/pdf");
    }

    public function getXMLEnlazados($uuidNotaCredito) {

        try {

            $datosNotaCredito = $this->notasCredito->select("*")->where("UUID", $uuidNotaCredito)->first();

            if (isset($datosNotaCredito)) {

                $request = service('request');
                $db = \Config\Database::connect();

                $columns = ['a.id', 'a.idDocumento', 'a.uuidXML', 'a.tipo', 'a.importe', 'c.status', 'c.archivoXML', 'a.created_at', 'a.updated_at', 'a.deleted_at'];

                // === FROM y JOIN ===
                $builder = $db->table('enlacexml a');
                $builder->join('xml c', 'c.uuidTimbre = a.uuidXML');

                // === WHERE principal ===
                $builder->where('a.idDocumento', $datosNotaCredito["id"]);
                $builder->where('a.tipo', "NCR");

                // === Total sin filtro ===
                $total = $builder->countAllResults(false); // no reset
                // === Búsqueda global ===
                $searchValue = $request->getPost('search')['value'] ?? '';
                if ($searchValue) {
                    $builder->groupStart();
                    foreach ($columns as $col) {
                        $builder->orLike($col, $searchValue);
                    }
                    $builder->groupEnd();
                }

                // === Total filtrado ===
                $filtered = $builder->countAllResults(false);

                // === Ordenamiento ===
                $orderColumnIndex = $request->getPost('order')[0]['column'] ?? 0;
                $orderColumn = $columns[$orderColumnIndex] ?? 'a.id';
                $orderDir = $request->getPost('order')[0]['dir'] ?? 'asc';
                $builder->orderBy($orderColumn, $orderDir);

                // === Paginación ===
                $length = $request->getPost('length') ?? 10;
                $start = $request->getPost('start') ?? 0;
                $builder->limit($length, $start);

                // === Ejecutar y devolver ===
                $query = $builder->get();
                $data = $query->getResultArray();

                return $this->response->setJSON([
                            'draw' => intval($request->getPost('draw')),
                            'recordsTotal' => $total,
                            'recordsFiltered' => $filtered,
                            'data' => $data
                ]);
            } else {

                $datos = $this->enlaceXML
                        ->select('id,idDocumento,uuidXML,tipo,importe')
                        ->where('idDocumento', 0)
                        ->findAll();

                return $this->response->setJSON([
                            'data' => $datos
                ]);
            }
        } catch (Exception $ex) {

            return $ex->getMessage();
        }
    }
}
