<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ClientReclamationType;
use App\Repository\CommandeRepository;
use App\Repository\LigneCommandeRepository;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/client/reclamation")
 */
class ClientReclamationController extends AbstractController
{



    private UserRepository $userRepository;
    private ReclamationRepository $reclamationRepository;


    public function __construct(UserRepository $userRepository, ReclamationRepository $reclamationRepository)
    {
        $this->userRepository = $userRepository;
        $this->reclamationRepository = $reclamationRepository;
    }

    /**
     * @Route("/", name="app_client_reclamation")
     */
    public function index(): Response
    {
        $user =  $this->userRepository->findOneBy(['email_user'=>$this->getUser()->getUsername()]);
        $reclamations = $this->reclamationRepository->findBy(['idUser'=>$user]);
//        dd($reclamations);
        return $this->render('client_reclamation/index.html.twig', [
            'controller_name' => 'ClientReclamationController',
            'reclamations'=>$reclamations
        ]);
    }
    /**
     * @Route("/new", name="app_client_reclamtaion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ClientReclamationType::class, $reclamation);
        $form->handleRequest($request);
        $user =  $this->userRepository->findOneBy(['email_user'=>$this->getUser()->getUsername()]);
        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setIdUser($user);
            $reclamation->setEtatReclam("En attente");
            $reclamation->setDateReclam();
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_client_reclamation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client_reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }
}
