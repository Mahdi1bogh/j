<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\User;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="app_blog_index", methods={"GET"})
     */
    public function index(BlogRepository $blogRepository): Response
    {
        return $this->render('blog/index.html.twig', [
            'blogs' => $blogRepository->findAll(),
            'numbers' => $blogRepository->num()
        ]);
    }

    /**
     * @Route("/Recherche", name="app_blog_Recherche")
     */
    public function rechercherByNiveauAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $blogs = $em->getRepository(Blog::class)->findAll();
        if($request->isMethod("POST") ){
            $titre_blog= $request->get('titre_blog');
            $blogs= $em->getRepository(Blog::class)->findBy(array('titre_blog'=>$titre_blog));
        }
        return $this->render("blog/Recherche.html.twig", array('blogs' => $blogs));
    }

    /**
     * @Route("/new", name="app_blog_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request, BlogRepository $blogRepository): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $badWords = array("one", "two", "three");
            $string = $form->get('titre_blog')->getData();
            $matches = array();
            $matchFound = preg_match_all(
                "/\b(" . implode($badWords, "|") . ")\b/i",
                $string,
                $matches
            );
            $file = $form->get('image_blog')->getData();
            $Filename = md5(uniqid()) . '.' . $file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images'),
                    $Filename
                );
            } catch (FileException $e) {

            }

            $blog->setImageBlog($Filename);
            $blog->setEmailPublisher($user->getUsername());
            if ($matchFound) {
                $words = array_unique($matches[0]);
                foreach ($words as $word) {
                    echo $word;
                }

            } else {
                $entityManager->persist($blog);
                $entityManager->flush();
                return $this->redirectToRoute('app_blog_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/{id}", name="app_blog_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Blog $blog): Response
    {
        return $this->render('blog/show.html.twig', [
            'blog' => $blog,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_blog_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Blog $blog, BlogRepository $blogRepository): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogRepository->add($blog);
            return $this->redirectToRoute('app_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog/edit.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_blog_delete", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Blog $blog, BlogRepository $blogRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $blog->getIdBlog(), $request->request->get('_token'))) {
            $blogRepository->remove($blog);
        }

        return $this->redirectToRoute('app_blog_index', [], Response::HTTP_SEE_OTHER);
    }


}
