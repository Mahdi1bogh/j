<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Form\ClientJoueurType;
use App\Form\JoueurType;
use App\Repository\CommandeRepository;
use App\Repository\EquipeRepository;
use App\Repository\JoueurRepository;
use App\Repository\LigneCommandeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/client/joueur")
 */
class ClientJoueurController extends AbstractController
{
    private JoueurRepository $joueurRepository;
    private UserRepository $userRepository;
    private EquipeRepository $equipeRepository;

    public function __construct(EquipeRepository $equipeRepository,JoueurRepository $joueurRepository, UserRepository $userRepository)
    {

        $this->joueurRepository = $joueurRepository;
        $this->userRepository = $userRepository;
        $this->equipeRepository = $equipeRepository;
    }
    /**
     * @Route("/list", name="app_client_joueur")
     */
    public function index(): Response
    {
        $user =  $this->userRepository->findOneBy(['email_user'=>$this->getUser()->getUsername()]);
        $equipes = $this->equipeRepository->findUserEquipe($user->getIdUser());
        $hasjoueur = $this->joueurRepository->findOneBy(['idUser'=>$user]);
//        dd($equipes);
//        dd($equipes);
        return $this->render('client_joueur/index.html.twig', [
            'controller_name' => 'ClientJoueurController',
            'equipes'=>$equipes,
            'joueur'=>$hasjoueur
        ]);
    }
    /**
     * @Route("/new", name="app_client_add_joueur", methods={"GET", "POST"})
     */
    public function new(MailerInterface $mailer, Request $request, EntityManagerInterface $entityManager): Response
    {
        $joueur = new Joueur();
        $user =  $this->userRepository->findOneBy(['email_user'=>$this->getUser()->getUsername()]);
        $hasjoueur = $this->joueurRepository->findOneBy(['idUser'=>$user]);
//        dd($hasjoueur);
        if($hasjoueur){
            return $this->render('client_joueur/new.html.twig', [

            ]);
        }
        $form = $this->createForm(ClientJoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $joueur->setIdUser($user);
            $email = (new TemplatedEmail())
                ->from('fatma.trabelsi@esprit.tn')
                ->to($user->getEmailUser())
                ->subject('Welcome To PLE sport ')
                ->htmlTemplate('user_email/joueur.html.twig')
                ->context([
                    'user'=>$user,
                    'joueur'=>$joueur
                ])
            ;
            $mailer->send($email);
            $entityManager->persist($joueur);
            $entityManager->flush();

            return $this->redirectToRoute('app_client_joueur', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client_joueur/new.html.twig', [
            'controller_name' => 'ClientJoueurController',
            'form'=>$form->createView()
        ]);
    }
}
