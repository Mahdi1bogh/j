<?php

namespace App\Controller;
use App\Entity\Commande;
use App\Entity\Lignecommande;
use App\Entity\Produits;
use App\Entity\User;
use App\Repository\CommandeRepository;
use App\Repository\LigneCommandeRepository;
use App\Repository\ProduitsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/client/commandes")
 */
class ClientCommandeController extends AbstractController
{

    private CommandeRepository $commandeRepository;

    private LigneCommandeRepository $LignecommandeRepository;

    private UserRepository $userRepository;


    public function __construct(CommandeRepository $commandeRepository, LignecommandeRepository $LignecommandeRepository, UserRepository $userRepository)
    {
        $this->commandeRepository = $commandeRepository;
        $this->LignecommandeRepository = $LignecommandeRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", name="app_client_commande")
     */
    public function index(CommandeRepository $commandeRepository, UserRepository $userRepository): Response
    {
        $user =  $this->userRepository->findOneBy(['email_user'=>$this->getUser()->getUsername()]);
//        dd($user);
//        $commandes = $commandeRepository->findCommandeByUsers();
        $commandes = $commandeRepository->findCommandeByOneUser($user->getIdUser());
//        dd($commandes);
        return $this->render('client_commande/index.html.twig', [
            'controller_name' => 'ClientCommandeController',
            'commandes' => $commandes
        ]);
    }
    /**
     * @Route("/produits", name="app_client_c_produits")
     */
    public function getProduits(ProduitsRepository $produitRepository): Response
    {

        $produits = $produitRepository->findAll();
//        dd($produits);
        return $this->render('client_commande/produits.html.twig', [
            'controller_name' => 'ClientCommandeController',
            'produits' => $produits
        ]);
    }

    /**
     * @Route("/showDetails/{id}", name="app_client_show_details")
     */
    public function showProductsByCommand(LigneCommandeRepository $ligneCommandeRepository, UserRepository $userRepository, int $id): Response
    {
        $user =  $this->userRepository->findOneBy(['email_user'=>$this->getUser()->getUsername()]);
//        dd($user);
//        $commandes = $commandeRepository->findCommandeByUsers();
        $ligneCommandes = $ligneCommandeRepository->findCommandeDetailsByOneUser($user->getIdUser(), $id);

//        dd($ligneCommandes);
        return $this->render('client_commande/show_details.html.twig', [
            'controller_name' => 'ClientCommandeController',
            'ligne_commandes' => $ligneCommandes,
            'id' => $id
        ]);
    }

    /**
     * @Route("/new/lc/{idProd}", name="app_client_add_ligne_commande", methods={"GET", "POST"})
     */
    public function addLigneCommande(ProduitsRepository  $produitRepository, EntityManagerInterface $entityManager, int $idProd): Response
    {

//        $produits = $produitRepository->findProduitsByCategory(1);
//        dd($produits);
        $user =  $this->userRepository->findOneBy(['email_user'=>$this->getUser()->getUsername()]);
//        $uId = 1;
        $commande = $this->commandeRepository->findMaxCommandeByUser($user->getIdUser());
        if (!$commande){
            $commande = new Commande();
//            $user = new User();
//            $user->setIdUser($uId);
            $commande->setIdUser($user);
            $commande->setEtat('En cours');
            $commande->setDateCommande();
            $commande->setAdresse('Tunis');
            $commande->setTotalPoints(0);
            $entityManager->persist($commande);
            $entityManager->flush();
//            dd($commande);
        }

        $produit = $produitRepository->findOneBy(['id_produit'=>$idProd]);
        $ligneCommande = new Lignecommande;
        $ligneCommande->setIdCommande($commande);
        $ligneCommande->setIdProduit($produit);
        $ligneCommande->setQuantite(1);
        $ligneCommande->setNbPts($produit->getNbPts());
        $entityManager->persist($ligneCommande);
        $entityManager->flush();
//        dd($ligneCommande);
        $this->addFlash('success', $produit->getNomProduit());

        return $this->redirectToRoute('app_client_c_produits');
    }
    public function getOrCreateCommande($user){

        $commande = new Commande();
        $commande->setIdUser($user);
        $commande->setEtat('En cours');
        $commande->setAdresse('Tunis');
        $commande->setTotalPoints(0);
        $commande->setDateCommande();
        return $commande;
//            dd($commande);

    }
    /**
     * @Route("/get_lc/", name="app_client_get_ligne_commande", methods={"GET", "POST"})
     */
    public function getLigneCommandes(EntityManagerInterface $entityManager): Response
    {

//        dd($produits);
//        dd($this->getUser()->getUsername());
        $user =  $this->userRepository->findOneBy(['email_user'=>$this->getUser()->getUsername()]);
//        dd($user);
        /** @var Commande $commande */
        $commande = $this->commandeRepository->findMaxCommandeByUser($user->getIdUser());
//        dd($commande);
        if(!$commande){
            /** @var Commande $commande */
            $commande = $this->getOrCreateCommande($user);
            $entityManager->persist($commande);
            $entityManager->flush();
            /** @var Commande $commande */
            $commande = $this->commandeRepository->findMaxCommandeByUser($user->getIdUser());
        }
//        dd($commande);
        $lignecommandes = $this->LignecommandeRepository->findWithProduitByCommande($commande->getIdCommande());
        $total_points = 0;
//        $total_quantite = 0;
        /** @var Lignecommande $l */
        foreach($lignecommandes as $l){
            $total_points =$total_points+ ($l->getQuantite() * $l->getNbPts());

        }
//        dd($total_points);
        return $this->render('client_commande/panier.html.twig',[
            'idCommande'=>$commande->getIdCommande(),
            'lignecommandes'=>$lignecommandes,
            'total_points'=>$total_points,
//            'total_quantite'=>$total_quantite,
        ]);
    }
    /**
     * @Route("del/{idLignecommande}", name="app_client_lignecommande_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, Lignecommande $lignecommande, EntityManagerInterface $entityManager): Response
    {
//        if ($this->isCsrfTokenValid('delete'.$lignecommande->getIdLignecommande(), $request->request->get('_token'))) {
        $entityManager->remove($lignecommande);
        $entityManager->flush();
//        }

        return $this->redirectToRoute('app_client_get_ligne_commande', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("proc/{idcommande}", name="app_client_proceed_commande", methods={"GET","POST"})
     */
    public function proceedCommande(int $idcommande, EntityManagerInterface $entityManager){
        $commande = $this->commandeRepository->findOneBy(['idCommande'=>$idcommande]);
        $lignecommandes = $this->LignecommandeRepository->findWithProduitByCommande($commande->getIdCommande());
        $total_points = 0;

//        $total_quantite = 0;
        /** @var Lignecommande $l */
        foreach($lignecommandes as $l){
            $total_points =$total_points+ ($l->getQuantite() * $l->getNbPts());

        }
        $commande->setTotalPoints($total_points);
        $commande->setEtat('En attente');
        $entityManager->flush();
        return $this->redirectToRoute('app_client_c_produits', [], Response::HTTP_SEE_OTHER);
    }
}
