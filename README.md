# CineMap - TP Laravel

Application de gestion d'emplacements de tournage de films. Les utilisateurs peuvent consulter des films et proposer des lieux où ils ont été tournés.

---

## Prérequis

- PHP 8.2+
- Composer
- Node.js
- SQLite (par défaut) ou MySQL

---

## Installation

```bash
git clone <repo>
cd TP_PHP

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Ensuite remplir les variables dans `.env` (voir les sections ci-dessous).

```bash
php artisan migrate
```

---

## Lancer le projet

```bash
php artisan serve
npm run dev
```

---

## Étape 1 - Authentification

Utilisation du starter kit Breeze. Inscription, connexion et déconnexion disponibles par défaut.

Après connexion, l'utilisateur est redirigé vers `/dashboard`.

---

## Étape 2 - CRUDs

Deux CRUDs principaux :

- **Films** : liste, détail, création, modification, suppression (admin uniquement pour edit/delete)
- **Locations** : liste, détail, création (utilisateur connecté), modification et suppression réservées à l'admin ou au créateur

---

## Étape 3 - Middleware admin

Un middleware `admin` a été créé dans `app/Http/Middleware/Isadmin.php`.

Il vérifie que `is_admin = true` sur l'utilisateur connecté, sinon retourne un 403.

Pour passer un utilisateur en admin, directement en base :

```sql
UPDATE users SET is_admin = 1 WHERE email = 'ton@email.com';
```

---

## Étape 4 - Queues et Jobs

Les upvotes passent par un job `RecalculateUpvotes` qui incrémente `upvotes_count` sur la location.

La queue est configurée en `database` dans `.env` (déjà mis par défaut).

Lancer le worker :

```bash
php artisan queue:work
```

Un simple bouton "upvote" sur la page d'une location déclenche le job.

---

## Étape 5 - Commande Artisan + scheduler

Commande : `location:cleanup`

Elle supprime les locations créées depuis plus de 14 jours avec moins de 2 upvotes.

Tester manuellement :

```bash
php artisan location:cleanup
```

La commande est enregistrée dans le scheduler (`routes/console.php`) pour tourner tous les jours.

Pour simuler le scheduler en local :

```bash
php artisan schedule:run
```

---

## Étape 6 - Laravel Pint

Formater le code :

```bash
./vendor/bin/pint
```

---

## Étape 7 - Connexion OAuth (Google / GitHub)

Ajouter dans `.env` :

```env
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
GITHUB_REDIRECT_URI=http://localhost/auth/github/callback

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback
```

Créer une OAuth App sur GitHub ou Google Cloud Console, coller les identifiants ci-dessus.

Sur la page de login un bouton "Se connecter avec GitHub" et "Se connecter avec Google" sont disponibles. Si l'email existe déjà en base, l'utilisateur est connecté à son compte existant.

---

## Étape 8 - Stripe + API JWT

### Stripe

Ajouter dans `.env` :

```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
```

Utiliser la carte de test Stripe : `4242 4242 4242 4242`, date future, CVC quelconque.

Le paiement de 5€ est demandé lors de la création d'une location.

### JWT

Générer la clé JWT :

```bash
php artisan jwt:secret
```

**Obtenir un token :**

```bash
POST /api/login
{
  "email": "ton@email.com",
  "password": "monpassword"
}
```

Réponse :

```json
{
  "token": "eyJ...",
  "token_type": "bearer"
}
```

**Appeler la route protégée :**

```bash
GET /api/films/{id}/locations
Authorization: Bearer eyJ...
```

Retourne les infos du film et la liste de ses emplacements avec le nombre d'upvotes.

---

## Étape 9 - MCP

Le serveur MCP est disponible à `/mcp/cinemap` (transport HTTP).

Deux outils exposés :

- `list_films` : liste tous les films avec leur année et le nombre de lieux
- `get_locations_for_film` : retourne les emplacements d'un film (paramètre : `film_id`)

### Lancer l'inspector MCP

```bash
env -i HOME=$HOME PATH=$PATH CLIENT_PORT=5174 npx @modelcontextprotocol/inspector@0.4.0 \
  --transport http \
  --server-url http://localhost/mcp/cinemap
```

Ouvre ensuite `http://localhost:5174` dans le navigateur.

> Si le port 5174 est déjà pris, changer `CLIENT_PORT` par un autre port libre.

### Connecter un client MCP (Claude Desktop, etc.)

```json
{
  "mcpServers": {
    "cinemap": {
      "type": "http",
      "url": "http://localhost/mcp/cinemap"
    }
  }
}
```

---

## Variables d'environnement résumé

```env
# App
APP_URL=http://localhost

# Queue
QUEUE_CONNECTION=database

# OAuth
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
GITHUB_REDIRECT_URI=http://localhost/auth/github/callback
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback

# Stripe
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...

# JWT (généré via php artisan jwt:secret)
JWT_SECRET=
```
# TPB3LARAVEL
