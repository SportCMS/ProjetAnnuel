# SportCMS

Création d'un cms en PHP from scratch

## Pour commencer

Suivre les parties suivantes pour débuter le projet !

### Intégration de SportCMS

#### Pré-requis

- Installer NodeJS et npm : https://nodejs.org/en/
- Installer Docker : https://www.docker.com/

#### Intégration Front-end et Back-end

- Cloner le répertoire : ``git clone https://github.com/SportCMS/ProjetAnnuel.git``
- Se rendre dans le répertoire : ``cd ProjetAnnuel``
- Installer les dépendances front : ``npm init`` au sein du projet
- Installer sass : ``npm install sass``  

## Démarrage

- Lancement du fichier docker-compose.yml via : ``docker-compose up`` à la racine du projet
- Exécutez dans le dossier webpack la commande : ``npm run watch``
- Ouvrir le fichier [index.php](index.php) dans votre navigateur

## Fabriqué avec

* [Scss](https://sass-lang.com/) - Compilateur Scss (front-end)
* [Visual Studio Code](https://code.visualstudio.com/) - Editeur de textes

## Implémentation design patterns - PHP

### Singleton 

Nous avons utilisé le design patern singleton pour la connexion à la base de données.  
Étant donné que nous avons énormément de connexion à la base de données, notamment avec les models, ce design patern s’est révélé très utile.  

- Chemin singleton : www/core/Db.class.php
- On utilise le singleton dans le fichier : www/core/Sql.class.php

### Query Builder

Nous avons utilisé le Query Builder pour faciliter la création de requête sql.  
Pour la création de requête nous avons utilisé le système Repository de Symfony.  
Chaque model à son dossier "QueryClass" avec l’ensemble de ses requêtes SQL.  
Chaque fichier QueryClass est l’enfant de la class Query, qui instancie la class MysqlBuilder et qui a des méthodes pour l’ensemble des QueryClass (get($id), getAll(), getBy($criterias)).   

- Chemins Query Builder : 
    - www/core/QueryBuilder.class.php
    - www/core/MysqlBuilder.class.php
    - www/core/Query.class.php
- Chemin fichiers querys :
    - www/querys/QueryLike.class.php
    - www/querys/QueryPassword_reset.class.php
    - www/querys/QueryUser.class.php
- Chemins fichiers utilisation Query builder :
    - www/controller/Article.class.php
    - www/controller/User.class.php

### Observeur 

Nous avons utilisé l’observateur pour l’envoi de mail. Ce design pattern s'est révélé très utile notamment pour envoyer des mails pour les reports et les nouveaux articles.

- Chemins Observeur :
    - www/core/Observer.class.php
    - www/core/ArticleNotification.class.php
    - www/core/ReportNotification.class.php
- Chemins fichiers utilisation Observeur :
    - www/controller/Article.class.php
    - www/controller/Comment.class.php

### Factory

Nous avons utilisé le design pattern factory pour les fonctionnalités de création de block de type text et de type form. 
Ce design pattern nous a permis d'éviter une certaine redondance au niveau de la programmation de manière à séparer la création des objets de l'utilisation tout en vérifiant l'existence du model instancié.
Ainsi, nous avons décidé de l'implémenter sur deux fonctionnalités phares de notre CMS.

- Chemin des modèles :
- Dossier models > dossier factories >
    - BlockContentFactory.class.php
    - ContentInterface.class.php
    - ContentForm.class.php
    - ContentText.class.php
- Instanciation des objets :
- Dossier controllers >
    - Admin.class.php – les parties concernées sont indiquées avec un commentaire /*DESIGN PATTERN FACTORY */
        - Ligne 21: use App\models\factories\BlockContentFactory
        - Fonction “createTextBlock()” – ligne 235
        - Fontion “createFormInput()” – ligne 281 et ligne 296

## Versions
**Dernière version stable :** 1.0
**Dernière version :** 1.1
Liste des versions : [Cliquer pour afficher](https://github.com/SportCMS/ProjetAnnuel/tags)

## Auteurs

* **Samy Amallah** _alias_ [@Choetsu](https://github.com/Choetsu)
* **Simon Farnault** _alias_ [@SimonBTSSio](https://github.com/SimonBTSSio)
* **Morade Chemlal** _alias_ [@mchemlal](https://github.com/mchemlal)
* **Antoine Chaberneaud** _alias_ [@senex127](https://github.com/senex127)
* **Ayman Bedda** _alias_ [@Ayman-BEDDA ](https://github.com/Ayman-BEDDA)

## License

SportCMS est sous licence ``en attente`` - voir le fichier [LICENSE.md](LICENSE.md) pour plus d'informations
