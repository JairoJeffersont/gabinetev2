 <?php

    use App\Controllers\AutorProposicaoController;
    use App\Controllers\ProposicaoController;
    use App\Helpers\GetApi;
    use App\Helpers\Validation;

    $getApi = new GetApi();
    $proposicaoController = new ProposicaoController();
    $autorProposicaoController = new AutorProposicaoController;
    $validation = new Validation();

    $ano = isset($_GET['ano']) ? $_GET['ano'] : date('Y');
    $autoria = isset($_GET['autoria']) ? $_GET['autoria'] : "1";
    $tipoGet = isset($_GET['tipo']) ? $_GET['tipo'] : 'PL';
    $tramitacao = isset($_GET['tramitacao']) ? $_GET['tramitacao'] : 1;
    $ordem = isset($_GET['ordem']) ? $_GET['ordem'] : 'DESC';

    $condicoes = [
        'autor_proposicao_nome_slug' => $validation->slug($nomeGabinete),
        'proposicao_ano' => $ano
    ];

    $condicoes = array_merge(
        $condicoes,
        $autoria != "0"
            ? ['proposicao_proponente' => '1']
            : ['proposicao_proponente' => '0']
    );

    $buscaProposicoes = $autorProposicaoController->listar('proposicao_id', $ordem, 10000, 1, $condicoes, 'AND');

    $proposicoes = [];
    $proposicoesFiltradas = [];

    if ($buscaProposicoes['status'] == 'success') {
        foreach ($buscaProposicoes['data'] as $item) {
            $proposicaoId = $item['proposicao_id'];
            $resultadoBusca = $proposicaoController->buscar($proposicaoId, 'proposicao_id');
            if ($resultadoBusca['status'] == 'success') {
                $proposicoes[] = $resultadoBusca['data'];
            }
        }
        foreach ($proposicoes as $item) {
            if ($item['proposicao_tipo'] == $tipoGet && $item['proposicao_tramitacao'] == $tramitacao) {
                $proposicoesFiltradas[] = $item;
            }
        }
    } else {
        $proposicoesFiltradas[] = $buscaProposicoes;
    }

    //print_r($proposicaoController->atualizarProposicoesCD('2025-08-07'));


    ?>

 <div class="card mb-2 ">
     <div class="card-body p-1">
         <a class="btn btn-primary custom-card-body btn-sm link_loading" href="?secao=home" role="button"><i class="bi bi-house-door-fill"></i> Início</a>
     </div>
 </div>

 <div class="card mb-2">
     <div class="card-header bg-primary text-white px-2 py-1 custom-card-header"><i class="bi bi-file-earmark-text"></i> Proposicoes</div>
     <div class="card-body custom-card-body p-2">
         <p class="card-text mb-2">Nesta seção, você pode pesquisar pelas proposições de autoria e co-autoria do deputado, facilitando o acesso às informações relevantes de forma rápida e organizada.</p>
         <p class="card-text mb-0">As informações são de responsabilidade da Câmara dos Deputados, podendo sofrer alterações a qualquer momento ou com algum atraso.
         </p>
     </div>
 </div>

 <div class="card mb-2">
     <div class="card-body custom-card-body p-2">
         <form class="row g-2 form_custom" id="form_novo" method="GET">
             <div class="col-md-1 col-2">
                 <input type="hidden" name="secao" value="proposicoes" />
                 <input type="text" class="form-control form-control-sm" inputmode="numeric" name="ano" data-mask="0000" value="<?= htmlspecialchars($ano) ?>">
             </div>
             <div class="col-md-1 col-10">
                 <select class="form-select form-select-sm" name="tipo" required>
                     <?php
                        $buscaTipo = $getApi->getJson('https://dadosabertos.camara.leg.br/api/v2/referencias/tiposProposicao');
                        if ($buscaTipo['status'] == 'success') {
                            $siglasAdicionadas = [];
                            foreach ($buscaTipo['data']['dados'] as $tipo) {
                                if (!in_array($tipo['sigla'], $siglasAdicionadas)) {
                                    if ($tipo['sigla'] == $tipoGet) {
                                        echo '<option value="' . $tipo['sigla'] . '" selected>' . $tipo['nome'] . '</option>';
                                    } else {
                                        echo '<option value="' . $tipo['sigla'] . '">' . $tipo['nome'] . '</option>';
                                    }

                                    $siglasAdicionadas[] = $tipo['sigla'];
                                }
                            }
                        } else {
                            echo '<option>' . $buscaTipo['message'] . '</option>';
                        }
                        ?>
                 </select>
             </div>

             <div class="col-md-1 col-6">
                 <select class="form-select form-select-sm" name="tramitacao" required>
                     <option value="1" <?= ($tramitacao == 1) ? 'selected' : '' ?>>Tramitando</option>
                     <option value="0" <?= ($tramitacao == 0) ? 'selected' : '' ?>>Arquivada</option>
                 </select>
             </div>

             <div class="col-md-1 col-6">
                 <select class="form-select form-select-sm" name="autoria" required>
                     <option value="1" <?= ($autoria == "1") ? 'selected' : '' ?>>Autoria</option>
                     <option value="0" <?= ($autoria == "0") ? 'selected' : '' ?>>Subscrição</option>
                 </select>
             </div>
             <div class="col-md-1 col-10">
                 <select class="form-select form-select-sm" name="ordem" required>
                     <option value="ASC" <?= ($ordem == "ASC") ? 'selected' : '' ?>>Crescente</option>
                     <option value="DESC" <?= ($ordem == "DESC") ? 'selected' : '' ?>>Decrescente</option>
                 </select>
             </div>
             <div class="col-md-1 col-2">
                 <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-search"></i></button>
             </div>
         </form>
     </div>
 </div>

 <div class="card mb-2 ">
     <div class="card-body custom-card-body p-1">
         <div class="table-responsive">
             <div class="table-responsive">
                 <table class="table table-hover table-striped table-bordered mb-0">
                     <thead>
                         <tr>
                             <th scope="col">Titulo</th>
                             <th scope="col">Ementa</th>
                         </tr>
                     </thead>
                     <tbody>
                         <?php

                            $status = $buscaProposicoes['status'] ?? null;
                            $message = $buscaProposicoes['message'] ?? '';
                            $errorId = $buscaProposicoes['error_id'] ?? '';

                            echo '<tr>';

                            switch ($status) {
                                case 'server_error':
                                    echo "<td colspan='2'>{$message} | {$errorId}</td>";
                                    break;

                                case 'empty':
                                    echo "<td colspan='2'>{$message}</td>";
                                    break;

                                default:
                                    if (!empty($proposicoesFiltradas)) {
                                        foreach ($proposicoesFiltradas as $proposicao) {
                                            echo '<tr>';
                                            echo "<td style='white-space: nowrap;'><b><a href='{$proposicao['proposicao_id']}'>{$proposicao['proposicao_titulo']}</a></b></td>";
                                            echo "<td>{$proposicao['proposicao_ementa']}</td>";
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo "<td colspan='2'>Nenhum registro encontrado</td>";
                                    }
                            }

                            echo '</tr>';
                            ?>
                     </tbody>
                 </table>
             </div>
         </div>
     </div>
 </div>