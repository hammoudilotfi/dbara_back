<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Entity\Savednote;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]

class ApiSavednoteController extends AbstractController
{
    private $RecetteRepository;
    public function __construct(RecetteRepository $RecetteRepository)
    {
        $this->RecetteRepository = $RecetteRepository;
    }
    #[Route('/getsavednote', name: 'getsavednote',methods: ["GET"])]
    public function getsavednote(): Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Savednote::class)
            ->findAll();
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'saved' =>$product->getSaved(),
                'note' => $product->getNote(),
            ];
        }
        return $this->json($data);
    }
    #[Route('/addsavednote', name: 'addsavednote',methods: ["POST"])]
    public function addsavednote(Request $request ,EntityManagerInterface $entityManager): Response
    {
        $savednote = new Savednote();
        $savednote->setSaved($request->request->get('saved'));
        $savednote->setNote($request->request->get('note'));
        //init subcategory-id
        $recette_id=(int)$request->request->get('recett_id');

        if ($recette_id){
            $recette=$this->RecetteRepository->find($recette_id);
            //dd($subcatecory);exit;
            if(!empty($recette)){
                $savednote->setRecett($recette);
            }
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($savednote);
        $entityManager->flush();
        return $this->json('Created new SavedNote successfully with id ' . $savednote->getId());
    }
    #[Route('/showsavednote/{id}', name: 'showsavednote',methods: ["GET"])]
    public function showsavednote(int $id): Response
    {
        $savednote = $this->getDoctrine()->getRepository(Savednote::class)->find($id);
        if (!$savednote) {

            return $this->json('No savednote found for id' . $id, 404);
        }
        $data[] = [
            'id' => $savednote->getId(),
            'saved' =>$savednote->getSaved(),
            'note' => $savednote->getNote(),
        ];
        return $this->json($data);
    }
    #[Route('/editsavednote/{id}', name: 'editsavednote',methods: ["PUT"])]
    public function editsavednote(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $savednote = $entityManager->getRepository(Savednote::class)->find($id);
        if (!$savednote) {
            return $this->json('No savednote found for id' . $id, 404);
        }
        $savednote->setSaved($request->request->get('saved'));
        $savednote->setNote($request->request->get('note'));
        $entityManager->flush();
        $data[] = [
            'id' => $savednote->getId(),
            'saved' =>$savednote->getSaved(),
            'note' => $savednote->getNote(),
        ];
        return $this->json($data);
    }
    #[Route('/deletesavednote/{id}', name: 'deletesavednotevote',methods: ["DELETE"])]
    public function deletesavednote(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $savednote = $entityManager->getRepository(Savednote::class)->find($id);
        if (!$savednote) {
            return $this->json('No savednote found for id' . $id, 404);
        }
        $entityManager->remove($savednote);
        $entityManager->flush();

        return $this->json('Deleted a savednote successfully with id ' . $id);
    }
    //calcule le moyen de note pour une recette
    #[Route('/recette/{id}/average-score', name: 'average-score',methods: ["POST"])]
    public function calculateAverageScore(Recette $recette): Response
    {
        $notes = $recette->getSavednotes();
        $totalScores = 0;
        $totalNotes = count($notes);
        foreach ($notes as $note) {
            $totalScores += $note->getNote();
        }
        $averageScore = $totalNotes > 0 ? $totalScores / $totalNotes : 0;
        return $this->json(['average_score' => $averageScore]);
    }
}
