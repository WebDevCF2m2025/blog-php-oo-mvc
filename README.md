# blog-php-oo-mvc
**Blog fait en PHP8 - MySQL en MVC - POO (Orienté Objet)**

**Avec Twig comme moteur de template**

### Configuration de la base de données

Installez Workbench : https://dev.mysql.com/downloads/workbench/

Puis création de la DB suivant votre choix de projet (voir `data/MyModel.mwb` pour le fichier vu en classe).

### Fichier de configuration

Dupliquez le fichier `config.dev.php` et nommez la copie en `config.php`. Réglez les paramètres de connexion à votre base de données (voir le fichier `data/my_blog_v2.sql` si vous souhaitez utiliser celle vue au cours).

Changez-y le chemin de la constante `RACINE_URL` vers l'URL de votre dossier public, pour que la réécriture sous forme de dossier, soit correcte (voir le fichier `public/.htaccess`).

```php
// path: config.php
// ...
// mettez votre url dans cette constante pour la réécriture d'URLs
const RACINE_URL = "http://chemin.vers.dossier.public";
```

### Base de donnée 

Pour l'exemple, chargez la base de donnée `my_blog` depuis le fichier `data/my_blog_v2.sql`

Pour se connecter chaque mot de passe est haché. Pour l'exercice, il suffit d'utiliser le login comme login ET mdp :

- admin : admin
- editor : editor
- user1 : user1
- user2 : user2


### Installation

Installez composer si ce n'est pas déjà fait : https://getcomposer.org/download/

On va ensuite installer Twig via composer :

```bash
composer require "twig/twig:^3.0"
```

Il suffira depuis un autre poste de travail de taper cette commande à la racine du projet :

```bash
composer install
```

Voir : https://twig.symfony.com/doc/3.x/intro.html#installation

et Packagist : https://packagist.org/packages/twig/twig

### Création des modèles

Créez les classes dans le dossier model. Un fichier par table, ces classes doivent hériter de `AbstractMapping.php`.

**La sécurisation se fera au niveau des `setters` de ces mapping.**

### Création des manageurs

Créez les classes de type `Manager`, elles doivent implémenter au moins `implements ManagerInterface`. Le `UserManager` doit également implémenter `UserInterface`. 

Vous pouvez utiliser le trait `model/StringTrait.php` au besoin.

**Toutes les requêtes devront être préparées et sécurisées.**

### Création des controllers

La connexion `PDO` se fait dans `controller/routerController.php`, c'est lui qui redirigera vers les différentes parties du site.

- **publicController** servira à la partie publique du site, nous pourrons y accéder même connecté, par exemple pour poster des messages.
- **adminController** servira à la partie administrateur du site, uniquement accessible aux administrateurs, éditeurs et rédacteurs.


### Remerciements
Merci à [Massine2k1](https://github.com/Massine2k1) pour son design que nous utiliserons dans ce projet :

https://github.com/WebDevCF2m2025/PHP8-OO/tree/main/classe1/Massine/07-avance/view