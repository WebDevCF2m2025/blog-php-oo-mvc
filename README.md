# blog-php-oo-mvc
**Blog fait en PHP8 - MySQL en MVC - POO (Orienté Objet)**

**Avec Twig comme moteur de template**

## Exemple en production

https://demo-blog.cf2m.be/

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

### Permission des utilisateurs

Pour se connecter chaque mot de passe est haché. Pour l'exercice, il suffit d'utiliser le login comme login ET mdp :

- admin : admin   | Rôle : Admin (en cours de création)
- editor : editor | Rôle : Editor (pas encore fait, peut juste poster des commentaires immédiatement affichés)
- user1 : user1   | Rôle : User (peuvent poster des commentaires non affichés)
- user2 : user2   | Rôle : User (peuvent poster des commentaires non affichés)


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

### Dans le contrôleur frontal on instancie Twig

```php
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
// pour le debug de Twig
use Twig\Extension\DebugExtension;

// ...

// Autoload de composer (pour Twig, mais aussi toutes
// les bibliothèques tierces en PHP)
require_once RACINE_PATH."/vendor/autoload.php";

// on définit où se trouve nos templates
$loader = new FilesystemLoader(RACINE_PATH.'/view'); // dans view
// On lance le système de template de
// Twig en instanciant son environment
$twig = new Environment($loader, [
    // mode de débogage activé
    'debug' => true,
    //'cache' => '/path/to/compilation_cache',
]);
// on ajoute l'extension de debug
$twig->addExtension(new DebugExtension());
// ...
// exemple d'un template simple
echo $twig->render('index.html.twig', ['name' => 'Fabien']);
```

### Installation d'un gestionnaire de mail

Nous utiliserons `symfony/mailer` pour l'envoi de mails.

Documentation : https://packagist.org/packages/symfony/mailer#v7.3.5
Installation via composer :

```bash
composer require symfony/mailer
```

Le fichier `.json` de composer pour faire fonctionner le mailer (ici mailjet)

```json
{
    "require": {
        "twig/twig": "^3.21",
        "ext-pdo": "*",
        "symfony/mailer": "^7.3",
        "symfony/mailjet-mailer": "^7.3",
        "symfony/http-client": "^7.3"
    }
}
```

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

### Templates

Nous gèrerons les templates en utilisant `Twig`.

### Utilisation de Bootstrap

Nous utiliserons `Bootstrap 5` et ses `incones` pour le template responsive :
- https://getbootstrap.com/
- https://icons.getbootstrap.com/


### Remerciements
Merci à [Massine2k1](https://github.com/Massine2k1) pour son design que nous utiliserons dans ce projet :

https://github.com/WebDevCF2m2025/PHP8-OO/tree/main/classe1/Massine/07-avance/view
