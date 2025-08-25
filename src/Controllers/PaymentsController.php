<?php

namespace julio101290\boilerplatesells\Controllers;

use App\Controllers\BaseController;
use julio101290\boilerplatesells\Models\{
    PaymentsModel
};
use julio101290\boilerplatelog\Models\LogModel;
use CodeIgniter\API\ResponseTrait;
use julio101290\boilerplatesells\Models\SellsModel;
use Exception;

class PaymentsController extends BaseController {

    use ResponseTrait;

    protected $log;
    protected $payments;
    protected $sells;

    public function __construct() {
        $this->payments = new PaymentsModel();
        $this->log = new LogModel();
        $this->sells = new SellsModel();
        helper('menu');
    }

    public function index() {
        if ($this->request->isAJAX()) {
            $datos = $this->payments->select('id,idSell,importPayment,importBack,datePayment,metodPayment,created_at,updated_at,deleted_at')->where('deleted_at', null);
            return \Hermawan\DataTables\DataTable::of($datos)->toJson(true);
        }
        $titulos["title"] = lang('payments.title');
        $titulos["subtitle"] = lang('payments.subtitle');
        return view('payments', $titulos);
    }

    /**
     * Read Payments
     */
    public function getPayments() {
        $idPayments = $this->request->getPost("idPayments");
        $datosPayments = $this->payments->find($idPayments);
        echo json_encode($datosPayments);
    }

    /**
     * Save or update Payments
     */
    public function save() {
        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;
        $datos = $this->request->getPost();

        $auth = service('authentication');
        if (!$auth->check()) {

            echo "No ha iniciado Session";
            return;
        }

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;

        $this->payments->db->transBegin();

        $datosVenta = $this->sells->select("*")->where("UUID", $datos["UUID"])->asArray()->first();

        $datos["idSell"] = $datosVenta["id"];
        $datos["idQuote"] = "0";

        try {


            $timestamp = strtotime($datos["datePayment"]);

            if ($timestamp !== false) {
                $datos["datePayment"] = date('Y-m-d H:i:s', $timestamp);
            } else {
                $datos["datePayment"] = null; // o manejar error manualmente
            }

            //metodPayment
            $datos["metodPayment"] = $datos["metodoPago"];

            if (!isset($datos["idComplemento"])) {

                $datos["idComplemento"] = 0;
            }
            if ($this->payments->save($datos) === false) {

                $this->payments->db->transRollback();

                $errores = $this->payments->errors();
                foreach ($errores as $field => $error) {
                    echo $error . " ";
                }



                return;
            }
            $dateLog["description"] = lang("vehicles.logDescription") . json_encode($datos);
            $dateLog["user"] = $userName;
            $this->log->save($dateLog);

            try {

                $newBalance["balance"] = $datosVenta["balance"] - ($datos["importPayment"] - $datos["importBack"]);

               
                $this->sells->update($datosVenta["id"],  $newBalance);

                $this->payments->transCommit();
            } catch (Exception $e) {
                
            }


            echo "Guardado Correctamente";
        } catch (\PHPUnit\Framework\Exception $ex) {
            echo "Error al guardar " . $ex->getMessage();
            $this->payments->DB::rollback();
        }

        return;
    }

    public function ctrGetPayments($uuid) {

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;
        $datos = $this->request->getPost();

        $auth = service('authentication');
        if (!$auth->check()) {

            echo "No ha iniciado Session";
            return;
        }

        $sell = $this->sells->select("*")->where("UUID", $uuid)->first();

        if (!isset($sell["id"])) {

            $sell["id"] = 0;
        }

        $request = service('request');

        $draw = (int) $request->getVar('draw');
        $start = (int) $request->getVar('start');
        $length = (int) $request->getVar('length');
        $search = $request->getVar('search')['value'] ?? '';
        $order = $request->getVar('order');
        $columnsReq = $request->getVar('columns');

        // Solo columnas válidas (string y que existan en BD)
        $validColumns = [
            'id',
            'datePayment',
            'importPayment',
            'importBack',
            'observaciones',
            'tipo'
        ];

        $columns = [];
        if ($columnsReq) {
            foreach ($columnsReq as $col) {
                if (
                        !empty($col['data']) &&
                        is_string($col['data']) &&
                        in_array($col['data'], $validColumns)
                ) {
                    $columns[] = $col['data'];
                }
            }
        }

        // Si no mandan columnas válidas, usar todas por defecto
        if (empty($columns)) {
            $columns = $validColumns;
        }

        // Query base
        $builder = $this->payments
                ->select(implode(',', $columns))
                ->where('deleted_at', null)
                ->where('idSell', $sell['id']);

        // Total sin filtros
        $recordsTotal = $builder->countAllResults(false);

        // Búsqueda global
        if (!empty($search)) {
            $builder->groupStart();
            foreach ($columnsReq as $col) {
                if (
                        $col['searchable'] === 'true' &&
                        !empty($col['data']) &&
                        is_string($col['data']) &&
                        in_array($col['data'], $validColumns)
                ) {
                    $builder->orLike($col['data'], $search);
                }
            }
            $builder->groupEnd();
        }

        // Total filtrados
        $recordsFiltered = $builder->countAllResults(false);

        // Ordenamiento
        if ($order) {
            foreach ($order as $o) {
                $colIdx = intval($o['column']);
                $dir = $o['dir'] === 'asc' ? 'ASC' : 'DESC';

                if (
                        isset($columnsReq[$colIdx]) &&
                        $columnsReq[$colIdx]['orderable'] === 'true' &&
                        !empty($columnsReq[$colIdx]['data']) &&
                        is_string($columnsReq[$colIdx]['data']) &&
                        in_array($columnsReq[$colIdx]['data'], $validColumns)
                ) {
                    $builder->orderBy($columnsReq[$colIdx]['data'], $dir);
                }
            }
        }

        // Paginación
        if ($length != -1) {
            $builder->limit($length, $start);
        }

        // Ejecutar query
        $query = $builder->get();
        $data = $query->getResultArray();

        // Respuesta JSON
        return $this->response->setJSON([
                    'draw' => $draw,
                    'recordsTotal' => $recordsTotal,
                    'recordsFiltered' => $recordsFiltered,
                    'data' => $data
        ]);
    }

    /**
     * Delete Payments
     * @param type $id
     * @return type
     */
    public function delete($id) {

        $infoPayments = $this->payments->find($id);

        $dataSell = $this->sells->find($infoPayments["idSell"]);

        $totalPago = $infoPayments["importPayment"] - $infoPayments["importBack"];

        $dataSellSave["balance"] = $dataSell["balance"] + $totalPago;

        helper('auth');

        $auth = service('authentication');
        if (!$auth->check()) {

            echo "no conectado";
            return redirect()->route('login');
        }
        $userName = user()->username;
        if (!$found = $this->payments->delete($id)) {
            return $this->failNotFound(lang('payments.msg.msg_get_fail'));
        }

        /**
         * Actualizamos saldo
         */
        $resultVenta = $this->sells->update($dataSell["id"], $dataSellSave);

        $this->payments->purgeDeleted();
        $logData["description"] = lang("payments.logDeleted") . json_encode($infoPayments);
        $logData["user"] = $userName;
        $this->log->save($logData);
        return $this->respondDeleted($found, lang('payments.msg_delete'));
    }
}
