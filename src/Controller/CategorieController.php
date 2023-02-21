<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


/**
 * 
 */
class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie/list", name="app_categorie_index", methods={"GET"})
     */
    
    public function index(CategorieRepository $categorieRepository): Response
    {
        
        return $this->render('categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
        
    }

    /**
     * @Route("/categorie/add", name="app_categorie_new")
     */
    public function new(Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
           
            return $this->redirectToRoute('app_categorie_index');
        }

        return $this->render('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/categorie/{id_categorie}", name="app_categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    /**
     * @Route("/categorie/edit/{id_categorie}", name="app_categorie_edit")
     */
    public function edit(Request $request, $id_categorie): Response
    {   $categorie=$this->getDoctrine()->getManager()->getRepository(Categorie::class)->find($id_categorie);
        $form = $this->createForm(CategorieType::class ,$categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_categorie_index');
        }

        return $this->render('categorie/edit.html.twig', [
            
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Categorie/delete/{id_categorie}", name="app_categorie_delete")
     */
    public function delete(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
