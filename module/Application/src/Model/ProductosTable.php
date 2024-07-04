<?php

namespace Application\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\Sql\Select;

class ProductosTable {
    protected $tableGateway;
    public function __construct(TableGatewayInterface $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    public function obtenerProductos($idferias=null, $idperfil=null){
        $condiciones = [];
        $adapter = $this->tableGateway->getAdapter();
        $sql = "SELECT p.*, e.nombre AS empresa, s.nombre AS segmento
        FROM productos p
        LEFT JOIN empresas e ON e.idempresas = p.idempresas
        LEFT JOIN zonas z ON z.idzonas = e.idzonas
        LEFT JOIN segmentos s ON s.idsegmentos = e.idsegmentos";
        if( $idperfil != 1 && $idferias != null ) $condiciones[] = "z.idferias = {$idferias}";
        if( !empty($condiciones) ) $sql .= " WHERE ".implode(" AND ", $condiciones);
        $sql .= " ORDER BY p.idproductos DESC";
        $data = $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE)->toArray();
        return $data;
    }
    public function obtenerDatoProductos($where){
        $rowset = $this->tableGateway->select($where);
        return $rowset->current();
    }
    public function actualizarDatosProductos($data,$idproductos){
        $rowset = $this->tableGateway->update($data,["idproductos" => $idproductos]);
    }
    public function agregarProductos($data){
        $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;
    }
    public function eliminarProductos($idproductos){
        $this->tableGateway->delete(["idproductos" => $idproductos]);
    }
    public function obtenerFiltroDatosProductos($start,$length,$search=null,$totalregistro=false){
        $adapter = $this->tableGateway->getAdapter();
        $sql = "SELECT * FROM productos";
        $sql .= " ORDER BY idproductos ASC";
        if(!$totalregistro)$sql .= " LIMIT {$start},{$length}";
        $data = $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE)->toArray();
        return $data;
    }
}