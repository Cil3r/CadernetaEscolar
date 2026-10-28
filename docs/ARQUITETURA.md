# Arquitetura da Caderneta Digital

## Estrutura de pastas
```
app/
  Http/Controllers/   Auth/ · DashboardController · AlunoController · TurmaController
                      OcorrenciaController · FaltaController · UtilizadorController
                      ConfiguracaoController
  Http/Middleware/    PerfilMiddleware (direcao | professor)
  Http/Requests/      validação dos formulários
  Models/             User · Turma · Aluno · Ocorrencia · Falta · Configuracao
  Policies/           TurmaPolicy · AlunoPolicy
database/             migrations · seeders · factories
resources/
  views/layouts/      app.blade.php · guest.blade.php
  views/components/   cartões, tabela, estrelas, modal, alertas, estado vazio
  views/              auth · dashboard · alunos · turmas · ocorrencias · faltas
                      utilizadores · configuracoes
  css/app.css · js/app.js
routes/web.php
docs/                 ARQUITETURA.md
```

## Modelo de dados
| Tabela | Campos principais |
|---|---|
| users | nome, email, password, perfil (direcao/professor), ativo |
| turmas | nome, classe, curso, ano_letivo, periodo |
| turma_professor | turma_id, user_id, disciplina |
| alunos | numero_processo, nome, data_nascimento, genero, encarregado_nome, encarregado_contacto, turma_id, ativo |
| ocorrencias | aluno_id, user_id, data, tipo, descricao, gravidade (1 a 5) |
| faltas | aluno_id, user_id, data, justificada, motivo |
| configuracoes | chave, valor |

## Rotas por perfil
| Rota | Página | Direção | Professor |
|---|---|---|---|
| /login | Entrar | sim | sim |
| /dashboard | Dashboard | global | só as suas turmas |
| /turmas | Turmas | criar/editar | só ver as suas |
| /alunos | Alunos | CRUD | só as suas turmas |
| /alunos/{id}/caderneta | Caderneta do aluno | sim | só da sua turma |
| /ocorrencias | Ocorrências | sim | as suas turmas |
| /faltas | Faltas | sim | as suas turmas |
| /utilizadores | Utilizadores | sim | não |
| /configuracoes | Limiares de alerta | sim | não |
| /perfil | Perfil | sim | sim |

## Roadmap
| Fase | Conteúdo | Quem |
|---|---|---|
| 1 | Setup + Git | EU (concluída) |
| 2 | Migrations, models, seeders | EU |
| 3 | Autenticação por perfil + layout base (sidebar, header, dark/light) | EU |
| 4 | Turmas e utilizadores | COLEGA 1 |
| 5 | Alunos | COLEGA 2 |
| 6 | Ocorrências com estrelas e filtros | EU |
| 7 | Faltas e contagens | COLEGA 2 |
| 8 | Caderneta do aluno | EU |
| 9 | Dashboard | EU |
| 10 | Configurações, polimento, testes, deploy | Todos |
