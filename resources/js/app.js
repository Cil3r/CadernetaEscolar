function aplicarTema(tema) {
    document.documentElement.classList.toggle('dark', tema === 'escuro');
    localStorage.setItem('caderneta-tema', tema);
}

function temaAtual() {
    return localStorage.getItem('caderneta-tema')
        ?? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'escuro' : 'claro');
}

document.addEventListener('DOMContentLoaded', () => {
    aplicarTema(temaAtual());

    document.querySelectorAll('[data-alternar-tema]').forEach((botao) => {
        botao.addEventListener('click', () => {
            aplicarTema(temaAtual() === 'escuro' ? 'claro' : 'escuro');
        });
    });

    const botaoMenu = document.querySelector('[data-abrir-menu]');
    const sidebar = document.querySelector('[data-sidebar]');
    const sobreposicao = document.querySelector('[data-sobreposicao-menu]');

    const fecharMenu = () => {
        sidebar?.classList.add('-translate-x-full');
        sobreposicao?.classList.add('hidden');
    };

    botaoMenu?.addEventListener('click', () => {
        sidebar?.classList.remove('-translate-x-full');
        sobreposicao?.classList.remove('hidden');
    });

    sobreposicao?.addEventListener('click', fecharMenu);

    document.querySelectorAll('[data-dropdown-botao]').forEach((botao) => {
        const painel = document.querySelector(`[data-dropdown-painel="${botao.dataset.dropdownBotao}"]`);

        botao.addEventListener('click', (evento) => {
            evento.stopPropagation();
            const aberto = !painel?.classList.contains('hidden');

            document.querySelectorAll('[data-dropdown-painel]').forEach((p) => p.classList.add('hidden'));

            if (!aberto) {
                painel?.classList.remove('hidden');
            }
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('[data-dropdown-painel]').forEach((p) => p.classList.add('hidden'));
    });
});
