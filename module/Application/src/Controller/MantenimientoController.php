<?php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

class MantenimientoController extends AbstractActionController {

    protected $serviceManager;
    protected $objPlanesTable;
    protected $objStandTable;
	protected $objStandModeloTable;
    protected $objPaginasTable;
    protected $objPlanesPaginasTable;
    protected $objMenusTable;
    protected $objPlanesMenusTable;
    protected $objSectoresTable;
    protected $objBancosTable;
    protected $imagenExtensionesValidas;
    protected $objSegmentosTable;
    protected $objTools;
    protected $objDistritosTable;
    protected $objPosicionBannerTable;
    protected $objTipoHabitacionTable;
    protected $objNumeroHabitacionTable;
    protected $objRangoPreciosTable;
    protected $objEtapaTable;
    
    public function __construct($serviceManager, $objPlanesTable, $objStandTable,$objStandModeloTable, $objPaginasTable, $objPlanesPaginasTable, $objMenusTable, $objPlanesMenusTable, $objSectoresTable, $objBancosTable, $objSegmentosTable, $objTools, $objDistritosTable, $objPosicionBannerTable, $objTipoHabitacionTable, $objNumeroHabitacionTable, $objRangoPreciosTable, $objEtapaTable) {
        $this->serviceManager = $serviceManager;
        $this->objPlanesTable = $objPlanesTable;
        $this->objStandTable = $objStandTable;
		$this->objStandModeloTable=$objStandModeloTable;
        $this->objPaginasTable = $objPaginasTable;
        $this->objPlanesPaginasTable = $objPlanesPaginasTable;
        $this->objMenusTable = $objMenusTable;
        $this->objPlanesMenusTable = $objPlanesMenusTable;
        $this->objSectoresTable = $objSectoresTable;
        $this->objBancosTable = $objBancosTable;
        $this->imagenExtensionesValidas = ['jpg','jpeg','png','gif','svg'];
        $this->objSegmentosTable = $objSegmentosTable;
        $this->objTools = $objTools;
        $this->objDistritosTable = $objDistritosTable;
        $this->objPosicionBannerTable = $objPosicionBannerTable;
        $this->objTipoHabitacionTable = $objTipoHabitacionTable;
        $this->objNumeroHabitacionTable = $objNumeroHabitacionTable;
        $this->objRangoPreciosTable = $objRangoPreciosTable;
        $this->objEtapaTable = $objEtapaTable;
    }

    public function planesAction(){
    
    }

    public function listarPlanesAction(){
        $dataPlanes = $this->objPlanesTable->obtenerPlanes();
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataPlanes as $item){
            $btnOrden = '<div class="opciones-flechas" data-ids="'.$item['idplanes'].'"><button class="flecha-arriba"><i class="fas fa-sort-up"></i></button><button class="flecha-abajo"><i class="fas fa-sort-down"></i></button></div>';
            $data_out['data'][] = [
                $item['nombre'],
                $item['cantidad_zonas'],
                $item['cantidad_empresas_zonas'],
                '<div class="clas btn btn-sm btn-info pop-up" href="/mantenimiento/editar-planes?idplanes='.$item['idplanes'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="clas btn btn-sm btn-danger pop-up" href="/mantenimiento/eliminar-planes?idplanes='.$item['idplanes'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div> '.$btnOrden
            ];
        }
        return $this->jsonZF($data_out);
    }
    
    public function agregarPlanesAction(){
        $data=[
            'paginas'=> $this->objPaginasTable->obtenerPaginas(),
            'menus'=> $this->objMenusTable->obtenerMenus()
        ];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarPlanesAction(){
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'cantidad_zonas'=>$datosFormulario['cantidad_zonas'],
            'cantidad_empresas_zonas'=>$datosFormulario['cantidad_empresas_zonas'],
            'orden'=> $this->objPlanesTable->obtenerUltimoOrdenPlanes()
        ];
        $lastIdPlanes = $this->objPlanesTable->agregarPlanes($data);

        $dataIdPaginas = ( isset($datosFormulario['idpaginas']) ) ? $datosFormulario['idpaginas'] : [];

        if(!empty($dataIdPaginas)){
            foreach($dataIdPaginas as $idpagina){
                $data = [
                    'idplanes'=> $lastIdPlanes,
                    'idpaginas'=> $idpagina
                ];
                $this->objPlanesPaginasTable->agregarPlanesPaginas($data);
            }
        }

        $dataIdMenus = ( isset($datosFormulario['idmenus']) ) ? $datosFormulario['idmenus'] : [];

        if(!empty($dataIdMenus)){
            foreach($dataIdMenus as $idmenu){
                $data = [
                    'idplanes'=> $lastIdPlanes,
                    'idmenus'=> $idmenu
                ];
                $this->objPlanesMenusTable->agregarPlanesMenus($data);
            }
        }

        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarPlanesAction(){
        $idplanes = $this->params()->fromQuery('idplanes');
        $dataPlanes = $this->objPlanesTable->obtenerDatoPlanes(['idplanes'=> $idplanes]);
        ////////// IDPAGINAS [INICIO] //////////
        $dataPlanes['paginas'] = $this->objPaginasTable->obtenerPaginas();
        $dataPlanesPaginas = $this->objPlanesPaginasTable->obtenerDatosPlanesPaginas(['idplanes'=> $idplanes]);
        $selectIdPaginas = [];
        foreach($dataPlanesPaginas as $item)$selectIdPaginas[] = $item['idpaginas'];
        $dataPlanes['idpaginas'] = $selectIdPaginas;
        ////////// IDPAGINAS [FIN] //////////
        ////////// IDMENUS [INICIO] //////////
        $dataPlanes['menus'] = $this->objMenusTable->obtenerMenus();
        $dataPlanesMenus = $this->objPlanesMenusTable->obtenerDatosPlanesMenus(['idplanes'=> $idplanes]);
        $selectIdMenus = [];
        foreach($dataPlanesMenus as $item)$selectIdMenus[] = $item['idmenus'];
        $dataPlanes['idmenus'] = $selectIdMenus;
        ////////// IDMENUS [FIN] //////////
        return $this->consoleZF($dataPlanes);
    }
    
    public function guardarEditarPlanesAction(){
        $idplanes = $this->params()->fromQuery('idplanes');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'cantidad_zonas'=>$datosFormulario['cantidad_zonas'],
            'cantidad_empresas_zonas'=>$datosFormulario['cantidad_empresas_zonas']
        ];
        $this->objPlanesTable->actualizarDatosPlanes($data, $idplanes);
        ////////// PLANES PAGINAS [INICIO] //////////
        $dataIdPaginas = ( isset($datosFormulario['idpaginas']) ) ? $datosFormulario['idpaginas'] : [];
        $this->objPlanesPaginasTable->eliminarPlanesPaginas(["idplanes" => $idplanes]);
        if(!empty($dataIdPaginas)){ 
            foreach($dataIdPaginas as $idpagina){
                $data = [
                    'idplanes'=> $idplanes,
                    'idpaginas'=> $idpagina
                ];
                $this->objPlanesPaginasTable->agregarPlanesPaginas($data);
            }
        }
        ////////// PLANES PAGINAS [FIN] //////////
        ////////// PLANES MENUS [INICIO] //////////
        $dataIdMenus = ( isset($datosFormulario['idmenus']) ) ? $datosFormulario['idmenus'] : [];
        $this->objPlanesMenusTable->eliminarPlanesMenus(['idplanes'=> $idplanes]);
        if(!empty($dataIdMenus)){
            foreach($dataIdMenus as $idmenu){
                $data = [
                    'idplanes'=> $idplanes,
                    'idmenus'=> $idmenu
                ];
                $this->objPlanesMenusTable->agregarPlanesMenus($data);
            }
        }
        ////////// PLANES MENUS [FIN] //////////
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarPlanesAction(){
        $idplanes = $this->params()->fromQuery('idplanes');
        $dataPlanes = $this->objPlanesTable->obtenerDatoPlanes(['idplanes'=> $idplanes]);
        return $this->consoleZF($dataPlanes);
    }
    
    public function confirmarEliminarPlanesAction(){
        $idplanes = $this->params()->fromQuery('idplanes');
        $this->objPlanesTable->eliminarPlanes($idplanes);
        $this->objPlanesPaginasTable->eliminarPlanesPaginas(["idplanes" => $idplanes]);
        $this->objPlanesMenusTable->eliminarPlanesMenus(["idplanes" => $idplanes]);
        $this->objPlanesTable->actualizarOrdenPlanes();
        return $this->jsonZF(['result'=>'success']);
    }

    public function guardarOrdenPlanesAction(){
        $datosFormulario = $this->params()->fromPost();
        $dataOrden = $datosFormulario['orden'];
        if(!empty($dataOrden)){
            foreach($dataOrden as $item) {
                $this->objPlanesTable->actualizarDatosPlanes(['orden'=> $item['orden']], $item['ids']);
            }
        }
        return $this->jsonZF(['result'=>'success']);
    }

    public function standAction(){
    
    }
    
    public function listarStandAction(){
        $dataStand = $this->objStandTable->obtenerStand();
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataStand as $item){
            $btnGaleria = '<div class="clas btn btn-sm btn-dark pop-up-2" href="/mantenimiento/galeria-stand?idstand='.$item['idstand'].'"><i class="fas fa-images"></i> <span class="hidden-xs">Galeria</span></div>';
            $data_out['data'][] = [
                $item['nombre'],
                $btnGaleria
                //$btnGaleria.' <div class="clas btn btn-sm btn-info pop-up" href="/mantenimiento/editar-stand?idstand='.$item['idstand'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="clas btn btn-sm btn-danger pop-up" href="/mantenimiento/eliminar-stand?idstand='.$item['idstand'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div>'
            ];
        }
        return $this->jsonZF($data_out);
    }
   
    public function agregarStandAction(){
        $data=[];
        return $this->consoleZF($data);
    }
    
    
    public function guardarAgregarStandAction(){
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
        ];
        $this->objStandTable->agregarStand($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarStandAction(){
        $idstand = $this->params()->fromQuery('idstand');
        $dataStand = $this->objStandTable->obtenerDatoStand(['idstand'=> $idstand]);
        return $this->consoleZF($dataStand);
    }
    
    public function guardarEditarStandAction(){
        $idstand = $this->params()->fromQuery('idstand');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
        ];
        $this->objStandTable->actualizarDatosStand($data, $idstand);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarStandAction(){
        $idstand = $this->params()->fromQuery('idstand');
        $dataStand = $this->objStandTable->obtenerDatoStand(['idstand'=> $idstand]);
        return $this->consoleZF($dataStand);
    }
    
    public function confirmarEliminarStandAction(){
        $idstand = $this->params()->fromQuery('idstand');
        $this->objStandTable->eliminarStand($idstand);
        return $this->jsonZF(['result'=>'success']);
    }

    public function paginasAction(){
    
    }
    
    public function listarPaginasAction(){
        $dataPaginas = $this->objPaginasTable->obtenerPaginas();
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataPaginas as $item){
            $btnOrden = '<div class="opciones-flechas" data-ids="'.$item['idpaginas'].'"><button class="flecha-arriba"><i class="fas fa-sort-up"></i></button><button class="flecha-abajo"><i class="fas fa-sort-down"></i></button></div>';
            $data_out['data'][] = [
                $item['nombre'],
                '<div class="clas btn btn-sm btn-info pop-up" href="/mantenimiento/editar-paginas?idpaginas='.$item['idpaginas'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="clas btn btn-sm btn-danger pop-up" href="/mantenimiento/eliminar-paginas?idpaginas='.$item['idpaginas'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div> '.$btnOrden
            ];
        }
        return $this->jsonZF($data_out);
    }
    
    public function agregarPaginasAction(){
        $data=[];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarPaginasAction(){
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
            'orden'=>$this->objPaginasTable->obtenerUltimoOrdenPaginas()
        ];
        $this->objPaginasTable->agregarPaginas($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarPaginasAction(){
        $idpaginas = $this->params()->fromQuery('idpaginas');
        $dataPaginas = $this->objPaginasTable->obtenerDatoPaginas(['idpaginas'=> $idpaginas]);
        return $this->consoleZF($dataPaginas);
    }
    
    public function guardarEditarPaginasAction(){
        $idpaginas = $this->params()->fromQuery('idpaginas');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
        ];
        $this->objPaginasTable->actualizarDatosPaginas($data, $idpaginas);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarPaginasAction(){
        $idpaginas = $this->params()->fromQuery('idpaginas');
        $dataPaginas = $this->objPaginasTable->obtenerDatoPaginas(['idpaginas'=> $idpaginas]);
        return $this->consoleZF($dataPaginas);
    }
    
    public function confirmarEliminarPaginasAction(){
        $idpaginas = $this->params()->fromQuery('idpaginas');
        $this->objPaginasTable->eliminarPaginas($idpaginas);
        $this->objPaginasTable->actualizarOrdenPaginas();
        return $this->jsonZF(['result'=>'success']);
    }

    private function generarHashUrl($string){
        $buscar = ["á","é","í","ó","ú"];
        $reemplazar = ["a","e","i","o","u"];
        return mb_strtolower(str_replace(" ", "-", str_replace($buscar, $reemplazar, $string)));
    }

    public function guardarOrdenPaginasAction(){
        $datosFormulario = $this->params()->fromPost();
        $dataOrden = $datosFormulario['orden'];
        if(!empty($dataOrden)){
            foreach($dataOrden as $item) {
                $this->objPaginasTable->actualizarDatosPaginas(['orden'=> $item['orden']], $item['ids']);
            }
        }
        return $this->jsonZF(['result'=>'success']);
    }

    public function menusAction(){
    
    }
    
    public function listarMenusAction(){
        $dataMenus = $this->objMenusTable->obtenerMenus();
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataMenus as $item){
            $tipo = ( $item['tipo'] == 'E' ) ? 'Encabezado (Interacción de la Feria)' : 'Pie de Página (Interacción del Stand)';
            $data_out['data'][] = [
                $tipo,
                $item['nombre'],
                '<div class="clas btn btn-sm btn-info pop-up" href="/mantenimiento/editar-menus?idmenus='.$item['idmenus'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="clas btn btn-sm btn-danger pop-up" href="/mantenimiento/eliminar-menus?idmenus='.$item['idmenus'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div>'
            ];
        }
        return $this->jsonZF($data_out);
    }
    
    public function agregarMenusAction(){
        $data=[];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarMenusAction(){
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'tipo'=>$datosFormulario['tipo'],
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->generarHashUrl($datosFormulario['hash_url']),
            'posicion'=>$datosFormulario['posicion']
        ];
        $this->objMenusTable->agregarMenus($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarMenusAction(){
        $idmenus = $this->params()->fromQuery('idmenus');
        $dataMenus = $this->objMenusTable->obtenerDatoMenus(['idmenus'=> $idmenus]);
        return $this->consoleZF($dataMenus);
    }
    
    public function guardarEditarMenusAction(){
        $idmenus = $this->params()->fromQuery('idmenus');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'tipo'=>$datosFormulario['tipo'],
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->generarHashUrl($datosFormulario['hash_url']),
            'posicion'=>$datosFormulario['posicion']
        ];
        $this->objMenusTable->actualizarDatosMenus($data, $idmenus);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarMenusAction(){
        $idmenus = $this->params()->fromQuery('idmenus');
        $dataMenus = $this->objMenusTable->obtenerDatoMenus(['idmenus'=> $idmenus]);
        return $this->consoleZF($dataMenus);
    }
    
    public function confirmarEliminarMenusAction(){
        $idmenus = $this->params()->fromQuery('idmenus');
        $this->objMenusTable->eliminarMenus($idmenus);
        return $this->jsonZF(['result'=>'success']);
    }

    public function sectoresAction(){
    
    }
    
    public function listarSectoresAction(){
        $dataSectores = $this->objSectoresTable->obtenerSectores();
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataSectores as $item){
            $btnGaleria = '<div class="clas btn btn-sm btn-dark pop-up-2" href="/mantenimiento/galeria-sectores?idsectores='.$item['idsectores'].'"><i class="fas fa-images"></i> <span class="hidden-xs">Galeria</span></div>';
            $data_out['data'][] = [
                $item['nombre'],
                $btnGaleria.' <div class="clas btn btn-sm btn-info pop-up" href="/mantenimiento/editar-sectores?idsectores='.$item['idsectores'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="clas btn btn-sm btn-danger pop-up" href="/mantenimiento/eliminar-sectores?idsectores='.$item['idsectores'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div>'
            ];
        }
        return $this->jsonZF($data_out);
    }
    
    public function agregarSectoresAction(){
        $data=[];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarSectoresAction(){
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
        ];
        $this->objSectoresTable->agregarSectores($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarSectoresAction(){
        $idsectores = $this->params()->fromQuery('idsectores');
        $dataSectores = $this->objSectoresTable->obtenerDatoSectores(['idsectores'=> $idsectores]);
        return $this->consoleZF($dataSectores);
    }
    
    public function guardarEditarSectoresAction(){
        $idsectores = $this->params()->fromQuery('idsectores');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
        ];
        $this->objSectoresTable->actualizarDatosSectores($data, $idsectores);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarSectoresAction(){
        $idsectores = $this->params()->fromQuery('idsectores');
        $dataSectores = $this->objSectoresTable->obtenerDatoSectores(['idsectores'=> $idsectores]);
        return $this->consoleZF($dataSectores);
    }
    
    public function confirmarEliminarSectoresAction(){
        $idsectores = $this->params()->fromQuery('idsectores');
        $this->objSectoresTable->eliminarSectores($idsectores);
        return $this->jsonZF(['result'=>'success']);
    }

    public function galeriaSectoresAction(){
        $idsectores = $this->params()->fromQuery('idsectores');
        $dataSectores = $this->objSectoresTable->obtenerDatoSectores(['idsectores'=> $idsectores]);
        return $this->consoleZF($dataSectores);
    }

    public function galeriaStandAction(){
        $idstand = $this->params()->fromQuery('idstand');
        $dataStand = $this->objStandTable->obtenerDatoStand(['idstand'=> $idstand]);
        return $this->consoleZF($dataStand);
    }

    public function bancosAction(){
    
    }
    
    public function listarBancosAction(){
        $dataBancos = $this->objBancosTable->obtenerBancos();
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataBancos as $item){
            $data_out['data'][] = [
                $item['nombre'],
                '<div class="clas btn btn-sm btn-info pop-up" href="/mantenimiento/editar-bancos?idbancos='.$item['idbancos'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="clas btn btn-sm btn-danger pop-up" href="/mantenimiento/eliminar-bancos?idbancos='.$item['idbancos'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div>'
            ];
        }
        return $this->jsonZF($data_out);
    }
    
    public function agregarBancosAction(){
        $data=[];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarBancosAction(){
        $archivos = $this->params()->fromFiles();
        $datosFormulario = $this->params()->fromPost();
        $horarioAtencion = $this->objTools->timeFormat($datosFormulario['hora_inicio'][0])."-".$this->objTools->timeFormat($datosFormulario['hora_fin'][0])." | ".$this->objTools->timeFormat($datosFormulario['hora_inicio'][1])."-".$this->objTools->timeFormat($datosFormulario['hora_fin'][1]);
        $cuota_inicial_porcentaje = (isset($datosFormulario['cuota_inicial_porcentaje'])) ? $datosFormulario['cuota_inicial_porcentaje'] : [];
        $plazo_credito_hipotecario = (isset($datosFormulario['plazo_credito_hipotecario'])) ? $datosFormulario['plazo_credito_hipotecario'] : [];
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'correo'=>$datosFormulario['correo'],
            'telefono'=>$datosFormulario['telefono'],
            'enlace'=>$datosFormulario['enlace'],
            'enlace_wsp'=>$datosFormulario['enlace_wsp'],
            'horario_atencion'=> $horarioAtencion,
            'cuota_inicial_porcentaje'=> implode(",", $cuota_inicial_porcentaje),
            'plazo_credito_hipotecario'=> implode(",", $plazo_credito_hipotecario),
            //'tipo_cuota'=>$datosFormulario['tipo_cuota'],
            //'tipo_cuota_porcentaje'=>$datosFormulario['tipo_cuota_porcentaje'],
        ];
        ////////// SCRIPT PARA GUARDAR IMAGEN [INICIO] //////////
        $imagenExtensionesValidas = [];
        $carpetaArchivos = '';
        $dataArchivo = [];
        $keyArchivo = [];
        if(!empty($archivos)){
            foreach($archivos as $key => $archivo){
                if($key === 'imagenes'){
                    continue;
                }
                switch($key){
                    case 'logo':
                        $imagenExtensionesValidas = $this->imagenExtensionesValidas;
                        $carpetaArchivos = getcwd().'/public/bancos/logo';
                        $keyArchivo[$key] = ['hash'=> 'hash_logo', 'nombre'=> 'nombre_logo'];
                        if( $archivo['size'] > 0 && $archivo['size'] > 5000000 )return $this->jsonZF(['result'=>'file_max_size']);
                    break;
                    case 'logo_small':
                        $imagenExtensionesValidas = $this->imagenExtensionesValidas;
                        $carpetaArchivos = getcwd().'/public/bancos/logo';
                        $keyArchivo[$key] = ['hash'=> 'hash_logo_small', 'nombre'=> 'nombre_logo_small'];
                    break;
                    case 'hash_encabezado_fondo':
                        $imagenExtensionesValidas = $this->imagenExtensionesValidas;
                        $carpetaArchivos = getcwd().'/public/bancos/encabezado';
                        $keyArchivo[$key] = ['hash'=> 'hash_encabezado_fondo', 'nombre'=> 'nombre_encabezado_fondo'];
                    break;
                    case 'hash_logo_asesor':
                        $imagenExtensionesValidas = $this->imagenExtensionesValidas;
                        $carpetaArchivos = getcwd().'/public/bancos/asesor/logo';
                        $keyArchivo[$key] = ['hash'=> 'hash_logo_asesor', 'nombre'=> 'nombre_logo_asesor'];
                    break;
                    default:
                    break;
                }
                $dataArchivo['id'] = md5(uniqid());
                if( $archivo['size'] !== 0 ) {
                    $dataArchivo['extension'] = strtolower(pathinfo($archivo['name'])['extension']);
                    if( in_array($dataArchivo['extension'], $imagenExtensionesValidas) ) {
                        $dataArchivo['nombre_completo'] = $dataArchivo['id'].'.'.$dataArchivo['extension'];
                        $dataArchivo['nombre_original'] = $archivo['name'];
                        if(move_uploaded_file($archivo['tmp_name'], $carpetaArchivos.'/'.$dataArchivo['nombre_completo'])){
                            $data[$keyArchivo[$key]['hash']] = $dataArchivo['nombre_completo'];
                            $data[$keyArchivo[$key]['nombre']] = $dataArchivo['nombre_original'];
                        }
                    }
                }
            }
        }
        ////////// SCRIPT PARA GUARDAR IMAGEN [FIN] //////////
        $this->objBancosTable->agregarBancos($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarBancosAction(){
        $idbancos = $this->params()->fromQuery('idbancos');
        $dataBancos = $this->objBancosTable->obtenerDatoBancos(['idbancos'=> $idbancos]);
        $dataBancos['horario_atencion'] = explode(" | ", $dataBancos['horario_atencion']);
        $dataBancos['cuota_inicial_porcentaje'] = explode(",", $dataBancos['cuota_inicial_porcentaje']);
        $dataBancos['plazo_credito_hipotecario'] = explode(",", $dataBancos['plazo_credito_hipotecario']);
        return $this->consoleZF($dataBancos);
    }
    
    public function guardarEditarBancosAction(){
        $archivos = $this->params()->fromFiles();
        $idbancos = $this->params()->fromQuery('idbancos');
        $datosFormulario = $this->params()->fromPost();
        $horarioAtencion = $this->objTools->timeFormat($datosFormulario['hora_inicio'][0])."-".$this->objTools->timeFormat($datosFormulario['hora_fin'][0])." | ".$this->objTools->timeFormat($datosFormulario['hora_inicio'][1])."-".$this->objTools->timeFormat($datosFormulario['hora_fin'][1]);
        $cuota_inicial_porcentaje = (isset($datosFormulario['cuota_inicial_porcentaje'])) ? $datosFormulario['cuota_inicial_porcentaje'] : [];
        $plazo_credito_hipotecario = (isset($datosFormulario['plazo_credito_hipotecario'])) ? $datosFormulario['plazo_credito_hipotecario'] : [];
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'correo'=>$datosFormulario['correo'],
            'telefono'=>$datosFormulario['telefono'],
            'enlace'=>$datosFormulario['enlace'],
            'enlace_wsp'=>$datosFormulario['enlace_wsp'],
            'horario_atencion'=> $horarioAtencion,
            'cuota_inicial_porcentaje'=> implode(",", $cuota_inicial_porcentaje),
            'plazo_credito_hipotecario'=> implode(",", $plazo_credito_hipotecario),
            //'tipo_cuota'=>$datosFormulario['tipo_cuota'],
            //'tipo_cuota_porcentaje'=>$datosFormulario['tipo_cuota_porcentaje'],
        ];

        ////////// SCRIPT PARA ACTUALIZAR IMAGEN [INICIO] //////////
        $imagenExtensionesValidas = [];
        $carpetaArchivos = '';
        $dataArchivo = [];
        $keyArchivo = [];
        if(!empty($archivos)){
            foreach($archivos as $key => $archivo){
                switch($key){
                    case 'logo':
                        $imagenExtensionesValidas = $this->imagenExtensionesValidas;
                        $carpetaArchivos = getcwd().'/public/bancos/logo';
                        $keyArchivo[$key] = ['hash'=> 'hash_logo', 'nombre'=> 'nombre_logo'];
                        if( $archivo['size'] > 0 && $archivo['size'] > 5000000 )return $this->jsonZF(['result'=>'file_max_size']);
                    break;
                    case 'logo_small':
                        $imagenExtensionesValidas = $this->imagenExtensionesValidas;
                        $carpetaArchivos = getcwd().'/public/bancos/logo';
                        $keyArchivo[$key] = ['hash'=> 'hash_logo_small', 'nombre'=> 'nombre_logo_small'];
                    break;
                    case 'hash_encabezado_fondo':
                        $imagenExtensionesValidas = $this->imagenExtensionesValidas;
                        $carpetaArchivos = getcwd().'/public/bancos/encabezado';
                        $keyArchivo[$key] = ['hash'=> 'hash_encabezado_fondo', 'nombre'=> 'nombre_encabezado_fondo'];
                    break;
                    case 'hash_logo_asesor':
                        $imagenExtensionesValidas = $this->imagenExtensionesValidas;
                        $carpetaArchivos = getcwd().'/public/bancos/asesor/logo';
                        $keyArchivo[$key] = ['hash'=> 'hash_logo_asesor', 'nombre'=> 'nombre_logo_asesor'];
                    break;
                    default:
                    break;
                }
                $dataArchivo['id'] = md5(uniqid());
                if( !empty($archivo['name']) && $archivo['size'] !== 0 ) {
                    $dataBanco = $this->objBancosTable->obtenerDatoBancos(['idbancos'=> $idbancos]);
                    if( $dataBanco ) {
                        if(file_exists($carpetaArchivos.'/'.$dataBanco[$keyArchivo[$key]['hash']])){
                            @unlink($carpetaArchivos.'/'.$dataBanco[$keyArchivo[$key]['hash']]);
                        }
                        $dataArchivo['extension'] = strtolower(pathinfo($archivo['name'])['extension']);
                        if( in_array($dataArchivo['extension'], $imagenExtensionesValidas) ) {
                            $dataArchivo['nombre_completo'] = $dataArchivo['id'].'.'.$dataArchivo['extension'];
                            $dataArchivo['nombre_original'] = $archivo['name'];
                            if(move_uploaded_file($archivo['tmp_name'], $carpetaArchivos.'/'.$dataArchivo['nombre_completo'])){
                                $data[$keyArchivo[$key]['hash']] = $dataArchivo['nombre_completo'];
                                $data[$keyArchivo[$key]['nombre']] = $dataArchivo['nombre_original'];
                            }
                        }
                    }
                }
            }
        }
        ////////// SCRIPT PARA ACTUALIZAR IMAGEN [FIN] //////////
        $this->objBancosTable->actualizarDatosBancos($data, $idbancos);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarBancosAction(){
        $idbancos = $this->params()->fromQuery('idbancos');
        $dataBancos = $this->objBancosTable->obtenerDatoBancos(['idbancos'=> $idbancos]);
        return $this->consoleZF($dataBancos);
    }
    
    public function confirmarEliminarBancosAction(){
        $idbancos = $this->params()->fromQuery('idbancos');
        $dataBancos = $this->objBancosTable->obtenerDatoBancos(['idbancos'=> $idbancos]);
        if( $dataBancos ) {
            $dataArchivos = [
                'hash_logo'=> ['directorio'=> getcwd().'/public/bancos/logo'],
                'hash_logo_small'=> ['directorio'=> getcwd().'/public/bancos/logo'],
                'hash_encabezado_fondo'=> ['directorio'=> getcwd().'/public/bancos/encabezado']

            ];
            foreach($dataArchivos as $key => $item) {
                if(isset($dataBancos[$key]) && file_exists($item['directorio'].'/'.$dataBancos[$key])){
                    @unlink($item['directorio'].'/'.$dataBancos[$key]);
                }
            }
        }
        $this->objBancosTable->eliminarBancos($idbancos);
        return $this->jsonZF(['result'=>'success']);
    }

    private function consoleZF($data){
        $viewModel = new ViewModel($data);
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    private function jsonZF($data){
        return new JsonModel($data);
    }

    public function segmentosAction(){
        //code
    }
    
    public function listarSegmentosAction(){
        $dataSegmentos = $this->objSegmentosTable->obtenerSegmentos();
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataSegmentos as $item){
            $data_out['data'][] = [
                $item['nombre'],
                '<div class="clas btn btn-sm btn-info pop-up" href="/mantenimiento/editar-segmentos?idsegmentos='.$item['idsegmentos'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="clas btn btn-sm btn-danger pop-up" href="/mantenimiento/eliminar-segmentos?idsegmentos='.$item['idsegmentos'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div>'
            ];
        }
        return $this->jsonZF($data_out);
    }
    
    public function agregarSegmentosAction(){
        $data=[];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarSegmentosAction(){
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
        ];
        $this->objSegmentosTable->agregarSegmentos($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarSegmentosAction(){
        $idsegmentos = $this->params()->fromQuery('idsegmentos');
        $dataSegmentos = $this->objSegmentosTable->obtenerDatoSegmentos(['idsegmentos'=> $idsegmentos]);
        return $this->consoleZF($dataSegmentos);
    }
    
    public function guardarEditarSegmentosAction(){
        $idsegmentos = $this->params()->fromQuery('idsegmentos');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre']
        ];
        $this->objSegmentosTable->actualizarDatosSegmentos($data, $idsegmentos);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarSegmentosAction(){
        $idsegmentos = $this->params()->fromQuery('idsegmentos');
        $dataSegmentos = $this->objSegmentosTable->obtenerDatoSegmentos(['idsegmentos'=> $idsegmentos]);
        return $this->consoleZF($dataSegmentos);
    }
    
    public function confirmarEliminarSegmentosAction(){
        $idsegmentos = $this->params()->fromQuery('idsegmentos');
        $this->objSegmentosTable->eliminarSegmentos($idsegmentos);
        return $this->jsonZF(['result'=>'success']);
    }

    public function distritosAction(){
        //code
    }
    
    public function listarDistritosAction(){
        $dataDistritos = $this->objDistritosTable->obtenerDistritos();
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataDistritos as $item){
            $data_out['data'][] = [
                $item['nombre'],
                '<div class="clas btn btn-sm btn-info pop-up" href="/cliente/editar-distritos?iddistritos='.$item['iddistritos'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="clas btn btn-sm btn-danger pop-up" href="/cliente/eliminar-distritos?iddistritos='.$item['iddistritos'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div>'
            ];
        }
        return $this->jsonZF($data_out);
    }
    
    public function agregarDistritosAction(){
        $data=[];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarDistritosAction(){
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'codigo'=>null,
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
        ];
        $this->objDistritosTable->agregarDistritos($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarDistritosAction(){
        $iddistritos = $this->params()->fromQuery('iddistritos');
        $dataDistritos = $this->objDistritosTable->obtenerDatoDistritos(['iddistritos'=> $iddistritos]);
        return $this->consoleZF($dataDistritos);
    }
    
    public function guardarEditarDistritosAction(){
        $iddistritos = $this->params()->fromQuery('iddistritos');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'codigo'=>null,
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
        ];
        $this->objDistritosTable->actualizarDatosDistritos($data, $iddistritos);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarDistritosAction(){
        $iddistritos = $this->params()->fromQuery('iddistritos');
        $dataDistritos = $this->objDistritosTable->obtenerDatoDistritos(['iddistritos'=> $iddistritos]);
        return $this->consoleZF($dataDistritos);
    }
    
    public function confirmarEliminarDistritosAction(){
        $iddistritos = $this->params()->fromQuery('iddistritos');
        $this->objDistritosTable->eliminarDistritos($iddistritos);
        return $this->jsonZF(['result'=>'success']);
    }

    public function posicionBannerAction(){
        //code
    }
    
    public function listarPosicionBannerAction(){
        $dataPosicionBanner = $this->objPosicionBannerTable->obtenerPosicionBanner();
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataPosicionBanner as $item){
            $data_out['data'][] = [
                $item['pagina'],
                $item['nombre'],
                '<div class="clas btn btn-sm btn-info pop-up" href="/mantenimiento/editar-posicion-banner?idposicionbanner='.$item['idposicionbanner'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="clas btn btn-sm btn-danger pop-up" href="/mantenimiento/eliminar-posicion-banner?idposicionbanner='.$item['idposicionbanner'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div>'
            ];
        }
        return $this->jsonZF($data_out);
    }
    
    public function agregarPosicionBannerAction(){
        $data=[
            'paginas'=> $this->objPaginasTable->obtenerPaginas(),
        ];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarPosicionBannerAction(){
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'idpaginas'=>$datosFormulario['idpaginas'],
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
        ];
        $this->objPosicionBannerTable->agregarPosicionBanner($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarPosicionBannerAction(){
        $idposicionbanner = $this->params()->fromQuery('idposicionbanner');
        $dataPosicionBanner = $this->objPosicionBannerTable->obtenerDatoPosicionBanner(['idposicionbanner'=> $idposicionbanner]);
        $dataPosicionBanner['paginas'] = $this->objPaginasTable->obtenerPaginas();
        return $this->consoleZF($dataPosicionBanner);
    }
    
    public function guardarEditarPosicionBannerAction(){
        $idposicionbanner = $this->params()->fromQuery('idposicionbanner');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'idpaginas'=>$datosFormulario['idpaginas'],
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
        ];
        $this->objPosicionBannerTable->actualizarDatosPosicionBanner($data, $idposicionbanner);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarPosicionBannerAction(){
        $idposicionbanner = $this->params()->fromQuery('idposicionbanner');
        $dataPosicionBanner = $this->objPosicionBannerTable->obtenerDatoPosicionBanner(['idposicionbanner'=> $idposicionbanner]);
        return $this->consoleZF($dataPosicionBanner);
    }
    
    public function confirmarEliminarPosicionBannerAction(){
        $idposicionbanner = $this->params()->fromQuery('idposicionbanner');
        $this->objPosicionBannerTable->eliminarPosicionBanner($idposicionbanner);
        return $this->jsonZF(['result'=>'success']);
    }

    public function tipoHabitacionAction(){
    
    }
    
    public function listarTipoHabitacionAction(){
        $dataTipoHabitacion = $this->objTipoHabitacionTable->obtenerTipoHabitacion();
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataTipoHabitacion as $item){
            $data_out['data'][] = [
                $item['nombre'],
                '<div class="clas btn btn-sm btn-info pop-up" href="/mantenimiento/editar-tipo-habitacion?idtipohabitacion='.$item['idtipohabitacion'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="clas btn btn-sm btn-danger pop-up" href="/mantenimiento/eliminar-tipo-habitacion?idtipohabitacion='.$item['idtipohabitacion'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div>'
            ];
        }
        return $this->jsonZF($data_out);
    }
    
    public function agregarTipoHabitacionAction(){
        $data=[];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarTipoHabitacionAction(){
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
        ];
        $this->objTipoHabitacionTable->agregarTipoHabitacion($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarTipoHabitacionAction(){
        $idtipohabitacion = $this->params()->fromQuery('idtipohabitacion');
        $dataTipoHabitacion = $this->objTipoHabitacionTable->obtenerDatoTipoHabitacion(['idtipohabitacion'=> $idtipohabitacion]);
        return $this->consoleZF($dataTipoHabitacion);
    }
    
    public function guardarEditarTipoHabitacionAction(){
        $idtipohabitacion = $this->params()->fromQuery('idtipohabitacion');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
        ];
        $this->objTipoHabitacionTable->actualizarDatosTipoHabitacion($data, $idtipohabitacion);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarTipoHabitacionAction(){
        $idtipohabitacion = $this->params()->fromQuery('idtipohabitacion');
        $dataTipoHabitacion = $this->objTipoHabitacionTable->obtenerDatoTipoHabitacion(['idtipohabitacion'=> $idtipohabitacion]);
        return $this->consoleZF($dataTipoHabitacion);
    }
    
    public function confirmarEliminarTipoHabitacionAction(){
        $idtipohabitacion = $this->params()->fromQuery('idtipohabitacion');
        $this->objTipoHabitacionTable->eliminarTipoHabitacion($idtipohabitacion);
        return $this->jsonZF(['result'=>'success']);
    }

    public function numeroHabitacionAction(){
    
    }
    
    public function listarNumeroHabitacionAction(){
        $dataNumeroHabitacion = $this->objNumeroHabitacionTable->obtenerNumeroHabitacion();
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataNumeroHabitacion as $item){
            $data_out['data'][] = [
                $item['nombre'],
                '<div class="clas btn btn-sm btn-info pop-up" href="/mantenimiento/editar-numero-habitacion?idnumerohabitacion='.$item['idnumerohabitacion'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="clas btn btn-sm btn-danger pop-up" href="/mantenimiento/eliminar-numero-habitacion?idnumerohabitacion='.$item['idnumerohabitacion'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div>'
            ];
        }
        return $this->jsonZF($data_out);
    }

    public function agregarNumeroHabitacionAction(){
        $data=[];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarNumeroHabitacionAction(){
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
        ];
        $this->objNumeroHabitacionTable->agregarNumeroHabitacion($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarNumeroHabitacionAction(){
        $idnumerohabitacion = $this->params()->fromQuery('idnumerohabitacion');
        $dataNumeroHabitacion = $this->objNumeroHabitacionTable->obtenerDatoNumeroHabitacion(['idnumerohabitacion'=> $idnumerohabitacion]);
        return $this->consoleZF($dataNumeroHabitacion);
    }
    
    public function guardarEditarNumeroHabitacionAction(){
        $idnumerohabitacion = $this->params()->fromQuery('idnumerohabitacion');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
        ];
        $this->objNumeroHabitacionTable->actualizarDatosNumeroHabitacion($data, $idnumerohabitacion);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarNumeroHabitacionAction(){
        $idnumerohabitacion = $this->params()->fromQuery('idnumerohabitacion');
        $dataNumeroHabitacion = $this->objNumeroHabitacionTable->obtenerDatoNumeroHabitacion(['idnumerohabitacion'=> $idnumerohabitacion]);
        return $this->consoleZF($dataNumeroHabitacion);
    }
    
    public function confirmarEliminarNumeroHabitacionAction(){
        $idnumerohabitacion = $this->params()->fromQuery('idnumerohabitacion');
        $this->objNumeroHabitacionTable->eliminarNumeroHabitacion($idnumerohabitacion);
        return $this->jsonZF(['result'=>'success']);
    }

    public function rangoPreciosAction(){
    
    }
    
    public function listarRangoPreciosAction(){
        $dataRangoPrecios = $this->objRangoPreciosTable->obtenerRangoPrecios();
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataRangoPrecios as $item){
            $data_out['data'][] = [
                $item['precio_min'],
                $item['precio_max'],
                '<div class="clas btn btn-sm btn-info pop-up" href="/mantenimiento/editar-rango-precios?idrangoprecios='.$item['idrangoprecios'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="clas btn btn-sm btn-danger pop-up" href="/mantenimiento/eliminar-rango-precios?idrango-precios='.$item['idrangoprecios'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div>'
            ];
        }
        return $this->jsonZF($data_out);
    }
    
    public function agregarRangoPreciosAction(){
        $data=[];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarRangoPreciosAction(){
        $datosFormulario = $this->params()->fromPost();
        $precio_min = (double)$datosFormulario['precio_min'];
        $precio_max = (double)$datosFormulario['precio_max'];
        if(!$this->objTools->validarNumeroRangoPrecio($precio_min, $precio_max)){
            return $this->jsonZF(['result'=>'rango_precio_invalido']);
        }
        $data = [
            'precio_min'=>$precio_min,
            'precio_max'=>$precio_max
        ];
        $this->objRangoPreciosTable->agregarRangoPrecios($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarRangoPreciosAction(){
        $idrangoprecios = $this->params()->fromQuery('idrangoprecios');
        $dataRangoPrecios = $this->objRangoPreciosTable->obtenerDatoRangoPrecios(['idrangoprecios'=> $idrangoprecios]);
        return $this->consoleZF($dataRangoPrecios);
    }
    
    public function guardarEditarRangoPreciosAction(){
        $idrangoprecios = $this->params()->fromQuery('idrangoprecios');
        $datosFormulario = $this->params()->fromPost();
        $precio_min = (double)$datosFormulario['precio_min'];
        $precio_max = (double)$datosFormulario['precio_max'];
        if(!$this->objTools->validarNumeroRangoPrecio($precio_min, $precio_max)){
            return $this->jsonZF(['result'=>'rango_precio_invalido']);
        }
        $data = [
            'precio_min'=>$precio_min,
            'precio_max'=>$precio_max
        ];
        $this->objRangoPreciosTable->actualizarDatosRangoPrecios($data, $idrangoprecios);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarRangoPreciosAction(){
        $idrangoprecios = $this->params()->fromQuery('idrangoprecios');
        $dataRangoPrecios = $this->objRangoPreciosTable->obtenerDatoRangoPrecios(['idrangoprecios'=> $idrangoprecios]);
        return $this->consoleZF($dataRangoPrecios);
    }
    
    public function confirmarEliminarRangoPreciosAction(){
        $idrangoprecios = $this->params()->fromQuery('idrangoprecios');
        $this->objRangoPreciosTable->eliminarRangoPrecios($idrangoprecios);
        return $this->jsonZF(['result'=>'success']);
    }

    public function etapaAction(){
    
    }
    
    public function listarEtapaAction(){
        $dataEtapa = $this->objEtapaTable->obtenerEtapa();
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataEtapa as $item){
            $data_out['data'][] = [
                $item['nombre'],
                '<div class="clas btn btn-sm btn-info pop-up" href="/mantenimiento/editar-etapa?idetapa='.$item['idetapa'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="clas btn btn-sm btn-danger pop-up" href="/mantenimiento/eliminar-etapa?idetapa='.$item['idetapa'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div>'
            ];
        }
        return $this->jsonZF($data_out);
    }
    
    public function agregarEtapaAction(){
        $data=[];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarEtapaAction(){
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
        ];
        $this->objEtapaTable->agregarEtapa($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarEtapaAction(){
        $idetapa = $this->params()->fromQuery('idetapa');
        $dataEtapa = $this->objEtapaTable->obtenerDatoEtapa(['idetapa'=> $idetapa]);
        return $this->consoleZF($dataEtapa);
    }
    
    public function guardarEditarEtapaAction(){
        $idetapa = $this->params()->fromQuery('idetapa');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
        ];
        $this->objEtapaTable->actualizarDatosEtapa($data, $idetapa);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarEtapaAction(){
        $idetapa = $this->params()->fromQuery('idetapa');
        $dataEtapa = $this->objEtapaTable->obtenerDatoEtapa(['idetapa'=> $idetapa]);
        return $this->consoleZF($dataEtapa);
    }
    
    public function confirmarEliminarEtapaAction(){
        $idetapa = $this->params()->fromQuery('idetapa');
        $this->objEtapaTable->eliminarEtapa($idetapa);
        return $this->jsonZF(['result'=>'success']);
    }

}