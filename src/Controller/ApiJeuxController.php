<?php

namespace App\Controller;

use App\Entity\Jeux;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiJeuxController extends AbstractController
{
    #[Route('/getjeux', name: 'getjeux', methods: ["GET"])]
    public function getjeux(): Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Jeux::class)
            ->findAll();
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'nom' => $product->getNom(),
                'duree' => $product->getDuree(),
                'type' => $product->getType(),
                'created_at' => $product->getCreatedAt(),
                'updated_at' => $product->getUpdatedAt(),
                'status' => $product->getStatus(),
                'datedebut' => $product->getDatedebut(),
                'datefin' => $product->getDatefin(),
                'description' => $product->getDescription(),
            ];
        }
        return $this->json($data);
    }

    #[Route('/addjeux', name: 'addjeux', methods: ["POST"])]
    public function addJeux(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jeux = new Jeux();
        $jeux->setNom($request->request->get('nom'));
        $jeux->setDuree($request->request->get('duree'));
        $jeux->setType($request->request->get('type'));
        $jeux->setCreatedAt(new \DateTime());
        $jeux->setUpdatedAt(new \DateTime());
        $jeux->setStatus($request->request->get('status'));
        $jeux->setDatedebut(new \DateTime());
        $jeux->setDatefin(new \DateTime());
        $jeux->setDescription($request->request->get('description'));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($jeux);
        $entityManager->flush();
        return $this->json('Created new jeux successfully with id ' . $jeux->getId());
    }
    #[Route('/showjeux/{id}', name: 'showjeux',methods: ["GET"])]
    public function showJeux(int $id): Response
    {
        $jeux = $this->getDoctrine()->getRepository(Jeux::class)->find($id);
        if (!$jeux) {

            return $this->json('No jeux found for id' . $id, 404);
        }
        $data[] = [
            'id' => $jeux->getId(),
            'nom' => $jeux->getNom(),
            'duree' => $jeux->getDuree(),
            'type' => $jeux->getType(),
            'created_at' => $jeux->getCreatedAt(),
            'updated_at' => $jeux->getUpdatedAt(),
            'status' => $jeux->getStatus(),
            'datedebut' => $jeux->getDatedebut(),
            'datefin' => $jeux->getDatefin(),
            'description' => $jeux->getDescription(),
        ];
        return $this->json($data);
    }
    #[Route('/editjeux/{id}', name: 'editjeux',methods: ["PUT"])]
    public function editJeux(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $jeux = $entityManager->getRepository(Jeux::class)->find($id);
        if (!$jeux) {
            return $this->json('No jeux found for id' . $id, 404);
        }
        $jeux->setNom($request->request->get('nom'));
        $jeux->setDuree($request->request->get('duree'));
        $jeux->setType($request->request->get('type'));
        $jeux->setCreatedAt(new \DateTime());
        $jeux->setUpdatedAt(new \DateTime());
        $jeux->setStatus($request->request->get('status'));
        $jeux->setDatedebut(new \DateTime());
        $jeux->setDatefin(new \DateTime());
        $jeux->setDescription($request->request->get('description'));
        $entityManager->flush();
        $data[] = [
            'id' => $jeux->getId(),
            'nom' => $jeux->getNom(),
            'duree' => $jeux->getDuree(),
            'type' => $jeux->getType(),
            'created_at' => $jeux->getCreatedAt(),
            'updated_at' => $jeux->getUpdatedAt(),
            'status' => $jeux->getStatus(),
            'datedebut' => $jeux->getDatedebut(),
            'datefin' => $jeux->getDatefin(),
            'description' => $jeux->getDescription(),
        ];
        return $this->json($data);
    }
    #[Route('/deletejeux/{id}', name: 'deletejeux',methods: ["DELETE"])]
    public function deleteJeux(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $jeux = $entityManager->getRepository(Jeux::class)->find($id);
        if (!$jeux) {
            return $this->json('No jeux found for id' . $id, 404);
        }
        $entityManager->remove($jeux);
        $entityManager->flush();

        return $this->json('Deleted a jeux successfully with id ' . $id);

    }

}
