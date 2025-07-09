<?php

use App\Controllers\GabineteController;
use App\Controllers\TipoGabineteController;
use App\Controllers\UsuarioController;
use App\Controllers\UsuarioTipoController;

$tipoGabineteController = new TipoGabineteController();
$gabineteController = new GabineteController();
$tipoUsuarioController = new UsuarioTipoController();
$usuarioController = new UsuarioController();

$gabineteSessao = $_SESSION['gabinete'];
$usuarioSessao = $_SESSION['id'];


$buscaGabinete = $gabineteController->buscar($gabineteSessao, 'id');
$buscaUsuarios = $usuarioController->listar('nome', 'asc', 100, 1, ['gabinete' => $gabineteSessao]);
$buscaTipo = $tipoGabineteController->buscar($buscaGabinete['data']['tipo'], 'id');
$buscaUsuario = $usuarioController->buscar($usuarioSessao, 'id');

?>
<div class="card mb-2 ">
    <div class="card-body custom-card-body p-1">
        <a class="btn btn-primary btn-sm custom-nav barra_navegacao" href="?secao=home" role="button"><i class="bi bi-house-door-fill"></i> Início</a>
    </div>
</div>

<div class="card mb-2">
    <div class="card-header custom-card-header px-2 py-1"> <i class="bi bi-person-gear"></i> Área do gestor </div>
    <div class="card-body custom-card-body p-2">
        <p class="card-text">Esta área é destinada à gestão do gabinete, incluindo o gerenciamento de usuários, níveis de acesso e dados do gabinete.</p>
    </div>
</div>

<div class="card mb-2">
    <div class="card-body custom-card-body p-2">
        <h5 class="card-title mb-2"><?= $buscaGabinete['data']['nome'] ?> - <?= $buscaGabinete['data']['estado'] ?> | <?= $buscaTipo['data']['nome'] ?></h5>
        <p class="card-text">Total de usuários no gabinete: <b><?= count($buscaUsuarios['data']) ?></b></p>
    </div>
</div>

<div class="card mb-2">
    <div class="card-body custom-card-body p-2">
        <p class="card-text mb-2"><b>Dados do gabinete</b></p>

        <?php

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_atualizar_gabinete'])) {

            $dadosGabinete = [
                'cidade' => $_POST['gabinete_municipio'],
                'partido' => $_POST['gabinete_partido']
            ];

            $resultGabinete = $gabineteController->atualizar($gabineteSessao, $dadosGabinete, 'id');

            if ($resultGabinete['status'] == 'success') {
                $buscaGabinete = $gabineteController->buscar($gabineteSessao, 'id');
                echo '<div class="alert alert-success custom-alert px-2 py-1 mb-2" role="alert" data-timeout="3">Gabinete atualizado com sucesso!</div>';
            }
        }

        ?>

        <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">
            <div class="col-md-3 col-12">
                <input type="text" class="form-control form-control-sm" name="gabinete_nome" value="<?= $buscaGabinete['data']['nome'] ?>" placeholder="Nome do gabinete" required>
            </div>

            <div class="col-md-1 col-12">
                <select class="form-select form-select-sm" id="partidos" data-selected="<?= $buscaGabinete['data']['partido'] ?>" name="gabinete_partido" data-selected="<?= isset($buscaGabinete['data']['estado']) ? $buscaGabinete['data']['estado'] : '' ?>">
                    <option value="">Partido</option>
                </select>
            </div>

            <div class="col-md-1 col-6">
                <select class="form-select form-select-sm" id="estado" name="gabinete_estado" data-selected="<?= isset($buscaGabinete['data']['estado']) ? $buscaGabinete['data']['estado'] : '' ?>">
                    <option value=" " selected>UF</option>
                </select>
            </div>

            <div class="col-md-2 col-6">
                <select class="form-select form-select-sm" id="municipio" name="gabinete_municipio" data-selected="<?= isset($buscaGabinete['data']['estado']) ? $buscaGabinete['data']['cidade'] : '' ?>">
                    <option value=" " selected>Município</option>
                </select>
            </div>

            <div class="col-md-2 col-12">
                <button type="submit" class="btn btn-success btn-sm confirm-action" name="btn_atualizar_gabinete"><i class="bi bi-floppy-fill"></i> Atualizar</button>
            </div>
        </form>
    </div>
</div>
<div class="card mb-2">
    <div class="card-body custom-card-body p-2">
        <p class="card-text mb-2"><b>Meus dados</b></p>

        <?php
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_atualizar_usuario'])) {

            $dadosUsuario = [
                'nome' => $_POST['usuario_nome'],
                'email' => $_POST['usuario_email'],
                'telefone' => $_POST['usuario_telefone'],
                'aniversario' => $_POST['usuario_aniversario']
            ];

            if (isset($_FILES['usuario_foto']) && $_FILES['usuario_foto']['error'] === 0) {
                $dadosUsuario['foto'] = $_FILES['usuario_foto'];
            }

            $resultUsuarios = $usuarioController->atualizarUsuario($usuarioSessao, $dadosUsuario, 'id');

            if ($resultUsuarios['status'] == 'success') {
                $buscaUsuario = $usuarioController->buscar($usuarioSessao, 'id');
                echo '<div class="alert alert-success custom-alert px-2 py-1 mb-2" role="alert" data-timeout="3">Usuário atualizado com sucesso!</div>';
            }
        }

        if ($buscaUsuario['data']['aniversario'] == date('d/m')) {
            echo '<div class="alert alert-info custom-alert px-2 py-1 mb-2" role="alert"><i class="bi bi-cake"></i> Feliz aniversário!</div>';
        }

        ?>

        <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">
            <div class="col-md-2 col-12">
                <input type="text" class="form-control form-control-sm" name="usuario_nome" placeholder="Nome" value="<?= $buscaUsuario['data']['nome'] ?>" required>
            </div>
            <div class="col-md-2 col-12">
                <input type="email" class="form-control form-control-sm" name="usuario_email" placeholder="Email" value="<?= $buscaUsuario['data']['email'] ?>" required>
            </div>
            <div class="col-md-2 col-6">
                <input type="text" class="form-control form-control-sm" name="usuario_telefone" placeholder="Celular (com DDD)" data-mask="(00) 00000-0000" value="<?= $buscaUsuario['data']['telefone'] ?>" maxlength="15">
            </div>
            <div class="col-md-1 col-6">
                <input type="text" class="form-control form-control-sm" name="usuario_aniversario" data-mask="00/00" placeholder="Aniversário (dd/mm)" value="<?= $buscaUsuario['data']['aniversario'] ?>">
            </div>
            <div class="col-md-2 col-6">
                <input type="file" class="form-control form-control-sm" name="usuario_foto">
            </div>
            <div class="col-md-1 col-12">
                <button type="submit" class="btn btn-primary btn-sm confirm-action" name="btn_atualizar_usuario"><i class="bi bi-floppy-fill"></i> Atualizar</button>
            </div>
        </form>
    </div>
</div>

<?php

$protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$caminho = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$linkCadastro = "$protocolo://$host$caminho/?secao=novo-usuario&gabinete=" . urlencode($gabineteSessao);
?>

<div class="card mb-2">
    <div class="card-body custom-card-body p-2">
        <p class="card-text mb-2">Para cadastrar novos usuários no sistema, envie o endereço abaixo e solicite que criem uma conta.</p>
        <p class="card-text">Link para o cadastro de novos usuários:
            <span id="link-cadastro" style="display: none;">
                <?= $linkCadastro ?>
            </span>
            <a href="javascript:void(0);" onclick="copyToClipboard()" id="btn_imprimir"><b>Copiar</b></a>
        </p>
    </div>
</div>

<div class="card mb-2">
    <div class="card-body custom-card-body p-2">
        <p class="card-text mb-2"><b>Usuários do gabinete</b></a></p>

        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Email</th>
                        <th scope="col">Aniversário</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Nível</th>
                        <th scope="col">Ativo</th>
                        <th scope="col">Criado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    if (!empty($buscaUsuarios['data'])) {
                        foreach ($buscaUsuarios['data'] as $usuario) {
                            echo '<tr>
                                    <td>' . (($usuario['id'] !== $usuarioSessao)
                                ? '<a href="?secao=usuario&id=' . $usuario['id'] . '">' . $usuario['nome'] . '</a>'
                                : $usuario['nome']) .
                                '</td>
                                    <td>' . $usuario['email'] . '</td>
                                    <td>' . $usuario['aniversario'] . '</td>
                                    <td>' . $usuario['telefone'] . '</td>
                                    <td>' . $tipoUsuarioController->buscar($usuario['tipo_id'], 'id')['data']['nome'] . '</td>
                                    <td>' . ($usuario['ativo'] ? 'Ativado' : 'Desativado') . '</td>
                                    <td>' . date('d/m/Y H:i', strtotime($usuario['criado_em'])) . '</td>
                                </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="8">Nenhum usuário registrado</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>