<?php

namespace App\Controller;

use App\Entity\Reeldbara;
use App\Entity\ReeldbaraFront;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/api', name: 'api_')]
class ReeldbaraFrontApiController extends AbstractController
{
    private $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    #[Route('/recettes/real', name: 'reeldbara_ajout')]
    public function addreeldbara(Request $request ,EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $reeldbara = new ReeldbaraFront();
        $reeldbara->setNom($request->request->get('nom'));
        $file = $request->files->get('video');
        if ($file) {
            $fileName = $this->uploadFile($file, $slugger);
            $reeldbara->setVideo($fileName);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reeldbara);
        $entityManager->flush();
        return $this->json('Created new Reel Dbara successfully with id ' . $reeldbara->getId());
    }
    /**
     * Uploads the file to the server and returns the generated file name.
     */
    private function uploadFile($file, $slugger)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move(
                $this->getParameter('photos_directory'),
                $fileName
            );
        } catch (FileException $e) {
            // Handle the exception if file upload fails
            throw new \Exception('Failed to upload file');
        }

        return $fileName;
    }
    #[Route('/upload/{fileName}', name: 'get_uploaded_video', methods: ['GET'])]
    public function getUploadedFiles(string $fileName): Response
    {
        $filePath = $this->getParameter('uploads_directory') . '/' . $fileName;

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('File not found');
        }

        $fileContent = file_get_contents($filePath);
        $response = new Response($fileContent, 200);
        $response->headers->set('Content-Type', 'video/mp4'); // Adjust the content type based on the video file type

        return $response;
    }
    #[Route('/showreeldbara', name: 'showreeldbara',methods: ["GET"])]
    public function showdbaretchef():Response
    {
        $products = $this->getDoctrine()
            ->getRepository(ReeldbaraFront::class)
            ->findAll();
        $data = [];
        foreach ($products as $product) {
            $videoFileName = $product->getVideo();
            if (!empty($videoFileName)) {
                $videoUrl = $this->generateUrl('get_uploaded_video', ['fileName' => $videoFileName]);
            } else {
                $videoUrl = null;
            }
            $data[] = [
                'id' => $product->getId(),
                'nom' => $product->getNom(),
                'video'=>$videoUrl,
            ];
        }
        return $this->json($data);

    }
    #[Route('/showreeldbara/{id}', name: 'showbyidreeldbara',methods: ["GET"])]
    public function showbyidreeldbara(int $id): Response
    {
        // dd($id);
        $reeldbara = $this->getDoctrine()->getRepository(Reeldbara::class)->find($id);
        if (!$reeldbara) {

            return $this->json('No Reel Dbara found for id' . $id, 404);
        }
        $videoFileName = $reeldbara->getVideo();
        if (!empty($videoFileName)) {
            $videoUrl = $this->generateUrl('get_uploaded_video', ['fileName' => $videoFileName]);
        } else {
            $videoUrl = null;
        }
        $data[] = [
            'id' => $reeldbara->getId(),
            'video'=>$videoUrl,
        ];
        return $this->json($data);
    }
    #[Route('/updatereeldbara/{id}', name: 'updatereeldbara',methods: ["PUT"])]
    public function updatereeldbara(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reeldbara = $entityManager->getRepository(ReeldbaraFront::class)->find($id);
        if (!$reeldbara) {
            return $this->json('No Reel Dbara found for id' . $id, 404);
        }
        $reeldbara->setNom($request->request->get('nom'));
        $reeldbara->setVideo($request->request->get('video'));
        $entityManager->flush();
        $data[] = [
            'id' => $reeldbara->getId(),
            'video'=>$reeldbara->getVideo(),
        ];
        return $this->json($data);

    }
    #[Route('/removereeldbara/{id}', name: 'removereeldbara',methods: ["DELETE"])]
    public function removereeldbara(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reeldbara = $entityManager->getRepository(ReeldbaraFront::class)->find($id);
        if (!$reeldbara) {
            return $this->json('No Reel Dbara found for id' . $id, 404);
        }
        $entityManager->remove($reeldbara);
        $entityManager->flush();

        return $this->json('Deleted a Reel Dbara successfully with id ' . $id);
    }
}
