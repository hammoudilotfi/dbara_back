<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiCategorieController extends AbstractController
{
    #[Route('/getcategory', name: 'getcategory',methods: ["GET"])]
    public function getCategory(): Response
    {
        $products = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $data =[];
        foreach ($products as $product) {
        $data[] = [
            'id' => $product->getId(),
            'titre' => $product->getTitre(),
            'description' => $product->getDescription(),
            'icone'=>$product->getIcone(),
        ];
    }
        return $this->json($data);

    }
    #[Route('/addcategory', name: 'addcategory',methods: ["POST"])]
    public function addcategory(Request $request ,EntityManagerInterface $entityManager): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = new Category();
        $category->setTitre($request->request->get('titre'));
        $category->setDescription($request->request->get('discription'));
        $category->setIcone($request->request->get('icone'));
        $entityManager->persist($category);
        $entityManager->flush();

        return $this->json('Created new category successfully with id ' . $category->getId());

    }
    #[Route('/showcategory/{id}', name: 'showcategory',methods: ["GET"])]
    public function showCategory(int $id): Response
    {
     $category =$this->getDoctrine()->getRepository(Category::class)->find($id);
     if(!$category) {
         return $this->json('No category found for id' . $id, 404);
     }
         $data =  [
             'id' => $category->getId(),
             'titre' => $category->getTitre(),
             'description' => $category->getDescription(),
             'icone' => $category->getIcone(),
         ];

         return $this->json($data);
     }
    #[Route('/editcategory/{id}', name: 'editcategory',methods: ["PUT"])]
    public function editCategory(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);
        if (!$category) {
            return $this->json('No category found for id' . $id, 404);
        }
        $category->setTitre($request->request->get('titre'));
        $category->setDescription($request->request->get('discription'));
        $category->setIcone($request->request->get('icone'));
        $entityManager->flush();
        $data[] = [
            'id' => $category->getId(),
            'titre' => $category->getTitre(),
            'description' => $category->getDescription(),
            'icone' =>$category->getIcone(),
        ];
        return $this->json($data);
    }
    #[Route('/deletecategory/{id}', name: 'deletecategory',methods: ["DELETE"])]
    public function deletecategory(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);
        if (!$category) {
            return $this->json('No category found for id' . $id, 404);
        }
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->json('Deleted a category successfully with id ' . $id);

    }

}
