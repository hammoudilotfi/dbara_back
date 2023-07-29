<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Repository\SubcategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/api', name: 'api_')]
class ApiRecetteController extends AbstractController
{
    private $SubcategoryRepository;
    private $slugger;

    public function __construct(SubcategoryRepository $SubcategoryRepository ,SluggerInterface $slugger)
    {
        $this->SubcategoryRepository = $SubcategoryRepository;
        $this->slugger = $slugger;
    }
    #[Route('/getrecette', name: 'getrecette',methods: ["GET"])]
    public function getRecettes():Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Recette::class)
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
                'updated_at' =>$product->getUpdatedAt(),
                'nom' => $product->getNom(),
                'description' => $product->getDescription(),
                'date_creation' =>$product->getDateCreation(),
                'temps_prepartion'=>$product->getTempsPreparation(),
                'niv_difficulte'=>$product->getNivDifficulte(),
                'temperature'=>$product->getTemperature(),
                'cost'=>$product->getCost(),
                'photo' => $photoUrl,
                'video'=>$videoUrl,
            ];
        }
        return $this->json($data);
    }
    #[Route('/uploads/{fileName}', name: 'get_uploaded_image', methods: ['GET'])]
    public function getUploadedFile(string $fileName): Response
    {
        $filePath = $this->getParameter('photos_directory') . '/' . $fileName;

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('File not found');
        }
        $fileContent = file_get_contents($filePath);
        $response = new Response($fileContent, 200);
        $response->headers->set('Content-Type', 'image/jpeg'); // Adjust the content type based on the file type

        return $response;
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

    #[Route('/addrecette', name: 'addrecette',methods: ["POST"])]
    public function addRecette(Request $request ,EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {

        $recette = new Recette();
        $recette->setUpdatedAt(new \DateTime());
        $recette->setNom($request->request->get('nom'));
        $recette->setDescription($request->request->get('description'));
        $recette->setDateCreation(new \DateTime());
        $recette->setTempsPreparation($request->request->get('temps_preparation'));
        $recette->setNivDifficulte($request->request->get('niv_difficulte'));
        $recette->setTemperature($request->request->get('temperature'));
        $recette->setCost($request->request->get('cost'));
        $file = $request->files->get('photo');
        if ($file) {
            $fileName = $this->uploadFile($file, $slugger, $this->getParameter('photos_directory'));
            $recette->setPhoto($fileName);
        }

        $file = $request->files->get('video');
        if ($file) {
            $fileName = $this->uploadFile($file, $slugger, $this->getParameter('uploads_directory'));
            $recette->setVideo($fileName);
        }
        //init subcategory-id
        $subcatecory_id=(int)$request->request->get('subcategory_id');

        if ($subcatecory_id){
            $subcatecory=$this->SubcategoryRepository->find($subcatecory_id);
            //dd($subcatecory);exit;
            if(!empty($subcatecory)){
                $recette->setSubcategory($subcatecory);
            }
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($recette);
        $entityManager->flush();
        return $this->json('Created new recette successfully with id ' . $recette->getId());
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
    #[Route('/showrecette/{id}', name: 'showrecette',methods: ["GET"])]
    public function showRecettes(int $id): Response
    {
       // dd($id);
        $recette = $this->getDoctrine()->getRepository(Recette::class)->find($id);
        if (!$recette) {

            return $this->json('No recette found for id' . $id, 404);
        }
        $photoFileName = $recette->getPhoto();
        $videoFileName = $recette->getVideo();
        $photoUrl = $this->generateUrl('get_uploaded_image', ['fileName' => $photoFileName]);
        if (!empty($videoFileName)) {
            $videoUrl = $this->generateUrl('get_uploaded_video', ['fileName' => $videoFileName]);
        } else {
            $videoUrl = null;
        }
        $data[] = [
            'id' => $recette->getId(),
            'updated_at' =>$recette->getUpdatedAt(),
            'nom' => $recette->getNom(),
            'description' => $recette->getDescription(),
            'date_creation' =>$recette->getDateCreation(),
            'temps_prepartion'=>$recette->getTempsPreparation(),
            'niv_difficulte'=>$recette->getNivDifficulte(),
            'temperature'=>$recette->getTemperature(),
            'cost'=>$recette->getCost(),
            'photo' => $photoUrl,
            'video'=>$videoUrl,
        ];
        return $this->json($data);
    }
  /*  #[Route('/getrecettebysubcategory/{subcategory_id}', name: 'getrecettebysubcategory',methods: ["GET"])]
    public function getrecettebysubcategory(int $subcategory_id): Response
    {
        // dd($id);
        $recette = $this->getDoctrine()->getRepository(Recette::class)->find($subcategory_id);
        if (!$recette) {

            return $this->json('No recette found for subcategory_id' . $subcategory_id, 404);
        }
        $data[] = [
            'id' => $recette->getId(),
            'updated_at' =>$recette->getUpdatedAt(),
            'nom' => $recette->getNom(),
            'description' => $recette->getDescription(),
            'date_creation' =>$recette->getDateCreation(),
            'temps_prepartion'=>$recette->getTempsPreparation(),
            'niv_difficulte'=>$recette->getNivDifficulte(),
            'temperature'=>$recette->getTemperature(),
            'cost'=>$recette->getCost(),
            'photo'=>$recette->getPhoto(),
            'video'=>$recette->getVideo(),
            'subcategory_id'=>$recette->getSubcategory(),
        ];
        return $this->json($data);
    }*/
    #[Route('/editrecette/{id}', name: 'editrecette',methods: ["PUT"])]
    public function edit(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $recette = $entityManager->getRepository(Recette::class)->find($id);
        if (!$recette) {
            return $this->json('No recette found for id' . $id, 404);
        }
        $recette->setUpdatedAt(new \DateTime());
        $recette->setNom($request->request->get('nom'));
        $recette->setDescription($request->request->get('description'));
        $recette->setDateCreation(new \DateTime());
        $recette->setTempsPreparation($request->request->get('temps_preparation'));
        $recette->setNivDifficulte($request->request->get('niv_difficulte'));
        $recette->setTemperature($request->request->get('temperature'));
        $recette->setCost($request->request->get('cost'));
        $recette->setPhoto($request->request->get('photo'));
        $recette->setVideo($request->request->get('video'));
        $entityManager->flush();
        $data[] = [
            'id' => $recette->getId(),
            'updated_at' =>$recette->getUpdatedAt(),
            'nom' => $recette->getNom(),
            'description' => $recette->getDescription(),
            'date_creation' =>$recette->getDateCreation(),
            'temps_prepartion'=>$recette->getTempsPreparation(),
            'niv_difficulte'=>$recette->getNivDifficulte(),
            'temperature'=>$recette->getTemperature(),
            'cost'=>$recette->getCost(),
            'photo'=>$recette->getPhoto(),
            'video'=>$recette->getVideo(),
        ];
        return $this->json($data);

    }

    #[Route('/deleterecette/{id}', name: 'deleterecette',methods: ["DELETE"])]
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $recette = $entityManager->getRepository(Recette::class)->find($id);
        if (!$recette) {
            return $this->json('No recette found for id' . $id, 404);
        }
        $entityManager->remove($recette);
        $entityManager->flush();

        return $this->json('Deleted a recette successfully with id ' . $id);
    }
}
