# mini-linkedin

API REST d'une plateforme de recrutement construite avec Laravel 11 et JWT.  
Projet réalisé dans le cadre du cours Technologies Backend.

---

## Prérequis

- PHP >= 8.2
- Composer
- MySQL
- [tymon/jwt-auth](https://github.com/tymondesigns/jwt-auth)

---

## Installation

```bash
# 1. Cloner le dépôt
git clone https://github.com/<votre-username>/mini-linkedin.git
cd mini-linkedin

# 2. Installer les dépendances
composer install

# 3. Copier le fichier d'environnement
cp .env.example .env

# 4. Configurer la base de données dans .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mini_linkedin
DB_USERNAME=root
DB_PASSWORD=

# 5. Générer les clés
php artisan key:generate
php artisan jwt:secret

# 6. Lancer les migrations et le seeder
php artisan migrate:fresh --seed

# 7. Démarrer le serveur
php artisan serve
```

---

## Données de test (seeder)

Le seeder génère : 2 admins, 5 recruteurs (2 à 3 offres chacun), 10 candidats (avec profil et compétences).  
Le mot de passe de tous les comptes générés est `password`.

Pour créer un compte manuellement :

```
POST /api/register
{
  "name": "...",
  "email": "...",
  "password": "password",
  "password_confirmation": "password",
  "role": "candidat" | "recruteur"
}
```

> Les comptes admin ne peuvent pas être créés via l'API.

---

## Récapitulatif des routes

### Authentification

| Méthode | Route           | Accès    | Description         |
|---------|-----------------|----------|---------------------|
| POST    | `/api/register` | Public   | Créer un compte     |
| POST    | `/api/login`    | Public   | Se connecter (JWT)  |
| POST    | `/api/logout`   | Connecté | Se déconnecter      |
| POST    | `/api/refresh`  | Connecté | Rafraîchir le token |
| GET     | `/api/me`       | Connecté | Infos utilisateur   |

### Profil

| Méthode | Route                                    | Accès    | Description                          |
|---------|------------------------------------------|----------|--------------------------------------|
| POST    | `/api/profil`                            | Candidat | Créer son profil (une seule fois)    |
| GET     | `/api/profil`                            | Candidat | Consulter son profil                 |
| PUT     | `/api/profil`                            | Candidat | Modifier son profil                  |
| POST    | `/api/profil/competences`                | Candidat | Ajouter une compétence (avec niveau) |
| DELETE  | `/api/profil/competences/{competenceId}` | Candidat | Retirer une compétence               |

### Offres d'emploi

| Méthode | Route                 | Accès     | Description                                    |
|---------|-----------------------|-----------|------------------------------------------------|
| GET     | `/api/offres`         | Public    | Liste des offres actives (filtre + pagination) |
| GET     | `/api/offres/{offre}` | Public    | Détail d'une offre                             |
| POST    | `/api/offres`         | Recruteur | Créer une offre                                |
| PUT     | `/api/offres/{offre}` | Recruteur | Modifier une offre (propriétaire uniquement)   |
| DELETE  | `/api/offres/{offre}` | Recruteur | Supprimer une offre (propriétaire uniquement)  |

Filtres disponibles sur `GET /api/offres` : `?localisation=Casablanca&type=CDI`  
Pagination : 10 offres par page, triées par date de création.

### Candidatures

| Méthode | Route                                    | Accès     | Description                                       |
|---------|------------------------------------------|-----------|---------------------------------------------------|
| POST    | `/api/offres/{offre}/candidater`         | Candidat  | Postuler à une offre                              |
| GET     | `/api/mes-candidatures`                  | Candidat  | Consulter ses propres candidatures                |
| GET     | `/api/offres/{offre}/candidatures`       | Recruteur | Candidatures reçues (propriétaire uniquement)     |
| PATCH   | `/api/candidatures/{candidature}/statut` | Recruteur | Changer le statut (en_attente, acceptee, refusee) |

### Administration

| Méthode | Route                       | Accès | Description                    |
|---------|-----------------------------|-------|--------------------------------|
| GET     | `/api/admin/users`          | Admin | Liste de tous les utilisateurs |
| DELETE  | `/api/admin/users/{user}`   | Admin | Supprimer un compte            |
| PATCH   | `/api/admin/offres/{offre}` | Admin | Activer / désactiver une offre |

---

## Events & Listeners

Deux événements sont déclenchés automatiquement et loggés dans `storage/logs/candidatures.log` :

- **CandidatureDeposee** — déclenché lors d'un dépôt de candidature. Enregistre la date, le nom du candidat et le titre de l'offre.
- **StatutCandidatureMis** — déclenché lors d'un changement de statut. Enregistre la date, l'ancien statut et le nouveau statut.

---

## Structure du projet

```
app/
├── Events/           # CandidatureDeposee, StatutCandidatureMis
├── Listeners/        # LogCandidatureDeposee, LogStatutCandidatureMis
├── Http/
│   ├── Controllers/  # Auth, Profil, Offre, Candidature, Admin
│   ├── Middleware/   # RoleMiddleware
│   └── Requests/     # StoreOffreRequest, StoreProfilRequest, UpdateProfilRequest
├── Models/           # User, Profil, Competence, Offre, Candidature
└── Providers/        # AppServiceProvider (enregistrement des events)
database/
├── migrations/
├── factories/
└── seeders/
postman/              # Collection Postman (.json)
routes/
└── api.php
```

---

## Collection Postman

Une collection Postman couvrant l'ensemble des endpoints est disponible dans le dossier `postman/`.  
Elle inclut les scénarios suivants : inscription, connexion, CRUD profil, CRUD offres, candidature, changement de statut, ainsi que les cas d'erreur (401, 403, 422).
