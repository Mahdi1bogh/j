<?php

namespace App\Controller;
use App\Entity\Avis;
use App\Entity\User;
use App\Form\AvisType;
use App\Form\ClientAvisType;
use App\Form\ClientReclamationType;
use App\Repository\AvisRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/client/avis")
 */
class ClientAvisController extends AbstractController


{




    private UserRepository $userRepository;
    private AvisRepository $avisRepository;


    public function __construct(UserRepository $userRepository, AvisRepository $avisRepository)
    {
        $this->userRepository = $userRepository;
        $this->avisRepository = $avisRepository;
    }

    /**
     * @Route("/", name="app_client_avis")
     */
    public function index(): Response
    {
        $user =  $this->userRepository->findOneBy(['email_user'=>$this->getUser()->getUsername()]);
        $avis = $this->avisRepository->findBy(['idUser'=>$user]);
//        dd($avis);
        return $this->render('client_avis/index.html.twig', [
            'controller_name' => 'ClientAvisController',
            'avis'=>$avis
        ]);
    }
    /**
     * @Route("/new", name="app_client_avis_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $avis = new avis();
        $form = $this->createForm(ClientAvisType::class, $avis);
        $form->handleRequest($request);
        $user =  $this->userRepository->findOneBy(['email_user'=>$this->getUser()->getUsername()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setIdUser($user);
           // $avis->setIdProduit();

            $entityManager->persist($avis);
            $entityManager->flush();

            return $this->redirectToRoute('app_client_avis', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client_avis/new.html.twig', [
            'avis' => $avis,
            'form' => $form->createView(),
        ]);
    }
}
