<?php
$tituloPagina = 'Pessoas atendidas';
require __DIR__ . '/../layouts/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="h3 mb-1">Pessoas atendidas</h1>
        <p class="text-secondary mb-0">Cadastro, edição e inativação sem excluir o histórico.</p>
    </div>
    <button class="btn btn-success" type="button" onclick="novaPessoa()">Nova pessoa</button>
</div>

<div id="alerta"></div>

<div class="card border-0 shadow-sm mb-4 d-none" id="cardFormulario">
    <div class="card-body">
        <h2 class="h5" id="tituloFormulario">Nova pessoa</h2>
        <form id="formPessoa">
            <input type="hidden" name="id" id="pessoaId">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nome *</label>
                    <input class="form-control" name="nome" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Documento (CPF) *</label>
                    <input class="form-control" id="inputCPF" name="documento" maxlength="14" placeholder="000.000.000-00" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Telefone</label>
                    <input class="form-control" id="inputTelefone" name="telefone" maxlength="15" placeholder="(00) 00000-0000">
                </div>
                <div class="col-md-6">
                    <label class="form-label">E-mail *</label>
                    <input class="form-control" type="email" name="email" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Curso</label>
                    <input class="form-control" name="curso">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Período</label>
                    <div class="input-group">
                        <input class="form-control" type="number" name="periodo" min="1" max="12" placeholder="Ex: 1, 2">
                        <span class="input-group-text">º</span>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Observações</label>
                    <textarea class="form-control" name="observacoes" rows="2"></textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="ativo">Ativo</option>
                        <option value="inativo">Inativo</option>
                    </select>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-success" type="submit">Salvar</button>
                <button class="btn btn-outline-secondary" type="button" onclick="fecharFormulario()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>Documento</th>
                    <th>E-mail</th>
                    <th>Curso</th>
                    <th>Período</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody id="tabelaPessoas">
                <tr><td colspan="7" class="text-center py-4">Carregando...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
const formPessoa = document.getElementById('formPessoa');
const cardFormulario = document.getElementById('cardFormulario');
const inputCPF = document.getElementById('inputCPF');
const inputTelefone = document.getElementById('inputTelefone');

// MÁSCARAS AUTOMÁTICAS (Segurança extra contra erros de digitação)
inputCPF.addEventListener('input', e => {
    let v = e.target.value.replace(/\D/g, "");
    if (v.length > 11) v = v.slice(0, 11);
    if (v.length > 9) v = v.replace(/^(\d{3})(\d{3})(\d{3})(\d{1,2})$/, "$1.$2.$3-$4");
    else if (v.length > 6) v = v.replace(/^(\d{3})(\d{3})(\d{1,3})$/, "$1.$2.$3");
    else if (v.length > 3) v = v.replace(/^(\d{3})(\d{1,3})$/, "$1.$2");
    e.target.value = v;
});

inputTelefone.addEventListener('input', e => {
    let v = e.target.value.replace(/\D/g, "");
    if (v.length > 11) v = v.slice(0, 11);
    if (v.length > 10) v = v.replace(/^(\d{2})(\d{5})(\d{4})$/, "($1) $2-$3");
    else if (v.length > 6) v = v.replace(/^(\d{2})(\d{4})(\d{1,4})$/, "($1) $2-$3");
    else if (v.length > 2) v = v.replace(/^(\d{2})(\d{1,4})$/, "($1) $2");
    else if (v.length > 0) v = v.replace(/^(\d{1,2})$/, "($1");
    e.target.value = v;
});

function abrirFormulario() {
    cardFormulario.classList.remove('d-none');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function fecharFormulario() {
    cardFormulario.classList.add('d-none');
    formPessoa.reset();
    document.getElementById('pessoaId').value = '';
}

function novaPessoa() {
    fecharFormulario();
    document.getElementById('tituloFormulario').textContent = 'Nova pessoa';
    abrirFormulario();
}

async function carregarPessoas() {
    try {
        const dados = AtendeLabApi.toList(await AtendeLabApi.get('pessoas', 'listar'));
        const tbody = document.getElementById('tabelaPessoas');
        if (!dados.length) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4">Nenhuma pessoa cadastrada.</td></tr>';
            return;
        }
        tbody.innerHTML = dados.map(p => `
            <tr>
                <td>${AtendeLabApi.escape(p.nome)}</td>
                <td>${AtendeLabApi.escape(p.documento)}</td>
                <td>${AtendeLabApi.escape(p.email)}</td>
                <td>${AtendeLabApi.escape(p.curso || '')}</td>
                <td>${p.periodo ? AtendeLabApi.escape(p.periodo) + 'º' : ''}</td>
                <td><span class="badge ${p.status === 'ativo' ? 'text-bg-success' : 'text-bg-secondary'}">${AtendeLabApi.escape(p.status)}</span></td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-primary" onclick="editarPessoa(${Number(p.id)})">Editar</button>
                    <button class="btn btn-sm btn-outline-danger" onclick="inativarPessoa(${Number(p.id)})">Inativar</button>
                </td>
            </tr>
        `).join('');
    } catch (error) {
        AtendeLabApi.showAlert('alerta', error.message, 'danger');
    }
}

async function editarPessoa(id) {
    try {
        const p = AtendeLabApi.toObject(await AtendeLabApi.get('pessoas', 'buscar', { id }));
        
        // Configura o estado visual do formulário de edição
        cardFormulario.classList.remove('d-none');
        formPessoa.reset();
        document.getElementById('tituloFormulario').textContent = 'Editar pessoa';
        window.scrollTo({ top: 0, behavior: 'smooth' });

        // Garante a injeção manual do ID para evitar falhas de mapeamento
        document.getElementById('pessoaId').value = id;

        for (const [key, value] of Object.entries(p)) {
            const field = formPessoa.elements.namedItem(key);
            if (field && key !== 'id') {
                // Se o período antigo tiver "º", extrai apenas o número puro antes de injetar
                field.value = key === 'periodo' && value ? parseInt(value) : (value ?? '');
            }
        }

        // Força a atualização visual das máscaras
        inputCPF.dispatchEvent(new Event('input'));
        inputTelefone.dispatchEvent(new Event('input'));
    } catch (error) {
        AtendeLabApi.showAlert('alerta', error.message, 'danger');
    }
}

formPessoa.addEventListener('submit', async event => {
    event.preventDefault();
    const id = document.getElementById('pessoaId').value;
    try {
        await AtendeLabApi.post('pessoas', id ? 'atualizar' : 'criar', new FormData(formPessoa));
        AtendeLabApi.showAlert('alerta', id ? 'Pessoa updated com sucesso.' : 'Pessoa cadastrada com sucesso.');
        fecharFormulario();
        await carregarPessoas();
    } catch (error) {
        AtendeLabApi.showAlert('alerta', error.message, 'danger');
    }
});

async function inativarPessoa(id) {
    if (!confirm('Deseja inativar esta pessoa?')) return;
    try {
        await AtendeLabApi.post('pessoas', 'inativar', { id });
        AtendeLabApi.showAlert('alerta', 'Pessoa inativada com sucesso.');
        await carregarPessoas();
    } catch (error) {
        AtendeLabApi.showAlert('alerta', error.message, 'danger');
    }
}

document.addEventListener('DOMContentLoaded', carregarPessoas);
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>