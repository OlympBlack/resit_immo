# RESIT_Immo — Gestion Locative

Application de gestion immobilière moderne développée avec **Laravel 11** (Backend) et **Nuxt 3 / Vue 3** (Frontend).

## 🚀 Fonctionnalités
- ✨ Dashboard avec statistiques en temps réel.
- 🏠 Gestion des Biens (CRUD).
- 👤 Gestion des Locataires (CRUD).
- 📄 Gestion des Contrats de location.
- 🔐 Authentification sécurisée (Sanctum).
- 🎨 Interface Premium avec Nuxt UI & TailwindCSS.

---

## 🛠 Installation & Lancement

### 1. Prérequis
- PHP 8.2+
- Composer
- Node.js 18+ & pnpm
- MySQL ou SQLite

### 2. Backend (Laravel)
```bash
# Aller dans le dossier backend
cd backend

# Installer les dépendances
composer install

# Configurer l'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate

# Lancer les migrations et les seeders (compte démo)
php artisan migrate --seed

# Lancer le serveur
php artisan serve
```
*Le serveur backend tourne par défaut sur `http://localhost:8000`.*

### 3. Frontend (Nuxt/Vue)
```bash
# Aller dans le dossier frontend
cd frontend

# Installer les dépendances
pnpm install

# Configurer l'environnement (si nécessaire)
# Par défaut l'API pointe sur http://localhost:8000/api
cp .env.example .env

# Lancer le serveur de développement
pnpm dev
```
*L'application est accessible sur `http://localhost:3000`.*

---

## 🔑 Identifiants de Démonstration
Pour tester l'application immédiatement après avoir lancé les seeders :
- **Email** : `admin@resit-immo.fr`
- **Mot de passe** : `password123`

## 📚 Documentation API
Une fois le serveur backend lancé, la documentation Swagger est disponible sur :
`http://localhost:8000/api/documentation`
