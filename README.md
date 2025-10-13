# Laravel 12 E-vote

Aplikasi e-voting berbasis Laravel 12.

## Fitur
- Multi-Election, Position, Candidate
- 1 user = 1 suara
- Dashboard Admin & User
- Export hasil (CSV/PDF) *(opsional)*
- Grafik realtime *(opsional)*

## Prasyarat
- PHP 8.1+
- Composer 2.x
- MySQL/MariaDB
- Node.js 18+ & npm

## Instalasi
```bash
git clone https://github.com/FAJRIAG/laravel12_E-vote.git
cd laravel12_E-vote
composer install
cp .env.example .env
php artisan key:generate
