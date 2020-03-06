<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use App\Form\ProduitType;
use Symfony\Component\HttpFoundation\Request;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="produit")
     */
    

    public function index(Request $request)
    {
        //Connexion à la BDD
        $pdo = $this->getDoctrine()->getManager();

        

        //->findOneBy(['id' => 2])    un seul résultat
        //->findBy(['nom' => "Nom de l'élément])   plusieurs résultat

        $produit = new Produit();

        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $pdo->persist($produit);
            $pdo->flush();
        }

        $produits = $pdo->getRepository(Produit::class)->findAll();

        return $this->render('produit/index.html.twig', [
            'prenom' => 'Yanny',
            'produits' => $produits,
            'form_ajout' => $form->createView(),
        ]);
    }

    /**
     * @Route("/produit/{id}", name="un_produit")
     */

    public function produit(Produit $produit=null, Request $request){

        if($produit != null){
                //Si le produit existe
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->persist($produit);
            $pdo->flush();
        }

        return $this->render('produit/produit.html.twig', [
            'produit' => $produit,
            'form_edit' => $form->createView(),
            
        ]);

        }
        else{
                // Si le produit existe pas, on redirige vers une autre apfge
                return $this->redirectToRoute('produit');

        }
    }

    /**
     * @Route ("produit/delete/{id}", name="delete_produit")
     */

    public function delete(Produit $produit=null){

        if($produit !=null){
            // On a trouvé un produit, on le supprime

            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($produit);
            $pdo->flush();
        }
        return $this->redirectToRoute('produit');
    }


}
