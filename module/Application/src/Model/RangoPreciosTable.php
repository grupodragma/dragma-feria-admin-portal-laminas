<?php

namespace Application\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\Sql\Select;

class RangoPreciosTable {
    protected $tableGateway;
    public function __construct(TableGatewayInterface $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    public function obtenerRangoPrecios(){
        $select = $this->tableGateway->getSql()->select();
        $select->order('idrangoprecios DESC');
        $rowset = $this->tableGateway->selectWith($select);
        return $rowset->toArray();
    }
    public function obtenerDatoRangoPrecios($where){
        $rowset = $this->tableGateway->select($where);
        return $rowset->current();
    }
    public function obtenerDatosRangoPrecios($where){
        $rowset = $this->tableGateway->select($where);
        return $rowset->toArray();
    }
    public function actualizarDatosRangoPrecios($data,$idrangoprecios){
        $rowset = $this->tableGateway->update($data,["idrangoprecios" => $idrangoprecios]);
    }
    public function agregarRangoPrecios($data){
        $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;
    }
    public function eliminarRangoPrecios($idrangoprecios){
        $this->tableGateway->delete(["idrangoprecios" => $idrangoprecios]);
    }
    public function obtenerFiltroDatosRangoPrecios($start,$length,$search=null,$totalregistro=false){
        $adapter = $this->tableGateway->getAdapter();
        $sql = "SELECT * FROM rangoprecios";
        $sql .= " ORDER BY idrangoprecios ASC";
        if(!$totalregistro)$sql .= " LIMIT {$start},{$length}";
        $data = $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE)->toArray();
        return $data;
    }
}