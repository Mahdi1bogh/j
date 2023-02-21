<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPassType;
use App\Form\User1Type;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;


class UserFController extends AbstractController
{


    /**
     * @Route("/userf/newF", name="app_user_f_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $file = $form->get('pdp')->getData();
            $Filename = md5(uniqid()).'.'.$file->guessExtension();
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

            return $this->redirectToRoute('home');
        }

        return $this->render('user_f/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/userf/{id_user}", name="app_user_f_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user_f/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/userf/{id_user}/editF", name="app_user_f_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $user->setMdpUser(
                $passwordEncoder->encodePassword($user, $user->getPassword()));
            $userRepository->add($user);
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_f/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/userf/{id_user}", name="app_user_f_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getIdUser(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }
        $session = new Session();
        $session->invalidate();
        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/forgot", name="forgot")
     */
    public function forgot(Request $request, UserRepository $rep, MailerInterface $mailer, TokenGeneratorInterface $tokenGeneratorInterface)
    {
        $form = $this->createForm(ResetPassType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $user = $rep->findOneBy(['email_user'=>$data['email']]);
            if(!$user)
            {
                $this->addFlash('danger', 'Cette adresse n\'existe pas');
                $this->redirectToRoute('app_login');
            }

            $token = $tokenGeneratorInterface->generateToken();

            try{
                $user->setResetToken($token);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

            }catch(\Exception $e)
            {
                $this->addFlash('warning', 'Une erreur est survenue' . $e->getMessage());
                return $this->redirectToRoute('app_login');
            }

            //$url = $this->generateUrl('resetpwd',['token' => $token]);
            $email = (new TemplatedEmail())
                ->from('primeleagueesports@gmail.com')
                ->to($user->getEmailUser())
                ->subject('Réinitialisation du mot de passe')
                ->htmlTemplate('user_f/template.html.twig')
                ->context([
                    'token' => $token
                ]);

            $mailer->send($email);

            $this->addFlash('message','Un mail de réinitialisation de mot de passe vous a été envoyé');
            return $this->redirectToRoute('app_login');

        }

        return $this->render('user_f/ForgotPassword.html.twig' , ['emailForm' => $form->createView()]);
    }

    /**
     * @Route("/resetpwd/{token}", name="resetpwd")
     */
    public function resetpwd($token, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token' => $token]);
        if(!$user)
        {
            $this->addFlash('danger', 'Token inconnu');
            return $this->redirectToRoute('app_login');
        }



        if($request->isMethod('POST'))
        {
            $nouveau = $request->request->get('password');
            $confirm = $request->request->get('passwordC');

            if($nouveau == $confirm)
            {

                $user->setResetToken(null);
                $user->setMdpUser($encoder->encodePassword($user, $nouveau));
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->addFlash('message','Mot de passe modifié avec succès');
                return $this->redirectToRoute('app_login');
            }


        }
        else
        {
            return $this->render('user_f/ResetPassword.html.twig',['token' => $token]);
        }
        return $this->redirectToRoute('app_login');
    }

}
