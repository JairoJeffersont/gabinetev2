$(function () {

    // Quando clicar em qualquer elemento com a classe .link_loading
    $('.link_loading').on('click', function (e) {
        //e.preventDefault(); // Evita o comportamento padrão do link (navegar para outra página)

        // Mostra o modal de carregamento (Bootstrap)
        const modal = new bootstrap.Modal($('#modalLoading')[0]);
        modal.show();
    });

    // Quando qualquer formulário for submetido
    $('form').on('submit', function (e) {
        // Mostra o modal de carregamento
        const modal = new bootstrap.Modal($('#modalLoading')[0]);
        modal.show();
    });

    // Para todos os alerts que têm o atributo data-timeout
    $('.alert[data-timeout]').each(function () {
        const $alert = $(this); // Alerta atual
        const timeout = parseInt($alert.attr('data-timeout')); // Tempo em segundos

        // Se o timeout for maior que 0, programa o fade out
        if (timeout > 0) {
            setTimeout(() => {
                // Faz o fadeOut (animação de desaparecimento) e depois remove o elemento do DOM
                $alert.fadeOut(500, function () {
                    $(this).remove();
                });
            }, timeout * 1000); // Converte para milissegundos
        }
    });

    // Confirmação de ações
    $('.confirm-action').on('click', function (e) {
        // Lê a mensagem do data-message, ou usa a padrão
        const msg = $(this).data('message') || "Deseja continuar?";

        // Mostra o diálogo de confirmação
        if (!confirm(msg)) {
            e.preventDefault(); // Se o usuário cancelar, evita que a ação continue
        }
    });

    // Scripts para carregar estados e municípios da API do IBGE
    $(document).ready(function () {
        const $estado = $('#estado');       // Select dos estados
        const $municipio = $('#municipio'); // Select dos municípios

        /**
         * Carrega todos os estados brasileiros via API do IBGE
         * e seleciona o estado informado (caso exista).
         */
        async function carregarEstados(selectedEstado) {
            try {
                const res = await fetch('https://servicodados.ibge.gov.br/api/v1/localidades/estados');
                const estados = await res.json();

                // Ordena os estados por nome
                estados.sort((a, b) => a.nome.localeCompare(b.nome));

                // Limpa o select e adiciona a opção padrão
                $estado.empty().append('<option value="">Selecione um estado</option>');

                // Adiciona cada estado ao select
                estados.forEach(estado => {
                    const isSelected = estado.sigla === selectedEstado ? 'selected' : '';
                    $estado.append(`<option value="${estado.sigla}" ${isSelected}>${estado.nome}</option>`);
                });

                // Se um estado já foi selecionado anteriormente, carrega os municípios correspondentes
                if (selectedEstado) {
                    $municipio.prop('disabled', false);
                    await carregarMunicipios(selectedEstado, $municipio.data('selected'));
                }
            } catch (error) {
                console.error('Erro ao carregar estados:', error);
            }
        }

        /**
         * Carrega todos os municípios de um estado selecionado
         * e marca o município previamente selecionado se existir.
         */
        async function carregarMunicipios(uf, selectedMunicipio) {
            // Se o estado não foi selecionado, desabilita e limpa o select de municípios
            if (!uf) {
                $municipio.empty().append('<option value="">Selecione um município</option>').prop('disabled', true);
                return;
            }

            try {
                const res = await fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${uf}/municipios`);
                const municipios = await res.json();

                // Ordena os municípios por nome
                municipios.sort((a, b) => a.nome.localeCompare(b.nome));

                // Limpa o select e adiciona a opção padrão
                $municipio.empty().append('<option value="">Selecione um município</option>');

                // Adiciona os municípios ao select
                municipios.forEach(mun => {
                    const isSelected = mun.nome === selectedMunicipio ? 'selected' : '';
                    $municipio.append(`<option value="${mun.nome}" ${isSelected}>${mun.nome}</option>`);
                });

                // Habilita o select
                $municipio.prop('disabled', false);
            } catch (error) {
                console.error('Erro ao carregar municípios:', error);
            }
        }

        /**
         * Quando o select de estado mudar, recarrega os municípios do novo estado.
         */
        $estado.on('change', function () {
            const uf = $(this).val();
            carregarMunicipios(uf, null); // Limpa município selecionado anterior
        });

        // Lê os valores selecionados previamente via atributos data-selected
        const selectedEstado = $estado.data('selected') || '';
        const selectedMunicipio = $municipio.data('selected') || '';

        // Carrega estados e depois os municípios, se for o caso
        carregarEstados(selectedEstado);
    });

});


function copyToClipboard() {
    // Pega o link do elemento com o id 'link-cadastro'
    var link = document.getElementById('link-cadastro').innerText;

    // Cria um elemento de input para copiar o texto para a Ã¡rea de transferÃªncia
    var tempInput = document.createElement('input');
    tempInput.value = link;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);

    // Opcional: pode adicionar um feedback visual aqui, como um alert ou tooltip
    alert('Link copiado!');
}


$(document).ready(function () {
    const $select = $('#partidos');
    const dataSelected = $select.data('selected'); // Lê o valor de data-selected

    // Mostra a opção de carregamento
    $select.html('<option disabled selected>Carregando partidos...</option>');

    $.ajax({
        url: "https://dadosabertos.camara.leg.br/api/v2/partidos?itens=100&ordenarPor=sigla",
        method: "GET",
        dataType: "json",
        success: function (resposta) {
            const partidos = resposta.dados;

            // Limpa todas as opções anteriores
            $select.empty();

            // Adiciona uma primeira opção padrão
            $select.append('<option value="">Selecione um partido</option>');

            // Adiciona os partidos ao select
            partidos.forEach(function (partido) {
                const selected = dataSelected == partido.sigla ? 'selected' : '';
                $select.append(`<option value="${partido.sigla}" ${selected}>${partido.sigla}</option>`);
            });
        },
        error: function () {
            $select.html('<option disabled selected>Erro ao carregar os partidos</option>');
            alert("Erro ao carregar os partidos.");
        }
    });
});

