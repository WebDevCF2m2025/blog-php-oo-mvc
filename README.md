# blog-php-oo-mvc
Blog fait en PHP8 - MySQL en MVC - OO

## Avec Twig comme moteur de template

### Configuration de la base de données

Installez Workbench : https://dev.mysql.com/downloads/workbench/

Puis création de la DB suivant le projet.

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

Voir : https://twig.symfony.com/doc/3.x/intro.html#installation

et Packagist : https://packagist.org/packages/twig/twig

### Création des modèles

Créer les classes dans le dossier model.


### Remerciements
Merci à Massine pour son design que nous utiliserons dans ce projet :

https://github.com/WebDevCF2m2025/PHP8-OO/tree/main/classe1/Massine/07-avance/view