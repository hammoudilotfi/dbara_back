<?php

namespace App\Controller;

use App\Entity\Dbaralive;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/api', name: 'api_')]
class ApiDbaraLiveController extends AbstractController
{
    private $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    #[Route('/adddbaralive', name: 'ajoutbaralive')]
    public function adddbaralive(Request $request ,EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $dbaralive = new Dbaralive();
        $dbaralive->setType($request->request->get('type'));
        $dbaralive->setNom($request->request->get('nom'));
        $dbaralive->setDescription($request->request->get('description'));
        $dbaralive->setTempsPreparation($request->request->get('temps_preparation'));
        $dbaralive->setNombreIngredient($request->request->get('nombre_ingredient'));
        $dbaralive->setNivDifficulte($request->request->get('niv_difficulte'));
        $dbaralive->setIngredient($request->request->get('ingredient'));
        $dbaralive->setApportsNutritifs($request->request->get('apports_nutritifs'));
        $file = $request->files->get('photo');
        if ($file) {
            $fileName = $this->uploadFile($file, $slugger, $this->getParameter('photos_directory'));
            $dbaralive->setPhoto($fileName);
        }

        $file = $request->files->get('video');
        if ($file) {
            $fileName = $this->uploadFile($file, $slugger, $this->getParameter('uploads_directory'));
            $dbaralive->setVideo($fileName);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($dbaralive);
        $entityManager->flush();
        return $this->json('Created new Dbara Live successfully with id ' . $dbaralive->getId());
    }
    /**
     * Uploads the file to the server and returns the generated file name.
     */

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
    private function uploadFile($file, $slugger,$targetDirectory)
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
    #[Route('/getdbaralive', name: 'getdbaralive',methods: ["GET"])]
    public function getdbaralive():Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Dbaralive::class)
            ->findAll();
        $data = [];
        foreach ($products as $product) {
            $photoFileName = $product->getPhoto();
            $videoFileName = $product->getVideo();
            if (!empty($videoFileName)) {
                $videoUrl = $this->generateUrl('get_uploaded_video', ['fileName' => $videoFileName]);
            } else {
                $videoUrl = null;
            }
            if (!empty($photoFileName)) {
                $photoUrl = $this->generateUrl('get_uploaded_image', ['fileName' => $photoFileName]);
            } else {
                $photoUrl = null;
            }
            $data[] = [
                'id' => $product->getId(),
                'type' => $product->getType(),
                'nom' => $product->getNom(),
                'description' => $product->getDescription(),
                'temps_prepartion'=>$product->getTempsPreparation(),
                'nombre_ingredient'=>$product->getNombreIngredient(),
                'niv_difficulte'=>$product->getNivDifficulte(),
                'ingredients'=>$product->getIngredient(),
                'apports_nutritifs'=>$product->getApportsNutritifs(),
                'photo' => $photoUrl,
                'video'=>$videoUrl,
            ];
        }
        return $this->json($data);

    }
    #[Route('/getdbaralive/{id}', name: 'getdbaralives',methods: ["GET"])]
    public function getdbaralives(int $id): Response
    {
        // dd($id);
        $dbaralive = $this->getDoctrine()->getRepository(Dbaralive::class)->find($id);
        if (!$dbaralive) {

            return $this->json('No Dbara Live found for id' . $id, 404);
        }
        $photoFileName = $dbaralive->getPhoto();
        $videoFileName = $dbaralive->getVideo();
        if (!empty($videoFileName)) {
            $videoUrl = $this->generateUrl('get_uploaded_video', ['fileName' => $videoFileName]);
        } else {
            $videoUrl = null;
        }
        $photoUrl = $this->generateUrl('get_uploaded_image', ['fileName' => $photoFileName]);
        $data[] = [
            'id' => $dbaralive->getId(),
            'type' => $dbaralive->getType(),
            'nom' => $dbaralive->getNom(),
            'description' => $dbaralive->getDescription(),
            'temps_prepartion'=>$dbaralive->getTempsPreparation(),
            'nombre_ingredient'=>$dbaralive->getNombreIngredient(),
            'niv_difficulte'=>$dbaralive->getNivDifficulte(),
            'ingredient'=>$dbaralive->getIngredient(),
            'apports_nutritifs'=>$dbaralive->getApportsNutritifs(),
            'photo' => $photoUrl,
            'video'=>$videoUrl,
        ];
        return $this->json($data);
    }
    #[Route('/editdbaralive/{id}', name: 'editdbaralive',methods: ["PUT"])]
    public function editdbaralive(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dbaralive = $entityManager->getRepository(Dbaralive::class)->find($id);
        if (!$dbaralive) {
            return $this->json('No Dbaret chef found for id' . $id, 404);
        }
        $dbaralive->setType($request->request->get('type'));
        $dbaralive->setNom($request->request->get('nom'));
        $dbaralive->setDescription($request->request->get('description'));
        $dbaralive->setTempsPreparation($request->request->get('temps_preparation'));
        $dbaralive->setNombreIngredient($request->request->get('nombre_ingredient'));
        $dbaralive->setNivDifficulte($request->request->get('niv_difficulte'));
        $dbaralive->setIngredient($request->request->get('ingredient'));
        $dbaralive->setApportsNutritifs($request->request->get('apports_nutritifs'));
        $dbaralive->setPhoto($request->request->get('photo'));
        $dbaralive->setVideo($request->request->get('video'));
        $entityManager->flush();
        $data[] = [
            'id' => $dbaralive->getId(),
            'type' => $dbaralive->getType(),
            'nom' => $dbaralive->getNom(),
            'description' => $dbaralive->getDescription(),
            'temps_prepartion'=>$dbaralive->getTempsPreparation(),
            'nombre_ingredient'=>$dbaralive->getNombreIngredient(),
            'niv_difficulte'=>$dbaralive->getNivDifficulte(),
            'ingredients'=>$dbaralive->getIngredients(),
            'apports_nutritifs'=>$dbaralive->getApportsNutritifs(),
            'photo'=>$dbaralive->getPhoto(),
            'video'=>$dbaralive->getVideo(),
        ];
        return $this->json($data);

    }
    #[Route('/deletedbaralive/{id}', name: 'deletedbaralive',methods: ["DELETE"])]
    public function deletedbaralive(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dbaralive = $entityManager->getRepository(Dbaralive::class)->find($id);
        if (!$dbaralive) {
            return $this->json('No Dbara Live found for id' . $id, 404);
        }
        $entityManager->remove($dbaralive);
        $entityManager->flush();

        return $this->json('Deleted a Dbara Live successfully with id ' . $id);
    }
}
