<?php

namespace App\Controller;

use App\Entity\Tournois;
use App\Form\TournoisType;
use App\Repository\TournoisRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TournoisController extends AbstractController
{
    /**
     * @Route("/tournois", name="app_tournois_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(TournoisRepository $tournoisRepository): Response
    {
        return $this->render('tournois/index.html.twig', [
            'tournois' => $tournoisRepository->findAll(),
        ]);
    }

    /**
     * @Route("/tournois/new", name="app_tournois_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, TournoisRepository $tournoisRepository): Response
    {
        $tournoi = new Tournois();
        $form = $this->createForm(TournoisType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tournoisRepository->add($tournoi);
            return $this->redirectToRoute('app_tournois_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tournois/new.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tournois/{id}", name="app_tournois_show", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(Tournois $tournoi): Response
    {
        return $this->render('tournois/show.html.twig', [
            'tournoi' => $tournoi,
        ]);
    }

    /**
     * @Route("/tournois/{id}/edit", name="app_tournois_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Tournois $tournoi, TournoisRepository $tournoisRepository): Response
    {
        $form = $this->createForm(TournoisType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tournoisRepository->add($tournoi);
            return $this->redirectToRoute('app_tournois_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tournois/edit.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tournois/{id}", name="app_tournois_delete", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Tournois $tournoi, TournoisRepository $tournoisRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tournoi->getIdTournois(), $request->request->get('_token'))) {
            $tournoisRepository->remove($tournoi);
        }

        return $this->redirectToRoute('app_tournois_index', [], Response::HTTP_SEE_OTHER);
    }
}
