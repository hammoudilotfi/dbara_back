<?php

namespace App\Controller;

use App\Entity\Vote;
use App\Repository\OptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiVoteController extends AbstractController
{
 /*   private $AbonnesRepository;

    public function __construct(AbonnesRepository $AbonnesRepository)
    {
        $this->AbonnesRepository = $AbonnesRepository;
    }*/
    private $OptionRepository;
    public function __construct(OptionRepository $OptionRepository)
    {
        $this->OptionRepository = $OptionRepository;
    }
    #[Route('/getvote', name: 'getvote',methods: ["GET"])]
    public function getvote(): Response
    {
       $products = $this->getDoctrine()
            ->getRepository(Vote::class)
            ->findAll();
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'created_at' =>$product->getCreatedAt(),
                'note' => $product->getNote(),
            ];
        }
        return $this->json($data);
    }
    #[Route('/addvote', name: 'addvote',methods: ["POST"])]
    public function addvote(Request $request ,EntityManagerInterface $entityManager): Response
    {
        $vote =new Vote();
        $vote->setCreatedAt(new \DateTime());
        $vote->setNote($request->request->get('note'));
        /*//init abonnes-id
         $abonnes_id=(int)$request->request->get('abonnes_id');

         if ($abonnes_id){
             $abonnes=$this->AbonnesRepository->find($abonnes_id);
             //dd($abonnes);exit;
             if(!empty($abonnes)){
                 $vote->setAbonnes($abonnes);
             }
         }*/
        //init abonnes-id
        $option_id=(int)$request->request->get('option_id');

        if ($option_id){
            $option=$this->OptionRepository->find($option_id);
            //dd($option);exit;
            if(!empty($option)){
                $vote->setOptione($option);
            }
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($vote);
        $entityManager->flush();
        return $this->json('Created new vote successfully with id ' . $vote->getId());
    }
    #[Route('/showvote/{id}', name: 'showvote',methods: ["GET"])]
    public function showvote(int $id): Response
    {
        $vote = $this->getDoctrine()->getRepository(Vote::class)->find($id);
        if (!$vote) {

            return $this->json('No vote found for id' . $id, 404);
        }
        $data[] = [
            'id' => $vote->getId(),
            'created_at' =>$vote->getCreatedAt(),
            'note' => $vote->getNote(),
        ];
        return $this->json($data);
    }
    #[Route('/editvote/{id}', name: 'editvote',methods: ["PUT"])]
    public function editvote(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $vote = $entityManager->getRepository(Vote::class)->find($id);
        if (!$vote) {
            return $this->json('No vote found for id' . $id, 404);
        }
        $vote->setCreatedAt(new \DateTime());
        $vote->setNote($request->request->get('note'));
        $entityManager->flush();
        $data[] = [
            'id' => $vote->getId(),
            'created_at' =>$vote->getCreatedAt(),
            'note' => $vote->getNote(),
        ];
        return $this->json($data);
    }
    #[Route('/deletevote/{id}', name: 'deletevote',methods: ["DELETE"])]
    public function deletevote(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $vote = $entityManager->getRepository(Vote::class)->find($id);
        if (!$vote) {
            return $this->json('No vote found for id' . $id, 404);
        }
        $entityManager->remove($vote);
        $entityManager->flush();

        return $this->json('Deleted a vote successfully with id ' . $id);
    }

}
