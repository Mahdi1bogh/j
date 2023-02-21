<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use function PHPUnit\Framework\stringStartsWith;


class UserController extends AbstractController
{
    /**
     * @Route("/user/admiin", name="app_user_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/user/new", name="app_user_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $file = $form->get('pdp')->getData();
            $Filename = md5(uniqid()) . '.' . $file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images'),
                    $Filename
                );
            } catch (FileException $e) {

            }


            $user->setPdp($Filename);

            $user->setMdpUser(
                $passwordEncoder->encodePassword($user, $user->getPassword()));
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/{id_user}", name="app_user_show", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/{id_user}/edit", name="app_user_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $user->setMdpUser(
                $passwordEncoder->encodePassword($user, $user->getPassword()));
            $userRepository->add($user);


            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/user/{id_user}", name="app_user_delete", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getIdUser(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }











    /**
     * @Route ("/user/admiin/M", name="app_user_indexM")
     */
    public function indexM(NormalizerInterface $Normalizer)
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();
       $serializer= new Serializer([new ObjectNormalizer()]);
       $formatted= $serializer->normalize($user);

       return new JsonResponse($formatted);

       /* $repository= $this->getDoctrine()->getRepository(User::class);
        $users= $repository->findAll();
        $jsonContent = $Normalizer->normalize($users, 'json');


        return new Response(json_encode($jsonContent));*/
    }


    /**
     * @Route("/user/new/M", name="app_user_newM")
     */
    public function newM(Request $request,NormalizerInterface $Normalizer, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $nom_user= $request->query->get("nom_user");
        $prenom_user = $request->query->get("prenom_user");
        $tel_user = $request->query->get("tel_user");
        $email_user = $request->query->get("email_user");
        $mdp_user = $request->query->get("mdp_user");
        $em= $this->getDoctrine()->getManager();

        $user->setNomUser($nom_user);
        $user->setPrenomUser($prenom_user);
        $user->setTelUser($tel_user);
        $user->setEmailUser($email_user);
        // $user->setMdpUser($mdp_user);
        $user->setMdpUser(
            $passwordEncoder->encodePassword($user, $mdp_user));




            $em->persist($user);
            $em->flush();


           $jsonContent = $Normalizer->normalize($user,'json');
            return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/user/M/{id_user}", name="app_user_showM", methods={"GET"})
     */
    public function showM(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }


    /**
     * @Route("/user/edit/M", name="app_user_editM")
     */
    public function editM(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $em= $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getManager()
            ->getRepository(User::class)
            ->find($request->get("id_user"));
        $user->setNomUser($request->get("nom_user"));
        $user->setPrenomUser($request->get("prenom_user"));
        $user->setTelUser($request->get("tel_user"));
        $user->setEmailUser($request->get("email_user"));


            $user->setMdpUser(
                $passwordEncoder->encodePassword($user, $user->getPassword()));

        $em->persist($user);
        $em->flush();
        $serializer= new Serializer([new ObjectNormalizer()]);
        $formatted= $serializer->normalize(($user));
        return new JsonResponse("users a ete modifiee avec success");




    }

    /**
     * @Route("/login/M", name="app_user_loginM")
     */
    public function SignInAction(Request $request){

        $email_user= $request->query->get("email_user");
        $mdp_user= $request->query->get("mdp_user");

        $em=$this->getDoctrine()->getManager();
        $user= $em->getRepository(User::class)->findOneBy(['email_user'=>$email_user]);
        if($user) {
            if (password_verify($mdp_user, $user->getPassword())) {
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($user);
                return new JsonResponse($formatted);
            }elseif (strlen($mdp_user) > 10 and $mdp_user == $user->getPassword()) {
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($user);
                return new JsonResponse($formatted);
            }
             else {
                return new Response("password not found");
            }
        }
        else{
            return new Response("user not found");
            }
    }


    /**
     * @Route("/passem", name="app_user_passem")
     */
    public function getPasswordByEmail(Request $request){
        $email_user= $request->get('email_user');
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['email_user'=>$email_user]);
        if($user) {
                $mdp_user=$user->getMdpUser();
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($mdp_user);
                return new JsonResponse($formatted);

            }
        return new Response("user not found");

    }




}