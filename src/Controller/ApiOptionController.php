<?php

namespace App\Controller;

use App\Entity\Option;
use App\Repository\JeuxRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiOptionController extends AbstractController
{
  /*  private $AbonnesRepository;

    public function __construct(AbonnesRepository $AbonnesRepository)
    {
        $this->AbonnesRepository = $AbonnesRepository;
    }*/
    private $JeuxRepository;

    public function __construct(JeuxRepository $jeuxRepository)
    {
        $this->JeuxRepository = $jeuxRepository;
    }
    #[Route('/getoption', name: 'getoption')]
    public function getoption(): Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Option::class)
            ->findAll();
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'media' => $product->getMedia(),
                'text' => $product->getText(),
                'date_creation' => $product->getDateCreation(),
                'status' => $product->getStatus(),
                'updated_at' => $product->getUpdatedAt(),
            ];
        }
        return $this->json($data);
    }
    #[Route('/addoption', name: 'addoption', methods: ["POST"])]
    public function addoption(Request $request, EntityManagerInterface $entityManager): Response
    {
        $option = new Option();
        $option->setMedia($request->request->get('media'));
        $option->setText($request->request->get('text'));
        $option->setDateCreation(new \DateTime());
        $option->setStatus($request->request->get('status'));
        $option->setUpdatedAt(new \DateTime());
        //init abonnes-id
       /* $abonnes_id=(int)$request->request->get('abonnes_id');

        if ($abonnes_id){
            $abonnes=$this->AbonnesRepository->find($abonnes_id);
            //dd($abonnes);exit;
            if(!empty($abonnes)){
                $option->setOptions($abonnes);
            }
        }*/
        //init jeux-id
        $jeux_id=(int)$request->request->get('jeux_id');

        if ($jeux_id){
            $jeux=$this->JeuxRepository->find($jeux_id);
            //dd($abonnes);exit;
            if(!empty($jeux)){
                $option->setJeux($jeux);
            }
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($option);
        $entityManager->flush();
        return $this->json('Created new option successfully with id ' . $option->getId());
    }
    #[Route('/showoption/{id}', name: 'showoption',methods: ["GET"])]
    public function showoption(int $id): Response
    {
        $option = $this->getDoctrine()->getRepository(Option::class)->find($id);
        if (!$option) {

            return $this->json('No option found for id' . $id, 404);
        }
        $data[] = [
            'id' => $option->getId(),
            'media' => $option->getMedia(),
            'text' => $option->getText(),
            'date_creation' => $option->getDateCreation(),
            'status' => $option->getStatus(),
            'updated_at' => $option->getUpdatedAt(),
        ];
       return $this->json($data);
    }
    #[Route('/editoption/{id}', name: 'editoption',methods: ["PUT"])]
    public function editoption(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $option = $entityManager->getRepository(Option::class)->find($id);
        if (!$option) {
            return $this->json('No option found for id' . $id, 404);
        }
        $option->setMedia($request->request->get('media'));
        $option->setText($request->request->get('text'));
        $option->setDateCreation(new \DateTime());
        $option->setStatus($request->request->get('status'));
        $option->setUpdatedAt(new \DateTime());
        $entityManager->flush();
        $data[] = [
            'id' => $option->getId(),
            'media' => $option->getMedia(),
            'text' => $option->getText(),
            'date_creation' => $option->getDateCreation(),
            'status' => $option->getStatus(),
            'updated_at' => $option->getUpdatedAt(),
        ];
        return $this->json($data);
    }
    #[Route('/deleteoption/{id}', name: 'deleteoption',methods: ["DELETE"])]
    public function deleteOption(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $option = $entityManager->getRepository(Option::class)->find($id);
        if (!$option) {
            return $this->json('No option found for id' . $id, 404);
        }
        $entityManager->remove($option);
        $entityManager->flush();

        return $this->json('Deleted a option successfully with id ' . $id);
    }



}
