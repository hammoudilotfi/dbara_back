<?php

namespace App\Controller;

use App\Entity\Reeldbara;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/api', name: 'api_')]
class ApiReelDbaraController extends AbstractController
{
    private $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    #[Route('/addreeldbara', name: 'ajoutreeldbara')]
    public function addreeldbara(Request $request ,EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $reeldbara = new Reeldbara();
        $reeldbara->setType($request->request->get('type'));
        $reeldbara->setNom($request->request->get('nom'));
        $reeldbara->setDescription($request->request->get('description'));
        $reeldbara->setTempsPreparation($request->request->get('temps_preparation'));
        $reeldbara->setNombreIngredient($request->request->get('nombre_ingredient'));
        $reeldbara->setNivDifficulte($request->request->get('niv_difficulte'));
        $reeldbara->setIngredients($request->request->get('ingredients'));
        $reeldbara->setApportsNutritifs($request->request->get('apports_nutritifs'));
        $file = $request->files->get('photo');
        if ($file) {
            $fileName = $this->uploadFile($file, $slugger, $this->getParameter('photos_directory'));
            $reeldbara->setPhoto($fileName);
        }

        $file = $request->files->get('video');
        if ($file) {
            $fileName = $this->uploadFile($file, $slugger, $this->getParameter('uploads_directory'));
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
    private function uploadFile($file, $slugger, $targetDirectory )
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        try {
            $file->move(
            // $this->getParameter('photos_directory'),
                $targetDirectory,
                $fileName
            );
        } catch (FileException $e) {
            // Handle the exception if file upload fails
            throw new \Exception('Failed to upload file');
        }
        return $fileName;
    }
    #[Route('/uploads/photos/{fileName}', name: 'get_uploaded_image', methods: ['GET'])]
    public function getUploadedFile(string $fileName): Response
    {
        $filePath = $this->getParameter('photos_directory') . '/' . $fileName;

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('File not found');
        }

        // Return the file as a response
        $fileContent = file_get_contents($filePath);
        $response = new Response($fileContent, 200);
        $response->headers->set('Content-Type', 'image/jpeg'); // Adjust the content type based on the file type

        return $response;
    }
    #[Route('/upload/videos/{fileName}', name: 'get_uploaded_video', methods: ['GET'])]
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
    #[Route('/getreeldbara', name: 'getreeldbara',methods: ["GET"])]
    public function getdbaretchef():Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Reeldbara::class)
            ->findAll();
        $data = [];
        foreach ($products as $product) {
            $photoFileName = $product->getPhoto();
            $videoFileName = $product->getVideo();
            $photoUrl = $this->generateUrl('get_uploaded_image', ['fileName' => $photoFileName]);
            if (!empty($videoFileName)) {
                $videoUrl = $this->generateUrl('get_uploaded_video', ['fileName' => $videoFileName]);
            } else {
                $videoUrl = null;
            }
            $data[] = [
                'id' => $product->getId(),
                'type' => $product->getType(),
                'nom' => $product->getNom(),
                'description' => $product->getDescription(),
                'temps_prepartion'=>$product->getTempsPreparation(),
                'nombre_ingredient'=>$product->getNombreIngredient(),
                'niv_difficulte'=>$product->getNivDifficulte(),
                'ingredients'=>$product->getIngredients(),
                'apports_nutritifs'=>$product->getApportsNutritifs(),
                'photo' => $photoUrl,
                'video'=>$videoUrl,
            ];
        }
        return $this->json($data);

    }
    #[Route('/getreeldbara/{id}', name: 'getreeldbarabyid',methods: ["GET"])]
    public function getreeldbaras(int $id): Response
    {
        // dd($id);
        $reeldbara = $this->getDoctrine()->getRepository(Reeldbara::class)->find($id);
        if (!$reeldbara) {

            return $this->json('No Reel Dbara found for id' . $id, 404);
        }
        $photoFileName = $reeldbara->getPhoto();
        $videoFileName = $reeldbara->getVideo();
        $photoUrl = $this->generateUrl('get_uploaded_image', ['fileName' => $photoFileName]);
        if (!empty($videoFileName)) {
            $videoUrl = $this->generateUrl('get_uploaded_video', ['fileName' => $videoFileName]);
        } else {
            $videoUrl = null;
        }
        $data[] = [
            'id' => $reeldbara->getId(),
            'type' => $reeldbara->getType(),
            'nom' => $reeldbara->getNom(),
            'description' => $reeldbara->getDescription(),
            'temps_prepartion'=>$reeldbara->getTempsPreparation(),
            'nombre_ingredient'=>$reeldbara->getNombreIngredient(),
            'niv_difficulte'=>$reeldbara->getNivDifficulte(),
            'ingredients'=>$reeldbara->getIngredients(),
            'apports_nutritifs'=>$reeldbara->getApportsNutritifs(),
            'photo' => $photoUrl,
            'video'=>$videoUrl,
        ];
        return $this->json($data);
    }
    #[Route('/editreeldbara/{id}', name: 'editreeldbara',methods: ["PUT"])]
    public function editreeldbara(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reeldbara = $entityManager->getRepository(Reeldbara::class)->find($id);
        if (!$reeldbara) {
            return $this->json('No Reel Dbara found for id' . $id, 404);
        }
        $reeldbara->setUpdatedAt(new \DateTime());
        $reeldbara->setNom($request->request->get('nom'));
        $reeldbara->setDescription($request->request->get('description'));
        $reeldbara->setDateCreation(new \DateTime());
        $reeldbara->setTempsPreparation($request->request->get('temps_preparation'));
        $reeldbara->setType($request->request->get('type'));
        $reeldbara->setTemperature($request->request->get('temperature'));
        $reeldbara->setCost($request->request->get('cost'));
        $reeldbara->setPhoto($request->request->get('photo'));
        $reeldbara->setVideo($request->request->get('video'));
        $entityManager->flush();
        $data[] = [
            'id' => $reeldbara->getId(),
            'type' => $reeldbara->getType(),
            'nom' => $reeldbara->getNom(),
            'description' => $reeldbara->getDescription(),
            'temps_prepartion'=>$reeldbara->getTempsPreparation(),
            'nombre_ingredient'=>$reeldbara->getNombreIngredient(),
            'niv_difficulte'=>$reeldbara->getNivDifficulte(),
            'ingredients'=>$reeldbara->getIngredients(),
            'apports_nutritifs'=>$reeldbara->getApportsNutritifs(),
            'photo'=>$reeldbara->getPhoto(),
            'video'=>$reeldbara->getVideo(),
        ];
        return $this->json($data);

    }
    #[Route('/deletereeldbara/{id}', name: 'deletereeldbara',methods: ["DELETE"])]
    public function deletereeldbara(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reeldbara = $entityManager->getRepository(Reeldbara::class)->find($id);
        if (!$reeldbara) {
            return $this->json('No Reel Dbara found for id' . $id, 404);
        }
        $entityManager->remove($reeldbara);
        $entityManager->flush();

        return $this->json('Deleted a Reel Dbara successfully with id ' . $id);
    }
}
