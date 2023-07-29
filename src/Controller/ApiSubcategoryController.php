<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Subcategory;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiSubcategoryController extends AbstractController
{
    private $CategoryRepository;

    public function __construct(CategoryRepository $CategoryRepository)
    {
        $this->CategoryRepository = $CategoryRepository;
    }
    #[Route('/getsubcategory', name: 'getsubcategory',methods: ["GET"])]
    public function getsubcategory(): Response
    {
        $products = $this->getDoctrine()->getRepository(Subcategory::class)->findAll();
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
    #[Route('/addsubcategory', name: 'addsubcategory',methods: ["POST"])]
    public function addsubcategory(Request $request ,EntityManagerInterface $entityManager): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $subcategory = new Subcategory();
        $subcategory->setTitre($request->request->get('titre'));
        $subcategory->setDescription($request->request->get('discription'));
        $subcategory->setIcone($request->request->get('icone'));
        //init category-id
        $category_id=(int)$request->request->get('category_id');

        if ($category_id){
            $category=$this->CategoryRepository->find($category_id);
            //dd($catecory);exit;
            if(!empty($category)){
                $subcategory->setCategory($category);
            }
        }
        $entityManager->persist($subcategory);
        $entityManager->flush();

        return $this->json('Created new subcategory successfully with id ' . $subcategory->getId());

    }
    #[Route('/showsubcategory/{id}', name: 'showsubcategory',methods: ["GET"])]
    public function showsubcategory(int $id): Response
    {
        $subcategory =$this->getDoctrine()->getRepository(Category::class)->find($id);
        if(!$subcategory) {
            return $this->json('No subcategory found for id' . $id, 404);
        }
        $data =  [
            'id' => $subcategory->getId(),
            'titre' => $subcategory->getTitre(),
            'description' => $subcategory->getDescription(),
            'icone' => $subcategory->getIcone(),
        ];

        return $this->json($data);
    }
    #[Route('/editsubcategory/{id}', name: 'editsubcategory',methods: ["PUT"])]
    public function editSubCategory(Request $request,int $id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $subcategory = $entityManager->getRepository(Category::class)->find($id);
        if (!$subcategory) {
            return $this->json('No subcategory found for id' . $id, 404);
        }
        $subcategory->setTitre($request->request->get('titre'));
        $subcategory->setDescription($request->request->get('discription'));
        $subcategory->setIcone($request->request->get('icone'));
        $entityManager->flush();
        $data[] = [
            'id' => $subcategory->getId(),
            'titre' => $subcategory->getTitre(),
            'description' => $subcategory->getDescription(),
            'icone' =>$subcategory->getIcone(),
        ];
        return $this->json($data);
    }
    #[Route('/deletesubcategory/{id}', name: 'deletesubcategory',methods: ["DELETE"])]
    public function deletesubcategory(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $subcategory = $entityManager->getRepository(Subcategory::class)->find($id);
        if (!$subcategory) {
            return $this->json('No subcategory found for id' . $id, 404);
        }
        $entityManager->remove($subcategory);
        $entityManager->flush();

        return $this->json('Deleted a subcategory successfully with id ' . $id);

    }

}
