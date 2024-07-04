<?php

namespace Application\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\Sql\Select;

class TipoHabitacionTable {
    protected $tableGateway;
    public function __construct(TableGatewayInterface $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    public function obtenerTipoHabitacion(){
        $select = $this->tableGateway->getSql()->select();
        $select->order('idtipohabitacion DESC');
        $rowset = $this->tableGateway->selectWith($select);
        return $rowset->toArray();
    }
    public function obtenerDatoTipoHabitacion($where){
        $rowset = $this->tableGateway->select($where);
        return $rowset->current();
    }
    public function obtenerDatosTipoHabitacion($where){
        $rowset = $this->tableGateway->select($where);
        return $rowset->toArray();
    }
    public function actualizarDatosTipoHabitacion($data,$idtipohabitacion){
        $rowset = $this->tableGateway->update($data,["idtipohabitacion" => $idtipohabitacion]);
    }
    public function agregarTipoHabitacion($data){
        $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;
    }
    public function eliminarTipoHabitacion($idtipohabitacion){
        $this->tableGateway->delete(["idtipohabitacion" => $idtipohabitacion]);
    }
    public function obtenerFiltroDatosTipoHabitacion($start,$length,$search=null,$totalregistro=false){
        $adapter = $this->tableGateway->getAdapter();
        $sql = "SELECT * FROM tipohabitacion";
        $sql .= " ORDER BY idtipohabitacion ASC";
        if(!$totalregistro)$sql .= " LIMIT {$start},{$length}";
        $data = $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE)->toArray();
        return $data;
    }
}