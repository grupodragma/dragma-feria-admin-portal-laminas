<?php

namespace Application\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\Sql\Select;

class PortalBannerTable {
    protected $tableGateway;
    public function __construct(TableGatewayInterface $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    public function obtenerPortalBanner(){
        $condiciones = [];
        $adapter = $this->tableGateway->getAdapter();
        $sql = "SELECT p.*, pb.nombre AS posicion, pg.nombre AS pagina
        FROM portal_banner p
        INNER JOIN posicion_banner pb ON pb.idposicionbanner = p.idposicionbanner
        LEFT JOIN paginas pg ON pg.idpaginas = pb.idpaginas";
        if( !empty($condiciones) ) $sql .= " WHERE ".implode(" AND ", $condiciones);
        $sql .= " ORDER BY pb.nombre ASC";
        return $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE)->toArray();
    }
    public function obtenerDatoPortalBanner($where){
        $rowset = $this->tableGateway->select($where);
        return $rowset->current();
    }
    public function obtenerDatosPortalBanner($where){
        $rowset = $this->tableGateway->select($where);
        return $rowset->toArray();
    }
    public function actualizarDatosPortalBanner($data,$idportalbanner){
        $rowset = $this->tableGateway->update($data,["idportalbanner" => $idportalbanner]);
    }
    public function agregarPortalBanner($data){
        $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;
    }
    public function eliminarPortalBanner($idportalbanner){
        $this->tableGateway->delete(["idportalbanner" => $idportalbanner]);
    }
    public function obtenerFiltroDatosPortalBanner($start,$length,$search=null,$totalregistro=false){
        $adapter = $this->tableGateway->getAdapter();
        $sql = "SELECT * FROM portalbanner";
        $sql .= " ORDER BY idportalbanner ASC";
        if(!$totalregistro)$sql .= " LIMIT {$start},{$length}";
        $data = $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE)->toArray();
        return $data;
    }
}