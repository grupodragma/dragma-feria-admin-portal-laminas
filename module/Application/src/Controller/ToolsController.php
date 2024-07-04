<?php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

class ToolsController extends AbstractActionController {

    protected $serviceManager;
    protected $objTools;
    protected $objEmpresasTable;
    
    public function __construct($serviceManager, $objTools, $objEmpresasTable) {
        $this->serviceManager = $serviceManager;
        $this->objTools = $objTools;
        $this->objEmpresasTable = $objEmpresasTable;
    }

    public function procesarEmpresasHashAction(){
        $dataEmpresa = $this->objEmpresasTable->obtenerDatosEmpresas([]);
        foreach($dataEmpresa as $empresa){
            //$this->objEmpresasTable->actualizarDatosEmpresas(['hash_url'=>$this->objTools->toAscii($empresa['nombre'])], $empresa['idempresas']);
            echo $empresa['hash_url']."\n";
        }
        return $this->jsonZF(['result'=> 'success']);
    }

    public function procesarEmpresasHashUrlImagenAction(){
        $dataEmpresa = $this->objEmpresasTable->obtenerDatosEmpresas([]);
        foreach($dataEmpresa as $empresa){
        	//$extensionImagen = pathinfo($empresa['hash_logo'])['extension'];
        	//$nuevoNombreImagen = $empresa['hash_url'].".".$extensionImagen;
        	//$this->objEmpresasTable->actualizarDatosEmpresas(['hash_logo'=> $nuevoNombreImagen], $empresa['idempresas']);
            //echo $empresa['idempresas']." ".$directorioImagenRutaCompleta." ".$nuevoImagenRutaCompleta."\n";
            /*$directorio = getcwd()."/public/empresas/logo";
            $nombreImagen = $empresa['hash_logo'];
            $directorioImagenRutaCompleta = $directorio."/".$nombreImagen;
            if($nombreImagen != null && !empty(glob($directorioImagenRutaCompleta))){
                $extensionImagen = pathinfo($directorioImagenRutaCompleta)['extension'];
                $nuevoImagenRutaCompleta = $directorio."/".$empresa['hash_url'].".".$extensionImagen;
                $nuevoNombreImagen = $empresa['hash_url'].".".$extensionImagen;
                rename($directorioImagenRutaCompleta, $nuevoImagenRutaCompleta);
                $this->objEmpresasTable->actualizarDatosEmpresas(['hash_logo'=> $nuevoNombreImagen], $empresa['idempresas']);
                echo $empresa['idempresas']." ".$directorioImagenRutaCompleta." ".$nuevoImagenRutaCompleta."\n";
            }*/
        }
        return $this->jsonZF(['result'=> 'success']);
    }

    private function jsonZF($data){
        return new JsonModel($data);
    }

}