<?php

namespace Application\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\Sql\Select;

class NumeroHabitacionTable {
    protected $tableGateway;
    public function __construct(TableGatewayInterface $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    public function obtenerNumeroHabitacion(){
        $select = $this->tableGateway->getSql()->select();
        $select->order('idnumerohabitacion DESC');
        $rowset = $this->tableGateway->selectWith($select);
        return $rowset->toArray();
    }
    public function obtenerDatoNumeroHabitacion($where){
        $rowset = $this->tableGateway->select($where);
        return $rowset->current();
    }
    public function obtenerDatosNumeroHabitacion($where){
        $rowset = $this->tableGateway->select($where);
        return $rowset->toArray();
    }
    public function actualizarDatosNumeroHabitacion($data,$idnumerohabitacion){
        $rowset = $this->tableGateway->update($data,["idnumerohabitacion" => $idnumerohabitacion]);
    }
    public function agregarNumeroHabitacion($data){
        $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;
    }
    public function eliminarNumeroHabitacion($idnumerohabitacion){
        $this->tableGateway->delete(["idnumerohabitacion" => $idnumerohabitacion]);
    }
    public function obtenerFiltroDatosNumeroHabitacion($start,$length,$search=null,$totalregistro=false){
        $adapter = $this->tableGateway->getAdapter();
        $sql = "SELECT * FROM numerohabitacion";
        $sql .= " ORDER BY idnumerohabitacion ASC";
        if(!$totalregistro)$sql .= " LIMIT {$start},{$length}";
        $data = $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE)->toArray();
        return $data;
    }
}