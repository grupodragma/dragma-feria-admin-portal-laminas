<?php

namespace Application\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\Sql\Select;

class PromocionesTable {
    protected $tableGateway;
    public function __construct(TableGatewayInterface $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    public function obtenerPromociones($idferias=null, $idperfil=null){
        $condiciones = [];
        $adapter = $this->tableGateway->getAdapter();
        $sql = "SELECT p.*, pd.nombre AS proyecto
        FROM promociones p
        LEFT JOIN productos pd ON pd.idproductos = p.idproductos";
        if( $idperfil != 1 && $idferias != null ) $condiciones[] = "z.idferias = {$idferias}";
        if( !empty($condiciones) ) $sql .= " WHERE ".implode(" AND ", $condiciones);
        $sql .= " ORDER BY p.idpromociones DESC";
        $data = $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE)->toArray();
        return $data;
    }
    public function obtenerDatoPromociones($where){
        $rowset = $this->tableGateway->select($where);
        return $rowset->current();
    }
    public function actualizarDatosPromociones($data,$idpromociones){
        $rowset = $this->tableGateway->update($data,["idpromociones" => $idpromociones]);
    }
    public function agregarPromociones($data){
        $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;
    }
    public function eliminarPromociones($idpromociones){
        $this->tableGateway->delete(["idpromociones" => $idpromociones]);
    }
    public function obtenerFiltroDatosPromociones($start,$length,$search=null,$totalregistro=false){
        $adapter = $this->tableGateway->getAdapter();
        $sql = "SELECT * FROM promociones";
        $sql .= " ORDER BY idpromociones ASC";
        if(!$totalregistro)$sql .= " LIMIT {$start},{$length}";
        $data = $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE)->toArray();
        return $data;
    }
}