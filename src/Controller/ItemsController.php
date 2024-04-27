<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ItemsController extends AbstractController
{
    #[Route('/items', 
        name: 'items.index')
    ]
    public function index(Request $request): Response
    {
        return $this->render('items/index.html.twig');
    }

    #[Route('/items/{itemName}-{id}', 
        name: 'items.show', 
        requirements: ['id' => '\d+', 'itemName' => '[a-z0-9-]+'])
    ]
    public function show(Request $request , string $itemName , int $id): Response
    {
        return $this->render('items/show.html.twig',[
            'itemName' => $itemName,
            'id' => $id,
            'personne' => [
                'nom' => 'miims',
                'prenom' => 'Loook',
                'age' => '26'
            ]
        ]);
    }
}