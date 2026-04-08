# POC CQRS - React + Laravel + Redis

Proof of Concept d'une architecture **CQRS** (Command Query Responsibility Segregation) avec :

- **Frontend** : React 19 + TypeScript + Vite
- **Backend** : Laravel 13 (PHP 8.3)
- **Message Broker** : Redis (queues pour commands et projections)
- **Bases de donnees** : deux SQLite separees (write / read)
- **Infra** : Docker Compose (PHP-FPM, Nginx, Redis, Node)

## Architecture

```
┌──────────┐       ┌───────────────┐       ┌──────────────┐
│ Frontend │──────>│  Nginx :8080  │──────>│  Laravel API  │
│ :5173    │       └───────────────┘       └──────┬───────┘
└──────────┘                                      │
                                           ┌──────┴───────┐
                                           │              │
                                     ┌─────▼─────┐  ┌────▼─────┐
                                     │  Write DB  │  │  Read DB │
                                     │  (SQLite)  │  │ (SQLite) │
                                     └─────┬──────┘  └──────────┘
                                           │              ▲
                                           │   ┌──────────┘
                                           ▼   │
                                     ┌─────────▼──┐
                                     │   Redis    │
                                     │  (Queues)  │
                                     └─────┬──────┘
                                           │
                                     ┌─────▼──────┐
                                     │   Worker   │
                                     │(Projections)│
                                     └────────────┘
```

## Prerequis

- [Docker](https://docs.docker.com/get-docker/) et Docker Compose
- [Node.js](https://nodejs.org/) (pour l'installation locale des dependances frontend)

## Demarrage rapide

```bash
# 1. Cloner le projet
git clone <url-du-repo>
cd poc-cqrs

# 2. Installer les dependances, lancer les migrations et le seeding
make setup

# 3. Demarrer tous les services
make up
```

L'application est accessible sur :

| Service  | URL                     |
|----------|-------------------------|
| Frontend | http://localhost:5173   |
| API      | http://localhost:8080   |
| Redis    | localhost:6380          |

## Commandes Make

| Commande        | Description                                                  |
|-----------------|--------------------------------------------------------------|
| `make install`  | Installe les dependances Composer et npm                     |
| `make setup`    | Install + migrate + seed (premier lancement)                 |
| `make up`       | Demarre les conteneurs Docker en arriere-plan                |
| `make down`     | Arrete les conteneurs Docker                                 |
| `make migrate`  | Execute les migrations sur les bases write et read           |
| `make seed`     | Execute les seeders                                          |
| `make logs`     | Affiche les logs de tous les conteneurs (follow)             |
| `make reset`    | Remet a zero les bases de donnees et relance migrations/seed |
| `make demo`     | Lance le seeder de demo                                      |
| `make artisan`  | Execute une commande Artisan (`make artisan cmd=...`)        |

## Services Docker

| Service    | Image / Build          | Role                                      |
|------------|------------------------|-------------------------------------------|
| `app`      | PHP 8.3 FPM Alpine     | Application Laravel                       |
| `nginx`    | Nginx Alpine           | Reverse proxy, sert l'API sur le port 8080|
| `worker`   | PHP 8.3 FPM Alpine     | Worker de queues (commands, projections)   |
| `redis`    | Redis 7 Alpine         | Message broker et cache                   |
| `frontend` | Node 22 Alpine         | Serveur de dev Vite (React)               |

## Structure du projet

```
poc-cqrs/
├── backend/             # Application Laravel (API CQRS)
│   ├── app/
│   ├── config/
│   ├── database/
│   │   ├── migrations/
│   │   │   ├── write/   # Migrations base d'ecriture
│   │   │   └── read/    # Migrations base de lecture
│   │   ├── write.sqlite
│   │   └── read.sqlite
│   └── routes/
├── frontend/            # Application React + TypeScript
│   └── src/
├── docker/
│   ├── nginx/           # Configuration Nginx
│   └── php/             # Dockerfile PHP
├── docker-compose.yml
└── Makefile
```

## Reinitialisation

Pour repartir de zero (supprime et recree les bases SQLite) :

```bash
make reset
```
