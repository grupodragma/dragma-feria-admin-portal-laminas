<?php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ClienteController extends AbstractActionController {

    protected $serviceManager;
    protected $objVisitantesTable;
    protected $sessionContainer;
    protected $objFeriasTable;
    protected $objClientesTable;
    protected $imagenExtensionesValidas;
    protected $objPlanesTable;
    protected $objZonasTable;
    protected $objCronogramasTable;
    protected $objExpositoresTable;
    protected $objEmpresasTable;
    protected $objStandTable;
    protected $objPaginasFeriasTable;
    protected $objPaginasZonasTable;
    protected $objPaginasStandTable;
    protected $objPaginasTable;
    protected $objConferenciasTable;
    protected $objExpositoresConferenciasTable;
    protected $objPlanesPaginasTable;
    protected $objSectoresTable;
    protected $objExpositoresProductosTable;
    protected $objStandGaleriaTable;
    protected $objExpositoresTarjetasTable;
    protected $objBancosTable;
    protected $objTools;
    protected $objMenusTable;
    protected $objChatsTable;
    protected $objSeoTable;
    protected $objFeriasPasosTable;
    protected $objPortalTable;
    protected $objBannerTable;
    protected $objProductosTable;
    protected $objSegmentosTable;
    protected $objDistritosTable;
    protected $objPosicionBannerTable;
    protected $objPortalBannerTable;
    protected $objTipoHabitacionTable;
    protected $objNumeroHabitacionTable;
    protected $objRangoPreciosTable;
    protected $objEtapaTable;
    protected $objPortalCorreosTable;
    
    public function __construct($serviceManager, $objVisitantesTable, $objFeriasTable, $objClientesTable, $objPlanesTable, $objZonasTable, $objCronogramasTable, $objExpositoresTable, $objEmpresasTable, $objStandTable, $objPaginasFeriasTable,$objPaginasZonasTable,$objPaginasStandTable, $objPaginasTable, $objConferenciasTable, $objExpositoresConferenciasTable, $objPlanesPaginasTable, $objSectoresTable, $objExpositoresProductosTable, $objStandGaleriaTable, $objExpositoresTarjetasTable, $objBancosTable, $objTools, $objMenusTable, $objChatsTable, $objSeoTable, $objFeriasPasosTable, $objPortalTable, $objBannerTable, $objProductosTable, $objSegmentosTable, $objDistritosTable, $objPosicionBannerTable, $objPortalBannerTable, $objTipoHabitacionTable, $objNumeroHabitacionTable, $objRangoPreciosTable, $objEtapaTable, $objPortalCorreosTable) {
        $this->serviceManager = $serviceManager;
        $this->objVisitantesTable = $objVisitantesTable;
        $this->sessionContainer = $this->serviceManager->get('DatosSession')->datosUsuario;
        $this->objFeriasTable = $objFeriasTable;
        $this->objClientesTable = $objClientesTable;
        $this->imagenExtensionesValidas = ['jpg','jpeg','png','gif','svg'];
        $this->objPlanesTable = $objPlanesTable;
        $this->objZonasTable = $objZonasTable;
        $this->objCronogramasTable = $objCronogramasTable;
        $this->objExpositoresTable = $objExpositoresTable;
        $this->objEmpresasTable = $objEmpresasTable;
        $this->objStandTable = $objStandTable;
        $this->objPaginasFeriasTable = $objPaginasFeriasTable;
        $this->objPaginasZonasTable = $objPaginasZonasTable;
        $this->objPaginasStandTable = $objPaginasStandTable;
        $this->objPaginasTable = $objPaginasTable;
        $this->objConferenciasTable = $objConferenciasTable;
        $this->objExpositoresConferenciasTable = $objExpositoresConferenciasTable;
        $this->objPlanesPaginasTable = $objPlanesPaginasTable;
        $this->objSectoresTable = $objSectoresTable;
        $this->objExpositoresProductosTable = $objExpositoresProductosTable;
        $this->objStandGaleriaTable = $objStandGaleriaTable;
        $this->objExpositoresTarjetasTable = $objExpositoresTarjetasTable;
        $this->objBancosTable = $objBancosTable;
        $this->objTools = $objTools;
        $this->objMenusTable = $objMenusTable;
        $this->objChatsTable = $objChatsTable;
        $this->objSeoTable = $objSeoTable;
        $this->objFeriasPasosTable = $objFeriasPasosTable;
        $this->objPortalTable = $objPortalTable;
        $this->objBannerTable = $objBannerTable;
        $this->categoriasBanner = ['PROYECTO','BANCO','INMOBILIARIA'];
        $this->objProductosTable = $objProductosTable;
        $this->objSegmentosTable = $objSegmentosTable;
        $this->objDistritosTable = $objDistritosTable;
        $this->objPosicionBannerTable = $objPosicionBannerTable;
        $this->objPortalBannerTable = $objPortalBannerTable;
        $this->objTipoHabitacionTable = $objTipoHabitacionTable;
        $this->objNumeroHabitacionTable = $objNumeroHabitacionTable;
        $this->objRangoPreciosTable = $objRangoPreciosTable;
        $this->objEtapaTable = $objEtapaTable;
        $this->objPortalCorreosTable = $objPortalCorreosTable;
    }

    public function visitantesAction(){
        //code
    }
    
    public function listarVisitantesAction(){
        ini_set("memory_limit","-1");
        $start = $this->params()->fromPost('start');
        $length = $this->params()->fromPost('length');
        $draw = $this->params()->fromPost('draw');
        $search = $this->params()->fromPost('search')['value'];
        $order = $this->params()->fromPost('order');
        $fecha = $this->params()->fromQuery('fecha');
        $dataTotalRecords = $this->objVisitantesTable->obtenerFiltroDatosVisitantes($start, $length, $search, $this->sessionContainer['idferias'], $this->sessionContainer['idperfil'], true);
        $total_records = count($dataTotalRecords);
        if(!empty($search)){
           $dataBaseUsuario = $this->objVisitantesTable->obtenerFiltroDatosVisitantes($start, $length, $search, $this->sessionContainer['idferias'], $this->sessionContainer['idperfil']);
           $total_filtered = count($dataBaseUsuario);
        }else{
           $dataBaseUsuario = $this->objVisitantesTable->obtenerFiltroDatosVisitantes($start, $length, $search, $this->sessionContainer['idferias'], $this->sessionContainer['idperfil']);
           $total_filtered = $total_records;
        }
        $data_out=["data"=>[]];
        $data_out['draw'] = $draw;
        $data_out['recordsTotal'] = $total_records;
        $data_out['recordsFiltered'] = $total_filtered;
        if(!empty($dataBaseUsuario)){
            foreach($dataBaseUsuario as $item){
                $data_out['data'][] = [
                    $item['nombres'],
                    $item['apellido_paterno'],
                    $item['apellido_materno'],
                    $item['correo'],
                    $item['telefono'],
                    $item['numero_documento'],
                    '<div class="btn btn-sm btn-info pop-up" href="/cliente/editar-visitantes?idvisitantes='.$item['idvisitantes'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="btn btn-sm btn-danger pop-up" href="/cliente/eliminar-visitantes?idvisitantes='.$item['idvisitantes'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div>'
                ];
            }
        }
        return $this->jsonZF($data_out);
    }
    
    public function agregarVisitantesAction(){
        $data=[
            'ferias'=> $this->objFeriasTable->obtenerFerias($this->sessionContainer['idferias'], $this->sessionContainer['idperfil'])
        ];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarVisitantesAction(){
        $datosFormulario = $this->params()->fromPost();
        $idferiasSelected = (isset($datosFormulario['idferias'])) ? $datosFormulario['idferias'] : $this->sessionContainer['idferias'];
        $data = [
            'nombres'=>$datosFormulario['nombres'],
            'apellido_paterno'=>$datosFormulario['apellido_paterno'],
            'apellido_materno'=>$datosFormulario['apellido_materno'],
            'correo'=>$datosFormulario['correo'],
            'telefono'=>$datosFormulario['telefono'],
            'tipo_documento'=>@$datosFormulario['tipo_documento'],
            'numero_documento'=>$datosFormulario['numero_documento'],
            'idferias'=>$idferiasSelected,
            'idusuario'=> $this->sessionContainer['idusuario'],
            'tipo_visitante'=> '',
            'fecha_creacion'=> date('Y-m-d H:i:s'),
            'contrasena'=> md5($datosFormulario['numero_documento']),
        ];
        $this->objVisitantesTable->agregarVisitantes($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarVisitantesAction(){
        $idvisitantes = $this->params()->fromQuery('idvisitantes');
        $dataVisitantes = $this->objVisitantesTable->obtenerDatoVisitantes(['idvisitantes'=> $idvisitantes]);
        $dataVisitantes['ferias'] = $this->objFeriasTable->obtenerFerias($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']);
        return $this->consoleZF($dataVisitantes);
    }
    
    public function guardarEditarVisitantesAction(){
        $idvisitantes = $this->params()->fromQuery('idvisitantes');
        $datosFormulario = $this->params()->fromPost();
        $idferiasSelected = (isset($datosFormulario['idferias'])) ? $datosFormulario['idferias'] : $this->sessionContainer['idferias'];
        $data = [
            'nombres'=>$datosFormulario['nombres'],
            'apellido_paterno'=>$datosFormulario['apellido_paterno'],
            'apellido_materno'=>$datosFormulario['apellido_materno'],
            'correo'=>$datosFormulario['correo'],
            'telefono'=>$datosFormulario['telefono'],
            'tipo_documento'=>@$datosFormulario['tipo_documento'],
            'numero_documento'=>$datosFormulario['numero_documento'],
            'idferias'=>$idferiasSelected,
            'idusuario'=> $this->sessionContainer['idusuario'],
            'tipo_visitante'=> '',
            'fecha_actualizacion'=> date('Y-m-d H:i:s'),
            'contrasena'=> md5($datosFormulario['numero_documento']),
        ];
        $this->objVisitantesTable->actualizarDatosVisitantes($data, $idvisitantes);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarVisitantesAction(){
        $idvisitantes = $this->params()->fromQuery('idvisitantes');
        $dataVisitantes = $this->objVisitantesTable->obtenerDatoVisitantes(['idvisitantes'=> $idvisitantes]);
        return $this->consoleZF($dataVisitantes);
    }
    
    public function confirmarEliminarVisitantesAction(){
        $idvisitantes = $this->params()->fromQuery('idvisitantes');
        $this->objVisitantesTable->eliminarVisitantes($idvisitantes);
        return $this->jsonZF(['result'=>'success']);
    }

    public function validarNumeroDocumentoAction(){
        $numero_documento = $this->params()->fromQuery('numero_documento');
        $key = $this->params()->fromQuery('key');
        $result = $this->objVisitantesTable->obtenerDatoVisitantes(['numero_documento'=> $numero_documento]);
        if($result == false || $numero_documento == $key) echo '"true"';
        else echo '"El número de documento ya se encuentra en uso"';
        die;
    }

    public function cargaMasivaAction(){
        $datosFormulario = $this->params()->fromPost();
        $idferiasSelected = (isset($datosFormulario['idferias'])) ? $datosFormulario['idferias'] : $this->sessionContainer['idferias'];
        $fileCsv = getcwd()."/public/tmp/carga_masiva_visitantes.csv";
        if(!file_exists($fileCsv)){
            die('Archivo no encontrado');
        }
        $handle = fopen($fileCsv, "r") or die("No se ha podido abrir el archivo.");
        $i=0;
        if ($handle) {
            while (!feof($handle)) {
                $i++;
                $buffer = fgets($handle, 4096);
                if($i==1)continue;
                $campos = explode(";",$buffer);
                if(count($campos) != 6)die('El formato es incorrecto');
                if($campos[0] == '' || $campos[3] == '')continue;
                $data = [
                    'nombres'=> $this->reemplazarCaracteresRaros($campos[0]),
                    'apellido_paterno'=> $this->reemplazarCaracteresRaros($campos[1]),
                    'apellido_materno'=> $this->reemplazarCaracteresRaros($campos[2]),
                    'numero_documento'=> $campos[3],
                    'telefono'=> trim(str_replace(" ", "", $campos[4])),
                    'correo'=> trim(str_replace(" ", "", $campos[5])),
                    'tipo_documento'=> '',
                    'idferias'=>$idferiasSelected,
                    'idusuario'=> $this->sessionContainer['idusuario'],
                    'tipo_visitante'=> '',
                    'fecha_creacion'=> date('Y-m-d H:i:s'),
                    'contrasena'=> md5($campos[3])
                ];
                print_r($data);
            }
            fclose($handle);
        }
        die;
    }

    public function importarVisitantesAction(){
        $data = [
            'ferias'=> $this->objFeriasTable->obtenerFerias($this->sessionContainer['idferias'], $this->sessionContainer['idperfil'])
        ];
        return $this->consoleZF($data);
    }

    public function guardarImportarVisitantesAction(){
        set_time_limit(300);
        $datosFormulario = $this->params()->fromPost();
        $archivoVisitante = $this->params()->fromFiles('archivo');
        $idferiasSelected = (isset($datosFormulario['idferias'])) ? $datosFormulario['idferias'] : $this->sessionContainer['idferias'];
        $carpetaTemporal = getcwd().'/public/tmp';
        $archivo = [];

        if( $archivoVisitante['size'] !== 0 ) {

            $extensionArchivo = strtolower(pathinfo($archivoVisitante['name'])['extension']);

            if( $extensionArchivo === 'csv' ) {

                $archivo['extension'] = $extensionArchivo;
                $archivo['nombre'] = $this->sessionContainer['idusuario'].'.'.$archivo['extension'];

                if( move_uploaded_file( $archivoVisitante['tmp_name'], $carpetaTemporal.'/'.$archivo['nombre'] ) ) {

                    if( file_exists($carpetaTemporal.'/'.$archivo['nombre']) ) {

                        $dataVisitantesExistentes = [];
                        $handle = fopen($carpetaTemporal.'/'.$archivo['nombre'], "r") or die("No se ha podido abrir el archivo.");
                        $i=0;
                        if ($handle) {
                            while (!feof($handle)) {
                                $i++;
                                $buffer = fgets($handle, 4096);
                                if($i==1)continue;
                                $campos = explode(";",$buffer);
                                if(count($campos) != 6)continue;
                                $nombres = $campos[0];
                                $apellido_paterno = $campos[1];
                                $apellido_materno = $campos[2];
                                $numero_documento = $campos[3];
                                $telefono = $campos[4];
                                $correo = $campos[5];
                                if($nombres == '' || $numero_documento == '')continue;
                                $data = [
                                    'nombres'=> html_entity_decode($nombres, ENT_QUOTES, "UTF-8"),
                                    'apellido_paterno'=> html_entity_decode($apellido_paterno, ENT_QUOTES, "UTF-8"),
                                    'apellido_materno'=> html_entity_decode($apellido_materno, ENT_QUOTES, "UTF-8"),
                                    'numero_documento'=> $numero_documento,
                                    'telefono'=> trim(str_replace(" ", "", $telefono)),
                                    'correo'=> trim(str_replace(" ", "", $correo)),
                                    'tipo_documento'=> '',
                                    'idferias'=>$idferiasSelected,
                                    'idusuario'=> $this->sessionContainer['idusuario'],
                                    'tipo_visitante'=> '',
                                    'fecha_creacion'=> date('Y-m-d H:i:s'),
                                    'contrasena'=> md5($numero_documento),
                                ];
                                //print_r($data);
                                $dataBaseUsuario = $this->objVisitantesTable->obtenerDatoVisitantes(['numero_documento'=> $numero_documento]);

                                if( !$dataBaseUsuario ) {
                                    $this->objVisitantesTable->agregarVisitantes($data);
                                } else {
                                    $dataVisitantesExistentes[] = $data;
                                }

                            }
                            fclose($handle);
                        }

                        if( !empty($dataVisitantesExistentes) ) {
                            return $this->jsonZF(['result'=>'visitantes_existentes', 'data'=> $dataVisitantesExistentes, 'total_visitantes_existentes'=> count($dataVisitantesExistentes)]);
                        } else {
                            return $this->jsonZF(['result'=>'success']);
                        }

                    }

                }

            } else {

                return $this->jsonZF(['result'=>'extension_invalida']);

            }

        }

        return $this->jsonZF(['result'=>'error']);

    }

    private function reemplazarCaracteresRaros($string = null) {
        $buscar = ['Ή','Ί','Ό','Ύ','Ώ','ΐ','α','β','γ','ι','λ','κ','μ','ξ','π','ρ','σ','τ','φ','χ','ψ','ω','Ϊ','Ϋ','ά','έ','ή','ί','ΰ','α','β','γ','δ','ε','ζ','η','θ','ι','κ','λ','μ','ξ','π','ρ','ς','σ','τ','φ','χ','ψ','ω','ϊ','ϋ','ό','ύ','ώ','Ё','Ђ','Ѓ','Є','Ѕ','І','Ї','Ј','Љ','Њ','Ћ','Ќ','Ў','Џ','А','Б','В','Г','Д','Е','Ж','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','а','б','в','г','д','е','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я','ё','ђ','ѓ','є','ѕ','і','ј','љ','њ','ћ','ќ','ў','џ','Ґ','ґ','Ẁ','ẁ','Ẃ','ẃ','ẅ','Ẅ','Ỳ','ỳ','–','—','―','‗','†','†','…','‰','‹','›','‼','‾','⁄','ⁿ','₣','₤','₧','€','℅','ℓ','№','™','Ω','℮','¼','½','¾','⅛','⅜','⅝','⅞','∂','∆','∏','∑','−','∕','√','∞','∫','≈','≠','≤','≥','□','◊','▫','`','´','·','˚','˙','΅','▪','▫','•●','◦','‘','’','‛','“','”','′','„','˛','˝','ˆ','ˇ','ˉ','˘','˜','′','ﬁ','ﬂ','Ǽ','æ','Ά','à','í','ä','å','Ā','Ă','ă','Ą','ǽ','Ạ','ạ','Ả','ả','Ấ','ấ','Ầ','ầ','Ẩ','ẩ','Ẫ','ẫ','Ậ','ậ','Ắ','ắ','Ằ','ằ','Ẳ','ẳ','Ẵ','ẵ','Ặ','ặ','à','á','í','ä','å','ā','æ','ą','Ǻ','ǻ','Ǽ','ǽ','Ẹ','ẹ','Ẻ','ẻ','Ẽ','ẽ','Ế','ế','Ề','ề','Ể','ể','Ễ','ễ','Ệ','Έ','ệ','è','É','ê','ë','Ỉ','ỉ','Ị','ị','ì','î','ï','Ọ','ọ','Ỏ','ỏ','Ố','ố','Ồ','ồ','Ổ','ỗ','Ộ','ộ','Ớ','ớ','Ờ','ờ','Ở','ở','Ỡ','ỡ','Ợ','ợ','Ụ','ụ','Ủ','ủ','Ứ','ứ','Ừ','ừ','Ử','ử','Ữ','ữ','Ự','ự','Ỳ','ỳ','Ỵ','ỵ','Ỷ','ỷ','Ỹ','ỹ','ð','ò','Ó','ô','õ','ö','ø','ù','Ú','û','ü','ý','þ','ß','ç','è','é','ê','ë','ì','í','î','ï','ð','ñ','ò','ó','ô','õ','ö','÷','ø','ù','ú','û','ü','ý','þ','ÿ','ç','Ć','ć','Ĉ','ĉ','Ċ','ċ','Č','č','Ď','ď','Đ','đ','Ē','ē','Ĕ','ĕ','Ė','ė','Ę','ę','Ě','ě','Ĝ','ĝ','Ğ','ğ','Ġ','ġ','Ģ','ģ','Ĥ','ĥ','Ħ','ħ','Ĩ','ĩ','Ī','ī','Ĭ','ĭ','Į','į','İ','ı','Ĳ','ĳ','Ĵ','ĵ','Ķ','ķ','ĸ','Ĺ','ĺ','Ļ','ļ','Ľ','ľ','Ŀ','ŀ','Ł','ł','Ń','ń','Ņ','ņ','Ň','ň','ŉ','Ŋ','ŋ','Ō','ō','Ŏ','ŏ','Ő','ő','œ','œ','Ŕ','ŕ','Ŗ','ŗ','Ř','ř','Ś','ś','Ŝ','ŝ','Ş','ş','š','š','Ţ','ţ','Ť','ť','Ŧ','ŧ','Ũ','ũ','Ū','ū','Ŭ','ŭ','Ů','ů','Ű','ű','Ų','ų','Ŵ','ŵ','Ŷ','ŷ','ÿ','Ź','ź','Ż','ż','Ž','ž','ſ','ƒ','Ǻ','ǻ','Ǽ','ǽ','Ǿ','ǿ','à','í','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ð','ñ','ò','ô','õ','ö','÷','ø','ù','û','ü','ý','þ','ÿ','Ā','ā','Ă','ă','Ą','ą','Ć','ć','Ĉ','ĉ','Ċ','ċ','Č','٠','Ẁ','ẁ','Ẃ','ẃ','Ẅ','ẅ','Ạ','ạ','Ả','','','','','','','Ь','Э','Ю','Я','а','б','в','г','д','е','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я','ё','ђ','ѓ','є','ѕ','і','ї','ј','љ','њ','ћ','ќ','ў','џ','Ґ','♣','p','!','œ','œ','Ŕ','ŕ','Ŗ','ŗ','Ř','ř','Ś','ś','Ŝ','ŝ','Ş','ş','š','š','Ţ','ţ','Ť','ť','Ŧ','ŧ','Ũ','ũ','Ū','ū','Ŭ','ŭ','Ů','ů','Ű','ű','Ų','ų','Ŵ','ŵ','Ŷ','ŷ','ÿ','Ź','ź','Ż','ż','Ž','ž','ſ','ƒ','Ǿ','ǿ','ˆ','ˇ','ˉ','˘','˙','˚','˛','˜','˝',';','΄','΅','Ά','·','Έ','Ή','Ί','Ό','Ύ','Ώ','ΐ','α','β','γ','δ','ε','ζ','η','θ','ι','λ','κ','μ','ν','ξ','ο','π','ρ','σ','τ','υ','φ','χ','ψ','ω','Ϊ','Ϋ','ά','έ','ή','ί','ΰ','α','β','γ','δ','ε','ζ','η','θ','ι','κ','λ','μ','ν','ξ','ο','π','ρ','ς','σ','τ','υ','φ','χ','ψ','ω','ϊ','ϋ','ό','ύ','ώ','Ё','Ђ','Ѓ','Є','Ѕ','І','Ї','Ј','Љ','Њ','Ћ','Ќ','Ў','Џ','А','Б','В','Г','Д','Е','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','Ã±'];
        $reemplazar = ['H','I','O','Y','','','','','','','','','','','','','','','','','','','I','Y','a','e','n','í','u','a','b','y','o','e','','n','O','','k','','u','E','','p','c','o','','o','X','','w','i','u','ó','ú','w','E','','r','E','S','І','I','J','','','','K','y','','А','','В','','A','E','','N','Ñ','K','','М','Н','О','','Р','С','Т','y','o','Х','','','','','b','','b','E','','R','а','б','B','r','A','е','','','N','Ñ','K','n','M','H','о','','р','с','t','у','o','x','','','','','','','','','','R','e','','r','','s','i','j','','','','K','y','','','r','W','w','W','w','w','W','Y','y','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','A','a','í','a','a','A','A','a','A','','A','a','A','a','A','a','A','a','A','a','A','a','A','a','A','a','A','a','A','a','A','a','A','a','a','á','i','a','a','a','','a','A','a','','','E','E','E','e','E','e','E','e','E','e','E','e','E','e','E','E','e','é','É','e','e','I','i','I','i','i','i','i','O','o','O','o','O','o','O','o','O','o','O','o','O','o','O','o','O','o','O','o','O','o','U','u','U','u','U','ú','U','ú','U','u','U','u','U','u','Y','y','Y','y','Y','y','Y','y','o','ó','Ó','o','o','o','o','ú','Ú','u','u','y','','','ç','é','é','e','e','i','í','i','i','o','ñ','ó','ó','ó','ó','ó','','o','ú','ú','u','u','y','','y','c','c','c','c','c','c','c','c','c','Ď','ď','Đ','đ','e','e','e','e','e','e','e','e','e','e','g','g','g','g','g','g','g','g','h','h','','','i','i','i','i','i','i','i','i','i','i','j','j','j','j','k','k','k','l','l','l','l','l','l','l','l','l','l','n','n','n','n','n','n','n','n','n','o','o','o','o','o','o','','','r','r','r','r','r','r','s','s','s','s','s','s','s','s','t','t','t','t','t','t','u','u','u','u','u','u','u','u','u','u','u','u','w','w','y','y','y','z','z','z','z','z','z','','f','a','a','','','o','o','a','í','a','a','','','e','é','e','e','i','í','i','i','','ñ','o','o','o','o','','o','u','u','u','y','','y','a','a','a','a','a','a','c','c','c','c','c','c','c','','w','w','w','w','w','w','a','a','a','','','','','','','','','','','a','','B','','','e','','','n','Ñ','k','','M','H','o','','p','c','','y','o','x','','','','','','','','','','R','e','','','','s','i','','j','','','','k','y','','','','p','','','','R','r','R','r','r','r','','','','','','','','','t','t','t','t','t','t','','','','','','','','','','','','','W','W','y','y','y','z','z','z','z','z','z','','','o','o','','','','','','','','','','','','','A','','E','H','I','O','Y','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','v','','o','','','','','','','','','','','','','ó','ú','w','E','','','E','S','I','I','J','','','','K','y','','A','','B','','','E','','','N','Ñ','K','','M','H','O','','P','C','T','','X','','','','','b','','b','','','R','ñ'];
        return str_replace($buscar, $reemplazar, $string);
    }

    public function feriasAction(){
    
    }
    
    public function listarFeriasAction(){
        $dataFerias = $this->objFeriasTable->obtenerFerias($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']);
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataFerias as $item){
            $logo = ( $item['hash_logo'] != '' ) ? 'ferias/logo/'.$item['hash_logo'] : 'img/no-imagen.jpg';
            $botonVisitantes = '<a class="dropdown-item pop-up-3 dropdown-menu-modal" href="/cliente/ferias-visitantes?idferias='.$item['idferias'].'"><i class="fas fa-users"></i> Visitantes</a>';
            $botonPaginas = '<a class="dropdown-item dropdown-menu-modal" href="/cliente/paginas-ferias?idferias='.$item['idferias'].'"><i class="fas fa-pager"></i> Ver Páginas</a>';
            $botonEditar = '<a class="dropdown-item dropdown-menu-modal pop-up" href="/cliente/editar-ferias?idferias='.$item['idferias'].'"><i class="fas fa-pencil-alt"></i> Editar</a>';
            $botonEliminar = '<a class="dropdown-item dropdown-menu-modal pop-up" href="/cliente/eliminar-ferias?idferias='.$item['idferias'].'"><i class="fas fa-times"></i> Eliminar</a>';
            $botonPersonalizar = '<a class="dropdown-item dropdown-menu-modal pop-up" href="/cliente/personalizar-ferias?idferias='.$item['idferias'].'"><i class="fas fa-palette"></i> Personalizar</a>';
            $botonConfigurarFormulario = '<a class="dropdown-item dropdown-menu-modal pop-up-2" href="/cliente/configurar-formularios-ferias?idferias='.$item['idferias'].'"><i class="fas fa-th-list"></i> Configurar Formulario</a>';
            $botonSeo = '<a class="dropdown-item dropdown-menu-modal pop-up" href="/cliente/seo-ferias?idferias='.$item['idferias'].'"><i class="fas fa-cog"></i> SEO</a>';
            $botonPaginaSecuencia = '<a class="dropdown-item dropdown-menu-modal pop-up" href="/cliente/pagina-pasos-ferias?idferias='.$item['idferias'].'"><i class="fas fa-cog"></i> Página Secuencia</a>';
            $data_out['data'][] = [
                '<div class="contenedor-imagen"><div style="background-image: url(\''.$logo.'\');border-radius: 5%;" class="imagen-fila"></div></div>',
                $item['nombre'],
                $item['cliente'],
                $item['plan'],
                $item['dominio'],
                '<div class="btn-group">
                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Seleccionar</button>
                    <div class="dropdown-menu dropdown-menu-right fadeinup">
                        '.$botonVisitantes.'
                        '.$botonPaginas.'
                        '.$botonEditar.'
                        '.$botonEliminar.'
                        '.$botonPersonalizar.'
                        '.$botonConfigurarFormulario.'
                        '.$botonSeo.'
                        '.$botonPaginaSecuencia.'
                    </div>
                </div>'
                // $botonVisitantes.' '.$botonPaginas.'  
            ];
        }
        return $this->jsonZF($data_out);
    }
    
    public function agregarFeriasAction(){
        $data=[
            'clientes'=> $this->objClientesTable->obtenerClientes(),
            'planes'=> $this->objPlanesTable->obtenerPlanes(),
        ];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarFeriasAction(){
        $imagenLogo = $this->params()->fromFiles('logo');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'idclientes'=>$datosFormulario['idclientes'],
            'idplanes'=>$datosFormulario['idplanes'],
            'dominio'=>$datosFormulario['dominio'],
            'link_rv'=>$datosFormulario['link_rv'],
            'correo'=>$datosFormulario['correo'],
            'pregunta'=>$datosFormulario['pregunta'],
            'texto_buscador'=>$datosFormulario['texto_buscador']
        ];

        ////////// SCRIPT PARA GUARDAR IMAGEN [INICIO] //////////
        $carpetaFeriasLogo = getcwd().'/public/ferias/logo';
        $datosImagenLogo = [];
        $datosImagenLogo['id'] = md5(uniqid());
        if( $imagenLogo['size'] !== 0 ) {
            $datosImagenLogo['extension'] = strtolower(pathinfo($imagenLogo['name'])['extension']);
            if( in_array($datosImagenLogo['extension'], $this->imagenExtensionesValidas) ) {
                $datosImagenLogo['nombre_completo'] = $datosImagenLogo['id'].'.'.$datosImagenLogo['extension'];
                $datosImagenLogo['nombre_original'] = $imagenLogo['name'];
                if(move_uploaded_file($imagenLogo['tmp_name'], $carpetaFeriasLogo.'/'.$datosImagenLogo['nombre_completo'])){
                    $data['hash_logo'] = $datosImagenLogo['nombre_completo'];
                    $data['nombre_logo'] = $datosImagenLogo['nombre_original'];
                }
            }
        }
        ////////// SCRIPT PARA GUARDAR IMAGEN [FIN] //////////

        $this->objFeriasTable->agregarFerias($data);
        return $this->jsonZF(['result'=>'success']);
    }

    public function personalizarFeriasAction(){
        $idferias = $this->params()->fromQuery('idferias');
        $dataFeria = $this->objFeriasTable->obtenerDatoFerias(['idferias'=> $idferias]);
        $colorConfiguracionInicial = json_encode($this->serviceManager->get("Config")['feria_colores_inicial']);
        $colorConfiguracion = ( $dataFeria && $dataFeria['color_personalizado'] != null ) ? $dataFeria['color_personalizado'] : $colorConfiguracionInicial;
        return $this->consoleZF([
            'idferias'=> $idferias,
            'color_configuracion'=> $colorConfiguracion,
            'color_configuracion_inicial'=> $colorConfiguracionInicial
        ]);
    }

    public function guardarPersonalizarFeriasAction(){
        $idferias = $this->params()->fromPost('idferias');
        $dataConfig = $this->params()->fromPost('config');
        $this->objFeriasTable->actualizarDatosFerias(['color_personalizado'=> $dataConfig], $idferias);
        return $this->jsonZF(['result'=>'success']);
    }

    public function editarFeriasAction(){
        $idferias = $this->params()->fromQuery('idferias');
        $dataFerias = $this->objFeriasTable->obtenerDatoFerias(['idferias'=> $idferias]);
        $dataFerias['clientes'] = $this->objClientesTable->obtenerClientes();
        $dataFerias['planes'] = $this->objPlanesTable->obtenerPlanes();
        return $this->consoleZF($dataFerias);
    }
    
    public function guardarEditarFeriasAction(){
        $imagenLogo = $this->params()->fromFiles('logo');
        $idferias = $this->params()->fromQuery('idferias');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'idclientes'=>$datosFormulario['idclientes'],
            'idplanes'=>$datosFormulario['idplanes'],
            'dominio'=>$datosFormulario['dominio'],
            'link_rv'=>$datosFormulario['link_rv'],
            'correo'=>$datosFormulario['correo'],
            'pregunta'=>$datosFormulario['pregunta'],
            'texto_buscador'=>$datosFormulario['texto_buscador']
        ];

        ////////// SCRIPT PARA ACTUALIZAR IMAGEN [INICIO] //////////
        $carpetaFeriasLogo = getcwd().'/public/ferias/logo';
        $datosImagenLogo = [];
        $datosImagenLogo['id'] = md5(uniqid());
        if( !empty($imagenLogo['name']) && $imagenLogo['size'] !== 0 ) {
            $dataFerias = $this->objFeriasTable->obtenerDatoFerias(['idferias'=> $idferias]);
            if( $dataFerias ) {
                if(file_exists($carpetaFeriasLogo.'/'.$dataFerias['hash_logo'])){
                    @unlink($carpetaFeriasLogo.'/'.$dataFerias['hash_logo']);
                }
                $datosImagenLogo['extension'] = strtolower(pathinfo($imagenLogo['name'])['extension']);
                if( in_array($datosImagenLogo['extension'], $this->imagenExtensionesValidas) ) {
                    $datosImagenLogo['nombre_completo'] = $datosImagenLogo['id'].'.'.$datosImagenLogo['extension'];
                    $datosImagenLogo['nombre_original'] = $imagenLogo['name'];
                    if(move_uploaded_file($imagenLogo['tmp_name'], $carpetaFeriasLogo.'/'.$datosImagenLogo['nombre_completo'])){
                        $data['hash_logo'] = $datosImagenLogo['nombre_completo'];
                        $data['nombre_logo'] = $datosImagenLogo['nombre_original'];
                    }
                }
            }
        }
        ////////// SCRIPT PARA ACTUALIZAR IMAGEN [FIN] //////////

        $this->objFeriasTable->actualizarDatosFerias($data, $idferias);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarFeriasAction(){
        $idferias = $this->params()->fromQuery('idferias');
        $dataFerias = $this->objFeriasTable->obtenerDatoFerias(['idferias'=> $idferias]);
        return $this->consoleZF($dataFerias);
    }
    
    public function confirmarEliminarFeriasAction(){
        $idferias = $this->params()->fromQuery('idferias');
        $dataFerias = $this->objFeriasTable->obtenerDatoFerias(['idferias'=> $idferias]);
        $carpetaFeriasLogo = getcwd().'/public/ferias/logo';
        if( $dataFerias ) {
            if(file_exists($carpetaFeriasLogo.'/'.$dataFerias['hash_logo'])){
                @unlink($carpetaFeriasLogo.'/'.$dataFerias['hash_logo']);
            }
        }
        $this->objFeriasTable->eliminarFerias($idferias);
        return $this->jsonZF(['result'=>'success']);
    }

    public function clientesAction(){
    
    }
    
    public function listarClientesAction(){
        $dataClientes = $this->objClientesTable->obtenerClientes();
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataClientes as $item){
            $logo = ( $item['hash_logo'] != '' ) ? 'clientes/logo/'.$item['hash_logo'] : 'img/no-imagen.jpg';
            $data_out['data'][] = [
                '<div class="contenedor-imagen"><div style="background-image: url(\''.$logo.'\');border-radius: 5%;" class="imagen-fila"></div></div>',
                $item['nombre'],
                '<div class="btn btn-sm btn-info pop-up" href="/cliente/editar-clientes?idclientes='.$item['idclientes'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="btn btn-sm btn-danger pop-up" href="/cliente/eliminar-clientes?idclientes='.$item['idclientes'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div>'
            ];
        }
        return $this->jsonZF($data_out);
    }

    public function agregarClientesAction(){
        $data=[];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarClientesAction(){
        $imagenLogo = $this->params()->fromFiles('logo');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre']
        ];

        ////////// SCRIPT PARA GUARDAR IMAGEN [INICIO] //////////
        $carpetaClientesLogo = getcwd().'/public/clientes/logo';
        $datosImagenLogo = [];
        $datosImagenLogo['id'] = md5(uniqid());
        if( $imagenLogo['size'] !== 0 ) {
            $datosImagenLogo['extension'] = strtolower(pathinfo($imagenLogo['name'])['extension']);
            if( in_array($datosImagenLogo['extension'], $this->imagenExtensionesValidas) ) {
                $datosImagenLogo['nombre_completo'] = $datosImagenLogo['id'].'.'.$datosImagenLogo['extension'];
                $datosImagenLogo['nombre_original'] = $imagenLogo['name'];
                if(move_uploaded_file($imagenLogo['tmp_name'], $carpetaClientesLogo.'/'.$datosImagenLogo['nombre_completo'])){
                    $data['hash_logo'] = $datosImagenLogo['nombre_completo'];
                    $data['nombre_logo'] = $datosImagenLogo['nombre_original'];
                }
            }
        }
        ////////// SCRIPT PARA GUARDAR IMAGEN [FIN] //////////

        $this->objClientesTable->agregarClientes($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarClientesAction(){
        $idclientes = $this->params()->fromQuery('idclientes');
        $dataClientes = $this->objClientesTable->obtenerDatoClientes(['idclientes'=> $idclientes]);
        return $this->consoleZF($dataClientes);
    }
    
    public function guardarEditarClientesAction(){
        $imagenLogo = $this->params()->fromFiles('logo');
        $idclientes = $this->params()->fromQuery('idclientes');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre']
        ];

        ////////// SCRIPT PARA ACTUALIZAR IMAGEN [INICIO] //////////
        $carpetaClientesLogo = getcwd().'/public/clientes/logo';
        $datosImagenLogo = [];
        $datosImagenLogo['id'] = md5(uniqid());
        if( !empty($imagenLogo['name']) && $imagenLogo['size'] !== 0 ) {
            $dataClientes = $this->objClientesTable->obtenerDatoClientes(['idclientes'=> $idclientes]);
            if( $dataClientes ) {
                if(file_exists($carpetaClientesLogo.'/'.$dataClientes['hash_logo'])){
                    @unlink($carpetaClientesLogo.'/'.$dataClientes['hash_logo']);
                }
                $datosImagenLogo['extension'] = strtolower(pathinfo($imagenLogo['name'])['extension']);
                if( in_array($datosImagenLogo['extension'], $this->imagenExtensionesValidas) ) {
                    $datosImagenLogo['nombre_completo'] = $datosImagenLogo['id'].'.'.$datosImagenLogo['extension'];
                    $datosImagenLogo['nombre_original'] = $imagenLogo['name'];
                    if(move_uploaded_file($imagenLogo['tmp_name'], $carpetaClientesLogo.'/'.$datosImagenLogo['nombre_completo'])){
                        $data['hash_logo'] = $datosImagenLogo['nombre_completo'];
                        $data['nombre_logo'] = $datosImagenLogo['nombre_original'];
                    }
                }
            }
        }
        ////////// SCRIPT PARA ACTUALIZAR IMAGEN [FIN] //////////

        $this->objClientesTable->actualizarDatosClientes($data, $idclientes);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarClientesAction(){
        $idclientes = $this->params()->fromQuery('idclientes');
        $dataClientes = $this->objClientesTable->obtenerDatoClientes(['idclientes'=> $idclientes]);
        return $this->consoleZF($dataClientes);
    }
    
    public function confirmarEliminarClientesAction(){
        $idclientes = $this->params()->fromQuery('idclientes');
        $dataClientes = $this->objClientesTable->obtenerDatoClientes(['idclientes'=> $idclientes]);
        $carpetaClientesLogo = getcwd().'/public/clientes/logo';
        if( $dataClientes ) {
            if(file_exists($carpetaClientesLogo.'/'.$dataClientes['hash_logo'])){
                @unlink($carpetaClientesLogo.'/'.$dataClientes['hash_logo']);
            }
        }
        $this->objClientesTable->eliminarClientes($idclientes);
        return $this->jsonZF(['result'=>'success']);
    }

    public function zonasAction(){
    
    }
    
    public function listarZonasAction(){
        $dataZonas = $this->objZonasTable->obtenerZonas($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']);
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataZonas as $item){
            $data_out['data'][] = [
                $item['nombre'],
                $item['feria'],
                '<a class="btn btn-sm btn-dark" href="/cliente/paginas-zonas-configuracion/'.$item['idzonas'].'"><i class="fas fa-cogs"></i> <span class="hidden-xs">Personalizar</span></a> <div class="btn btn-sm btn-info pop-up" href="/cliente/editar-zonas?idzonas='.$item['idzonas'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="btn btn-sm btn-danger pop-up" href="/cliente/eliminar-zonas?idzonas='.$item['idzonas'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div>'
            ];
        }
        return $this->jsonZF($data_out);
    }
    
    public function agregarZonasAction(){
        $data=[
            'ferias'=> $this->objFeriasTable->obtenerFerias($this->sessionContainer['idferias'], $this->sessionContainer['idperfil'])
        ];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarZonasAction(){
        $datosFormulario = $this->params()->fromPost();
        $idferiasSelected = (isset($datosFormulario['idferias'])) ? $datosFormulario['idferias'] : $this->sessionContainer['idferias'];
        $dataPlanFeria = $this->objFeriasTable->validarPlan($idferiasSelected);
        $totalZonas = $this->objZonasTable->obtenerTotalZonasPorFeria($idferiasSelected);
        if(($totalZonas + 1) > $dataPlanFeria['cantidad_zonas']) {
            return $this->jsonZF(['result'=>'plan_excedido', 'data'=> $dataPlanFeria]);
        }
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'idferias'=>$idferiasSelected,
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
            'orden'=>  $this->objZonasTable->obtenerUltimoOrdenZonasPorFeria($idferiasSelected)
        ];
        $this->objZonasTable->agregarZonas($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarZonasAction(){
        $idzonas = $this->params()->fromQuery('idzonas');
        $dataZonas = $this->objZonasTable->obtenerDatoZonas(['idzonas'=> $idzonas]);
        $dataZonas['ferias'] = $this->objFeriasTable->obtenerFerias($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']);
        return $this->consoleZF($dataZonas);
    }
    
    public function guardarEditarZonasAction(){
        $idzonas = $this->params()->fromQuery('idzonas');
        $datosFormulario = $this->params()->fromPost();
        $idferiasSelected = (isset($datosFormulario['idferias'])) ? $datosFormulario['idferias'] : $this->sessionContainer['idferias'];
        $dataZona = $this->objZonasTable->obtenerDatoZonas(['idzonas'=> $idzonas]);
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'idferias'=>$idferiasSelected,
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
        ];
        if($idferiasSelected != $dataZona['idferias'])$data['orden'] = $this->objZonasTable->obtenerUltimoOrdenZonasPorFeria($idferiasSelected);
        $this->objZonasTable->actualizarDatosZonas($data, $idzonas);
        if($idferiasSelected != $dataZona['idferias'])$this->objZonasTable->reordenarOrdenZonasPorFeria($dataZona['idferias']);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarZonasAction(){
        $idzonas = $this->params()->fromQuery('idzonas');
        $dataZonas = $this->objZonasTable->obtenerDatoZonas(['idzonas'=> $idzonas]);
        return $this->consoleZF($dataZonas);
    }
    
    public function confirmarEliminarZonasAction(){
        $idzonas = $this->params()->fromQuery('idzonas');
        $idferias = $this->params()->fromQuery('idferias');
        $this->objZonasTable->eliminarZonas($idzonas);
        $this->objZonasTable->reordenarOrdenZonasPorFeria($idferias);
        return $this->jsonZF(['result'=>'success']);
    }

    public function cronogramasAction(){
    
    }
    
    public function listarCronogramasAction(){
        $dataCronogramas = $this->objCronogramasTable->obtenerCronogramas($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']);
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataCronogramas as $item){
            $logo = ( $item['hash_logo'] != '' ) ? 'cronogramas/logo/'.$item['hash_logo'] : 'img/no-imagen.jpg';
            $data_out['data'][] = [
                '<div class="contenedor-imagen"><div style="background-image: url(\''.$logo.'\');border-radius: 5%;" class="imagen-fila"></div></div>',
                $item['titulo'],
                date('d/m/Y', strtotime($item['fecha'])),
                date('H:i', strtotime($item['hora_inicio'])),
                date('H:i', strtotime($item['hora_fin'])),
                $item['feria'],
                $item['expositor'],
                '<div class="btn btn-sm btn-info pop-up" href="/cliente/editar-cronogramas?idcronogramas='.$item['idcronogramas'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="btn btn-sm btn-danger pop-up" href="/cliente/eliminar-cronogramas?idcronogramas='.$item['idcronogramas'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div>'
            ];
        }
        return $this->jsonZF($data_out);
    }
    
    public function agregarCronogramasAction(){
        $dataPlan = ( $this->sessionContainer['idferias'] != '' ) ? $this->objFeriasTable->validarPlan($this->sessionContainer['idferias']) : [];
        $data=[
            'ferias'=> $this->objFeriasTable->obtenerFerias($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']),
            'expositores'=> $this->objExpositoresTable->obtenerExpositores($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']),
            'planes'=> $dataPlan,
            'conferencias'=> $this->objConferenciasTable->obtenerConferencias($this->sessionContainer['idferias'], $this->sessionContainer['idperfil'])
        ];
        //print_r($data['conferencias']);
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarCronogramasAction(){
        $archivos = $this->params()->fromFiles();
        $datosFormulario = $this->params()->fromPost();
        $idferiasSelected = (isset($datosFormulario['idferias'])) ? $datosFormulario['idferias'] : $this->sessionContainer['idferias'];
        $data = [
            'titulo'=>$datosFormulario['titulo'],
            'fecha'=>date('Y-m-d', strtotime(str_replace("/", "-", $datosFormulario['fecha']))),
            'hora_inicio'=>$datosFormulario['hora_inicio'],
            'hora_fin'=>$datosFormulario['hora_fin'],
            'idferias'=>$idferiasSelected,
            'idexpositores'=>$datosFormulario['idexpositores'],
            'idconferencias'=>@$datosFormulario['idconferencias']
        ];

        ////////// SCRIPT PARA GUARDAR IMAGEN [INICIO] //////////
        $imagenExtensionesValidas = [];
        $carpetaArchivos = '';
        $dataArchivos = [];
        $keyDataArchivo = [];
        if(!empty($archivos)){
            foreach($archivos as $key => $archivo){
                switch($key){
                    case 'logo':
                        $imagenExtensionesValidas = $this->imagenExtensionesValidas;
                        $carpetaArchivos = getcwd().'/public/cronogramas/logo';
                        $keyDataArchivo[$key] = ['hash'=> 'hash_logo', 'nombre'=> 'nombre_logo'];
                    break;
                    case 'portada_izquierda':
                        $imagenExtensionesValidas = $this->imagenExtensionesValidas;
                        $carpetaArchivos = getcwd().'/public/cronogramas/portada';
                        $keyDataArchivo[$key] = ['hash'=> 'hash_portada_izquierda', 'nombre'=> 'nombre_portada_izquierda'];
                    break;
                    case 'portada_derecha':
                        $imagenExtensionesValidas = $this->imagenExtensionesValidas;
                        $carpetaArchivos = getcwd().'/public/cronogramas/portada';
                        $keyDataArchivo[$key] = ['hash'=> 'hash_portada_derecha', 'nombre'=> 'nombre_portada_derecha'];
                    break;
                }
                $dataArchivos['id'] = md5(uniqid());
                if( $archivo['size'] !== 0 ) {
                    $dataArchivos['extension'] = strtolower(pathinfo($archivo['name'])['extension']);
                    if( in_array($dataArchivos['extension'], $imagenExtensionesValidas) ) {
                        $dataArchivos['nombre_completo'] = $dataArchivos['id'].'.'.$dataArchivos['extension'];
                        $dataArchivos['nombre_original'] = $archivo['name'];
                        if(move_uploaded_file($archivo['tmp_name'], $carpetaArchivos.'/'.$dataArchivos['nombre_completo'])){
                            $data[$keyDataArchivo[$key]['hash']] = $dataArchivos['nombre_completo'];
                            $data[$keyDataArchivo[$key]['nombre']] = $dataArchivos['nombre_original'];
                        }
                    }
                }
            }
        }
        ////////// SCRIPT PARA GUARDAR IMAGEN [FIN] //////////

        $this->objCronogramasTable->agregarCronogramas($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarCronogramasAction(){
        $idcronogramas = $this->params()->fromQuery('idcronogramas');
        $dataCronogramas = $this->objCronogramasTable->obtenerDatoCronogramas(['idcronogramas'=> $idcronogramas]);
        $dataCronogramas['ferias'] = $this->objFeriasTable->obtenerFerias($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']);
        $dataCronogramas['expositores'] = $this->objExpositoresTable->obtenerExpositores($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']);
        $dataCronogramas['planes'] = $this->objFeriasTable->validarPlan($dataCronogramas['idferias']);
        $dataCronogramas['conferencias'] = $this->objConferenciasTable->obtenerConferencias($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']);
        return $this->consoleZF($dataCronogramas);
    }
    
    public function guardarEditarCronogramasAction(){
        $archivos = $this->params()->fromFiles();
        $idcronogramas = $this->params()->fromQuery('idcronogramas');
        $datosFormulario = $this->params()->fromPost();
        $idferiasSelected = (isset($datosFormulario['idferias'])) ? $datosFormulario['idferias'] : $this->sessionContainer['idferias'];
        $data = [
            'titulo'=>$datosFormulario['titulo'],
            'fecha'=>date('Y-m-d', strtotime(str_replace("/", "-", $datosFormulario['fecha']))),
            'hora_inicio'=>$datosFormulario['hora_inicio'],
            'hora_fin'=>$datosFormulario['hora_fin'],
            'idferias'=>$idferiasSelected,
            'idexpositores'=>$datosFormulario['idexpositores'],
            'idconferencias'=>@$datosFormulario['idconferencias']
        ];

        ////////// SCRIPT PARA ACTUALIZAR IMAGEN [INICIO] //////////
        $imagenExtensionesValidas = [];
        $carpetaArchivos = '';
        $dataArchivos = [];
        $keyDataArchivo = [];
        if(!empty($archivos)){
            foreach($archivos as $key => $archivo){
                switch($key){
                    case 'logo':
                        $imagenExtensionesValidas = $this->imagenExtensionesValidas;
                        $carpetaArchivos = getcwd().'/public/cronogramas/logo';
                        $keyDataArchivo[$key] = ['hash'=> 'hash_logo', 'nombre'=> 'nombre_logo'];
                    break;
                    case 'portada_izquierda':
                        $imagenExtensionesValidas = $this->imagenExtensionesValidas;
                        $carpetaArchivos = getcwd().'/public/cronogramas/portada';
                        $keyDataArchivo[$key] = ['hash'=> 'hash_portada_izquierda', 'nombre'=> 'nombre_portada_izquierda'];
                    break;
                    case 'portada_derecha':
                        $imagenExtensionesValidas = $this->imagenExtensionesValidas;
                        $carpetaArchivos = getcwd().'/public/cronogramas/portada';
                        $keyDataArchivo[$key] = ['hash'=> 'hash_portada_derecha', 'nombre'=> 'nombre_portada_derecha'];
                    break;
                }
                $dataArchivos['id'] = md5(uniqid());
                if( !empty($archivo['name']) && $archivo['size'] !== 0 ) {
                    $dataCronograma = $this->objCronogramasTable->obtenerDatoCronogramas(['idcronogramas'=> $idcronogramas]);
                    if( $dataCronograma ) {
                        if(file_exists($carpetaArchivos.'/'.$dataCronograma[$keyDataArchivo[$key]['hash']])){
                            @unlink($carpetaArchivos.'/'.$dataCronograma[$keyDataArchivo[$key]['hash']]);
                        }
                        $dataArchivos['extension'] = strtolower(pathinfo($archivo['name'])['extension']);
                        if( in_array($dataArchivos['extension'], $imagenExtensionesValidas) ) {
                            $dataArchivos['nombre_completo'] = $dataArchivos['id'].'.'.$dataArchivos['extension'];
                            $dataArchivos['nombre_original'] = $archivo['name'];
                            if(move_uploaded_file($archivo['tmp_name'], $carpetaArchivos.'/'.$dataArchivos['nombre_completo'])){
                                $data[$keyDataArchivo[$key]['hash']] = $dataArchivos['nombre_completo'];
                                $data[$keyDataArchivo[$key]['nombre']] = $dataArchivos['nombre_original'];
                            }
                        }
                    }
                }
            }
        }
        ////////// SCRIPT PARA ACTUALIZAR IMAGEN [FIN] //////////
        $this->objCronogramasTable->actualizarDatosCronogramas($data, $idcronogramas);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarCronogramasAction(){
        $idcronogramas = $this->params()->fromQuery('idcronogramas');
        $dataCronogramas = $this->objCronogramasTable->obtenerDatoCronogramas(['idcronogramas'=> $idcronogramas]);
        return $this->consoleZF($dataCronogramas);
    }
    
    public function confirmarEliminarCronogramasAction(){
        $idcronogramas = $this->params()->fromQuery('idcronogramas');
        $dataCronogramas = $this->objCronogramasTable->obtenerDatoCronogramas(['idcronogramas'=> $idcronogramas]);
        if( $dataCronogramas ) {
            $dataArchivos = [
                'logo'=> ['directorio'=> getcwd().'/public/cronogramas/logo'],
                'portada_izquierda'=> ['directorio'=> getcwd().'/public/cronogramas/portada'],
                'portada_derecha'=> ['directorio'=> getcwd().'/public/cronogramas/portada']
            ];
            foreach($dataArchivos as $key => $item) {
                if(isset($dataCronogramas['hash_'.$key]) && file_exists($item['directorio'].'/'.$dataCronogramas['hash_'.$key])){
                    @unlink($item['directorio'].'/'.$dataCronogramas['hash_'.$key]);
                }
            }
        }
        $this->objCronogramasTable->eliminarCronogramas($idcronogramas);
        return $this->jsonZF(['result'=>'success']);
    }

    public function expositoresAction(){
    
    }
    
    public function listarExpositoresAction(){
        $dataExpositores = $this->objExpositoresTable->obtenerExpositores($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']);
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataExpositores as $item){
            $botonConferencias = '<div class="btn btn-sm btn-dark pop-up" href="/cliente/expositores-conferencias?idexpositores='.$item['idexpositores'].'"><i class="fas fa-camera-movie"></i> <span class="hidden-xs">Conferencia</span></div>';
            $botonProductos = '<a class="btn btn-sm btn-success" href="/cliente/expositores-productos?idexpositores='.$item['idexpositores'].'"><i class="fas fa-box-open"></i> <span class="hidden-xs">Productos</span></a>';
            $botonTarjetas = '<a class="btn btn-sm btn-dark pop-up-2" href="/cliente/expositores-tarjetas?idexpositores='.$item['idexpositores'].'"><i class="fas fa-users"></i> <span class="hidden-xs">Tarjetas</span></a>';
            $data_out['data'][] = [
                $item['nombres'],
                $item['apellido_paterno'],
                $item['apellido_materno'],
                $botonTarjetas.' '.$botonProductos.' '.$botonConferencias.' <div class="btn btn-sm btn-info pop-up" href="/cliente/editar-expositores?idexpositores='.$item['idexpositores'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="btn btn-sm btn-danger pop-up" href="/cliente/eliminar-expositores?idexpositores='.$item['idexpositores'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div>'
            ];
        }
        return $this->jsonZF($data_out);
    }
    
    public function agregarExpositoresAction(){
        $data=[
            'ferias'=> $this->objFeriasTable->obtenerFerias($this->sessionContainer['idferias'], $this->sessionContainer['idperfil'])
        ];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarExpositoresAction(){
        $imagenLogo = $this->params()->fromFiles('logo');
        $imagenFoto = $this->params()->fromFiles('foto');
        $datosFormulario = $this->params()->fromPost();
        $idferiasSelected = (isset($datosFormulario['idferias'])) ? $datosFormulario['idferias'] : $this->sessionContainer['idferias'];
        $horarioAtencion = $this->objTools->timeFormat($datosFormulario['hora_inicio'][0])."-".$this->objTools->timeFormat($datosFormulario['hora_fin'][0])." | ".$this->objTools->timeFormat($datosFormulario['hora_inicio'][1])."-".$this->objTools->timeFormat($datosFormulario['hora_fin'][1]);
        $data = [
            'nombres'=>$datosFormulario['nombres'],
            'apellido_paterno'=>$datosFormulario['apellido_paterno'],
            'apellido_materno'=>$datosFormulario['apellido_materno'],
            'correo'=>$datosFormulario['correo'],
            'telefono'=>$datosFormulario['telefono'],
            'tipo_documento'=>@$datosFormulario['tipo_documento'],
            'numero_documento'=>$datosFormulario['numero_documento'],
            'idusuario'=> $this->sessionContainer['idusuario'],
            'tipo_visitante'=> '',
            'fecha_creacion'=> date('Y-m-d H:i:s'),
            'contrasena'=> md5($datosFormulario['numero_documento']),
            'idferias'=> $idferiasSelected,
            'enlace_conferencia_asesor'=> $datosFormulario['enlace_conferencia_asesor'],
            'enlace_wsp'=> $datosFormulario['enlace_wsp'],
            'horario_atencion'=> $horarioAtencion
        ]; 
        ////////// SCRIPT PARA GUARDAR IMAGEN [INICIO] //////////
        $directorioImagen = getcwd().'/public/expositores/conferencia';
        $datosImagenLogo = [];
        $datosImagenLogo['id'] = md5(uniqid());
        if( $imagenLogo['size'] !== 0 ) {
            $datosImagenLogo['extension'] = strtolower(pathinfo($imagenLogo['name'])['extension']);
            if( in_array($datosImagenLogo['extension'], $this->imagenExtensionesValidas) ) {
                $datosImagenLogo['nombre_completo'] = $datosImagenLogo['id'].'.'.$datosImagenLogo['extension'];
                $datosImagenLogo['nombre_original'] = $imagenLogo['name'];
                if(move_uploaded_file($imagenLogo['tmp_name'], $directorioImagen.'/'.$datosImagenLogo['nombre_completo'])){
                    $data['hash_fondo_conferencia'] = $datosImagenLogo['nombre_completo'];
                    $data['nombre_fondo_conferencia'] = $datosImagenLogo['nombre_original'];
                }
            }
        }
        ////////// SCRIPT PARA GUARDAR IMAGEN [FIN] //////////
        $directorioImagen = getcwd().'/public/expositores/foto';
        $datosImagenLogo = [];
        $datosImagenLogo['id'] = md5(uniqid());
        if( $imagenFoto['size'] !== 0 ) {
            $datosImagenLogo['extension'] = strtolower(pathinfo($imagenFoto['name'])['extension']);
            if( in_array($datosImagenLogo['extension'], $this->imagenExtensionesValidas) ) {
                $datosImagenLogo['nombre_completo'] = $datosImagenLogo['id'].'.'.$datosImagenLogo['extension'];
                $datosImagenLogo['nombre_original'] = $imagenFoto['name'];
                if(move_uploaded_file($imagenFoto['tmp_name'], $directorioImagen.'/'.$datosImagenLogo['nombre_completo'])){
                    $data['hash_foto'] = $datosImagenLogo['nombre_completo'];
                    $data['nombre_foto'] = $datosImagenLogo['nombre_original'];
                }
            }
        }
        ////////// SCRIPT PARA GUARDAR IMAGEN FOTO [FIN] //////////
        $this->objExpositoresTable->agregarExpositores($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarExpositoresAction(){
        $idexpositores = $this->params()->fromQuery('idexpositores');
        $dataExpositores = $this->objExpositoresTable->obtenerDatoExpositores(['idexpositores'=> $idexpositores]);
        $dataExpositores['ferias'] = $this->objFeriasTable->obtenerFerias($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']);
        $dataExpositores['horario_atencion'] = explode(" | ", $dataExpositores['horario_atencion']);
        return $this->consoleZF($dataExpositores);
    }
    
    public function guardarEditarExpositoresAction(){
        $imagenLogo = $this->params()->fromFiles('logo');
        $imagenFoto = $this->params()->fromFiles('foto');
        $idexpositores = $this->params()->fromQuery('idexpositores');
        $datosFormulario = $this->params()->fromPost();
        $idferiasSelected = (isset($datosFormulario['idferias'])) ? $datosFormulario['idferias'] : $this->sessionContainer['idferias'];
        $horarioAtencion = $this->objTools->timeFormat($datosFormulario['hora_inicio'][0])."-".$this->objTools->timeFormat($datosFormulario['hora_fin'][0])." | ".$this->objTools->timeFormat($datosFormulario['hora_inicio'][1])."-".$this->objTools->timeFormat($datosFormulario['hora_fin'][1]);
        $data = [
            'nombres'=>$datosFormulario['nombres'],
            'apellido_paterno'=>$datosFormulario['apellido_paterno'],
            'apellido_materno'=>$datosFormulario['apellido_materno'],
            'correo'=>$datosFormulario['correo'],
            'telefono'=>$datosFormulario['telefono'],
            'tipo_documento'=>@$datosFormulario['tipo_documento'],
            'numero_documento'=>$datosFormulario['numero_documento'],
            'idusuario'=> $this->sessionContainer['idusuario'],
            'tipo_visitante'=> '',
            'fecha_actualizacion'=> date('Y-m-d H:i:s'),
            'contrasena'=> md5($datosFormulario['numero_documento']),
            'idferias'=> $idferiasSelected,
            'enlace_conferencia_asesor'=> $datosFormulario['enlace_conferencia_asesor'],
            'enlace_wsp'=> $datosFormulario['enlace_wsp'],
            'horario_atencion'=> $horarioAtencion
        ];
        $dataExpositor = $this->objExpositoresTable->obtenerDatoExpositores(['idexpositores'=> $idexpositores]);
        ////////// SCRIPT PARA ACTUALIZAR IMAGEN [INICIO] //////////
        $directorioImagen = getcwd().'/public/expositores/conferencia';
        $datosImagenLogo = [];
        $datosImagenLogo['id'] = md5(uniqid());
        if( !empty($imagenLogo['name']) && $imagenLogo['size'] !== 0 ) {
            if( $dataExpositor ) {
                if(file_exists($directorioImagen.'/'.$dataExpositor['hash_fondo_conferencia'])){
                    @unlink($directorioImagen.'/'.$dataExpositor['hash_fondo_conferencia']);
                }
                $datosImagenLogo['extension'] = strtolower(pathinfo($imagenLogo['name'])['extension']);
                if( in_array($datosImagenLogo['extension'], $this->imagenExtensionesValidas) ) {
                    $datosImagenLogo['nombre_completo'] = $datosImagenLogo['id'].'.'.$datosImagenLogo['extension'];
                    $datosImagenLogo['nombre_original'] = $imagenLogo['name'];
                    if(move_uploaded_file($imagenLogo['tmp_name'], $directorioImagen.'/'.$datosImagenLogo['nombre_completo'])){
                        $data['hash_fondo_conferencia'] = $datosImagenLogo['nombre_completo'];
                        $data['nombre_fondo_conferencia'] = $datosImagenLogo['nombre_original'];
                    }
                }
            }
        }
        ////////// SCRIPT PARA ACTUALIZAR IMAGEN [FIN] //////////
        $directorioImagen = getcwd().'/public/expositores/foto';
        $datosImagenLogo = [];
        $datosImagenLogo['id'] = md5(uniqid());
        if( !empty($imagenFoto['name']) && $imagenFoto['size'] !== 0 ) {
            if( $dataExpositor ) {
                if(file_exists($directorioImagen.'/'.$dataExpositor['hash_foto'])){
                    @unlink($directorioImagen.'/'.$dataExpositor['hash_foto']);
                }
                $datosImagenLogo['extension'] = strtolower(pathinfo($imagenFoto['name'])['extension']);
                if( in_array($datosImagenLogo['extension'], $this->imagenExtensionesValidas) ) {
                    $datosImagenLogo['nombre_completo'] = $datosImagenLogo['id'].'.'.$datosImagenLogo['extension'];
                    $datosImagenLogo['nombre_original'] = $imagenFoto['name'];
                    if(move_uploaded_file($imagenFoto['tmp_name'], $directorioImagen.'/'.$datosImagenLogo['nombre_completo'])){
                        $data['hash_foto'] = $datosImagenLogo['nombre_completo'];
                        $data['nombre_foto'] = $datosImagenLogo['nombre_original'];
                    }
                }
            }
        }
        ////////// SCRIPT PARA ACTUALIZAR IMAGEN FOTO [FIN] //////////
        $this->objExpositoresTable->actualizarDatosExpositores($data, $idexpositores);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarExpositoresAction(){
        $idexpositores = $this->params()->fromQuery('idexpositores');
        $dataExpositores = $this->objExpositoresTable->obtenerDatoExpositores(['idexpositores'=> $idexpositores]);
        return $this->consoleZF($dataExpositores);
    }
    
    public function confirmarEliminarExpositoresAction(){
        $idexpositores = $this->params()->fromQuery('idexpositores');
        $dataExpositor = $this->objExpositoresTable->obtenerDatoExpositores(['idexpositores'=> $idexpositores]);
        $directorioImagen = getcwd().'/public/expositores/conferencia';
        if( $dataExpositor ) {
            if(file_exists($directorioImagen.'/'.$dataExpositor['hash_fondo_conferencia'])){
                @unlink($directorioImagen.'/'.$dataExpositor['hash_fondo_conferencia']);
            }
        }
        $directorioImagen = getcwd().'/public/expositores/foto';
        if( $dataExpositor ) {
            if(file_exists($directorioImagen.'/'.$dataExpositor['hash_foto'])){
                @unlink($directorioImagen.'/'.$dataExpositor['hash_foto']);
            }
        }
        $this->objExpositoresTable->eliminarExpositores($idexpositores);
        return $this->jsonZF(['result'=>'success']);
    }

    public function expositoresConferenciasAction(){
        $idexpositores = $this->params()->fromQuery('idexpositores');
        $dataExpositores = $this->objExpositoresTable->obtenerDatoExpositores(['idexpositores'=> $idexpositores]);
        return $this->consoleZF($dataExpositores);
    }

    public function guardarExpositoresConferenciasAction(){
        $idexpositores = $this->params()->fromQuery('idexpositores');
        $dataEmpresas = $this->objEmpresasTable->obtenerDatoEmpresas(['idexpositores'=> $idexpositores]);
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'enlace_conferencia'=>$datosFormulario['enlace_conferencia']
        ];
        $this->objExpositoresTable->actualizarDatosExpositores($data, $idexpositores);
        $dataExpositores = $this->objExpositoresTable->obtenerDatoExpositores(['idexpositores'=> $idexpositores]);
        if(!$dataEmpresas)return $this->jsonZF(['result'=> 'success']);
        return $this->jsonZF(['result'=>'successConferencia', 'dataConferencia'=> ['idempresas'=> $dataEmpresas['idempresas'], 'idexpositores'=> $dataExpositores['idexpositores'], 'enlace_conferencia'=> $dataExpositores['enlace_conferencia']]]);
    }

    public function expositoresTarjetasAction(){
        $idexpositores = $this->params()->fromQuery('idexpositores');
        return $this->consoleZF(['idexpositores'=> $idexpositores]);
    }

    public function listarExpositoresTarjetasAction(){
        $idexpositores = $this->params()->fromQuery('idexpositores');
        $dataExpositoresTarjetas = $this->objExpositoresTarjetasTable->obtenerDatosExpositoresTarjetas(['idexpositores'=> $idexpositores]);
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataExpositoresTarjetas as $item){
            $dataVisitante = $this->objVisitantesTable->obtenerDatoVisitantes(['idvisitantes'=> $item['idvisitantes']]);
            $data_out['data'][] = [
                $dataVisitante['nombres'],
                $dataVisitante['apellido_paterno'],
                $dataVisitante['apellido_materno']
            ];
        }
        return $this->jsonZF($data_out);
    }

    public function empresasAction(){
        
    }
    
    public function listarEmpresasAction(){
        $dataEmpresas = $this->objEmpresasTable->obtenerEmpresas($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']);
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataEmpresas as $item){
            $logo = ( $item['hash_logo'] != '' ) ? 'empresas/logo/'.$item['hash_logo'] : 'img/no-imagen.jpg';
            $opcionPersonalizarStand = '<a class="dropdown-item dropdown-menu-modal" href="/cliente/paginas-stand-configuracion/'.$item['idempresas'].'"><i class="fas fa-cogs"></i> Personalizar Stand</a>';
            $opcionEditar = '<a class="dropdown-item dropdown-menu-modal pop-up" href="/cliente/editar-empresas?idempresas='.$item['idempresas'].'"><i class="fas fa-pencil-alt"></i> Editar</a>';
            $opcionEliminar = '<a class="dropdown-item dropdown-menu-modal pop-up" href="/cliente/eliminar-empresas?idempresas='.$item['idempresas'].'"><i class="fas fa-times"></i> Eliminar</a>';
            $opcionHistorialChat = '<a class="dropdown-item dropdown-menu-modal pop-up-3" href="/cliente/historial-chat-empresas?idempresas='.$item['idempresas'].'"><i class="fas fa-comments"></i> Historial Chat</a>';
            $data_out['data'][] = [
                '<div class="contenedor-imagen"><div style="background-image: url(\''.$logo.'\');border-radius: 5%;" class="imagen-fila"></div></div>',
                $item['nombre'],
                $item['zona'],
                $item['stand'],
                $item['expositor'],
                '<div class="btn-group">
                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Seleccionar</button>
                    <div class="dropdown-menu dropdown-menu-right fadeinup">
                        '.$opcionPersonalizarStand.'
                        '.$opcionEditar.'
                        '.$opcionEliminar.'
                        '.$opcionHistorialChat.'
                    </div>
                </div>'
            ];
        }
        return $this->jsonZF($data_out);
    }

    public function historialChatEmpresasAction(){
        $idempresas = $this->params()->fromQuery('idempresas');
        $dataHistorialChat = $this->objChatsTable->obtenerHistorialChats($idempresas);
        $historialChat = [];
        if(!empty($dataHistorialChat)){
            foreach($dataHistorialChat as $chat){
                if($chat['usuario'] == '')continue;
                list($date, $time) = explode(" ", $chat['fecha_hora']);
                $message = $chat['mensaje'];
                $usuario = $chat['usuario'];
                $tipo = $chat['tipo_usuario'];
                $historialChat[$date][] = [
                    'type'=> $tipo,
                    'user'=> $usuario,
                    'time'=> $time,
                    'msg'=> $message
                ];
            }
        }
        $data = [
            'id'=> $idempresas,
            'historial'=> $historialChat,
        ];
        return $this->consoleZF($data);
    }
    
    public function descargarHistorialChatAction(){
        $codigoUnico = $this->params()->fromPost('id');
        $dataHistorialChat = $this->objChatsTable->obtenerHistorialChats($codigoUnico);
        $tiposUsuario = [
            "V"=> "VISITANTE",
            "E"=> "EXPOSITORES",
            "S"=> "SPEAKERS"
        ];
        $directorioChat = getcwd()."/public/chats/historial/";
        $archivoChat = "chat.txt";
        if(!empty($dataHistorialChat)){
            $file = fopen($directorioChat.$archivoChat, "w") or die("Unable to open file!");
            $mensajes = "";
            foreach($dataHistorialChat as $chat){
                list($date, $time) = explode(" ", $chat['fecha_hora']);
                $message = $chat['mensaje'];
                $usuario = $chat['usuario'];
                $tipo = $chat['tipo_usuario'];
                $historialChat[$date][] = [
                    'type'=> $tipo,
                    'user'=> $usuario,
                    'time'=> $time,
                    'msg'=> $message
                ];
                $mensajes .= $chat['fecha_hora']."|";
                $mensajes .= $tiposUsuario[$tipo]."|";
                $mensajes .= $usuario."|";
                $mensajes .= $message."\n";
            }
            fwrite($file, $mensajes);
            fclose($file);
        }
        return $this->jsonZF([
            'file'=> $archivoChat,
            'result'=>'success'
        ]);
    }
    
    public function agregarEmpresasAction(){
        $data=[
            'zonas'=> $this->objZonasTable->obtenerZonas($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']),
            'stand'=> $this->objStandTable->obtenerStand(),
            'expositores'=> $this->objExpositoresTable->obtenerExpositores($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']),
            'bancos'=> $this->objBancosTable->obtenerBancos(),
            'segmentos'=> $this->objSegmentosTable->obtenerSegmentos()
        ];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarEmpresasAction(){
        $imagenLogo = $this->params()->fromFiles('logo');
        $datosFormulario = $this->params()->fromPost();
        /******** VALIDAR PLAN POR FERIA [INICIO] *********/
        $dataZonas =  $this->objZonasTable->obtenerDatoZonas(['idzonas'=> $datosFormulario['idzonas']]);
        $dataPlanFeria = $this->objFeriasTable->validarPlan($dataZonas['idferias']);
        $totalEmpresas = $this->objEmpresasTable->obtenerTotalEmpresasPorFeria($dataZonas['idferias'], $datosFormulario['idzonas']);
        if(($totalEmpresas + 1) > $dataPlanFeria['cantidad_empresas_zonas']) {
            return $this->jsonZF(['result'=>'plan_excedido', 'data'=> $dataPlanFeria]);
        }
        /******** VALIDAR PLAN POR FERIA [FIN] *********/
        $idbancos = (isset($datosFormulario['idbancos']) && $datosFormulario['idbancos'] != '')?$datosFormulario['idbancos']:0;
        $estadoBotonEnVivo = (isset($datosFormulario['config_estado_boton_envivo'])) ? $datosFormulario['config_estado_boton_envivo'] : 0;
        $standEstadoRv = (isset($datosFormulario['stand_config_estado_rv'])) ? $datosFormulario['stand_config_estado_rv'] : 0;
        $data = [
            'idzonas'=>$datosFormulario['idzonas'],
            'nombre'=>$datosFormulario['nombre'],
            'enlace_video'=>$datosFormulario['enlace_video'],
            'enlace_video_2'=>$datosFormulario['enlace_video_2'],
            'enlace_wsp'=>$datosFormulario['enlace_wsp'],
            'enlace_web'=>$datosFormulario['enlace_web'],
            'enlace_rv'=>$datosFormulario['enlace_rv'],
            'idstand'=>$datosFormulario['idstand'],
            'idexpositores'=>(isset($datosFormulario['idexpositores'])) ? $datosFormulario['idexpositores'] : 0,
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
            'orden'=> $this->objEmpresasTable->obtenerUltimoOrdenEmpresasPorZona($datosFormulario['idzonas']),
            'idstandgaleria'=> $datosFormulario['idstandgaleria'],
            'banner_izquierda_1'=> @$datosFormulario['banner_izquierda_1'],
            'banner_izquierda_2'=> @$datosFormulario['banner_izquierda_2'],
            'banner_derecha_1'=> @$datosFormulario['banner_derecha_1'],
            'banner_derecha_2'=> @$datosFormulario['banner_derecha_2'],
            'idbancos'=> $idbancos,
            'titulo_boton_envivo'=> @$datosFormulario['titulo_boton_envivo'],
            'config_estado_boton_envivo'=> $estadoBotonEnVivo,
            'stand_config_estado_rv'=> $standEstadoRv,
            'idsegmentos'=> !empty(@$datosFormulario['idsegmentos']) ? $datosFormulario['idsegmentos'] : 0,
            'resumen'=> @$datosFormulario['resumen'],
            'descripcion'=> @$datosFormulario['descripcion'],
        ];

        ////////// SCRIPT PARA GUARDAR IMAGEN [INICIO] //////////
        $carpetaEmpresasLogo = getcwd().'/public/empresas/logo';
        $datosImagenLogo = [];
        $datosImagenLogo['id'] = $this->objTools->toAscii($datosFormulario['nombre']); //md5(uniqid());
        if( $imagenLogo['size'] !== 0 ) {
            $datosImagenLogo['extension'] = strtolower(pathinfo($imagenLogo['name'])['extension']);
            if( in_array($datosImagenLogo['extension'], $this->imagenExtensionesValidas) ) {
                $datosImagenLogo['nombre_completo'] = $datosImagenLogo['id'].'.'.$datosImagenLogo['extension'];
                $datosImagenLogo['nombre_original'] = $imagenLogo['name'];
                if(move_uploaded_file($imagenLogo['tmp_name'], $carpetaEmpresasLogo.'/'.$datosImagenLogo['nombre_completo'])){
                    $data['hash_logo'] = $datosImagenLogo['nombre_completo'];
                    $data['nombre_logo'] = $datosImagenLogo['nombre_original'];
                }
            }
        }
        ////////// SCRIPT PARA GUARDAR IMAGEN [FIN] //////////

        $this->objEmpresasTable->agregarEmpresas($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarEmpresasAction(){
        $idempresas = $this->params()->fromQuery('idempresas');
        $dataEmpresas = $this->objEmpresasTable->obtenerDatoEmpresas(['idempresas'=> $idempresas]);
        $dataEmpresas['zonas'] = $this->objZonasTable->obtenerZonas($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']);
        $dataEmpresas['stand'] = $this->objStandTable->obtenerStand();
        $dataEmpresas['standSelected'] = $this->objStandTable->obtenerDatoStand(['idstand'=> $dataEmpresas['idstand']]);
        $dataEmpresas['expositores'] = $this->objExpositoresTable->obtenerExpositores($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']);
        $dataEmpresas['bancos'] = $this->objBancosTable->obtenerBancos();
        $dataEmpresas['segmentos'] = $this->objSegmentosTable->obtenerSegmentos();
        return $this->consoleZF($dataEmpresas);
    }
    
    public function guardarEditarEmpresasAction(){
        $imagenLogo = $this->params()->fromFiles('logo');
        $idempresas = $this->params()->fromQuery('idempresas');
        $datosFormulario = $this->params()->fromPost();
        $dataEmpresa = $this->objEmpresasTable->obtenerDatoEmpresas(['idempresas'=> $idempresas]);
        $idbancos = (isset($datosFormulario['idbancos']) && $datosFormulario['idbancos'] != '')?$datosFormulario['idbancos']:0;
        $estadoBotonEnVivo = (isset($datosFormulario['config_estado_boton_envivo'])) ? $datosFormulario['config_estado_boton_envivo'] : 0;
        $standEstadoRv = (isset($datosFormulario['stand_config_estado_rv'])) ? $datosFormulario['stand_config_estado_rv'] : 0;
        $data = [
            'idzonas'=>$datosFormulario['idzonas'],
            'nombre'=>$datosFormulario['nombre'],
            'enlace_video'=>$datosFormulario['enlace_video'],
            'enlace_video_2'=>$datosFormulario['enlace_video_2'],
            'enlace_wsp'=>$datosFormulario['enlace_wsp'],
            'enlace_web'=>$datosFormulario['enlace_web'],
            'enlace_rv'=>$datosFormulario['enlace_rv'],
            'idstand'=>$datosFormulario['idstand'],
            'idexpositores'=>(isset($datosFormulario['idexpositores'])) ? $datosFormulario['idexpositores'] : 0,
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
            'idstandgaleria'=> $datosFormulario['idstandgaleria'],
            'banner_izquierda_1'=> @$datosFormulario['banner_izquierda_1'],
            'banner_izquierda_2'=> @$datosFormulario['banner_izquierda_2'],
            'banner_derecha_1'=> @$datosFormulario['banner_derecha_1'],
            'banner_derecha_2'=> @$datosFormulario['banner_derecha_2'],
            'idbancos'=> $idbancos,
            'titulo_boton_envivo'=> @$datosFormulario['titulo_boton_envivo'],
            'config_estado_boton_envivo'=> $estadoBotonEnVivo,
            'stand_config_estado_rv'=> $standEstadoRv,
            'idsegmentos'=> !empty(@$datosFormulario['idsegmentos']) ? $datosFormulario['idsegmentos'] : 0,
            'resumen'=> @$datosFormulario['resumen'],
            'descripcion'=> @$datosFormulario['descripcion'],
        ];
        if($datosFormulario['idzonas'] != $dataEmpresa['idzonas']){
            $data['orden'] = $this->objEmpresasTable->obtenerUltimoOrdenEmpresasPorZona($datosFormulario['idzonas']);
        }
        ////////// SCRIPT PARA ACTUALIZAR IMAGEN [INICIO] //////////
        $carpetaEmpresasLogo = getcwd().'/public/empresas/logo';
        $datosImagenLogo = [];
        $datosImagenLogo['id'] = $this->objTools->toAscii($datosFormulario['nombre']);
        if( !empty($imagenLogo['name']) && $imagenLogo['size'] !== 0 ) {
            $dataEmpresas = $this->objEmpresasTable->obtenerDatoEmpresas(['idempresas'=> $idempresas]);
            if( $dataEmpresas ) {
                if(file_exists($carpetaEmpresasLogo.'/'.$dataEmpresas['hash_logo'])){
                    @unlink($carpetaEmpresasLogo.'/'.$dataEmpresas['hash_logo']);
                }
                $datosImagenLogo['extension'] = strtolower(pathinfo($imagenLogo['name'])['extension']);
                if( in_array($datosImagenLogo['extension'], $this->imagenExtensionesValidas) ) {
                    $datosImagenLogo['nombre_completo'] = $datosImagenLogo['id'].'.'.$datosImagenLogo['extension'];
                    $datosImagenLogo['nombre_original'] = $imagenLogo['name'];
                    if(move_uploaded_file($imagenLogo['tmp_name'], $carpetaEmpresasLogo.'/'.$datosImagenLogo['nombre_completo'])){
                        $data['hash_logo'] = $datosImagenLogo['nombre_completo'];
                        $data['nombre_logo'] = $datosImagenLogo['nombre_original'];
                    }
                }
            }
        }
        ////////// SCRIPT PARA ACTUALIZAR IMAGEN [FIN] //////////
        $this->objEmpresasTable->actualizarDatosEmpresas($data, $idempresas);
        if($datosFormulario['idzonas'] != $dataEmpresa['idzonas'])$this->objEmpresasTable->reordenarOrdenEmpresasPorZona($dataEmpresa['idzonas']);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarEmpresasAction(){
        $idempresas = $this->params()->fromQuery('idempresas');
        $dataEmpresas = $this->objEmpresasTable->obtenerDatoEmpresas(['idempresas'=> $idempresas]);
        return $this->consoleZF($dataEmpresas);
    }
    
    public function confirmarEliminarEmpresasAction(){
        $idempresas = $this->params()->fromQuery('idempresas');
        $idzonas = $this->params()->fromQuery('idzonas');
        $dataEmpresas = $this->objEmpresasTable->obtenerDatoEmpresas(['idempresas'=> $idempresas]);
        $carpetaEmpresasLogo = getcwd().'/public/empresas/logo';
        if( $dataEmpresas ) {
            if(file_exists($carpetaEmpresasLogo.'/'.$dataEmpresas['hash_logo'])){
                @unlink($carpetaEmpresasLogo.'/'.$dataEmpresas['hash_logo']);
            }
        }
        $this->objEmpresasTable->eliminarEmpresas($idempresas);
        $this->objEmpresasTable->reordenarOrdenEmpresasPorZona($idzonas);
        return $this->jsonZF(['result'=>'success']);
    }

    public function guardarOrdenEmpresasAction(){
        $datosFormulario = $this->params()->fromPost();
        $dataOrden = $datosFormulario['orden'];
        print_r($dataOrden);
        if(!empty($dataOrden)){
            foreach($dataOrden as $item) {
                //$this->objPaginasTable->actualizarDatosPaginas(['orden'=> $item['orden']], $item['ids']);
            }
        }
        return $this->jsonZF(['result'=>'success']);
    }

    public function modelosStandAction() {
        $idstand = $this->params()->fromQuery('idstand');
        $data = $this->objStandGaleriaTable->obtenerDatosStandGaleria($idstand);
        return $this->consoleZF(['modelos'=> $data]);
    }

    public function paginasFeriasAction(){
        $idferias = $this->params()->fromQuery('idferias');
        $dataFerias = $this->objFeriasTable->obtenerDatoFerias(['idferias'=> $idferias]);
        return $this->consoleView($dataFerias);
    }
    
    public function listarPaginasFeriasAction(){
        $idferias = $this->params()->fromQuery('idferias');
        $dataFerias = $this->objFeriasTable->obtenerDatoFerias(['idferias'=> $idferias]);
        $dataPaginas = $this->objPlanesPaginasTable->obtenerPaginasPorIdPlanes($dataFerias['idplanes']);
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataPaginas as $item){
            $data_out['data'][] = [
                $item['pagina'],
                '<a class="btn btn-sm btn-dark" href="/cliente/paginas-ferias-configuracion/'.$idferias.'/'.$item['idpaginas'].'"><i class="fas fa-cogs"></i> <span class="hidden-xs">Personalizar</span></a>'
            ];
        }
        return $this->jsonZF($data_out);
    }

    public function agregarPaginasFeriasAction(){
        $data=[
            'paginas'=> $this->objPaginasTable->obtenerPaginas(),
            'ferias'=> $this->objFeriasTable->obtenerFerias($this->sessionContainer['idferias'], $this->sessionContainer['idperfil'])
        ];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarPaginasFeriasAction(){
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'idpaginas'=>$datosFormulario['idpaginas'],
            'idferias'=>$datosFormulario['idferias']
        ];
        $this->objPaginasFeriasTable->agregarPaginasFerias($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarPaginasFeriasAction(){
        $idpaginasferias = $this->params()->fromQuery('idpaginasferias');
        $dataPaginas = $this->objPaginasFeriasTable->obtenerDatoPaginasFerias(['idpaginasferias'=> $idpaginasferias]);
        $dataPaginas['paginas'] = $this->objPaginasTable->obtenerPaginas();
        $dataPaginas['ferias'] = $this->objFeriasTable->obtenerFerias($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']);
        return $this->consoleZF($dataPaginas);
    }
    
    public function guardarEditarPaginasFeriasAction(){
        $idpaginasferias = $this->params()->fromQuery('idpaginasferias');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'idpaginas'=>$datosFormulario['idpaginas'],
            'idferias'=>$datosFormulario['idferias']
        ];
        $this->objPaginasFeriasTable->actualizarDatosPaginasFerias($data, $idpaginasferias);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarPaginasFeriasAction(){
        $idpaginasferias = $this->params()->fromQuery('idpaginasferias');
        $dataPaginas = $this->objPaginasFeriasTable->obtenerDatoPaginasFerias(['idpaginasferias'=> $idpaginasferias]);
        return $this->consoleZF($dataPaginas);
    }
    
    public function confirmarEliminarPaginasFeriasAction(){
        $idpaginasferias = $this->params()->fromQuery('idpaginasferias');
        $this->objPaginasFeriasTable->eliminarPaginasFerias($idpaginasferias);
        return $this->jsonZF(['result'=>'success']);
    }

    public function paginasFeriasConfiguracionAction(){
        $this->layout()->setTemplate('layout/feria');
        $idferias = $this->params()->fromRoute('idferias', 0);
        $idpaginas = $this->params()->fromRoute('idpaginas', 0);
        $dataFeria = $this->objFeriasTable->obtenerDatoFerias(['idferias'=> $idferias]);
        $dataPagina = $this->objPaginasTable->obtenerDatoPaginas(['idpaginas'=> $idpaginas]);
        if( $idferias === 0 || $idpaginas === 0 || !$dataFeria || !$dataPagina){
            return $this->redirect()->toRoute('cliente', ['action' => 'ferias']);
        }
        $dataCliente = $this->objClientesTable->obtenerDatoClientes(['idclientes'=> $dataFeria['idclientes']]);
        $dataPaginasFerias = $this->objPaginasFeriasTable->obtenerDatoPaginasFerias(['idpaginas'=> $idpaginas, 'idferias'=> $idferias]);
        $dataConfiguracion = ( $dataPaginasFerias['configuracion'] != '') ? json_decode($dataPaginasFerias['configuracion'], true) : [];
        $dataPlanesPaginas = $this->objPlanesPaginasTable->obtenerPaginasPorIdPlanes($dataFeria['idplanes']);
        $data = [
            'idpaginasferias'=> $dataPaginasFerias['idpaginasferias'],
            'configuracion'=> $dataConfiguracion,
            'feria'=> $dataFeria,
            'pagina'=> $dataPagina,
            'cliente'=> $dataCliente,
            'planes_paginas'=> $dataPlanesPaginas
        ];
        return $this->consoleView($data);
    }
    public function paginasZonasConfiguracionAction(){
        $this->layout()->setTemplate('layout/feria');
        $idzonas = $this->params()->fromRoute('idzonas', 0);
        if(!$idzonas)return $this->redirect()->toRoute('cliente', ['action'=> 'zonas']);
        $dataZonas = $this->objZonasTable->obtenerDatoZonas(['idzonas'=> $idzonas]);
        $dataFeria = $this->objFeriasTable->obtenerDatoFerias(['idferias'=> $dataZonas['idferias']]);
        $dataCliente = $this->objClientesTable->obtenerDatoClientes(['idclientes'=> $dataFeria['idclientes']]);
        $dataListaZonas = $this->objZonasTable->obtenerDatosZonas($dataFeria['idferias']);
        $dataPaginasZonas = $this->objPaginasZonasTable->obtenerDatoPaginasZonas(['idzonas'=> $idzonas]);
        $dataConfiguracion = ( $dataPaginasZonas['configuracion'] != '') ? json_decode($dataPaginasZonas['configuracion'], true) : [];
        $dataEmpresas = $this->objEmpresasTable->obtenerEmpresasGalerias($idzonas);
        $data = [
            'idpaginaszonas'=> $dataPaginasZonas['idpaginaszonas'],
            'configuracion'=> $dataConfiguracion,
            'zona'=> $dataZonas,
            'feria'=> $dataFeria,
            'cliente'=> $dataCliente,
            'listaZonas'=> $dataListaZonas,
            'empresas'=> $dataEmpresas
        ];
        //print_r($data['empresas']);
        return $this->consoleView($data);
    }

    public function guardarParametrosZonaAction(){
        $idzonas = $this->params()->fromPost('idzonas');
        $idpaginaszonas = $this->params()->fromPost('idpaginaszonas');
        $dataImagenes = $this->params()->fromFiles();
        $plantilla = $this->params()->fromPost('plantilla');
        $banner_opciones = $this->params()->fromPost('banner_opciones');
        $directorio = getcwd().'/public/paginas';
        $directorio = $directorio."/".$plantilla;
        if(!empty($dataImagenes)){
            foreach($dataImagenes as $idimagen => $archivo){
                $dataActualizarConfiguracion = [];
                $dataConfiguracion = [];
                $data = [];
                $dataPaginasZonas = $this->objPaginasZonasTable->obtenerDatoPaginasZonas(['idpaginaszonas'=> $idpaginaszonas]);
                if($dataPaginasZonas)$dataConfiguracion = ($dataPaginasZonas['configuracion'] != '') ? json_decode($dataPaginasZonas['configuracion'], true) : [];
                if($archivo['name'] != '' && $archivo['size'] > 0){
                    if(empty($dataConfiguracion))$dataArchivoImagen = $this->guardarImagenZona($directorio, $archivo);
                    else $dataArchivoImagen = $this->guardarImagenZona($directorio, $archivo, @$dataConfiguracion[$idimagen]['hash']);
                    $data[$idimagen] = ['hash'=> $dataArchivoImagen['hash_logo'], 'nombre'=> $dataArchivoImagen['nombre_logo']];
                }
                if(isset($banner_opciones)){
                    $data['banner_opciones'] = json_decode($banner_opciones);
                }
                $dataActualizarConfiguracion['configuracion'] = json_encode(array_merge($dataConfiguracion, $data));
                if($dataPaginasZonas){
                    $this->objPaginasZonasTable->actualizarDatosPaginasZonas($dataActualizarConfiguracion, $idpaginaszonas);
                } else {
                    $dataActualizarConfiguracion['idzonas'] = $idzonas;
                    $idpaginaszonas = $this->objPaginasZonasTable->agregarPaginasZonas($dataActualizarConfiguracion);
                }
            }
        }
        return $this->jsonZF(['result'=>'success', 'idpaginaszonas'=> $idpaginaszonas]);
    }
    public function paginasStandConfiguracionAction(){
        $this->layout()->setTemplate('layout/feria');
        $idempresas = $this->params()->fromRoute('idempresas', 0);
        if(!$idempresas)return $this->redirect()->toRoute('cliente', ['action'=> 'empresas']);
        $dataEmpresas = $this->objEmpresasTable->obtenerDatoEmpresas(['idempresas'=> $idempresas]);
        $plantillaSeleccionado = '';
        if((int)$dataEmpresas['idstandgaleria']){
            $dataStandGaleria = $this->objStandGaleriaTable->obtenerDatoStandGaleria(['idstandgaleria'=> $dataEmpresas['idstandgaleria']]);
            $dataStand = $this->objStandTable->obtenerDatoStand(['idstand'=> $dataStandGaleria['idstand']]);
            $plantillaSeleccionado = mb_strtolower($dataStand['hash_url']);
        }
        $dataZonas = $this->objZonasTable->obtenerDatoZonas(['idzonas'=> $dataEmpresas['idzonas']]);
        $dataFeria = $this->objFeriasTable->obtenerDatoFerias(['idferias'=> $dataZonas['idferias']]);
        $dataCliente = $this->objClientesTable->obtenerDatoClientes(['idclientes'=> $dataFeria['idclientes']]);
        $dataPaginasStand = $this->objPaginasStandTable->obtenerDatoPaginasStand(['idempresas'=> $idempresas]);
        $dataConfiguracion = !empty($dataPaginasStand['configuracion']) ? json_decode($dataPaginasStand['configuracion'], true) : [];
        $dataBanco = $this->objBancosTable->obtenerDatoBancos(['idbancos'=> $dataEmpresas['idbancos']]);
        $data = [
            'idpaginasstand'=> $dataPaginasStand['idpaginasstand'] ?? null,
            'configuracion'=> $dataConfiguracion,
            'feria'=> $dataFeria,
            'cliente'=> $dataCliente,
            'empresa'=> $dataEmpresas,
            'listaEmpresas'=> $this->objEmpresasTable->obtenerEmpresas($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']),
            'plantillaSeleccionado'=> $plantillaSeleccionado,
            'banco'=> $dataBanco
        ];
        //print_r($data['banco']);
        return $this->consoleView($data);
    }

    public function guardarParametrosStandAction(){
        $idempresas = $this->params()->fromPost('idempresas');
        $idpaginasstand = $this->params()->fromPost('idpaginasstand');
        $plantilla = $this->params()->fromPost('plantilla');
        $dataImagenes = $this->params()->fromFiles();
        $directorio = getcwd().'/public/paginas';
        $tv_texto_1 = $this->params()->fromPost('tv_texto_1');

        switch($plantilla) {
            case 'stand-big':
            case 'stand-circular':
            case 'stand-standar':
            case 'stand-vip':
            case 'stand-big-scotiabank':
            case 'stand-circular-portal':
                $directorio = $directorio."/".$plantilla;
                if(!empty($dataImagenes)){
                    foreach($dataImagenes as $idimagen => $archivo){
                        $dataActualizarConfiguracion = [];
                        $dataConfiguracion = [];
                        $data = [];
                        $dataPaginasStand = $this->objPaginasStandTable->obtenerDatoPaginasStand(['idpaginasstand'=> $idpaginasstand]);
                        if($dataPaginasStand)$dataConfiguracion = ( $dataPaginasStand['configuracion'] != '') ? json_decode($dataPaginasStand['configuracion'], true) : [];
                        if($archivo['name'] != '' && $archivo['size'] > 0){
                            if(empty($dataConfiguracion))$dataArchivoImagen = $this->guardarImagenPagina($directorio, $archivo);
                            else $dataArchivoImagen = $this->guardarImagenPagina($directorio, $archivo, @$dataConfiguracion[$idimagen]['hash']);
                            $data[$idimagen] = ['hash'=> $dataArchivoImagen['hash_logo'], 'nombre'=> $dataArchivoImagen['nombre_logo']];
                        }
                        if(isset($tv_texto_1)){
                            $data['tv_texto_1'] = $tv_texto_1;
                        }
                        $dataActualizarConfiguracion['configuracion'] = json_encode(array_merge($dataConfiguracion, $data));
                        if($dataPaginasStand){
                            $this->objPaginasStandTable->actualizarDatosPaginasStand($dataActualizarConfiguracion, $idpaginasstand);
                        } else {
                            $dataActualizarConfiguracion['idempresas'] = $idempresas;
                            $idpaginasstand = $this->objPaginasStandTable->agregarPaginasStand($dataActualizarConfiguracion);
                        }
                    }
                }
            break;
        }

        return $this->jsonZF(['result'=>'success', 'idpaginasstand'=> $idpaginasstand]);
    }
    private function guardarImagenZona($directorio, $archivo, $hashimagen = null){
        $data = [];
        $data['hash_logo'] = '';
        $data['nombre_logo'] = '';
        $datosArchivo = [];
        $datosArchivo['id'] = md5(uniqid());
        if($hashimagen != null){
            if(file_exists($directorio.'/'.$hashimagen)){
                @unlink($directorio.'/'.$hashimagen);
            }
        }
        $datosArchivo['extension'] = strtolower(pathinfo($archivo['name'])['extension']);
        if( in_array($datosArchivo['extension'], $this->imagenExtensionesValidas) ) {
            $datosArchivo['nombre_completo'] = $datosArchivo['id'].'.'.$datosArchivo['extension'];
            $datosArchivo['nombre_original'] = $archivo['name'];
            if(move_uploaded_file($archivo['tmp_name'], $directorio.'/'.$datosArchivo['nombre_completo'])){
                $data['hash_logo'] = $datosArchivo['nombre_completo'];
                $data['nombre_logo'] = $datosArchivo['nombre_original'];
            }
        }
        return $data;
    }

    private function guardarImagenStand($directorio, $archivo, $hashimagen = null){
        $data = [];
        $data['hash_logo'] = '';
        $data['nombre_logo'] = '';
        $datosArchivo = [];
        $datosArchivo['id'] = md5(uniqid());
        if($hashimagen != null){
            if(file_exists($directorio.'/'.$hashimagen)){
                @unlink($directorio.'/'.$hashimagen);
            }
        }
        $datosArchivo['extension'] = strtolower(pathinfo($archivo['name'])['extension']);
        if( in_array($datosArchivo['extension'], $this->imagenExtensionesValidas) ) {
            $datosArchivo['nombre_completo'] = $datosArchivo['id'].'.'.$datosArchivo['extension'];
            $datosArchivo['nombre_original'] = $archivo['name'];
            if(move_uploaded_file($archivo['tmp_name'], $directorio.'/'.$datosArchivo['nombre_completo'])){
                $data['hash_logo'] = $datosArchivo['nombre_completo'];
                $data['nombre_logo'] = $datosArchivo['nombre_original'];
            }
        }
        return $data;
    }

    private function fileUploadErros($errorValue, $upload_max_size) {
        $phpFileUploadErrors = [
            0 => 'There is no error, the file uploaded with success',
            1 => 'Exceeds php.ini upload_max_filesize of '.$upload_max_size.'MB',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        ];
        return $phpFileUploadErrors[$errorValue];
    }

    public function guardarParametrosPaginaAction(){
        $idpaginas = $this->params()->fromPost('idpaginas');
        $idferias = $this->params()->fromPost('idferias');
        $idpaginasferias = $this->params()->fromPost('idpaginasferias');
        $plantilla = $this->params()->fromPost('plantilla');
        $dataImagenes = $this->params()->fromFiles();
        $directorio = getcwd().'/public/paginas';

        switch($plantilla) {
            case 'home':
            case 'registro':
            case 'recepcion':
                $directorio = $directorio."/".$plantilla;
                if(!empty($dataImagenes)){
                    foreach($dataImagenes as $idimagen => $archivo){
                        //echo $this->fileUploadErros($archivo['error'], $archivo['size']);
                        $dataActualizarConfiguracion = [];
                        $dataConfiguracion = [];
                        $data = [];
                        $dataPaginasFerias = $this->objPaginasFeriasTable->obtenerDatoPaginasFerias(['idpaginasferias'=> $idpaginasferias]);
                        if($dataPaginasFerias)$dataConfiguracion = ( $dataPaginasFerias['configuracion'] != '') ? json_decode($dataPaginasFerias['configuracion'], true) : [];
                        if($archivo['name'] != '' && $archivo['size'] > 0){
                            if(empty($dataConfiguracion))$dataArchivoImagen = $this->guardarImagenPagina($directorio, $archivo);
                            else $dataArchivoImagen = $this->guardarImagenPagina($directorio, $archivo, @$dataConfiguracion[$idimagen]['hash']);
                            $data[$idimagen] = ['hash'=> $dataArchivoImagen['hash_logo'], 'nombre'=> $dataArchivoImagen['nombre_logo']];
                        }
                        $dataActualizarConfiguracion['configuracion'] = json_encode(array_merge($dataConfiguracion, $data));
                        if($dataPaginasFerias){
                            $this->objPaginasFeriasTable->actualizarDatosPaginasFerias($dataActualizarConfiguracion, $idpaginasferias);
                        } else {
                            $dataActualizarConfiguracion['idpaginas'] = $idpaginas;
                            $dataActualizarConfiguracion['idferias'] = $idferias;
                            $idpaginasferias = $this->objPaginasFeriasTable->agregarPaginasFerias($dataActualizarConfiguracion);
                        }
                    }
                }
            break;
            case 'hall':
            case 'hall-conferencias':
                $auditorio_opciones = $this->params()->fromPost('hall_conferencias_opciones');
                $banner_opciones = $this->params()->fromPost('banner_opciones');
                $directorio = $directorio."/".$plantilla;
                if(!empty($dataImagenes)){
                    foreach($dataImagenes as $idimagen => $archivo){
                        $dataActualizarConfiguracion = [];
                        $dataConfiguracion = [];
                        $data = [];
                        $dataPaginasFerias = $this->objPaginasFeriasTable->obtenerDatoPaginasFerias(['idpaginasferias'=> $idpaginasferias]);
                        if($dataPaginasFerias)$dataConfiguracion = ( $dataPaginasFerias['configuracion'] != '') ? json_decode($dataPaginasFerias['configuracion'], true) : [];
                        if($archivo['name'] != '' && $archivo['size'] > 0){
                            if(empty($dataConfiguracion))$dataArchivoImagen = $this->guardarImagenPagina($directorio, $archivo);
                            else $dataArchivoImagen = $this->guardarImagenPagina($directorio, $archivo, @$dataConfiguracion[$idimagen]['hash']);
                            $data[$idimagen] = ['hash'=> $dataArchivoImagen['hash_logo'], 'nombre'=> $dataArchivoImagen['nombre_logo']];
                        }
                        if(isset($auditorio_opciones) && !empty(json_decode($auditorio_opciones))){
                            $data['hall_conferencias_opciones'] = json_decode($auditorio_opciones);
                        }
                        if(isset($banner_opciones)){
                            $data['banner_opciones'] = json_decode($banner_opciones);
                        }
                        $dataActualizarConfiguracion['configuracion'] = json_encode(array_merge($dataConfiguracion, $data));
                        if($dataPaginasFerias){
                            $this->objPaginasFeriasTable->actualizarDatosPaginasFerias($dataActualizarConfiguracion, $idpaginasferias);
                        } else {
                            $dataActualizarConfiguracion['idpaginas'] = $idpaginas;
                            $dataActualizarConfiguracion['idferias'] = $idferias;
                            $idpaginasferias = $this->objPaginasFeriasTable->agregarPaginasFerias($dataActualizarConfiguracion);
                        }
                    }
                }
            break;
        }

        return $this->jsonZF(['result'=>'success', 'idpaginasferias'=> $idpaginasferias]);
    }

    private function guardarImagenPagina($directorio, $archivo, $hashimagen = null){
        $data = [];
        $data['hash_logo'] = '';
        $data['nombre_logo'] = '';
        $datosArchivo = [];
        $datosArchivo['id'] = md5(uniqid());
        if($hashimagen != null){
            if(file_exists($directorio.'/'.$hashimagen)){
                @unlink($directorio.'/'.$hashimagen);
            }
        }
        $datosArchivo['extension'] = strtolower(pathinfo($archivo['name'])['extension']);
        if( in_array($datosArchivo['extension'], $this->imagenExtensionesValidas) ) {
            $datosArchivo['nombre_completo'] = $datosArchivo['id'].'.'.$datosArchivo['extension'];
            $datosArchivo['nombre_original'] = $archivo['name'];
            if(move_uploaded_file($archivo['tmp_name'], $directorio.'/'.$datosArchivo['nombre_completo'])){
                $data['hash_logo'] = $datosArchivo['nombre_completo'];
                $data['nombre_logo'] = $datosArchivo['nombre_original'];
            }
        }
        return $data;
    }

    public function conferenciasAction(){
    
    }
    
    public function listarConferenciasAction(){
        $dataConferencias = $this->objConferenciasTable->obtenerConferencias($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']);
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataConferencias as $item){
            $data_out['data'][] = [
                $item['titulo'],
                '<div class="btn btn-sm btn-info pop-up" href="/cliente/editar-conferencias?idconferencias='.$item['idconferencias'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="btn btn-sm btn-danger pop-up" href="/cliente/eliminar-conferencias?idconferencias='.$item['idconferencias'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div>'
            ];
        }
        return $this->jsonZF($data_out);
    }
    
    public function agregarConferenciasAction(){
        $data=[
            'ferias'=> $this->objFeriasTable->obtenerFerias($this->sessionContainer['idferias'], $this->sessionContainer['idperfil'])
        ];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarConferenciasAction(){
        $imagenConferencia = $this->params()->fromFiles('imagen');
        $datosFormulario = $this->params()->fromPost();
        $idferiasSelected = (isset($datosFormulario['idferias'])) ? $datosFormulario['idferias'] : $this->sessionContainer['idferias'];
        $data = [
            'idferias'=>$idferiasSelected,
            'titulo'=>$datosFormulario['titulo'],
            'enlace'=>$datosFormulario['enlace'],
        ];

        ////////// SCRIPT PARA GUARDAR IMAGEN [INICIO] //////////
        $carpetaConferenciaImagen = getcwd().'/public/conferencias/imagen';
        $datosImagenConferencia = [];
        $datosImagenConferencia['id'] = md5(uniqid());
        if( $imagenConferencia['size'] !== 0 ) {
            $datosImagenConferencia['extension'] = strtolower(pathinfo($imagenConferencia['name'])['extension']);
            if( in_array($datosImagenConferencia['extension'], $this->imagenExtensionesValidas) ) {
                $datosImagenConferencia['nombre_completo'] = $datosImagenConferencia['id'].'.'.$datosImagenConferencia['extension'];
                $datosImagenConferencia['nombre_original'] = $imagenConferencia['name'];
                if(move_uploaded_file($imagenConferencia['tmp_name'], $carpetaConferenciaImagen.'/'.$datosImagenConferencia['nombre_completo'])){
                    $data['hash_imagen'] = $datosImagenConferencia['nombre_completo'];
                    $data['nombre_imagen'] = $datosImagenConferencia['nombre_original'];
                }
            }
        }
        ////////// SCRIPT PARA GUARDAR IMAGEN [FIN] //////////

        $this->objConferenciasTable->agregarConferencias($data);
        return $this->jsonZF(['result'=>'success', 'dataConferencias'=> $data]);
    }
    
    public function editarConferenciasAction(){
        $idconferencias = $this->params()->fromQuery('idconferencias');
        $dataConferencias = $this->objConferenciasTable->obtenerDatoConferencias(['idconferencias'=> $idconferencias]);
        $dataConferencias['ferias'] = $this->objFeriasTable->obtenerFerias($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']);
        return $this->consoleZF($dataConferencias);
    }
    
    public function guardarEditarConferenciasAction(){
        $imagenConferencia = $this->params()->fromFiles('imagen');
        $idconferencias = $this->params()->fromQuery('idconferencias');
        $datosFormulario = $this->params()->fromPost();
        $idferiasSelected = (isset($datosFormulario['idferias'])) ? $datosFormulario['idferias'] : $this->sessionContainer['idferias'];
        $data = [
            'idferias'=>$idferiasSelected,
            'titulo'=>$datosFormulario['titulo'],
            'enlace'=>$datosFormulario['enlace'],
        ];

        ////////// SCRIPT PARA ACTUALIZAR IMAGEN [INICIO] //////////
        $carpetaConferenciaImagen = getcwd().'/public/conferencias/imagen';
        $datosImagenConferencia = [];
        $datosImagenConferencia['id'] = md5(uniqid());
        if( !empty($imagenConferencia['name']) && $imagenConferencia['size'] !== 0 ) {
            $dataConferencias = $this->objConferenciasTable->obtenerDatoConferencias(['idconferencias'=> $idconferencias]);
            if( $dataConferencias ) {
                if(file_exists($carpetaConferenciaImagen.'/'.$dataConferencias['hash_imagen'])){
                    @unlink($carpetaConferenciaImagen.'/'.$dataConferencias['hash_imagen']);
                }
                $datosImagenConferencia['extension'] = strtolower(pathinfo($imagenConferencia['name'])['extension']);
                if( in_array($datosImagenConferencia['extension'], $this->imagenExtensionesValidas) ) {
                    $datosImagenConferencia['nombre_completo'] = $datosImagenConferencia['id'].'.'.$datosImagenConferencia['extension'];
                    $datosImagenConferencia['nombre_original'] = $imagenConferencia['name'];
                    if(move_uploaded_file($imagenConferencia['tmp_name'], $carpetaConferenciaImagen.'/'.$datosImagenConferencia['nombre_completo'])){
                        $data['hash_imagen'] = $datosImagenConferencia['nombre_completo'];
                        $data['nombre_imagen'] = $datosImagenConferencia['nombre_original'];
                    }
                }
            }
        }
        ////////// SCRIPT PARA ACTUALIZAR IMAGEN [FIN] //////////

        $this->objConferenciasTable->actualizarDatosConferencias($data, $idconferencias);
        return $this->jsonZF(['result'=>'success', 'dataConferencias'=> $data]);
    }
    
    public function eliminarConferenciasAction(){
        $idconferencias = $this->params()->fromQuery('idconferencias');
        $dataConferencias = $this->objConferenciasTable->obtenerDatoConferencias(['idconferencias'=> $idconferencias]);
        return $this->consoleZF($dataConferencias);
    }
    
    public function confirmarEliminarConferenciasAction(){
        $idconferencias = $this->params()->fromQuery('idconferencias');
        $dataConferencias = $this->objConferenciasTable->obtenerDatoConferencias(['idconferencias'=> $idconferencias]);
        $carpetaConferenciaImagen = getcwd().'/public/conferencias/imagen';
        if( $dataConferencias ) {
            if(file_exists($carpetaConferenciaImagen.'/'.$dataConferencias['hash_imagen'])){
                @unlink($carpetaConferenciaImagen.'/'.$dataConferencias['hash_imagen']);
            }
        }
        $this->objConferenciasTable->eliminarConferencias($idconferencias);
        return $this->jsonZF(['result'=>'success']);
    }

    public function paginasAuditorioOpcionesAction() {
        $data=[
            'menusEncabezado'=> $this->objMenusTable->obtenerDatosMenus(['tipo'=> 'E'])
        ];
        return $this->consoleZF($data);
    }

    public function paginasBannerOpcionesAction() {
        $id = $this->params()->fromQuery('id');
        $accion = $this->params()->fromQuery('accion');
        $idferias = $this->params()->fromQuery('idferias');
        $dataEmpresas = [];
        switch($accion) {
            /*case 'zonas':
                $dataEmpresas = $this->objEmpresasTable->obtenerEmpresasGalerias($id);
            break;*/
            case 'zonas':
            case 'hall':
            case 'hall-conferencias':
                $resultado = $this->objEmpresasTable->obtenerEmpresasBannerOpciones($idferias);
                $dataEmpresas = array_map(
                    function($data) {
                        $data['orden_origin'] = $data['orden'];
                        $data['orden'] = $data['zona_orden']."|".$data['orden']."|".$data['hash_url'];
                        return $data;
                    },
                    $resultado
                );
                /*echo count($dataEmpresas);
                die;*/
            break;
        }
        $data = [
            'empresas'=> $dataEmpresas
        ];
        //print_r($data['empresas']);
        return $this->consoleZF($data);
    }

    public function expositoresproductosAction(){
        $idexpositores = $this->params()->fromQuery('idexpositores');
        $data = [
            'idexpositores'=> $idexpositores
        ];
        return $this->consoleView($data);
    }
    
    public function listarExpositoresProductosAction(){
        $idexpositores = $this->params()->fromQuery('idexpositores');
        $dataExpositoresProductos = $this->objExpositoresProductosTable->obtenerExpositoresProductos($idexpositores);
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataExpositoresProductos as $item){
            $data_out['data'][] = [
                $item['nombre'],
                '<div class="btn btn-sm btn-info pop-up" href="/cliente/editar-expositores-productos?idexpositoresproductos='.$item['idexpositoresproductos'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="btn btn-sm btn-danger pop-up" href="/cliente/eliminar-expositores-productos?idexpositoresproductos='.$item['idexpositoresproductos'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div>'
            ];
        }
        return $this->jsonZF($data_out);
    }

    public function agregarExpositoresProductosAction(){
        $idexpositores = $this->params()->fromQuery('idexpositores');
        $data=[
            'idexpositores'=> $idexpositores
        ];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarExpositoresProductosAction(){
        $imagenProducto = $this->params()->fromFiles('imagen');
        $idexpositores = $this->params()->fromQuery('idexpositores');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'idexpositores'=>$idexpositores
        ];
        ////////// SCRIPT PARA GUARDAR IMAGEN [INICIO] //////////
        $carpetaProductoImagen = getcwd().'/public/expositores/productos/imagen';
        $datosImagenProducto = [];
        $datosImagenProducto['id'] = md5(uniqid());
        if( $imagenProducto['size'] !== 0 ) {
            $datosImagenProducto['extension'] = strtolower(pathinfo($imagenProducto['name'])['extension']);
            if( in_array($datosImagenProducto['extension'], $this->imagenExtensionesValidas) ) {
                $datosImagenProducto['nombre_completo'] = $datosImagenProducto['id'].'.'.$datosImagenProducto['extension'];
                $datosImagenProducto['nombre_original'] = $imagenProducto['name'];
                if(move_uploaded_file($imagenProducto['tmp_name'], $carpetaProductoImagen.'/'.$datosImagenProducto['nombre_completo'])){
                    $data['hash_imagen'] = $datosImagenProducto['nombre_completo'];
                    $data['nombre_imagen'] = $datosImagenProducto['nombre_original'];
                }
            } else {
                return $this->jsonZF(['result'=>'formato_incorrecto']);   
            }
        }
        ////////// SCRIPT PARA GUARDAR IMAGEN [FIN] //////////
        $this->objExpositoresProductosTable->agregarExpositoresProductos($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarExpositoresProductosAction(){
        $idexpositoresproductos = $this->params()->fromQuery('idexpositoresproductos');
        $dataExpositoresProductos = $this->objExpositoresProductosTable->obtenerDatoExpositoresProductos(['idexpositoresproductos'=> $idexpositoresproductos]);
        return $this->consoleZF($dataExpositoresProductos);
    }
    
    public function guardarEditarExpositoresProductosAction(){
        $imagenProducto = $this->params()->fromFiles('imagen');
        $idexpositoresproductos = $this->params()->fromQuery('idexpositoresproductos');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre']
        ];
        ////////// SCRIPT PARA ACTUALIZAR IMAGEN [INICIO] //////////
        $carpetaProductoImagen = getcwd().'/public/expositores/productos/imagen';
        $datosImagenProducto = [];
        $datosImagenProducto['id'] = md5(uniqid());
        if( !empty($imagenProducto['name']) && $imagenProducto['size'] !== 0 ) {
            $dataExpositoresProductos = $this->objExpositoresProductosTable->obtenerDatoExpositoresProductos(['idexpositoresproductos'=> $idexpositoresproductos]);
            if( $dataExpositoresProductos ) {
                $datosImagenProducto['extension'] = strtolower(pathinfo($imagenProducto['name'])['extension']);
                if( in_array($datosImagenProducto['extension'], $this->imagenExtensionesValidas) ) {
                    if(file_exists($carpetaProductoImagen.'/'.$dataExpositoresProductos['hash_imagen'])){
                        @unlink($carpetaProductoImagen.'/'.$dataExpositoresProductos['hash_imagen']);
                    }
                    $datosImagenProducto['nombre_completo'] = $datosImagenProducto['id'].'.'.$datosImagenProducto['extension'];
                    $datosImagenProducto['nombre_original'] = $imagenProducto['name'];
                    if(move_uploaded_file($imagenProducto['tmp_name'], $carpetaProductoImagen.'/'.$datosImagenProducto['nombre_completo'])){
                        $data['hash_imagen'] = $datosImagenProducto['nombre_completo'];
                        $data['nombre_imagen'] = $datosImagenProducto['nombre_original'];
                    }
                } else {
                    return $this->jsonZF(['result'=>'formato_incorrecto']);   
                }
            }
        }
        ////////// SCRIPT PARA ACTUALIZAR IMAGEN [FIN] //////////
        $this->objExpositoresProductosTable->actualizarDatosExpositoresProductos($data, $idexpositoresproductos);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarExpositoresProductosAction(){
        $idexpositoresproductos = $this->params()->fromQuery('idexpositoresproductos');
        $dataExpositoresProductos = $this->objExpositoresProductosTable->obtenerDatoExpositoresProductos(['idexpositoresproductos'=> $idexpositoresproductos]);
        return $this->consoleZF($dataExpositoresProductos);
    }
    
    public function confirmarEliminarExpositoresProductosAction(){
        $idexpositoresproductos = $this->params()->fromQuery('idexpositoresproductos');
        $dataExpositoresProductos = $this->objExpositoresProductosTable->obtenerDatoExpositoresProductos(['idexpositoresproductos'=> $idexpositoresproductos]);
        $carpetaProductoImagen = getcwd().'/public/expositores/productos/imagen';
        if( $dataExpositoresProductos ) {
            if(file_exists($carpetaProductoImagen.'/'.$dataExpositoresProductos['hash_imagen'])){
                @unlink($carpetaProductoImagen.'/'.$dataExpositoresProductos['hash_imagen']);
            }
        }
        $this->objExpositoresProductosTable->eliminarExpositoresProductos($idexpositoresproductos);
        return $this->jsonZF(['result'=>'success']);
    }

    public function feriasVisitantesAction(){
        $idferias = $this->params()->fromQuery('idferias');
        $data=['idferias'=> $idferias];
        return $this->consoleZF($data);
    }

    public function listarFeriasVisitantesAction(){
        $idferias = $this->params()->fromQuery('idferias');
        $rango_fechas = $this->params()->fromQuery('rango_fechas');
        $dataFerias = $this->objFeriasTable->listarFeriasVisitantes($idferias, $rango_fechas);
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataFerias as $item){
            $data_out['data'][] = [
                $item['nombres'],
                $item['apellido_paterno'],
                $item['apellido_materno'],
                $item['correo'],
                $item['telefono'],
                $item['numero_documento'],
                $item['fecha_registro_web']
            ];
        }
        return $this->jsonZF($data_out);
    }

    public function descargarReporteFeriaVisitantesAction(){
        $idferias = $this->params()->fromPost('idferias');
        $rango_fechas = $this->params()->fromPost('rango_fechas');
        $dataFeriVisitantes = ( $idferias != '' ) ? $this->objFeriasTable->listarFeriasVisitantes($idferias, $rango_fechas) : [];
        $dataFeria = $this->objFeriasTable->obtenerDatoFerias(['idferias'=> $idferias]);

        $configFormulario = ( $dataFeria['config_formulario'] != '') ? json_decode($dataFeria['config_formulario'], true) : json_decode('[{"nombre":"Nombres","keyinput":"nombres","elemento":"input","opcion":[]},{"nombre":"Apellido Paterno","keyinput":"apellido_paterno","elemento":"input","opcion":[]},{"nombre":"Apellido Materno","keyinput":"apellido_materno","elemento":"input","opcion":[]},{"nombre":"Correo","keyinput":"correo","elemento":"input","opcion":[]},{"nombre":"Teléfono","keyinput":"telefono","elemento":"input","opcion":[]}]', true);

        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            $sheet->setCellValue('A1', 'Feria');
            $sheet->setCellValue('B1', 'Fecha Registro');
            $sheet->setCellValue('C1', 'Condición');
            $sheet->setCellValue('D1', 'N° Documento');

            $Cantidad_de_columnas_a_crear = count($configFormulario) - 1;
            $Contador = 0;
            $Letra = 'E';
            $listIdColumna = [];

            while($Contador<=$Cantidad_de_columnas_a_crear)
            {
                $sheet->setCellValue($Letra.'1', $configFormulario[$Contador]['nombre']);
                $listIdColumna[$Letra] = $configFormulario[$Contador]['keyinput'];
                $Contador++;
                $Letra++;
            }

            $sheet->getStyle("A1:".array_key_last($listIdColumna)."1")->getFont()->setBold( true );
            
            $i = 2;

            foreach( $dataFeriVisitantes as $item ) {
                $condicion = ( $item['condicion'] == 'S' ) ? 'SI' : 'NO';

                $sheet->setCellValue('A'.$i, $item['feria']);
                $sheet->setCellValue('B'.$i, date('d/m/Y H:i', strtotime($item['fecha_registro_web'])));
                $sheet->setCellValue('C'.$i, $condicion);
                $sheet->setCellValue('D'.$i,  $item['numero_documento']);

                foreach($listIdColumna as $col => $id) {
                    $sheet->setCellValue($col.$i, $item[$id]);
                }
                
                $i++;
            }
            
            $file = getcwd().'/public/tmp/reporte_visitantes.xlsx';
            $sheet = new Xlsx($spreadsheet);
            $sheet->save($file);
            
        } catch (PDOException $e) {
            return new JsonModel(['result'=> 'error', 'data'=> $e->getMessage()]);
        }

        return new JsonModel(['result'=> 'success']);
    }

    public function configurarFormulariosFeriasAction(){
        $idferias = $this->params()->fromQuery('idferias');
        $dataFeria = $this->objFeriasTable->obtenerDatoFerias(['idferias'=> $idferias]);
        $dataFeria['config_formulario'] = ( $dataFeria['config_formulario'] != '') ? json_decode($dataFeria['config_formulario'], true) : json_decode('[{"nombre":"Nombres","keyinput":"nombres","elemento":"input","opcion":[]},{"nombre":"Apellido Paterno","keyinput":"apellido_paterno","elemento":"input","opcion":[]},{"nombre":"Apellido Materno","keyinput":"apellido_materno","elemento":"input","opcion":[]},{"nombre":"Correo","keyinput":"correo","elemento":"input","opcion":[]},{"nombre":"Teléfono","keyinput":"telefono","elemento":"input","opcion":[]}]', true);
        return $this->consoleZF($dataFeria);
    }

    public function guardarConfigurarFormulariosFeriasAction(){
        $idferias = $this->params()->fromPost('idferias');
        $forms = $this->params()->fromPost('forms');
        $data = [
            'config_formulario'=> json_encode($forms)
        ];
        $this->objFeriasTable->actualizarDatosFerias($data, $idferias);
        //Guardar campos
        if(isset($forms) && !empty($forms)){
            $dataFields = [];
            foreach($forms as $form){
                $dataFields[] = trim($this->objTools->toAscii($form['keyinput'], '_'));
            }
            $this->objVisitantesTable->createFieldVisitantes($dataFields);
        }
        return $this->jsonZF(['result'=>'success']);
    }

    public function seoFeriasAction(){
        $idferias = $this->params()->fromQuery('idferias');
        $dataFeria = $this->objFeriasTable->obtenerDatoFerias(['idferias'=> $idferias]);
        $dataMenus = $this->objMenusTable->obtenerDatosMenus([]);
        $data = [
            'idferias'=> $idferias,
            'feria'=> $dataFeria,
            'menus'=> $dataMenus
        ];
        return $this->consoleZF($data);
    }

    public function obtenerSeoAction(){
        $idmenus = $this->params()->fromPost('idmenus');
        $idferias = $this->params()->fromPost('idferias');
        $dataSeo = $this->objSeoTable->obtenerDatoSeo(['idferias'=> $idferias, 'idmenus'=> $idmenus]);
        if($dataSeo){
            $dataSeo['keywords'] = ($dataSeo['keywords'] !== '') ? explode(',', $dataSeo['keywords']) : [];
        }
        return $this->jsonZF($dataSeo);
    }

    public function guardarSeoFeriasAction(){
        $datosFormulario = $this->params()->fromPost();
        $dataSeo = $this->objSeoTable->obtenerDatoSeo(['idferias'=> $datosFormulario['idferias'], 'idmenus'=> $datosFormulario['idmenus']]);
        $keywords = (isset($datosFormulario['keywords']) && $datosFormulario['keywords'] !== '') ? implode(',', $datosFormulario['keywords']) : '';
        $data = [
            'titulo'=> $datosFormulario['titulo'],
            'descripcion'=> $datosFormulario['descripcion'],
            'keywords'=> $keywords,
            'idferias'=> $datosFormulario['idferias'],
            'idmenus'=> $datosFormulario['idmenus']
        ];
        if(!$dataSeo){
            $this->objSeoTable->agregarSeo($data);
        } else {
            $this->objSeoTable->actualizarDatosSeo($data, $dataSeo['idseo']);
        }
        return $this->jsonZF(['result'=>'success']);
    }

    public function paginaPasosFeriasAction(){
        $idferias = $this->params()->fromQuery('idferias');
        $dataFeria = $this->objFeriasTable->obtenerDatoFerias(['idferias'=> $idferias]);
        $dataMenus = $this->objMenusTable->obtenerDatosMenus([]);
        $dataFeriasPasos = $this->objFeriasPasosTable->obtenerDatoFeriasPasos(['idferias'=> $idferias]);
        $data = [
            'idferias'=> $idferias,
            'feria'=> $dataFeria,
            'menus'=> $dataMenus,
            'feriasPasos'=> $dataFeriasPasos
        ];
        return $this->consoleZF($data);
    }

    public function guardarPaginaPasosFeriasAction(){
        $datosFormulario = $this->params()->fromPost();
        $idferias = $datosFormulario['idferias'];
        $idferiaspasos = $datosFormulario['idferiaspasos'];
        $paso_1 = (isset($datosFormulario['paso_1'])) ? $datosFormulario['paso_1'] : 0;
        $paso_1_idmenu = (isset($datosFormulario['paso_1_idmenu'])) ? $datosFormulario['paso_1_idmenu'] : 0;
        $paso_2 = (isset($datosFormulario['paso_2'])) ? $datosFormulario['paso_2'] : 0;
        $paso_2_idmenu = (isset($datosFormulario['paso_2_idmenu'])) ? $datosFormulario['paso_2_idmenu'] : 0;
        $paso_3 = (isset($datosFormulario['paso_3'])) ? $datosFormulario['paso_3'] : 0;
        $paso_3_idmenu = (isset($datosFormulario['paso_3_idmenu'])) ? $datosFormulario['paso_3_idmenu'] : 0;
        if($idferias != ''){
            $data = [
                'idferias'=> $idferias,
                'paso_1'=> ($paso_1_idmenu) ? $paso_1 : 0,
                'paso_1_idmenu'=> $paso_1_idmenu,
                'paso_2'=> ($paso_2_idmenu) ? $paso_2 : 0,
                'paso_2_idmenu'=> $paso_2_idmenu,
                'paso_3'=> ($paso_3_idmenu) ? $paso_3 : 0,
                'paso_3_idmenu'=> $paso_3_idmenu
            ];
            if($idferiaspasos === '') {
                $this->objFeriasPasosTable->agregarFeriasPasos($data);
            } else {
                $this->objFeriasPasosTable->actualizarDatoFeriasPasos($data, ["idferias" => $idferias]);
            }
        }
        return $this->jsonZF(['result'=>'success']);
    }

    public function portalAction(){
        //code
    }
    
    public function listarPortalAction(){
        $dataPortal = $this->objPortalTable->obtenerPortal();
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataPortal as $item){
            $botonConfigurarFormulario = '<a class="dropdown-item dropdown-menu-modal pop-up-3" href="/cliente/configurar-formularios-portal?idportal='.$item['idportal'].'"><i class="fas fa-th-list"></i> Configurar Formulario</a>';
            $botonConfigurar = '<a class="dropdown-item dropdown-menu-modal pop-up" href="/cliente/configurar-portal?idportal='.$item['idportal'].'"><i class="fas fa-cog"></i> Configurar</a>';
            $botonPosicionBanner = '<a class="dropdown-item dropdown-menu-modal" href="/cliente/portal-banner?idportal='.$item['idportal'].'"><i class="fas fa-cog"></i> Posición Banner</a>';
            $botonConfigurarCorreos = '<a class="dropdown-item dropdown-menu-modal pop-up-2" href="/cliente/configurar-correos-portal?idportal='.$item['idportal'].'"><i class="fas fa-envelope-open-text"></i> Configurar Correos</a>';
            $data_out['data'][] = [
                $item['nombre'],
                '<div class="btn-group">
                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Seleccionar</button>
                    <div class="dropdown-menu dropdown-menu-right fadeinup">
                        '.$botonConfigurar.'
                        '.$botonConfigurarFormulario.'
                        '.$botonPosicionBanner.'
                        '.$botonConfigurarCorreos.'
                    </div>
                </div>'
            ];
        }
        return $this->jsonZF($data_out);
    }
    
    public function agregarPortalAction(){
        $data=[];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarPortalAction(){
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
        ];
        $this->objPortalTable->agregarPortal($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarPortalAction(){
        $idportal = $this->params()->fromQuery('idportal');
        $dataPortal = $this->objPortalTable->obtenerDatoPortal(['idportal'=> $idportal]);
        return $this->consoleZF($dataPortal);
    }
    
    public function guardarEditarPortalAction(){
        $idportal = $this->params()->fromQuery('idportal');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'nombre'=>$datosFormulario['nombre'],
            'hash_url'=>$this->objTools->toAscii($datosFormulario['nombre']),
        ];
        $this->objPortalTable->actualizarDatosPortal($data, $idportal);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarPortalAction(){
        $idportal = $this->params()->fromQuery('idportal');
        $dataPortal = $this->objPortalTable->obtenerDatoPortal(['idportal'=> $idportal]);
        return $this->consoleZF($dataPortal);
    }
    
    public function confirmarEliminarPortalAction(){
        $idportal = $this->params()->fromQuery('idportal');
        $this->objPortalTable->eliminarPortal($idportal);
        return $this->jsonZF(['result'=>'success']);
    }

    public function configurarPortalAction(){
        $idportal = $this->params()->fromQuery('idportal');
        $data = $this->objPortalTable->obtenerDatoPortal(['idportal'=> $idportal]);
        $data['posiciones'] = $this->objPosicionBannerTable->obtenerPosicionBanner();
        return $this->consoleZF($data);
    }

    public function guardarConfigurarPortalAction(){
        $idportal = $this->params()->fromPost('idportal');
        $archivos = $this->params()->fromFiles();
        $datosFormulario = $this->params()->fromPost();
        $fondoFechaInicioProgramadaUsuario = date('Y-m-d', strtotime(str_replace('/', '-', $datosFormulario['fondo_principal_prog_fecha_inicio'])));
        $fondoFechaFinProgramadaUsuario = date('Y-m-d', strtotime(str_replace('/', '-', $datosFormulario['fondo_principal_prog_fecha_fin'])));
        $data = [
            'url_video_tutorial'=>$datosFormulario['url_video_tutorial'],
            'texto_titulo_formulario'=>$datosFormulario['texto_titulo_formulario'],
            'fondo_principal_prog_fecha_inicio'=>$fondoFechaInicioProgramadaUsuario,
            'fondo_principal_prog_fecha_fin'=>$fondoFechaFinProgramadaUsuario,
            'form_principal_titulo_color_texto'=>$datosFormulario['form_principal_titulo_color_texto'],
            'form_principal_campo_fondo_color'=>$datosFormulario['form_principal_campo_fondo_color'],
            'form_principal_campo_color_texto'=>$datosFormulario['form_principal_campo_color_texto'],
            'form_principal_campo_color_texto'=>$datosFormulario['form_principal_campo_color_texto'],
            'form_principal_boton_fondo_color'=>$datosFormulario['form_principal_boton_fondo_color'],
            'form_principal_boton_hover_color'=>$datosFormulario['form_principal_boton_hover_color'],
        ];
        ////////// SCRIPT PARA GUARDAR IMAGEN [INICIO] //////////
        $imagenExtensionesValidas = [];
        $carpetaArchivos = '';
        $datosArchivo = [];
        $keyDataArchivo = [];
        $prefijo = '';
        if(!empty($archivos)){
            foreach($archivos as $key => $archivo){
                switch($key){
                    case 'hash_logo':
                        $imagenExtensionesValidas = ['jpg','jpeg','png'];
                        $carpetaArchivos = getcwd().'/public/portal/imagenes';
                        $keyDataArchivo[$key] = ['hash'=> 'hash_logo', 'nombre'=> 'nombre_logo'];
                        $prefijo = 'portal';
                        break;
                    case 'hash_logo_banner':
                        $imagenExtensionesValidas = ['jpg','jpeg','png'];
                        $carpetaArchivos = getcwd().'/public/portal/imagenes';
                        $keyDataArchivo[$key] = ['hash'=> 'hash_logo_banner', 'nombre'=> 'nombre_logo_banner'];
                        $prefijo = 'portal';
                        break;
                    case 'hash_fondo_principal':
                        $imagenExtensionesValidas = ['jpg','jpeg','png'];
                        $carpetaArchivos = getcwd().'/public/portal/imagenes';
                        $keyDataArchivo[$key] = ['hash'=> 'hash_fondo_principal', 'nombre'=> 'nombre_fondo_principal'];
                        $prefijo = 'portal';
                        break;
                    default:
                        break;
                }
                $datosArchivo['id'] = md5(uniqid());
                if( $archivo['size'] !== 0 && $archivo['name'] != '') {
                    //ELIMINAR IMAGEN EXISTENTE
                    $dataPortal = $this->objPortalTable->obtenerDatoPortal(['idportal'=> $idportal]);
                    if($dataPortal && file_exists($carpetaArchivos.'/'.$dataPortal[$keyDataArchivo[$key]['hash']])){
                        @unlink($carpetaArchivos.'/'.$dataPortal[$keyDataArchivo[$key]['hash']]);
                    }
                    //GUARDAR IMAGEN
                    $datosArchivo['extension'] = strtolower(pathinfo($archivo['name'])['extension']);
                    $datosArchivo['filename'] = $this->objTools->toAscii(pathinfo($archivo['name'])['filename']);
                    if( in_array($datosArchivo['extension'], $imagenExtensionesValidas) ) {
                        $datosArchivo['nombre_completo'] = $prefijo.'-'.$datosArchivo['filename'].'.'.$datosArchivo['extension'];
                        $datosArchivo['nombre_original'] = $archivo['name'];
                        if(move_uploaded_file($archivo['tmp_name'], $carpetaArchivos.'/'.$datosArchivo['nombre_completo'])){
                            $data[$keyDataArchivo[$key]['hash']] = $datosArchivo['nombre_completo'];
                            $data[$keyDataArchivo[$key]['nombre']] = $datosArchivo['nombre_original'];
                        }
                    }
                }
            }
        }
        ////////// SCRIPT PARA GUARDAR IMAGEN [FIN] //////////
        $this->objPortalTable->actualizarDatosPortal($data, $idportal);
        return $this->jsonZF(['result'=>'success']);
    }

    public function configurarFormulariosPortalAction(){
        $idportal = $this->params()->fromQuery('idportal');
        $dataPortal = $this->objPortalTable->obtenerDatoPortal(['idportal'=> $idportal]);
        $dataPortal['config_formulario'] = ( $dataPortal['config_formulario'] != '') ? json_decode($dataPortal['config_formulario'], true) : json_decode($this->serviceManager->get('Config')['config_formulario_inicial'], true);
        return $this->consoleZF($dataPortal);
    }

    public function guardarConfigurarFormulariosPortalAction(){
        $idportal = $this->params()->fromPost('idportal');
        $forms = $this->params()->fromPost('forms');
        $data = [
            'config_formulario'=> json_encode($forms)
        ];
        $this->objPortalTable->actualizarDatosPortal($data, $idportal);
        //Guardar campos
        if(isset($forms) && !empty($forms)){
            $dataFields = [];
            foreach($forms as $form){
                $dataFields[] = trim($this->objTools->toAscii($form['keyinput'], '_'));
            }
            //Agregar campos por cada nuevo elemento
            //$this->objVisitantesTable->createFieldVisitantes($dataFields);
        }
        return $this->jsonZF(['result'=>'success']);
    }

    public function bannerAction(){
        //code
    }
    
    public function listarBannerAction(){
        $dataBanner = $this->objBannerTable->obtenerBanner();
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataBanner as $item){
            $data_out['data'][] = [
                $item['elemento'],
                date('d/m/Y', strtotime($item['fecha_programa_inicio'])),
                date('d/m/Y', strtotime($item['fecha_programa_fin'])),
                $item['categoria'],
                $item['pagina']." - ".$item['banner'],
                '<div class="clas btn btn-sm btn-info pop-up" href="/cliente/editar-banner?idbanner='.$item['idbanner'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="clas btn btn-sm btn-danger pop-up" href="/cliente/eliminar-banner?idbanner='.$item['idbanner'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div>'
            ];
        }
        return $this->jsonZF($data_out);
    }
    
    public function agregarBannerAction(){
        $data=[
            'categoriasBanner'=> $this->categoriasBanner,
            'proyectos'=> $this->objProductosTable->obtenerProductos($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']),
            'bancos'=> $this->objBancosTable->obtenerBancos(),
            'posiciones'=> $this->objPosicionBannerTable->obtenerPosicionBanner(),
            'empresas'=> $this->objEmpresasTable->obtenerEmpresas(),
        ];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarBannerAction(){
        $imagen = $this->params()->fromFiles('imagen');
        $datosFormulario = $this->params()->fromPost();
        $fecha_programa_inicio_usuario = date('Y-m-d', strtotime(str_replace('/', '-', $datosFormulario['fecha_programa_inicio'])));
        $fecha_programa_fin_usuario = date('Y-m-d', strtotime(str_replace('/', '-', $datosFormulario['fecha_programa_fin'])));
        $categoria = $datosFormulario['categoria'];
        $idtabla = 0;
        if(isset($datosFormulario['idproductos'])){
            $idtabla = $datosFormulario['idproductos'];
        }
        if(isset($datosFormulario['idbancos'])){
            $idtabla = $datosFormulario['idbancos'];
        }
        if(isset($datosFormulario['idempresas'])){
            $idtabla = $datosFormulario['idempresas'];
        }
        $idposicionbanner = $datosFormulario['idposicionbanner'];
        $data = [
            'categoria'=>$categoria,
            'idtabla'=>$idtabla,
            'fecha_programa_inicio'=>$fecha_programa_inicio_usuario,
            'fecha_programa_fin'=>$fecha_programa_fin_usuario,
            'idposicionbanner'=>$idposicionbanner,
        ];
        //Validar banner programadas
        if($this->validarFechasBannerUser($fecha_programa_inicio_usuario, $fecha_programa_fin_usuario, $datosFormulario)){
            return $this->jsonZF(['result'=>'Formato de fechas inválidos']);
        }
        if($this->objBannerTable->validarRangoFechasBanner($fecha_programa_inicio_usuario, $fecha_programa_fin_usuario, $categoria, $idposicionbanner)){
            return $this->jsonZF(['result'=>'Fechas registrados']);
        }
        $bannerFechasExtremos = $this->objBannerTable->obtenerFechaInicioFinBanner($categoria, $idposicionbanner);
        if($bannerFechasExtremos){
            $dateBeginUser = new \DateTime($fecha_programa_inicio_usuario);
            $dateEndUser   = new \DateTime($fecha_programa_fin_usuario);
            for($i = $dateBeginUser; $i <= $dateEndUser; $i->modify('+1 day')){
                if($i->format("Y-m-d") == $bannerFechasExtremos['fecha_inicio'] || $i->format("Y-m-d") == $bannerFechasExtremos['fecha_fin']) {
                    return $this->jsonZF(['result'=>'Fechas registrados']);
                }
            }
        }
        //Guardar imagen
        $carpetaImagen = getcwd().'/public/banner/imagenes';
        $datosImagen = [];
        $datosImagen['id'] = md5(uniqid());
        if( $imagen['size'] !== 0 && $imagen['name'] !== '') {
            $datosImagen['extension'] = strtolower(pathinfo($imagen['name'])['extension']);
            if( in_array($datosImagen['extension'], ['jpg','jpeg','png']) ) {
                $datosImagen['nombre_completo'] = $datosImagen['id'].'.'.$datosImagen['extension'];
                $datosImagen['nombre_original'] = $imagen['name'];
                if(move_uploaded_file($imagen['tmp_name'], $carpetaImagen.'/'.$datosImagen['nombre_completo'])){
                    $data['hash_banner'] = $datosImagen['nombre_completo'];
                    $data['nombre_banner'] = $datosImagen['nombre_original'];
                }
            }
        }
        //Guardar banner
        $this->objBannerTable->agregarBanner($data);
        return $this->jsonZF(['result'=>'success']);
    }

    private function validarFechasBannerUser($fecha_programa_inicio_usuario, $fecha_programa_fin_usuario, $datosFormulario){
        $dateFormatoValid = $this->objTools->validateFormatDate('Y-m-d');
        if(!$dateFormatoValid->isValid($fecha_programa_inicio_usuario) || !$dateFormatoValid->isValid($fecha_programa_fin_usuario) || strtotime($fecha_programa_inicio_usuario) > strtotime($fecha_programa_fin_usuario)){
            return true;
        }
        $dateFormatoValidUser = $this->objTools->validateFormatDate('d/m/Y');
        if(!$dateFormatoValidUser->isValid($datosFormulario['fecha_programa_inicio']) || !$dateFormatoValidUser->isValid($datosFormulario['fecha_programa_fin'])){
            return true;
        }
    }
    
    public function editarBannerAction(){
        $idbanner = $this->params()->fromQuery('idbanner');
        $dataBanner = $this->objBannerTable->obtenerDatoBanner(['idbanner'=> $idbanner]);
        $dataBanner['categoriasBanner'] = $this->categoriasBanner;
        $dataBanner['proyectos'] = $this->objProductosTable->obtenerProductos($this->sessionContainer['idferias'], $this->sessionContainer['idperfil']);
        $dataBanner['bancos'] = $this->objBancosTable->obtenerBancos();
        $dataBanner['posiciones'] = $this->objPosicionBannerTable->obtenerPosicionBanner();
        $dataBanner['empresas'] = $this->objEmpresasTable->obtenerEmpresas();
        return $this->consoleZF($dataBanner);
    }
    
    public function guardarEditarBannerAction(){
        $idbanner = $this->params()->fromQuery('idbanner');
        $imagen = $this->params()->fromFiles('imagen');
        $datosFormulario = $this->params()->fromPost();
        $fecha_programa_inicio_usuario = date('Y-m-d', strtotime(str_replace('/', '-', $datosFormulario['fecha_programa_inicio'])));
        $fecha_programa_fin_usuario = date('Y-m-d', strtotime(str_replace('/', '-', $datosFormulario['fecha_programa_fin'])));
        $categoria = $datosFormulario['categoria'];
        $idtabla = 0;
        if(isset($datosFormulario['idproductos'])){
            $idtabla = $datosFormulario['idproductos'];
        }
        if(isset($datosFormulario['idbancos'])){
            $idtabla = $datosFormulario['idbancos'];
        }
        if(isset($datosFormulario['idempresas'])){
            $idtabla = $datosFormulario['idempresas'];
        }
        $idposicionbanner = $datosFormulario['idposicionbanner'];
        $data = [
            'categoria'=>$categoria,
            'idtabla'=>$idtabla,
            'fecha_programa_inicio'=>$fecha_programa_inicio_usuario,
            'fecha_programa_fin'=>$fecha_programa_fin_usuario,
            'idposicionbanner'=>$idposicionbanner,
        ];
        //Validar usuario cambio de fechas
        $dataBannerExiste = $this->objBannerTable->obtenerDatoBanner(['idbanner'=> $idbanner]);
        if(!$dataBannerExiste){
            return $this->jsonZF(['result'=>'Banner no existe']);
        }
        if($dataBannerExiste['fecha_programa_inicio'] !== $fecha_programa_inicio_usuario || $dataBannerExiste['fecha_programa_fin'] !== $fecha_programa_fin_usuario || $dataBannerExiste['idposicionbanner'] !== $idposicionbanner) {
            //Validar formato de fechas válidos
            if($this->validarFechasBannerUser($fecha_programa_inicio_usuario, $fecha_programa_fin_usuario, $datosFormulario)){
                return $this->jsonZF(['result'=>'Formato de fechas inválidos']);
            }
            //Validar banner programadas
            if($validarRangoFechasBanner = $this->objBannerTable->validarRangoFechasBanner($fecha_programa_inicio_usuario, $fecha_programa_fin_usuario, $categoria, $idposicionbanner)){
                if($validarRangoFechasBanner['idbanner'] != $idbanner)return $this->jsonZF(['result'=>'Fechas registrados']);
            }
            $bannerFechasExtremos = $this->objBannerTable->obtenerFechaInicioFinBanner($categoria, $idposicionbanner);
            if($bannerFechasExtremos && $bannerFechasExtremos['idbanner'] != $idbanner){
                $dateBeginUser = new \DateTime($fecha_programa_inicio_usuario);
                $dateEndUser   = new \DateTime($fecha_programa_fin_usuario);
                for($i = $dateBeginUser; $i <= $dateEndUser; $i->modify('+1 day')){
                    if($i->format("Y-m-d") == $bannerFechasExtremos['fecha_inicio'] || $i->format("Y-m-d") == $bannerFechasExtremos['fecha_fin']) {
                        return $this->jsonZF(['result'=>'Fechas registrados']);
                    }
                }
            }
        }
        //Guardar imagen
        $carpetaImagen = getcwd().'/public/banner/imagenes';
        $datosImagen = [];
        $datosImagen['id'] = md5(uniqid());
        if( !empty($imagen['name']) && $imagen['size'] !== 0 ) {
            $dataBanner = $this->objBannerTable->obtenerDatoBanner(['idbanner'=> $idbanner]);
            if( $dataBanner ) {
                if(file_exists($carpetaImagen.'/'.$dataBanner['hash_banner'])){
                    @unlink($carpetaImagen.'/'.$dataBanner['hash_banner']);
                }
                $datosImagen['extension'] = strtolower(pathinfo($imagen['name'])['extension']);
                if( in_array($datosImagen['extension'], $this->imagenExtensionesValidas) ) {
                    $datosImagen['nombre_completo'] = $datosImagen['id'].'.'.$datosImagen['extension'];
                    $datosImagen['nombre_original'] = $imagen['name'];
                    if(move_uploaded_file($imagen['tmp_name'], $carpetaImagen.'/'.$datosImagen['nombre_completo'])){
                        $data['hash_banner'] = $datosImagen['nombre_completo'];
                        $data['nombre_banner'] = $datosImagen['nombre_original'];
                    }
                }
            }
        }
        //Actualizar banner
        $this->objBannerTable->actualizarDatosBanner($data, $idbanner);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarBannerAction(){
        $idbanner = $this->params()->fromQuery('idbanner');
        $dataBanner = $this->objBannerTable->obtenerDatoBanner(['idbanner'=> $idbanner]);
        return $this->consoleZF($dataBanner);
    }
    
    public function confirmarEliminarBannerAction(){
        $idbanner = $this->params()->fromQuery('idbanner');
        $dataBanner = $this->objBannerTable->obtenerDatoBanner(['idbanner'=> $idbanner]);
        $carpetaImagen = getcwd().'/public/banner/imagenes';
        if($dataBanner && file_exists($carpetaImagen.'/'.$dataBanner['hash_banner'])) {
            @unlink($carpetaImagen.'/'.$dataBanner['hash_banner']);
        }
        $this->objBannerTable->eliminarBanner($idbanner);
        return $this->jsonZF(['result'=>'success']);
    }

    private function consoleView($data){
        return new ViewModel($data);
    }

    private function consoleZF($data){
        $viewModel = new ViewModel($data);
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    private function jsonZF($data){
        return new JsonModel($data);
    }

    public function portalbannerAction(){
        $idportal = $this->params()->fromQuery('idportal');
        $data = [
            'idportal'=> $idportal
        ];
        return $this->consoleView($data);
    }
    
    public function listarPortalBannerAction(){
        $dataPortalBanner = $this->objPortalBannerTable->obtenerPortalBanner();
        $data_out = [];
        $data_out['data'] = [];
        foreach($dataPortalBanner as $item){
            $data_out['data'][] = [
                $item['pagina']." - ".$item['posicion'],
                '<div class="clas btn btn-sm btn-info pop-up" href="/cliente/editar-portal-banner?idportalbanner='.$item['idportalbanner'].'"><i class="fas fa-pencil-alt"></i> <span class="hidden-xs">Editar</span></div> <div class="clas btn btn-sm btn-danger pop-up" href="/cliente/eliminar-portal-banner?idportalbanner='.$item['idportalbanner'].'"><i class="fas fa-times"></i> <span class="hidden-xs">Eliminar</span></div>'
            ];
        }
        return $this->jsonZF($data_out);
    }
    
    public function agregarPortalBannerAction(){
        $idportal = $this->params()->fromQuery('idportal');
        $data = [
            'idportal'=> $idportal,
            'posiciones'=> $this->objPosicionBannerTable->obtenerPosicionBanner()
        ];
        return $this->consoleZF($data);
    }
    
    public function guardarAgregarPortalBannerAction(){
        $imagen = $this->params()->fromFiles('imagen');
        $idportal = $this->params()->fromQuery('idportal');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'idportal'=>$idportal,
            'idposicionbanner'=>$datosFormulario['idposicionbanner']
        ];
        //Validar Posición Existente
        $dataPortalBanner = $this->objPortalBannerTable->obtenerDatoPortalBanner(['idposicionbanner'=> $datosFormulario['idposicionbanner']]);
        if($dataPortalBanner){
            return $this->jsonZF(['result'=>'existe']);
        }
        //Guardar imagen
        $carpetaImagen = getcwd().'/public/portal/banner';
        $datosImagen = [];
        $datosImagen['id'] = md5(uniqid());
        if( $imagen['size'] !== 0 && $imagen['name'] !== '') {
            $datosImagen['extension'] = strtolower(pathinfo($imagen['name'])['extension']);
            if( in_array($datosImagen['extension'], ['jpg','jpeg','png']) ) {
                $datosImagen['nombre_completo'] = $datosImagen['id'].'.'.$datosImagen['extension'];
                $datosImagen['nombre_original'] = $imagen['name'];
                if(move_uploaded_file($imagen['tmp_name'], $carpetaImagen.'/'.$datosImagen['nombre_completo'])){
                    $data['hash_banner'] = $datosImagen['nombre_completo'];
                    $data['nombre_banner'] = $datosImagen['nombre_original'];
                }
            }
        }
        //Guardar datos
        $this->objPortalBannerTable->agregarPortalBanner($data);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function editarPortalBannerAction(){
        $idportalbanner = $this->params()->fromQuery('idportalbanner');
        $dataPortalBanner = $this->objPortalBannerTable->obtenerDatoPortalBanner(['idportalbanner'=> $idportalbanner]);
        $dataPortalBanner['posiciones'] = $this->objPosicionBannerTable->obtenerPosicionBanner();
        return $this->consoleZF($dataPortalBanner);
    }
    
    public function guardarEditarPortalBannerAction(){
        $idportalbanner = $this->params()->fromQuery('idportalbanner');
        $imagen = $this->params()->fromFiles('imagen');
        $datosFormulario = $this->params()->fromPost();
        $data = [
            'idportal'=>$datosFormulario['idportal'],
            'idposicionbanner'=>$datosFormulario['idposicionbanner']
        ];
        //Validar Posición Existente
        $dataPortalBanner = $this->objPortalBannerTable->obtenerDatoPortalBanner(['idportalbanner'=> $idportalbanner]);
        $dataPortalBannerPosicion = $this->objPortalBannerTable->obtenerDatoPortalBanner(['idposicionbanner'=> $datosFormulario['idposicionbanner']]);
        if($dataPortalBannerPosicion && $dataPortalBannerPosicion['idposicionbanner'] != $dataPortalBanner['idposicionbanner']){
            return $this->jsonZF(['result'=>'existe']);
        }
        //Guardar imagen
        $carpetaImagen = getcwd().'/public/portal/banner';
        $datosImagen = [];
        $datosImagen['id'] = md5(uniqid());
        if( !empty($imagen['name']) && $imagen['size'] !== 0 ) {
            //$dataPortalBanner = $this->objPortalBannerTable->obtenerDatoPortalBanner(['idportalbanner'=> $idportalbanner]);
            if( $dataPortalBanner ) {
                if(file_exists($carpetaImagen.'/'.$dataPortalBanner['hash_banner'])){
                    @unlink($carpetaImagen.'/'.$dataPortalBanner['hash_banner']);
                }
                $datosImagen['extension'] = strtolower(pathinfo($imagen['name'])['extension']);
                if( in_array($datosImagen['extension'], $this->imagenExtensionesValidas) ) {
                    $datosImagen['nombre_completo'] = $datosImagen['id'].'.'.$datosImagen['extension'];
                    $datosImagen['nombre_original'] = $imagen['name'];
                    if(move_uploaded_file($imagen['tmp_name'], $carpetaImagen.'/'.$datosImagen['nombre_completo'])){
                        $data['hash_banner'] = $datosImagen['nombre_completo'];
                        $data['nombre_banner'] = $datosImagen['nombre_original'];
                    }
                }
            }
        }
        //Actualizar Datos
        $this->objPortalBannerTable->actualizarDatosPortalBanner($data, $idportalbanner);
        return $this->jsonZF(['result'=>'success']);
    }
    
    public function eliminarPortalBannerAction(){
        $idportalbanner = $this->params()->fromQuery('idportalbanner');
        $dataPortalBanner = $this->objPortalBannerTable->obtenerDatoPortalBanner(['idportalbanner'=> $idportalbanner]);
        return $this->consoleZF($dataPortalBanner);
    }
    
    public function confirmarEliminarPortalBannerAction(){
        $idportalbanner = $this->params()->fromQuery('idportalbanner');
        $this->objPortalBannerTable->eliminarPortalBanner($idportalbanner);
        return $this->jsonZF(['result'=>'success']);
    }

    public function configurarCorreosPortalAction(){
        $idportal = $this->params()->fromQuery('idportal');
        $plantillaCorreos = [
            'promocion'=> ['value'=> 'Promoción']
        ];
        $data = [
            'idportal'=> $idportal,
            'plantillaCorreos'=> $plantillaCorreos
        ];
        return $this->consoleZF($data);
    }

    public function guardarConfigurarCorreosPortalAction(){
        $idportal = $this->params()->fromPost('idportal');
        $datosFormulario = $this->params()->fromPost();
        $plantilla = $datosFormulario['plantilla_correo'];
        $data = [
            'idportal'=> $idportal,
            'plantilla_correo'=> $plantilla,
            'asunto'=> $datosFormulario['asunto'],
            'contenido'=> $datosFormulario['contenido'],
            'correo_copia'=> ''
        ];
        //Validar correos validos
        if(isset($datosFormulario['correo_copia'])){
            $correosValidos = [];
            foreach($datosFormulario['correo_copia'] as $correo){
                if(filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                    $correosValidos[] = $correo;
                }
            }
            $data['correo_copia'] = implode(",", $correosValidos);
        }
        //Validar si existe plantilla
        $dataFeriasCorreo = $this->objPortalCorreosTable->obtenerDatoPortalCorreos(['idportal'=> $idportal, 'plantilla_correo'=> $plantilla]);
        if($dataFeriasCorreo){
            $this->objPortalCorreosTable->actualizarDatoPortalCorreos($data, ['idportal'=> $idportal, 'plantilla_correo'=> $plantilla]);
        } else {
            $this->objPortalCorreosTable->agregarPortalCorreos($data);
        }
        return $this->jsonZF(['result'=>'success']);
    }

    public function obtenerPlantillaCorreosAction(){
        $idportal = $this->params()->fromPost('idportal');
        $plantilla = $this->params()->fromPost('plantilla');
        $data = $this->objPortalCorreosTable->obtenerDatoPortalCorreos(['idportal'=> $idportal, 'plantilla_correo'=> $plantilla]);
        return $this->jsonZF($data);
    }

}