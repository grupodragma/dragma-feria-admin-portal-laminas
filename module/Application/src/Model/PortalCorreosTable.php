<?php

namespace Application\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\Sql\Select;

class PortalCorreosTable {
    protected $tableGateway;
    public function __construct(TableGatewayInterface $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    public function obtenerPortalCorreos(){
        $select = $this->tableGateway->getSql()->select();
        $select->order('idportalcorreos DESC');
        $rowset = $this->tableGateway->selectWith($select);
        return $rowset->toArray();
    }
    public function obtenerDatoPortalCorreos($where){
        $rowset = $this->tableGateway->select($where);
        return $rowset->current();
    }
    public function obtenerDatosPortalCorreos($where){
        $rowset = $this->tableGateway->select($where);
        return $rowset->toArray();
    }
    public function actualizarDatoPortalCorreos($data,$where){
        $this->tableGateway->update($data,$where);
    }
    public function agregarPortalCorreos($data){
        $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;
    }
    public function eliminarPortalCorreos($idportalcorreos){
        $this->tableGateway->delete(["idportalcorreos" => $idportalcorreos]);
    }
}