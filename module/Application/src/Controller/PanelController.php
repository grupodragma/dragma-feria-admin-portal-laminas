<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PanelController extends AbstractActionController {

    protected $serviceManager;
    protected $accesoTable;
    protected $usuarioTable;
    protected $uploaderService;
    protected $salt = '::::::(`_´)::::: NCL/SECURE';
    protected $dir_avatar;
    protected $objSectoresGaleriaTable;
    protected $objStandGaleriaTable;
    protected $objZonasTable;
    protected $objEmpresasTable;
    protected $objFeriasTable;
    protected $objUsuarioEventosTable;
    protected $objVisitantesTable;

    public function __construct($serviceManager, $accesoTable, $usuarioTable, $uploaderService, $objSectoresGaleriaTable, $objStandGaleriaTable, $objZonasTable, $objEmpresasTable, $objFeriasTable, $objUsuarioEventosTable, $objVisitantesTable) {
        $this->serviceManager = $serviceManager;
        $this->accesoTable = $accesoTable;
        $this->usuarioTable = $usuarioTable;
        $this->uploaderService = $uploaderService;
        $this->dir_avatar = '/img/avatars/';
        $this->objSectoresGaleriaTable = $objSectoresGaleriaTable;
        $this->objStandGaleriaTable = $objStandGaleriaTable;
        $this->objZonasTable = $objZonasTable;
        $this->objEmpresasTable = $objEmpresasTable;
        $this->sessionContainer = $this->serviceManager->get('DatosSession')->datosUsuario;
        $this->objFeriasTable = $objFeriasTable;
        $this->objUsuarioEventosTable = $objUsuarioEventosTable;
        $this->objVisitantesTable = $objVisitantesTable;
    }
    
    public function indexAction() {
        $sessionContainer = $this->serviceManager->get('DatosSession')->datosUsuario;
        $dataDashBoard = $this->accesoTable->obtenerDashboardUsuario($sessionContainer);
        return new ViewModel([
            'dashboard' => $dataDashBoard,
            'ferias'=> $this->objFeriasTable->obtenerDatosFerias([])
        ]);
    }

    public function editarPerfilAction() {
        $sessionContainer = $this->serviceManager->get('DatosSession')->datosUsuario;
        $dataUsuario = $this->usuarioTable->obternerDatosUsuarios($sessionContainer['idusuario']);
        if(file_exists(getcwd().'/public'.$this->dir_avatar.$sessionContainer['token'].'.jpg'))$img_user_perfil = $this->dir_avatar.$sessionContainer['token'].'.jpg';
        else $img_user_perfil = $this->dir_avatar.'male.png';
        $view = new ViewModel([
            'dataUsuario' => $dataUsuario,
            'img_user_perfil' => $img_user_perfil
        ]);
        $view->setTerminal(true);
        return $view;
    }

    public function guardarEditarPerfilAction() {
        $sessionContainer = $this->serviceManager->get('DatosSession');
        $params = $this->params()->fromPost();
        $files = $this->params()->fromFiles('img_perfil');
        $data = [
            'nombres'=>$params['nombres'],
            'apellido_paterno'=>$params['apellido_paterno'],
            'apellido_materno'=>$params['apellido_materno'],
            'telefono'=>$params['telefono'],
        ];
        $sessionContainer->datosUsuario['nombres'] = $params['nombres'];
        $sessionContainer->datosUsuario['apellido_paterno'] = $params['apellido_paterno'];
        $sessionContainer->datosUsuario['apellido_materno'] = $params['apellido_materno'];
        if(!empty($params['contrasena'])){
            if($params['contrasena'] == $params['repita_contrasena']){
                $data['contrasena'] = md5($this->salt.$params['contrasena']);
            }else{
                return new JsonModel(['result'=>'error','msj'=>'Las contraseñas no coinciden. Intente Nuevamente.','action'=>'no_close_modal']);
            }
        }
        if(!empty($files['name'])){
            if(file_exists(getcwd().'/public'.$this->dir_avatar.$sessionContainer->datosUsuario['token'].'.jpg')){
                unlink(getcwd().'/public'.$this->dir_avatar.$sessionContainer->datosUsuario['token'].'.jpg');
            }
            $token = md5(uniqid());
            $this->uploaderService->tojpg = true;
            $this->uploaderService->upload($files['tmp_name'],getcwd().'/public'.$this->dir_avatar.$token.'.jpg',['maxwidth'=>50,'maxheight'=>50]);
            $data['token'] = $token;
            $sessionContainer->datosUsuario['token'] = $token;
        }
        $dataUsuario = $this->usuarioTable->actualizarDatosUsuarios($data,['idusuario'=>$sessionContainer->datosUsuario['idusuario']]);
        return new JsonModel(['result'=>'success','datausuario'=>$sessionContainer->datosUsuario]);
    }

    public function subirImagenGaleriaAction() {
        $params = $this->params()->fromPost();
        $archivo = $this->params()->fromFiles('file');
        $imagenExtensionesValidas = ['jpg','jpeg','png'];
        $carpetaArchivos = '';
        $dataArchivos = [];
        $data = [];

        switch($params['action']){
            case 'sectores':
                $carpetaArchivos = getcwd().'/public/sectores/galeria';
                $data['idsectores'] = $params['id'];
            break;
            case 'stand':
                $carpetaArchivos = getcwd().'/public/stand/galeria';
                $data['idstand'] = $params['id'];
            break;
        }

        $dataArchivos['id'] = md5(uniqid());
        if( $archivo['size'] !== 0 ) {
            $dataArchivos['extension'] = strtolower(pathinfo($archivo['name'])['extension']);
            if( in_array($dataArchivos['extension'], $imagenExtensionesValidas) ) {
                $dataArchivos['nombre_completo'] = $dataArchivos['id'].'.'.$dataArchivos['extension'];
                $dataArchivos['nombre_original'] = $archivo['name'];
                if(move_uploaded_file($archivo['tmp_name'], $carpetaArchivos.'/'.$dataArchivos['nombre_completo'])){
                    $data['hash_imagen'] = $dataArchivos['nombre_completo'];
                    $data['nombre_imagen'] = $dataArchivos['nombre_original'];
                    if($params['action'] == 'sectores'){
                        $this->objSectoresGaleriaTable->agregarSectoresGaleria($data);
                    } else if($params['action'] == 'stand'){
                        $this->objStandGaleriaTable->agregarStandGaleria($data);
                    }
                    return new JsonModel(['result'=>'success']);
                }
            } else {
                return new JsonModel(['result'=>'invalid-format']);
            }
        } else {
            return new JsonModel(['result'=>'error']);
        }
        
    }

    public function listarGaleriaAction() {
        $id = $this->params()->fromQuery('id');
        $action = $this->params()->fromQuery('action');
        $response = [];
        $response['action'] = $action;
        $data = [];
        switch($action) {
            case 'sectores':
                $data = $this->objSectoresGaleriaTable->obtenerDatosSectoresGaleria($id);
                $response['data'] = array_map(function($item) {
                    $item['idgaleria'] = $item['idsectoresgaleria'];
                    return $item;
                }, $data);
            break;
            case 'stand':
                $data = $this->objStandGaleriaTable->obtenerDatosStandGaleria($id);
                $response['data'] = array_map(function($item) {
                    $item['idgaleria'] = $item['idstandgaleria'];
                    return $item;
                }, $data);
            break;
        }
        return new JsonModel($response);
    }

    public function eliminarGaleriaAction() {
        $id = $this->params()->fromQuery('id');
        $action = $this->params()->fromQuery('action');
        $directorio = '';
        $dataGaleria = [];
        switch($action) {
            case 'sectores':
                $dataGaleria = $this->objSectoresGaleriaTable->obtenerDatoSectoresGaleria(['idsectoresgaleria '=> $id]);
                $directorio = getcwd().'/public/sectores/galeria';
                $this->objSectoresGaleriaTable->eliminarSectoresGaleria($id);
            break;
            case 'stand':
                $dataGaleria = $this->objStandGaleriaTable->obtenerDatoStandGaleria(['idstandgaleria '=> $id]);
                $directorio = getcwd().'/public/stand/galeria';
                $this->objStandGaleriaTable->eliminarStandGaleria($id);
            break;
        }
        if( $dataGaleria ) {
            if(file_exists($directorio.'/'.$dataGaleria['hash_imagen'])){
                @unlink($directorio.'/'.$dataGaleria['hash_imagen']);
            }
        }
        return new JsonModel(['result'=>'success']);
    }
    
    public function seleccionarGaleriaAction() {
        $id = $this->params()->fromQuery('id');
        $action = $this->params()->fromQuery('action');
        switch($action) {
            case 'sectores':
                $this->objSectoresGaleriaTable->imagenPrimarioSectoresGaleria($id);
            break;
            case 'stand':
                $this->objStandGaleriaTable->imagenPrimarioStandGaleria($id);
            break;
        }
        return new JsonModel(['result'=>'success']);
    }

    public function dataGraficosPieAction(){
        $accion = $this->params()->fromPost('accion');
        $idferias = $this->params()->fromPost('idferias');
        $rango_fechas = $this->params()->fromPost('rango_fechas');
        $idferiasSelected = (isset($idferias)) ? $idferias : $this->sessionContainer['idferias'];

        $data = [];
        $data['datasets'] = [];
        $data['datasets'][0]['data'] = [];
        $data['datasets'][0]['backgroundColor'] = [];
        $data['datasets'][0]['label'] = 'PIE';
        $data['labels'] = [];

        $dataRsp = [];

        switch($accion){
            case 'zonas':
                $dataRsp = ( $idferiasSelected != '' ) ? $this->objZonasTable->graficoTotalClickZonas($idferiasSelected, $rango_fechas) : [];
                $data['accion'] = $accion;
            break;
            case 'empresas':
                $dataRsp = ( $idferiasSelected != '' ) ? $this->objEmpresasTable->graficoTotalClickEmpresas($idferiasSelected, $rango_fechas) : [];
                $data['accion'] = $accion;
            break;
        }
        
        if(!empty($dataRsp)){
            foreach($dataRsp as $item){
                $data['datasets'][0]['data'][] = $item['total_click'];
                $data['datasets'][0]['backgroundColor'][] = $this->random_color();
                $data['labels'][] = $item['label'];
            }
        }

        return new JsonModel(['result'=>'success', 'data'=> $data]);
    }
    
    public function listarEmpresaEventosAction(){
        $idferias = $this->params()->fromQuery('idferias');
        $rango_fechas = $this->params()->fromQuery('rango_fechas');
        $idferiasSelected = (isset($idferias)) ? $idferias : $this->sessionContainer['idferias'];
        $dataEventos = ( $idferiasSelected != '' ) ? $this->objUsuarioEventosTable->listarEmpresasAgrupados($idferiasSelected, $rango_fechas) : [];
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataEventos as $item){
            $data_out['data'][] = [
                $item['empresa'],
                $item['total_nro_sesiones'],
                '<div class="clas btn btn-sm btn-primary pop-up-3" href="/panel/empresa-eventos?idempresas='.$item['idempresas'].'&idferias='.$idferiasSelected.'"><i class="fas fa-check"></i> <span class="hidden-xs">Ver Eventos</span></div> '
            ];
        }
        return new JsonModel($data_out);
    }

    public function empresaEventosAction(){
        $idempresas = $this->params()->fromQuery('idempresas');
        $idferias = $this->params()->fromQuery('idferias');
        $dataEmpresa = $this->objEmpresasTable->obtenerDatoEmpresas(['idempresas'=> $idempresas]);
        return $this->consoleZF([
            'idempresas'=> $idempresas,
            'idferias'=> $idferias,
            'empresa'=> $dataEmpresa
        ]);
    }

    public function listarEmpresaEventosGeneralAction(){
        $idempresas = $this->params()->fromQuery('idempresas');
        $idferias = $this->params()->fromQuery('idferias');
        $rango_fechas = $this->params()->fromQuery('rango_fechas');
        $dataEventos = $this->objUsuarioEventosTable->obtenerDataEventosEmpresas($idferias, $idempresas, $rango_fechas);
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataEventos as $item){
            $iconCheckSuccess = '<i class="fas fa-check icon-success"></i>';
            $video = ($item['video'] == 1) ? $iconCheckSuccess : '';
            $whatsapp = ($item['whatsapp'] == 1) ? $iconCheckSuccess : '';
            $rv = ($item['rv'] == 1) ? $iconCheckSuccess : '';
            $banner_izquierda_1 = ($item['banner_izquierda_1'] == 1) ? $iconCheckSuccess : '';
            $banner_izquierda_2 = ($item['banner_izquierda_2'] == 1) ? $iconCheckSuccess : '';
            $banner_derecha_1 = ($item['banner_derecha_1'] == 1) ? $iconCheckSuccess : '';
            $banner_derecha_2 = ($item['banner_derecha_2'] == 1) ? $iconCheckSuccess : '';
            $productos = ($item['productos'] == 1) ? $iconCheckSuccess : '';
            $promociones = ($item['promociones'] == 1) ? $iconCheckSuccess : '';
            $vivo = ($item['vivo'] == 1) ? $iconCheckSuccess : '';
            $data_out['data'][] = [
                $item['visitante'],
                $item['total_visitas'],
                $item['numero_documento'],
                $item['correo'],
                $item['telefono'],
                $video,
                $whatsapp,
                $productos,
                $promociones,
                $banner_izquierda_1,
                $banner_izquierda_2,
                $banner_derecha_1,
                $banner_derecha_2,
                $rv,
                $vivo
            ];
        }
        return new JsonModel($data_out);
    }

    public function descargarReporteEventosAction(){
        $accion = $this->params()->fromPost('accion');
        $idempresas = $this->params()->fromPost('idempresas');
        $idferias = $this->params()->fromPost('idferias');
        $rango_fechas = $this->params()->fromPost('rango_fechas');
        $idferiasSelected = (isset($idferias)) ? $idferias : $this->sessionContainer['idferias'];
        $dataEventosEmpresas = ( $idferiasSelected != '' ) ? $this->objUsuarioEventosTable->obtenerDataEventosEmpresas($idferiasSelected, $idempresas, $rango_fechas) : [];

        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
        
            $sheet->setCellValue('A1', 'Zona');
            $sheet->setCellValue('B1', 'Empresa');
            $sheet->setCellValue('C1', 'Visitante');
            $sheet->setCellValue('D1', 'Visitas');
            $sheet->setCellValue('E1', 'DNI');
            $sheet->setCellValue('F1', 'Correo');
            $sheet->setCellValue('G1', 'Celular');
            $sheet->setCellValue('H1', 'Video');
            $sheet->setCellValue('I1', 'Whatsapp');
            $sheet->setCellValue('J1', 'Productos');
            $sheet->setCellValue('K1', 'Promociones');
            $sheet->setCellValue('L1', 'Banner 1');
            $sheet->setCellValue('M1', 'Banner 2');
            $sheet->setCellValue('N1', 'Banner 3');
            $sheet->setCellValue('O1', 'Banner 4');
            $sheet->setCellValue('P1', 'RV');
            $sheet->setCellValue('Q1', 'Vivo');
            $sheet->setCellValue('R1', 'Reserva Cita');
            $sheet->setCellValue('S1', 'Banner Llamada');
            $sheet->setCellValue('T1', 'Banner Whatsapp');
        
            $sheet->getStyle("A1:T1")->getFont()->setBold( true );
        
            $i = 2;
        
            foreach( $dataEventosEmpresas as $item ) {        
                $video = ($item['video'] == 1) ? 'SI' : 'NO';
                $whatsapp = ($item['whatsapp'] == 1) ? 'SI' : 'NO';
                $rv = ($item['rv'] == 1) ? 'SI' : 'NO';
                $banner_izquierda_1 = ($item['banner_izquierda_1'] == 1) ? 'SI' : 'NO';
                $banner_izquierda_2 = ($item['banner_izquierda_2'] == 1) ? 'SI' : 'NO';
                $banner_derecha_1 = ($item['banner_derecha_1'] == 1) ? 'SI' : 'NO';
                $banner_derecha_2 = ($item['banner_derecha_2'] == 1) ? 'SI' : 'NO';
                $productos = ($item['productos'] == 1) ? 'SI' : 'NO';
                $promociones = ($item['promociones'] == 1) ? 'SI' : 'NO';
                $vivo = ($item['vivo'] == 1) ? 'SI' : 'NO';
                $reserva_cita = ($item['reserva_cita'] == 1) ? 'SI' : 'NO';
                $bf_llamada = ($item['bf_llamada'] == 1) ? 'SI' : 'NO';
                $bf_wsp = ($item['bf_wsp'] == 1) ? 'SI' : 'NO';

                $sheet->setCellValue('A'.$i, $item['zona']);
                $sheet->setCellValue('B'.$i, $item['empresa']);
                $sheet->setCellValue('C'.$i, $item['visitante']);
                $sheet->setCellValue('D'.$i, $item['total_visitas']);
                $sheet->setCellValue('E'.$i, $item['numero_documento']);
                $sheet->setCellValue('F'.$i, $item['correo']);
                $sheet->setCellValue('G'.$i, $item['telefono']);
                $sheet->setCellValue('H'.$i, $video);
                $sheet->setCellValue('I'.$i, $whatsapp);
                $sheet->setCellValue('J'.$i, $productos);
                $sheet->setCellValue('K'.$i, $promociones);
                $sheet->setCellValue('L'.$i, $banner_izquierda_1);
                $sheet->setCellValue('M'.$i, $banner_izquierda_2);
                $sheet->setCellValue('N'.$i, $banner_derecha_1);
                $sheet->setCellValue('O'.$i, $banner_derecha_2);
                $sheet->setCellValue('P'.$i, $rv);
                $sheet->setCellValue('Q'.$i, $vivo);
                $sheet->setCellValue('R'.$i, $reserva_cita);
                $sheet->setCellValue('S'.$i, $bf_llamada);
                $sheet->setCellValue('T'.$i, $bf_wsp);
        
                $i++;
        
            }
        
            foreach(range('A','T') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
            
            $file = getcwd().'/public/tmp/reporte.xlsx';
            $sheet = new Xlsx($spreadsheet);
            $sheet->save($file);
            
        } catch (PDOException $e) {
            return new JsonModel(['result'=> 'error', 'data'=> $e->getMessage()]);
        }

        return new JsonModel(['result'=> 'success']);
    }

    private function random_color_part() {
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }
    
    private function random_color() {
        return '#'. $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
    }

    public function totalReporteEncabezadoAction(){
        $accion = $this->params()->fromPost('accion');
        $idferias = $this->params()->fromPost('idferias');
        $rango_fechas = $this->params()->fromPost('rango_fechas');
        $idferiasSelected = (isset($idferias)) ? $idferias : $this->sessionContainer['idferias'];
        $total = 0;
        switch($accion) {
            case 'UE';
            case 'SR';
            case 'PV';
                //
            break;
            case 'TODOS';
                $total = [
                    'UE'=> ($idferiasSelected != '') ? $this->objVisitantesTable->totalUsuariosEfectivos($idferiasSelected,$rango_fechas) : 0,
                    'SR'=> ($idferiasSelected != '') ? $this->objUsuarioEventosTable->totalSesionesRealizadas($idferiasSelected,$rango_fechas) : 0,
                    'PV'=> ($idferiasSelected != '') ? $this->objUsuarioEventosTable->totalPaginasVisitadas($idferiasSelected,$rango_fechas) : 0,
                ];
            break;
        }
        return new JsonModel(['result'=> 'success', 'accion'=> $accion, 'data_total'=> $total]);
    }

    private function consoleZF($data){
        $viewModel = new ViewModel($data);
        $viewModel->setTerminal(true);
        return $viewModel;
    }

}
