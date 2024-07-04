<?php

namespace Application\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\Sql\Select;

class PosicionBannerTable {
    protected $tableGateway;
    public function __construct(TableGatewayInterface $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    public function obtenerPosicionBanner(){
        $condiciones = [];
        $adapter = $this->tableGateway->getAdapter();
        $sql = "SELECT pb.*, p.nombre AS pagina
        FROM posicion_banner pb
        LEFT JOIN paginas p ON p.idpaginas = pb.idpaginas";
        if( !empty($condiciones) ) $sql .= " WHERE ".implode(" AND ", $condiciones);
        $sql .= " ORDER BY p.nombre ASC";
        $data = $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE)->toArray();
        return $data;
    }
    public function obtenerDatoPosicionBanner($where){
        $rowset = $this->tableGateway->select($where);
        return $rowset->current();
    }
    public function obtenerDatosPosicionBanner($where){
        $rowset = $this->tableGateway->select($where);
        return $rowset->toArray();
    }
    public function actualizarDatosPosicionBanner($data,$idposicionbanner){
        $rowset = $this->tableGateway->update($data,["idposicionbanner" => $idposicionbanner]);
    }
    public function agregarPosicionBanner($data){
        $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;
    }
    public function eliminarPosicionBanner($idposicionbanner){
        $this->tableGateway->delete(["idposicionbanner" => $idposicionbanner]);
    }
    public function obtenerFiltroDatosPosicionBanner($start,$length,$search=null,$totalregistro=false){
        $adapter = $this->tableGateway->getAdapter();
        $sql = "SELECT * FROM posicionbanner";
        $sql .= " ORDER BY idposicionbanner ASC";
        if(!$totalregistro)$sql .= " LIMIT {$start},{$length}";
        $data = $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE)->toArray();
        return $data;
    }
}