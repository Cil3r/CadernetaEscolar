# Caderneta Digital

Aplicação web para registo e consulta de ocorrências, faltas e cadernetas de alunos.

## Stack
Laravel · MySQL · Blade · Tailwind CSS

## Como correr localmente
```bash
git clone https://github.com/Cil3r/CadernetaEscolar.git
cd CadernetaEscolar
git checkout develop
composer install
npm install
cp .env.example .env
php artisan key:generate
```

### Base de dados (MySQL)
```bash
sudo mysql -e "CREATE DATABASE caderneta_digital CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'caderneta'@'localhost' IDENTIFIED BY 'caderneta123'; GRANT ALL PRIVILEGES ON caderneta_digital.* TO 'caderneta'@'localhost'; FLUSH PRIVILEGES;"
```
No `.env`, configurar:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=caderneta_digital
DB_USERNAME=caderneta
DB_PASSWORD=caderneta123
```
Depois:
```bash
php artisan migrate
npm run dev        # num terminal
php artisan serve  # noutro terminal
```

## Fluxo Git
- `main`: versão estável
- `develop`: integração
- `feature/*`: uma branch por tarefa, sempre criada a partir de `develop`
- Commits no formato Conventional Commits (`feat:`, `fix:`, `chore:`, `docs:`)
- Nunca fazer commit do `.env`
- Todo o trabalho entra em `develop` por Pull Request
