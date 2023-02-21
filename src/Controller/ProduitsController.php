<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produits;
use App\Form\ProduitsType;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * 
 */
class ProduitsController extends AbstractController
{
    /**
     * @Route("/produit/list", name="app_produits_index", methods={"GET"})
     */
    public function index(ProduitsRepository $produitsRepository): Response
    {
        return $this->render('produits/index.html.twig', [
            'produits' => $produitsRepository->findAll(),
            
        ]);
    }

    /**
     * @Route("/produit/add", name="app_produits_new")
     */
    public function new(Request $request, ProduitsRepository $produitsRepository): Response
    {
        $produit = new Produits();
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
           
            return $this->redirectToRoute('app_produits_index');
        }

        return $this->render('produits/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/produit/{id_produit}", name="app_produits_show", methods={"GET"})
     */
    public function show(Produits $produit): Response
    {
        return $this->render('produits/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("/produit/edit/{id_produit}", name="app_produits_edit")
     */
    public function edit(Request $request,$id_produit): Response
    {   $produit=$this->getDoctrine()->getManager()->getRepository(Produits::class)->find($id_produit);
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_produits_index');
        }

        return $this->render('produits/edit.html.twig', [
            
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Produit/delete/{id_produit}", name="app_produits_delete")
     */
    public function delete(Request $request, Produits $produit, ProduitsRepository $produitsRepository): Response
    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($produit);
        $em->flush();

        return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/Produit/triQuantite", name="triParQuantite")
     */
/*
    public function TriQuantite(Request $request, ProduitsRepository $repository)
    {
         $em = $this->getDoctrine()->getManager();
         $query= $em->createQuery(
             'SELECT p FROM App\Entity\Produits p 
             ORDER BY p.quantite'
         );
         $repository = $this->getDoctrine()->getRepository(Produits::class);
         $produit = $query->getResult();
         return $this->render('produits/index.html.twig',
           array('produits' => $produit));
     }
     */
}
