<?php 
@session_start();
$mostrar_registros = @$_SESSION['registros'];
$id_usuario = @$_SESSION['id'];
require_once("../verificar.php");
require_once("../../conexao.php");



$dataInicial = $_POST['dataInicial'];
$dataFinal = $_POST['dataFinal'];
$tipo_data = $_POST['tipo_data'];
$atacadista = $_POST['atacadista'];
$formaPGTO = $_POST['formaPGTO'];
$filtro = $_POST['pago'];
$funcionario = $_POST['funcionario'];
$cargo = $_POST['cargo'];


$url_pdf = $url_sistema . "painel/rel/pagar.php?dataInicial=$dataInicial&dataFinal=$dataFinal&tipo_data=$tipo_data&atacadista=$atacadista&formaPGTO=$formaPGTO&funcionario=$funcionario&cargo=$cargo&pago=$filtro&mostrar_registros=$mostrar_registros&id_usuario=$id_usuario&token=A5030";
$html = file_get_contents($url_pdf);

//CARREGAR DOMPDF
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

header("Content-Transfer-Encoding: binary");
header("Content-Type: image/png");

//INICIALIZAR A CLASSE DO DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$pdf = new DOMPDF($options);


//Definir o tamanho do papel e orientação da página
$pdf->set_paper('A4', 'portrait');

//CARREGAR O CONTEÚDO HTML
$pdf->load_html($html);

//RENDERIZAR O PDF
$pdf->render();
//NOMEAR O PDF GERADO


$pdf->stream(
	'contas_pagar.pdf',
	array("Attachment" => false)
);

 ?>