<?php
namespace Application\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Expression;

class SubModulosTable {
    
    protected $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    public function obtenerDatoSubModulos($where){
        $rowset = $this->tableGateway->select($where);
        return $rowset->current();
    }
    public function obtenerDatosSubModulos($where){
        $rowset = $this->tableGateway->select($where)->toArray();
        return $rowset;
    }
    public function actualizarDatosSubModulos($data, $idsubmodulo){
        $rowset = $this->tableGateway->update($data, ['idsubmodulo' => $idsubmodulo]);
    }
    public function agregarSubModulo($data){
        $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;
    }
    public function eliminarSubModulo($where){
        $this->tableGateway->delete($where);
    }
    public function obtenerUltimoOrdenSubModuloPorIdModulo($idmodulo){
        $adapter = $this->tableGateway->getAdapter();
        $sql = "SELECT IF((MAX(orden) + 1), (MAX(orden) + 1), 1) AS ultimo_orden FROM fd_submodulos WHERE idmodulo = {$idmodulo}";
        return $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE)->current()['ultimo_orden'];
    }
}

