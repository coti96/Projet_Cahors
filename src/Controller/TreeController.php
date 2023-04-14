<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CommutateurRepository;
use App\Repository\RouteurRepository;
use App\Repository\ServeurRepository;
use App\Repository\PareFeuRepository;

class TreeController extends AbstractController 
{
    public function buildTree (ServeurRepository $serveurRepository,
    CommutateurRepository $commutateurRepository, 
    RouteurRepository $routeurRepository, 
    PareFeuRepository $parefeuRepository) 
    { 
        
        // Récupération des données des trois tables dans la base de données
          $parefeux = $parefeuRepository->findAll();
          $routeurs = $routeurRepository->findAll();
         $serveurs = $serveurRepository->findAll();
         $commutateurs = $commutateurRepository->findAll();
    
        

       

        // On fusionne les 4 tableaux en un seul tableau appelé datas     
        $datas = array_merge($routeurs,$parefeux,$commutateurs,$serveurs);

       // On initialise un tableau tree=[] vide au début     
        $tree = [];
       
            
          
        
       
            //Pour chaque donnée du data 
            foreach ($datas as $donnee) 
            { 
               
            
              
                //si la donnée n'a pas de parent, rajouter la donnée au tableau tree 
                if (empty($donnee->getParent())  && !in_array($donnee, $tree)) 
                { 
                    $tree[] = ['id' => $donnee->getNom(), 'children' => []];
                    $dataAdded = true;
                ;}
                
                //Sinon, parcourir le tableau avec l'identifiant parent, et la donnée
                else 
                { 
                    $tree=$this->parcourirTableau($donnee->getParent(), $tree, $donnee->getNom()); 
                  
                }
            
            

            }
       

        
      

        
                
                //Convertir le tree en JS
                $convertedtree=$this->convertToTreantFormat($tree);  
                
               
                return $this->render('tree/index.html.twig', [ 'tree' => $convertedtree ]);
    }


    public function parcourirTableau ($parent, array &$tableau, $donnee)
    {       
            // Pour chaque noeud dans le tableau
            foreach ($tableau as &$noeud) {
                // Si le noeud correspond à l'identifiantParent
                if ($noeud['id'] == $parent) {
                   

                  

                    
                   
                   // On ajoute la donnee en tant qu'enfant 
                  
                    $nouveau_noeud = ['id' => $donnee, 'children' => []];   
                   
                    array_push($noeud['children'], $nouveau_noeud);
                    break;
                    }
                
                else { // Sinon on continue la recherche dans les enfants du noeud
                        $this->parcourirTableau($parent, $noeud['children'], $donnee);
                      
                        }
            }
  
        return $tableau;  
    }
    
    
       public function convertToTreantFormat($data) {
        // Initialiser un tableau vide pour stocker les données du noeud
        $nodes = [];
    
        // Parcourir les données de l'arbre
        foreach ($data as $datum) {
            // Initialiser un tableau vide pour stocker les enfants du noeud
            $children = [];
    
            // Si la donnée a des enfants
            if (isset($datum['children'])) {
                // Parcourir les enfants
                foreach ($datum['children'] as $child) {
                    // Ajouter les enfants à la liste d'enfants
                    $children[] = ['text' => ['name' => $child['id']]];
    
                    // Si le noeud enfant a également des enfants
                    if (isset($child['children'])) {
                        // Ajouter les enfants du noeud enfant à la liste d'enfants
                        $children[count($children) - 1]['children'] = $this->convertToTreantFormat($child['children']);
                    }
                }
            }
    
            // Ajouter le noeud au tableau de noeuds
            $nodes[] = ['text' => ['name' => $datum['id']], 'children' => $children];
        }
    
        // Retourner le tableau de noeuds sous forme de chaîne JSON
        return $nodes;
    }  
    
}

