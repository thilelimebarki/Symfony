<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ArticleType;
use App\Form\UpdateType;
use App\Form\CommentaireType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Entity\Comment;

final class BlogController extends AbstractController
{
    #[Route('/', name: 'app_blog')]
    public function index(ArticleRepository $repository): Response
    {
        //recuperer la liset des articles
        $articles = $repository -> findAll();
        return $this->render('blog/index.html.twig', [
            'titre' => 'Voici la liste des articles',
            'articles' => $articles
        ]);
    }
    // gestion du formulaire 
    #[Route('/new', name: 'app_new')]
    public function new(ArticleRepository $repository, Request $request, EntityManagerInterface $em, #[Autowire('%upload_directory%')] string $photoDir,): Response
    {
        $form = $this->createForm(ArticleType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted()){

            $articleInfo = $form ->getData();
            $articleInfo->setuser($this->getUser());
            $file = $form->get('urlImg')->getData();
            $filename = uniqid().'.'.$file->guessExtension();
            $articleInfo->setUrlImg($filename);
            $file->move($photoDir, $filename);

            $em->persist($articleInfo);
            $em->flush();

            return $this->redirectToRoute ('app_blog');
        }

        return $this->render('blog/form.html.twig',[
            'form' => $form,
        ]);
    }

    //methode pour la suppression
    #[Route('/supprimer/{id}', name: 'app_supp')]
    public function delete(ArticleRepository $repository, int $id, EntityManagerInterface $em): Response
    {

        $articles = $repository -> find($id);
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('app_blog');
    }

    #[Route('/article/{id}', name: 'app_article')]
public function show(ArticleRepository $repository, int $id, EntityManagerInterface $em, Request $request): Response
{
    $article = $repository->find($id);

    $comment = new Comment();
    $form = $this->createForm(CommentaireType::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $comment->setArticle($article); // On relie le commentaire à l'article
        $em->persist($comment);
        $em->flush();

        return $this->redirectToRoute('app_article', ['id' => $article->getId()]);
    }

    return $this->render('blog/article.html.twig', [
        'article' => $article,
        'form' => $form,
        'comments' => $article->getComments(), // afficher les commentaires dans la vue
    ]);
}


    #[Route('/midifier/{id}', name: 'app_updt')]
    public function update(ArticleRepository $repository, int $id, EntityManagerInterface $em, Request $request): Response
    {

        $article = $repository -> find($id);
        $form = $this->createForm(UpdateType::class, $article);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $newArticleInfo = $form->getData();
            $article->setTitle($newArticleInfo->getTitle());
            $article->setContent($newArticleInfo->getContent());

            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('app_blog');
        }
        

        return $this->render('blog/update.html.twig',[
            'form' => $form,
        ]);
    }


}
