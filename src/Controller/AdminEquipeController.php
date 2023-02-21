<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Form\EquipeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/equipe")
 */
class AdminEquipeController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_equipe_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $equipes = $entityManager
            ->getRepository(Equipe::class)
            ->findAll();

        return $this->render('admin_equipe/index.html.twig', [
            'equipes' => $equipes,
        ]);
    }

    /**
     * @Route("/new", name="app_admin_equipe_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $equipe = new Equipe();
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file =$equipe->getLogoEquipe()->getClientOriginalName();

            $file1=$equipe->getLogoEquipe();


            try {
                $file1->move(
                    $this->getParameter('equipe_directory'),
                    $file
                );
            } catch (FileException $e) {

            }
            $equipe ->setLogoEquipe($file);
            $entityManager->persist($equipe);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_equipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_equipe/new.html.twig', [
            'equipe' => $equipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idEquipe}", name="app_admin_equipe_show", methods={"GET"})
     */
    public function show(Equipe $equipe): Response
    {
        return $this->render('admin_equipe/show.html.twig', [
            'equipe' => $equipe,
        ]);
    }

    /**
     * @Route("/{idEquipe}/edit", name="app_admin_equipe_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Equipe $equipe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            try {
                $file =$equipe->getLogoEquipe()->getClientOriginalName();

                $file1=$equipe->getLogoEquipe();

                $file1->move(
                    $this->getParameter('equipe_directory'),
                    $file
                );
            } catch (FileException $e) {

            }
            $equipe ->setLogoEquipe($file);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_equipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_equipe/edit.html.twig', [
            'equipe' => $equipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idEquipe}", name="app_admin_equipe_delete", methods={"POST"})
     */
    public function delete(Request $request, Equipe $equipe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipe->getIdEquipe(), $request->request->get('_token'))) {
            $entityManager->remove($equipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_equipe_index', [], Response::HTTP_SEE_OTHER);
    }
}
