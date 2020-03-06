<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie;
use App\Form\CategorieType;
use Symfony\Component\HttpFoundation\Request;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */

    
    public function index(Request $request)
    {

        $pdo = $this->getDoctrine()->getManager();

        
        $categorie = new Categorie();

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $pdo->persist($categorie);
            $pdo->flush();
            $this->addFlash("success", "Catégorie ajouté");
        }

        $categories = $pdo->getRepository(Categorie::class)->findAll();

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
            'form_ajout' => $form->createView(),
        ]);
    }


    /**
     * @Route("/categorie/{id}", name="une_categorie")
     */


    public function categorie(Categorie $categorie, Request $request){

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->persist($categorie);
            $pdo->flush();
            $this->addFlash("success", "Catégorie modifié");
        }
        
        return $this->render('categorie/categorie.html.twig', [
            'categorie' => $categorie,
            'form_edit' => $form->createView(),
            
        ]);



        
    }
    

        /**
     * @Route ("categorie/delete/{id}", name="delete_categorie")
     */

    public function delete(Categorie $categorie=null){

        if($categorie !=null){
            // On a trouvé une categorie, on le supprime

            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($categorie);
            $pdo->flush();

            $this->addFlash("success", "Catégorie supprimé");
        }
        else{
            $this->addFlash("danger", "Catégorie introuvable");
        }
        return $this->redirectToRoute('categorie');
    }
}
