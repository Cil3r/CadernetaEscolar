import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

document.addEventListener('DOMContentLoaded', () => {
    const elementoDados = document.getElementById('dados-dashboard');

    if (! elementoDados) {
        return;
    }

    const dados = JSON.parse(elementoDados.textContent);
    const escuro = document.documentElement.classList.contains('dark');
    const corTexto = escuro ? '#cfe3fb' : '#163b6d';

    const ocorrenciasCanvas = document.getElementById('grafico-ocorrencias');
    if (ocorrenciasCanvas) {
        new Chart(ocorrenciasCanvas, {
            type: 'bar',
            data: {
                labels: ['1 estrela', '2 estrelas', '3 estrelas', '4 estrelas', '5 estrelas'],
                datasets: [{
                    label: 'Ocorrências',
                    data: dados.ocorrenciasPorGravidade,
                    backgroundColor: ['#22c55e', '#84cc16', '#eab308', '#f97316', '#ef4444'],
                    borderRadius: 6,
                }],
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: corTexto }, grid: { display: false } },
                    y: { ticks: { color: corTexto, stepSize: 1 }, beginAtZero: true },
                },
            },
        });
    }

    const faltasCanvas = document.getElementById('grafico-faltas');
    if (faltasCanvas) {
        new Chart(faltasCanvas, {
            type: 'doughnut',
            data: {
                labels: ['Justificadas', 'Injustificadas'],
                datasets: [{
                    data: [dados.faltasJustificadas, dados.faltasInjustificadas],
                    backgroundColor: ['#22c55e', '#ef4444'],
                }],
            },
            options: {
                plugins: { legend: { position: 'bottom', labels: { color: corTexto } } },
            },
        });
    }
});
