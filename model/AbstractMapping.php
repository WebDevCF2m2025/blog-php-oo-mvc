<?php

// création du namespace
namespace model;

// Classe abstraite de mapping pour tous les mappings
abstract class AbstractMapping
{
    // création d'un constructeur pour tous les enfants
    public function __construct(array $datas)
    {
        // appel de l'hydratation
        $this->hydrate($datas);

    }

    /* création d'une méthode d'hydratation, c'est-à-dire de
    mettre des valeurs aux propriétés en utilisant les setters
    existants
    */
    protected function hydrate(array $datas)
    {
        // tant qu'on a des données
        foreach ($datas as $setter=>$value){

            // création du nom du setter
            $setterName = "set".str_replace("_","",ucwords($setter, '_'));
            if(method_exists($this,$setterName)){
                // appel du setter
                $this->$setterName($value);
            }
        }
    }
}
