<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


class RecipeController extends AbstractController
{
    #[Route(path: '/recette', name: 'app_recipe_index')]
    public function index(Request $request, RecipeRepository $repository, EntityManagerInterface $em): Response
    {
        // $recipe = new Recipe;
        // $recipe->setTitle('Omelette lala')
        // ->setSlug('omelette-au-from')
        // ->setContent('Omelette au from Content')
        // ->setDuration(13)
        // ->setCreatedAt(new DateTimeImmutable())
        // ->setUpdatedAt(new DateTimeImmutable());

        // $em->persist($recipe);
        // $em->flush();

        // $recipes = $repository->findAll();
        $recipes = $em->getRepository(Recipe :: class)->findAll();
        // $recipes[7]
        // ->setTitle('Saka lala')
        // ->setSlug('saka-lala')
        // ->setContent('Saka lala Content');

        // $em->remove($recipes[6]);
        $em->flush();

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes
        ]); 
        
    }
    #[Route('/recette/{slug}-{id}', name: 'app_recipe_show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request , string $slug , int $id, RecipeRepository $repository)
    {
        $recipe = $repository->find($id);
        if($recipe->getSlug() !== $slug){
            return $this->redirectToRoute('app_recipe_show', ['id' => $recipe->getId(), 'slug' => $recipe->getSlug()]);
        }
        return $this->render('recipe/show.html.twig',[
        'recipe' => $recipe
        ]);
    }

    #[Route(path: '/recette/{id}/edit', name: 'app_recipe_edit')]
    public function edit(Recipe $recipe,Request $request,EntityManagerInterface $em): Response{
        $form = $this->createForm(RecipeType::class,$recipe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('warning','Hello guys, comment ça va ?');
            return $this->redirectToRoute('app_recipe_show', ['id' => $recipe->getId(), 'slug' => $recipe->getSlug()]);
        }
        return $this->render('recipe/edit.html.twig',[
            'recipe' => $recipe,
            'gazouz' => $form
        ]);
    }
    #[Route(path: '/recette/create', name: 'app_recipe_create')]
    public function create(Request $request,EntityManagerInterface $em): Response{
        $recipe = new Recipe;
        $recipe->setCreatedAt(new DateTimeImmutable())
        ->setUpdatedAt(new DateTimeImmutable());

// TEST : 

        // $title = $recipe->getTitle();
        // $slug = join(str_replace(' ','-',str_split(strtolower($title))));
        // $recipe->setSlug($slug);


        $form = $this->createForm(RecipeType::class,$recipe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('warning','La recette '. $recipe->getTitle() .' a bien été créé.');
            return $this->redirectToRoute('app_recipe_index');
        }
        return $this->render('recipe/create.html.twig',[
            'gazouz' => $form,
        ]);
    }
    #[Route(path: '/recette/{id}/delete', name: 'app_recipe_delete')]
    public function delete(Recipe $recipe,EntityManagerInterface $em): Response{
        $titre = $recipe->getTitle();
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('info', 'La recette " '.$titre.' " a bien été supprimé.');
        return $this->redirectToRoute('app_recipe_index');
    }

}
