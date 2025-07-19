<?php

namespace julio101290\boilerplatesells\Models;

use CodeIgniter\Model;

class NotasCreditoModel extends Model {

    protected $table = 'notascredito';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'idEmpresa',
        'folio',
        'idUser',
        'idCustumer',
        'listProducts',
        'listPagos',
        'taxes',
        'IVARetenido',
        'ISRRetenido',
        'subTotal',
        'total',
        'balance',
        'date',
        'dateVen',
        'generalObservations',
        'quoteTo',
        'delivaryTime',
        'created_at',
        'updated_at',
        'idQuote',
        'RFCReceptor',
        'usoCFDI',
        'metodoPago',
        'formaPago',
        'razonSocialReceptor',
        'codigoPostalReceptor',
        'regimenFiscalReceptor',
        'idVehiculo',
        'idChofer',
        'idSucursal',
        'idArqueoCaja',
        'tipoVehiculo',
        'noCTAOrdenante',
        'noCTABeneficiario',
        'RFCCTAOrdenante',
        'RFCCTABeneficiario',
        'tipoDocumentoRelacionado',
        'UUIDRelacion',
        'UUID'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [
        'idEmpresa' => 'is_natural_no_zero',
        'idSucursal' => 'is_natural_no_zero',
        'idCustumer' => 'is_natural_no_zero',
        'folio' => 'is_natural',
        'idUser' => 'is_natural',
        'listProducts' => 'string',
        'taxes' => 'decimal',
        'IVARetenido' => 'decimal',
        'ISRRetenido' => 'decimal',
        'subTotal' => 'decimal',
        'total' => 'decimal',
        'balance' => 'decimal',
        'date' => 'valid_date[Y-m-d]',
        'dateVen' => 'valid_date[Y-m-d]',
        'quoteTo' => 'max_length[512]',
        'delivaryTime' => 'permit_empty|max_length[512]',
        'generalObservations' => 'permit_empty|max_length[512]',
        'UUID' => 'required',
        'RFCReceptor' => 'permit_empty|max_length[15]',
        'usoCFDI' => 'permit_empty|max_length[32]',
        'metodoPago' => 'permit_empty|max_length[32]',
        'formaPago' => 'permit_empty|max_length[32]',
        'regimenFiscalReceptor' => 'permit_empty|max_length[1024]',
        'razonSocialReceptor' => 'permit_empty|max_length[1024]',
        'codigoPostalReceptor' => 'permit_empty|exact_length[5]|numeric',
        'noCTAOrdenante' => 'permit_empty|max_length[64]',
        'noCTABeneficiario' => 'permit_empty|max_length[64]',
        'RFCCTAOrdenante' => 'permit_empty|max_length[64]',
        'RFCCTABeneficiario' => 'permit_empty|max_length[64]',
        'tipoDocumentoRelacionado' => 'permit_empty|string|max_length[5]',
        'UUIDRelacion' => 'permit_empty|string|max_length[40]',
    ];
    protected $validationMessages = [
        'UUID' => [
            'is_unique' => 'El UUID ya está registrado. Debe ser único.',
            'max_length' => 'El UUID no puede superar los 36 caracteres.',
        ],
        'RFCReceptor' => [
            'max_length' => 'El RFC del receptor no debe exceder los 15 caracteres.',
        ],
        'usoCFDI' => [
            'max_length' => 'El uso de CFDI no puede exceder los 32 caracteres.',
        ],
        'codigoPostalReceptor' => [
            'exact_length' => 'El código postal debe tener exactamente 5 dígitos.',
            'numeric' => 'El código postal solo debe contener números.',
        ],
        'date' => [
            'valid_date' => 'La fecha no tiene un formato válido (Y-m-d).',
        ],
        'total' => [
            'decimal' => 'El total debe ser un número decimal válido.',
        ],
        'idEmpresa' => [
            'is_natural_no_zero' => 'Debe seleccionar una empresa válida.',
        ],
    ];
    protected $skipValidation = false;

    public function mdlGetNotasCredito($empresas) {
        $db = $this->db;

        // Detectar si estamos en PostgreSQL
        $isPostgres = $db->getPlatform() === 'Postgre';

        $quotes = $isPostgres ? '"' : '';

        // Concatenación compatible para nombre completo
        $nameExpression = $isPostgres ? "b.firstname || ' ' || b.lastname" : "CONCAT_WS(' ', b.firstname, b.lastname)";

        // Construir el array de campos con comillas según sea necesario
        $select = [
            "a.{$quotes}UUID{$quotes} AS {$quotes}UUID{$quotes}",
            "a.id",
            "{$nameExpression} AS {$quotes}nameCustumer{$quotes}",
            "a.{$quotes}idCustumer{$quotes} AS {$quotes}idCustumer{$quotes}",
            "a.folio",
            "a.date",
            "b.email AS {$quotes}correoCliente{$quotes}",
            "a.{$quotes}dateVen{$quotes}",
            "a.total",
            "a.taxes",
            "a.{$quotes}subTotal{$quotes}",
            "a.balance",
            "a.{$quotes}delivaryTime{$quotes} AS {$quotes}delivaryTime{$quotes}",
            "a.{$quotes}generalObservations{$quotes} AS {$quotes}generalObservations{$quotes}",
            "a.idQuote",
            "a.{$quotes}IVARetenido{$quotes} AS {$quotes}IVARetenido{$quotes}",
            "a.{$quotes}ISRRetenido{$quotes} AS {$quotes}ISRRetenido{$quotes}",
            "a.{$quotes}idSucursal{$quotes}",
            "a.{$quotes}RFCReceptor{$quotes} AS {$quotes}RFCReceptor{$quotes}",
            "a.{$quotes}usoCFDI{$quotes} AS {$quotes}usoCFDI{$quotes}",
            "a.{$quotes}metodoPago{$quotes} AS {$quotes}metodoPago{$quotes}",
            "a.{$quotes}formaPago{$quotes} AS {$quotes}formaPago{$quotes}",
            "a.{$quotes}razonSocialReceptor{$quotes} AS {$quotes}razonSocialReceptor{$quotes}",
            "a.{$quotes}codigoPostalReceptor{$quotes} AS {$quotes}codigoPostalReceptor{$quotes}",
            "a.{$quotes}regimenFiscalReceptor{$quotes} AS {$quotes}regimenFiscalReceptor{$quotes}",
            "a.{$quotes}idVehiculo{$quotes} AS {$quotes}idVehiculo{$quotes}",
            "a.{$quotes}idChofer{$quotes} AS {$quotes}idChofer{$quotes}",
            "a.{$quotes}tipoVehiculo{$quotes} AS {$quotes}tipoVehiculo{$quotes}",
            "a.{$quotes}idArqueoCaja{$quotes} AS {$quotes}idArqueoCaja{$quotes}",
            "a.{$quotes}tipoDocumentoRelacionado{$quotes}",
            "a.{$quotes}UUIDRelacion{$quotes}",
            "a.created_at",
            "a.updated_at",
            "a.deleted_at"
        ];

        $result = $db->table('notascredito a')
                ->select(implode(',', $select))
                ->join('custumers b', 'a.idCustumer = b.id', 'left')
                ->join('empresas c', 'a.idEmpresa = c.id', 'left')
                ->whereIn('a.idEmpresa', $empresas);

        return $result;
    }

    /**
     * Search by filters
     */
    public function mdlGetNotasCreditoFilters(
            $empresas,
            $from,
            $to,
            $allSells,
            $empresa = 0,
            $sucursal = 0,
            $cliente = 0,
            $params = []
    ) {
        $dbDriver = $this->db->getPlatform();
        $quotes = $dbDriver === 'Postgre' ? '"' : '';

        $nameExpression = $dbDriver === 'Postgre' ? "(b.firstname || ' ' || b.lastname) AS \"nameCustumer\"" : "CONCAT_WS(' ', b.firstname, b.lastname) AS nameCustumer";

        $builder = $this->db->table('notascredito a')
                ->select("
            a.UUID,
            a.id,
            {$nameExpression},
            a.idCustumer,
            b.email AS correoCliente,
            a.folio,
            a.date,
            a.dateVen,
            a.total,
            a.taxes,
            a.subTotal,
            a.balance,
            a.delivaryTime,
            a.generalObservations,
            a.idQuote,
            a.IVARetenido,
            a.ISRRetenido,
            a.idSucursal,
            a.RFCReceptor,
            a.usoCFDI,
            a.metodoPago,
            a.formaPago,
            a.razonSocialReceptor,
            a.codigoPostalReceptor,
            a.regimenFiscalReceptor,
            a.idVehiculo,
            a.idChofer,
            a.tipoVehiculo,
            a.idArqueoCaja,
            a.tipoDocumentoRelacionado,
            a.UUIDRelacion,
            a.created_at,
            a.updated_at,
            a.deleted_at
        ")
                ->join('custumers b', 'a.idCustumer = b.id', 'left')
                ->join('empresas c', 'a.idEmpresa = c.id', 'left')
                ->where('a.date >=', $from . ' 00:00:00')
                ->where('a.date <=', $to . ' 23:59:59')
                ->whereIn('a.idEmpresa', $empresas);

        if ($allSells !== 'true') {
            //     $builder->where('a.balance >', 0);
        }

        if ($empresa != '0') {
            $builder->where('a.idEmpresa', $empresa);
        }

        if ($sucursal != '0') {
            $builder->where('a.idSucursal', $sucursal);
        }

        if ($cliente != '0') {
            $builder->where('a.idCustumer', $cliente);
        }

        // 🔎 Búsqueda global (como search[value])
        $search = $params['search']['value'] ?? '';
        if (!empty($search)) {
            $builder->groupStart();
            foreach ($params['columns'] as $col) {
                if (!empty($col['data'])) {
                    $builder->orLike($col['data'], $search);
                }
            }
            $builder->groupEnd();
        }

        // Filtros por columna
        if (!empty($params['columns'])) {
            foreach ($params['columns'] as $col) {
                if (!empty($col['search']['value'])) {
                    $builder->like($col['data'], $col['search']['value']);
                }
            }
        }

        // Ordenamiento
        if (!empty($params['order'])) {
            foreach ($params['order'] as $ord) {
                $colIndex = $ord['column'];
                $dir = $ord['dir'] ?? 'asc';
                $colName = $params['columns'][$colIndex]['data'];
                $builder->orderBy($colName, $dir);
            }
        }

        // Paginación
        if (isset($params['length']) && $params['length'] != -1) {
            $builder->limit($params['length'], $params['start']);
        }

        $data = $builder->get()->getResultArray();

        // Conteo total sin filtros
        $totalBuilder = $this->db->table('notascredito a')
                ->join('custumers b', 'a.idCustumer = b.id', 'left')
                ->join('empresas c', 'a.idEmpresa = c.id', 'left')
                ->where('a.date >=', $from . ' 00:00:00')
                ->where('a.date <=', $to . ' 23:59:59')
                ->whereIn('a.idEmpresa', $empresas);

        if ($allSells !== 'true') {
            $totalBuilder->where('a.balance >', 0);
        }

        if ($empresa != '0') {
            $totalBuilder->where('a.idEmpresa', $empresa);
        }

        if ($sucursal != '0') {
            $totalBuilder->where('a.idSucursal', $sucursal);
        }

        if ($cliente != '0') {
            $totalBuilder->where('a.idCustumer', $cliente);
        }

        $total = $totalBuilder->countAllResults();

        return [
            'data' => $data,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
        ];
    }

    /**
     * Obtener Cotización por UUID
     */
    public function mdlGetNotascreditoUUID($uuid, $empresas) {
        $db = $this->db;

        // Detectar si estamos en PostgreSQL
        $isPostgres = $db->getPlatform() === 'Postgre';

        $quotes = $isPostgres ? '"' : '';

        $nameExpression = $isPostgres ? "b.firstname || ' ' || b.lastname" : "CONCAT_WS(' ', b.firstname, b.lastname)";

        $select = [
            "a.{$quotes}idCustumer{$quotes} AS {$quotes}idCustumer{$quotes}",
            "a.folio",
            "a.{$quotes}UUID{$quotes} AS {$quotes}UUID{$quotes}",
            "a.{$quotes}idUser{$quotes}",
            "a.id",
            "{$nameExpression} AS {$quotes}nameCustumer{$quotes}",
            "a.{$quotes}idEmpresa{$quotes}",
            "c.nombre AS {$quotes}nombreEmpresa{$quotes}",
            "a.{$quotes}listProducts{$quotes}",
            "a.date",
            "a.{$quotes}dateVen{$quotes}",
            "a.{$quotes}subTotal{$quotes}",
            "a.total",
            "a.taxes",
            "a.{$quotes}tasaCero{$quotes}",
            "a.{$quotes}IVARetenido{$quotes} AS {$quotes}IVARetenido{$quotes}",
            "a.{$quotes}ISRRetenido{$quotes} AS {$quotes}ISRRetenido{$quotes}",
            "a.{$quotes}idQuote{$quotes}",
            "a.{$quotes}delivaryTime{$quotes} AS {$quotes}delivaryTime{$quotes}",
            "a.{$quotes}generalObservations{$quotes} AS {$quotes}generalObservations{$quotes}",
            "a.{$quotes}RFCReceptor{$quotes} AS {$quotes}RFCReceptor{$quotes}",
            "a.{$quotes}usoCFDI{$quotes} AS {$quotes}usoCFDI{$quotes}",
            "a.{$quotes}metodoPago{$quotes} AS {$quotes}metodoPago{$quotes}",
            "a.{$quotes}formaPago{$quotes} AS {$quotes}formaPago{$quotes}",
            "a.{$quotes}razonSocialReceptor{$quotes} AS {$quotes}razonSocialReceptor{$quotes}",
            "a.{$quotes}codigoPostalReceptor{$quotes} AS {$quotes}codigoPostalReceptor{$quotes}",
            "a.{$quotes}regimenFiscalReceptor{$quotes} AS {$quotes}regimenFiscalReceptor{$quotes}",
            "a.{$quotes}idSucursal{$quotes}",
            "a.{$quotes}idVehiculo{$quotes} AS {$quotes}idVehiculo{$quotes}",
            "a.{$quotes}idChofer{$quotes} AS {$quotes}idChofer{$quotes}",
            "a.{$quotes}tipoVehiculo{$quotes} AS {$quotes}tipoVehiculo{$quotes}",
            "a.{$quotes}idArqueoCaja{$quotes} AS {$quotes}idArqueoCaja{$quotes}",
            "a.{$quotes}tipoDocumentoRelacionado{$quotes}",
            "a.{$quotes}UUIDRelacion{$quotes}",
            "a.created_at",
            "a.updated_at",
            "a.deleted_at",
        ];

        $result = $db->table('notascredito a')
                ->select(implode(',', $select))
                ->join('custumers b', 'a.idCustumer = b.id', 'left')
                ->join('empresas c', 'a.idEmpresa = c.id', 'left')
                ->where("a.{$quotes}UUID{$quotes}", $uuid)
                ->whereIn('a.idEmpresa', $empresas)
                ->get()
                ->getRowArray();

        return $result;
    }

    public function mdlObtenerVentasFacturadasPendientesDePago(
            $idEmpresa,
            $idSucursal,
            $idCustumer
    ) {
        $db = $this->db;
        $isPostgres = $db->getPlatform() === 'Postgre';
        $quotes = $isPostgres ? '"' : '';

        $select = [
            "a.id",
            "a.folio",
            "a.{$quotes}idCustumer{$quotes} AS {$quotes}idCustumer{$quotes}",
            "a.total",
            "a.balance",
            "c.serie",
            "a.date",
            "a.{$quotes}dateVen{$quotes}",
            "a.taxes",
        ];

        $builder = $db->table('sells a')
                ->select(implode(',', $select))
                ->join('enlacexml b', 'a.id = b.idDocumento', 'left')
                ->join('xml c', 'c.uuidTimbre = b.uuidXML', 'left')
                ->join('custumers e', 'a.idCustumer = e.id', 'left')
                ->where('a.balance >', 0)
                ->where('a.idEmpresa', $idEmpresa)
                ->where('a.idSucursal', $idSucursal)
                ->where('a.idCustumer', $idCustumer);

        return $builder->get()->getResultArray();
    }
}
