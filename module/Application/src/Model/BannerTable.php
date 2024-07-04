<?php

namespace Application\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\Sql\Select;

class BannerTable {
    protected $tableGateway;
    public function __construct(TableGatewayInterface $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    public function obtenerBanner(){
        $condiciones = [];
        $adapter = $this->tableGateway->getAdapter();
        $sql = "SELECT
            b.*,
            p.nombre AS pagina,
            pb.nombre AS banner,
            CONCAT(p.nombre,' ', pb.nombre) AS posicion_pagina,
            (
                CASE
                    WHEN categoria = 'PROYECTO' THEN (SELECT p.nombre FROM productos p WHERE idproductos = idtabla)
                    WHEN categoria = 'BANCO' THEN (SELECT b.nombre FROM bancos b WHERE idbancos = idtabla)
                    WHEN categoria = 'INMOBILIARIA' THEN (SELECT b.nombre FROM empresas b WHERE idempresas = idtabla)
                END
            ) AS elemento
        FROM banner b
        INNER JOIN posicion_banner pb ON pb.idposicionbanner = b.idposicionbanner
        INNER JOIN paginas p ON p.idpaginas = pb.idpaginas";
        if( !empty($condiciones) )$sql .= " WHERE ".implode(" AND ", $condiciones);
        $sql .= " ORDER BY b.categoria, b.fecha_programa_inicio, b.fecha_programa_fin ASC";
        return $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE)->toArray();
    }
    public function obtenerDatoBanner($where){
        $rowset = $this->tableGateway->select($where);
        return $rowset->current();
    }
    public function obtenerDatosBanner($where){
        $rowset = $this->tableGateway->select($where);
        return $rowset->toArray();
    }
    public function actualizarDatosBanner($data,$idbanner){
        $rowset = $this->tableGateway->update($data,["idbanner" => $idbanner]);
    }
    public function agregarBanner($data){
        $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;
    }
    public function eliminarBanner($idbanner){
        $this->tableGateway->delete(["idbanner" => $idbanner]);
    }
    public function obtenerFiltroDatosBanner($start,$length,$search=null,$totalregistro=false){
        $adapter = $this->tableGateway->getAdapter();
        $sql = "SELECT * FROM banner";
        $sql .= " ORDER BY idbanner ASC";
        if(!$totalregistro)$sql .= " LIMIT {$start},{$length}";
        $data = $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE)->toArray();
        return $data;
    }
    public function validarRangoFechasBanner($fecha_inicio, $fecha_fin, $categoria, $idposicionbanner){
        $adapter = $this->tableGateway->getAdapter();
        $sql = "SELECT *
        FROM(
            SELECT *
            FROM banner
            WHERE ( '{$fecha_inicio}' BETWEEN fecha_programa_inicio AND fecha_programa_fin) OR ('{$fecha_fin}' BETWEEN fecha_programa_inicio AND fecha_programa_fin)
        ) AS b
        WHERE b.categoria = '{$categoria}' AND b.idposicionbanner = '{$idposicionbanner}'";
        return $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE)->current();
    }
    public function obtenerFechaInicioFinBanner($categoria, $idposicionbanner){
        $condiciones = [];
        $adapter = $this->tableGateway->getAdapter();
        $sql = "SELECT MIN(fecha_programa_inicio) AS fecha_inicio, MAX(fecha_programa_fin) AS fecha_fin, idbanner FROM banner WHERE categoria ='{$categoria}' AND idposicionbanner ='{$idposicionbanner}'";
        if( !empty($condiciones) ) {
            $sql .= " WHERE ".implode(" AND ", $condiciones);
        }
        return $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE)->current();
    }
}