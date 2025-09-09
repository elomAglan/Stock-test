📌 Test Technique – Gestion d’Articles (Backend Laravel + Frontend Vue)
🎯 Objectif

Développer une application complète (API + interface web) permettant de gérer des articles et des catégories, avec un système d’authentification utilisateur.

🛠️ Technologies utilisées

Backend : Laravel (PHP  10)

Frontend : Vue 3 (Composition API, Pinia pour le state management)

Base de données : MySQL

Authentification : JWT ou session sécurisée

API RESTful pour la communication entre frontend et backend

🚀 Fonctionnalités implémentées
🔑 Authentification

POST /api/register : inscription d’un utilisateur

POST /api/login : connexion d’un utilisateur

Protection des routes sensibles par token JWT

Gestion des permissions pour certaines actions

📝 Gestion des articles

POST /api/articles : créer un article (auth requis)

GET /api/articles : lister tous les articles

GET /api/articles/{id} : afficher un article spécifique

PUT /api/articles/{id} : modifier un article (uniquement par l’auteur)

DELETE /api/articles/{id} : supprimer un article (uniquement par l’auteur)

Champs de l’article :

title (string, requis)

description (text, requis)

stock (int, optionnel)

author (relation avec l’utilisateur connecté)

category (relation avec Category)

📂 Gestion des catégories

POST /api/categories : créer une catégorie (auth requis)

GET /api/categories : lister toutes les catégories

📐 Modèles & Relations
👤 User

Champs standards : name, email, password (haché)

Relation : un utilisateur peut créer plusieurs articles

📂 Category

name (string, requis)

Relation : une catégorie peut contenir plusieurs articles

📝 Article

title (string, requis)

description (text, requis)

stock (int, optionnel)

author (relation User)

category (relation Category)

Relations :

Un article appartient à une seule catégorie

Une catégorie peut contenir plusieurs articles

💻 Frontend (Vue)

Pages principales :

Login / Register : authentification utilisateur

Liste des articles : affichage avec filtre et recherche

Détails article : vue complète d’un article

Création / édition d’article : formulaire protégé par auth

Gestion des catégories : ajout et visualisation

Utilisation de composants réutilisables et state centralisé (Pinia)

Communication avec le backend via Axios

✅ Bonnes pratiques respectées

Code organisé en modules (controllers, services, models pour Laravel ; composants et stores pour Vue)

Validation stricte des entrées (FormRequest Laravel / Vuelidate côté Vue)

Gestion claire des erreurs (messages JSON cohérents et alertes frontend)

Respect des conventions REST

Séparation claire des responsabilités (auth, articles, catégories)

🧪 Tests

Backend : tests unitaires pour

Authentification (inscription, connexion)

CRUD articles

Gestion des catégories

Frontend : tests basiques sur les composants principaux (Vue Test Utils / Vitest)

📂 Installation & Lancement
🔹 Backend Laravel
git clone [<repo_url>](https://github.com/elomAglan/Stock-test.git)
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve

🔹 Frontend Vue
cd frontend
npm install
npm run dev

📎 Exemples d’appels API
Authentification
POST /api/register
{
  "name": "Alice",
  "email": "alice@mail.com",
  "password": "secret123"
}

POST /api/login
{
  "email": "alice@mail.com",
  "password": "secret123"
}

Création d’un article
POST /api/articles
Authorization: Bearer <token>
{
  "title": "Mon article",
  "description": "Contenu complet",
  "stock": 10,
  "category": 1
}

Gestion des catégories
POST /api/categories
Authorization: Bearer <token>
{
  "name": "Tech"
}
