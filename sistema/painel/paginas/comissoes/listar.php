<?php 
require_once("../../../conexao.php");
$tabela = 'pagar';
$data_hoje = date('Y-m-d');

$dataInicial = @$_POST['dataInicial'];
$dataFinal = @$_POST['dataFinal'];
$status = '%'.@$_POST['status'].'%';
$funcionario = '%'.@$_POST['funcionario'].'%';

$funcionario2 = $_POST['funcionario'];
$query2 = $pdo->query("SELECT * FROM usuarios where id = '$funcionario2'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if($total_reg2 > 0){
			$nome_func2 = $res2[0]['nome'];
		}else{
			$nome_func2 = 'Sem Referência!';
		}

$total_pago = 0;
$total_a_pagar = 0;
$total_pendente = 0;

$query = $pdo->query("SELECT * FROM $tabela where data_lanc >= '$dataInicial' and data_lanc <= '$dataFinal' and pago LIKE '$status' and funcionario LIKE '$funcionario' and referencia = 'Comissão' ORDER BY pago asc, vencimento asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){

echo <<<HTML
	<small>
	<table class="table table-striped table-hover table-bordered text-nowrap border-bottom dt-responsive" id="tabela">
	<thead>
	<tr> 
	<th>Descricao</th>	
	<th>Valor</th> 
	<th>Funcionário</th>
	<th>Data Serviço</th>		
	<th>Vencimento</th>	
	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];	
	$descricao = $res[$i]['descricao'];
	$tipo = $res[$i]['referencia'];
	$valor = $res[$i]['valor'];
	$data_lanc = $res[$i]['data_lanc'];
	$data_pgto = $res[$i]['data_pgto'];
	$data_venc = $res[$i]['vencimento'];
	$usuario_lanc = $res[$i]['usuario_lanc'];
	$usuario_baixa = $res[$i]['usuario_pgto'];
	$foto = $res[$i]['arquivo'];	
	$funcionario = $res[$i]['funcionario'];	
	
	$pago = $res[$i]['pago'];
	
	
	$valorF = number_format($valor, 2, ',', '.');
	$data_lancF = implode('/', array_reverse(@explode('-', $data_lanc)));
	$data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));
	$data_vencF = implode('/', array_reverse(@explode('-', $data_venc)));
	

		


		$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_baixa'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if($total_reg2 > 0){
			$nome_usuario_pgto = $res2[0]['nome'];
		}else{
			$nome_usuario_pgto = 'Nenhum!';
		}




		$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_lanc'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if($total_reg2 > 0){
			$nome_usuario_lanc = $res2[0]['nome'];
		}else{
			$nome_usuario_lanc = 'Sem Referência!';
		}



		$query2 = $pdo->query("SELECT * FROM usuarios where id = '$funcionario'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if($total_reg2 > 0){
			$nome_func = $res2[0]['nome'];
			$tel_func = $res2[0]['telefone'];
			$chave_pix_func = $res2[0]['pix'];			
		}else{
			$nome_func = 'Sem Referência!';
			$chave_pix_func = '';
			$tel_func = '';			

		}		



		if($pago == 'Não'){
			$classe_alerta = 'text-danger';
			$data_pgtoF = 'Pendente';
			$visivel = '';
			$total_a_pagar += $valor;
			$total_pendente += 1;
		}else{
			$classe_alerta = 'verde';
			$visivel = 'ocultar';
			$total_pago += $valor;
		}


			//extensão do arquivo
$ext = pathinfo($foto, PATHINFO_EXTENSION);
if($ext == 'pdf'){
	$tumb_arquivo = 'pdf.png';
}else if($ext == 'rar' || $ext == 'zip'){
	$tumb_arquivo = 'rar.png';
}else{
	$tumb_arquivo = $foto;
}
		

if($data_venc < $data_hoje and $pago != 'Sim'){
	$classe_debito = 'vermelho-escuro';
}else{
	$classe_debito = '';
}
		


echo <<<HTML
<tr class="{$classe_debito}">
<td><i class="fa fa-square {$classe_alerta}"></i> {$descricao}</td>
<td>R$ {$valorF}</td>
<td>{$nome_func}</td>
<td>{$data_lancF}</td>
<td>{$data_vencF}</td>

<td>
		

		<a class="btn btn-primary btn-sm" href="#" onclick="mostrar('{$descricao}', '{$valorF}', '{$data_lancF}', '{$data_vencF}',  '{$data_pgtoF}', '{$nome_usuario_lanc}', '{$nome_usuario_pgto}', '{$nome_func}', '{$tel_func}', '{$chave_pix_func}')" title="Ver Dados"><i class="fa fa-info-circle"></i></a>


		<div class="dropdown" style="display: inline-block;">                      
		<a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-trash"></i></a>
		<div  class="dropdown-menu tx-13">
		<div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger"><button class="btn-danger">Sim</button></span></a></p>
		</div>
		</div>
		</div>





		   <div class="dropdown head-dpdn2" style="display: inline-block;">                      
            <a class="btn btn-success btn-sm" title="Baixar Conta" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown {$visivel}"><i class="fa fa-check-square "></i></a>
             <div  class="dropdown-menu tx-13">
                 <div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
                     <p>Confirmar Baixa na Conta? <a href="#" onclick="baixar('{$id}')"><span class="text-verde"><button class="btn-danger">Sim</button></span></a></p>
                 </div>
              </div>
        </div>


		
	
		</td>
</tr>
HTML;

}

$total_pagoF = number_format($total_pago, 2, ',', '.');
$total_a_pagarF = number_format($total_a_pagar, 2, ',', '.');

echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>

<br>	
<div align="right">Total Pago: <span class="verde">R$ {$total_pagoF}</span> </div>
<div align="right">Total à Pagar: <span class="text-danger">R$ {$total_a_pagarF}</span> </div>

</small>
HTML;


}else{
	echo 'Não possui nenhum registro Cadastrado!';
}

?>

<script type="text/javascript">
	$(document).ready( function () {

	var func = '<?=$nome_func2?>';	
		$('#titulo_inserir').text(func);
		$('#total_pgto').text('<?=$total_a_pagarF?>');	
		$('#total_comissoes').text('<?=$total_pendente?>');	

		$('#id_funcionario').val('<?=$funcionario?>');
		$('#data_inicial').val('<?=$dataInicial?>');
		$('#data_final').val('<?=$dataFinal?>');	

		

    $('#tabela').DataTable({
    		"ordering": false,
			"stateSave": true
    	});
    $('#tabela_filter label input').focus();
} );
</script>



<script type="text/javascript">
	function mostrar(descricao, valor, data_lanc, data_venc, data_pgto, usuario_lanc, usuario_pgto, func, tel, tipo_chave){

		$('#nome_dados').text(descricao);
		$('#valor_dados').text(valor);
		$('#data_lanc_dados').text(data_lanc);
		$('#data_venc_dados').text(data_venc);
		$('#data_pgto_dados').text(data_pgto);
		$('#usuario_lanc_dados').text(usuario_lanc);
		$('#usuario_baixa_dados').text(usuario_pgto);
		$('#nome_func_dados').text(func);
		$('#telefone_dados').text(tel);		
		$('#chave_pix_dados').text(tipo_chave);	
		

		$('#modalDados').modal('show');
	}
</script>




