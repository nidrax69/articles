Zelty
Ce projet est une API Laravel pour gérer des articles.

Installation
Dépendances
Avant d'installer le projet, assurez-vous que les dépendances suivantes sont installées sur votre système :

PHP >= 8.2
Composer
SQLite (ou tout autre SGBD pris en charge par Laravel)
Étapes
Clonez le projet à partir de GitHub :

bash
Copy code
git clone https://github.com/nidrax69/zelty.git
Accédez au dossier du projet :

bash
Copy code
cd zelty
Installez les dépendances :

bash
Copy code
composer install
Copiez le fichier .env.example en .env :

bash
Copy code
cp .env.example .env
Générez une clé d'application Laravel :

bash
Copy code
php artisan key:generate
Créez la base de données SQLite :

bash
Copy code
touch database/database.sqlite
Exécutez les migrations pour créer les tables de la base de données :

bash
Copy code
php artisan migrate
(Optionnel) Ajoutez des données de test en exécutant le seeder :

bash
Copy code
php artisan db:seed
Lancez le serveur de développement :

bash
Copy code
php artisan serve
L'API est maintenant accessible à l'adresse http://localhost:8000/api.

Documentation
Authentification
L'API utilise un jeton d'authentification pour les requêtes protégées. Pour obtenir un jeton, envoyez une requête POST à http://localhost:8000/api/login avec les informations d'identification de l'utilisateur. Le jeton sera inclus dans la réponse.

Endpoints
Voici la liste des endpoints disponibles :

Endpoint	Méthode	Description
/api/login	POST	Obtient un jeton d'authentification
/api/articles	GET	Récupère une liste d'articles
/api/articles	POST	Crée un nouvel article
/api/articles/{id}	GET	Récupère un article par ID
/api/articles/{id}	PUT	Met à jour un article par ID
/api/articles/{id}	DELETE	Supprime un article par ID
Paramètres de requête
Les endpoints suivants acceptent des paramètres de requête :

/api/articles : q (recherche par titre et auteur), status (statut de l'article : "draft" ou "published"), per_page (nombre d'articles par page)
Exemples de requêtes
Recherche d'articles contenant le mot "Laravel" :

bash
Copy code
GET /api/articles?q=Laravel

