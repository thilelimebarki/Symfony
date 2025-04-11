<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\UserRepositoory;

final class AuthController extends AbstractController
{
    #[Route('/auth', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(RegisterType::class);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $user = $form->getData(); //recuperer les infoss du formulaire 
            $hashedPassword = $hasher->hashPassword(
                $user,
                $user->getPassword()
            ); //hasher le mdp

        $user->setPassword($hashedPassword); //mettre à jour l'objet user

        $em->persist($user); //une sorte de cache le temps que la requete soit completement executée
        $em->flush(); //sauvegarder les données dans la base
        return $this->redirectToRoute('app_blog');
        }

        return $this->render('auth/index.html.twig', [
            'form' => $form
        ]);
    }
}
