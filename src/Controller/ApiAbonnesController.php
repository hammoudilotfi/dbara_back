<?php

namespace App\Controller;

use App\Entity\Abonnes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]

class ApiAbonnesController extends AbstractController
{
    #[Route('/addabonnes', name: 'addabonnes', methods: ["POST"])]
    public function addabonnes(Request $request, EntityManagerInterface $entityManager): Response
    {
        $abonnes = new Abonnes();
        $abonnes->setNum($request->request->get('num'));
        $abonnes->setStatus($request->request->get('status'));
        $abonnes->setDateInscri( new \DateTime());
        $abonnes->setDateDesinscri (new \DateTime());
        $abonnes->setLastLogin(new \DateTime());
        $abonnes->setNom($request->request->get('nom'));
        $abonnes->setPrenom($request->request->get('prenom'));
        $abonnes->setPhoto($request->request->get('photo'));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($abonnes);
        $entityManager->flush();
        return $this->json('Created new abonnes successfully with id ' . $abonnes->getId());
    }

    #[Route('/getabonnes', name: 'getabonnes', methods: ["GET"])]
    public function getAbonnes(): Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Abonnes::class)
            ->findAll();
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'num' => $product->getNum(),
                'status' => $product->getStatus(),
                'dateinscri' => $product->getDateInscri(),
                'datedesinscri' => $product->getDateDesinscri(),
                'lastlogin' => $product->getLastlogin(),
                'nom' => $product->getNom(),
                'prenom' => $product->getPrenom(),
                'photo' => $product->getPhoto(),
            ];
        }
        return $this->json($data);
    }
    #[Route('/showabonnes/{id}', name: 'showabonnes',methods: ["GET"])]
    public function showabonnes(int $id): Response
    {
        $abonnes = $this->getDoctrine()->getRepository(Abonnes::class)->find($id);
        if (!$abonnes) {

            return $this->json('No Abonnes found for id' . $id, 404);
        }
        $data[] = [
            'id' => $abonnes->getId(),
            'num' => $abonnes->getNum(),
            'status' => $abonnes->getStatus(),
            'dateinscri' => $abonnes->getDateInscri(),
            'datedesinscri' => $abonnes->getDateDesinscri(),
            'lastlogin' => $abonnes->getLastlogin(),
            'nom' => $abonnes->getNom(),
            'prenom' => $abonnes->getPrenom(),
            'photo' => $abonnes->getPhoto(),
        ];
        return $this->json($data);
    }
    #[Route('/editabonnes/{id}', name: 'editabonnes',methods: ["PUT"])]
    public function editAbonnes(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $abonnes = $entityManager->getRepository(Abonnes::class)->find($id);
        if (!$abonnes) {
            return $this->json('No Abonnes found for id' . $id, 404);
        }
        $abonnes->setNum($request->request->get('num'));
        $abonnes->setStatus($request->request->get('status'));
        $abonnes->setDateInscri(new \DateTime());
        $abonnes->setDateDesinscri(new \DateTime());
        $abonnes->setLastLogin(new \DateTime());
        $abonnes->setNom($request->request->get('nom'));
        $abonnes->setPrenom($request->request->get('prenom'));
        $abonnes->setPhoto($request->request->get('photo'));
        $entityManager->flush();
        $data[] = [
            'id' => $abonnes->getId(),
            'num' => $abonnes->getNum(),
            'status' => $abonnes->getStatus(),
            'dateinscri' => $abonnes->getDateInscri(),
            'datedesinscri' => $abonnes->getDateDesinscri(),
            'lastlogin' => $abonnes->getLastlogin(),
            'nom' => $abonnes->getNom(),
            'prenom' => $abonnes->getPrenom(),
            'photo' => $abonnes->getPhoto(),
        ];
        return $this->json($data);
    }
    #[Route('/deleteabonnes/{id}', name: 'deleteabonnes',methods: ["DELETE"])]
    public function deleteAbonnes(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $abonnes = $entityManager->getRepository(Abonnes::class)->find($id);
        if (!$abonnes) {
            return $this->json('No Abonnes found for id' . $id, 404);
        }
        $entityManager->remove($abonnes);
        $entityManager->flush();

        return $this->json('Deleted a Abonnes successfully with id ' . $id);
    }
}
