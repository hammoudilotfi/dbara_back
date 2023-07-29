<?php

namespace App\Controller;

use App\Entity\Dbaretelchef;
use App\Entity\DbartiElPrefere;
use App\Entity\Subcategory;
use App\Repository\DbaretelchefRepository;
use App\Repository\SubcategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use JMS\Serializer\Annotation\Groups;

#[Route('/api', name: 'api_')]
class ApiDbaretchefController extends AbstractController
{
    private $SubcategoryRepository;
    private $slugger;

    public function __construct(SubcategoryRepository $SubcategoryRepository ,SluggerInterface $slugger)
    {
        $this->SubcategoryRepository = $SubcategoryRepository;
        $this->slugger = $slugger;
    }

    #[Route('/ajoutbaretchef', name: 'ajoutbaretchef', methods: ['POST'])]
    public function adddbaretchef(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): JsonResponse
    {
        $recettechef = new Dbaretelchef();
        $recettechef->setType($request->request->get('type'));
        $recettechef->setNom($request->request->get('nom'));
        $recettechef->setDescription($request->request->get('description'));
        $recettechef->setTempsPreparation($request->request->get('temps_preparation'));
        $recettechef->setNombreIngredient($request->request->get('nombre_ingredient'));
        $recettechef->setNivDifficulte($request->request->get('niv_difficulte'));
        $recettechef->setIngredients($request->request->get('ingredients'));
        $recettechef->setApportsNutritifs($request->request->get('apports_nutritifs'));
        $file = $request->files->get('photo');
        //dd($file);
        //exit();
        if ($file) {
            $fileName = $this->uploadFile($file, $slugger, $this->getParameter('photos_directory'));
            $recettechef->setPhoto($fileName);
        }
        $file = $request->files->get('video');
        //dd($file);
        if ($file) {
            $fileName = $this->uploadFile($file, $slugger, $this->getParameter('uploads_directory'));
            $recettechef->setVideo($fileName);
        }
        // init subcategory-id
        $subcategoryId = (int)$request->request->get('subcategory_id');
        if ($subcategoryId) {
            $subcatecory = $this->getDoctrine()->getRepository(Subcategory::class)->find($subcategoryId);
            if ($subcatecory) {
                $recettechef->setSubcategory($subcatecory);
            }
        }
        $entityManager->persist($recettechef);
        $entityManager->flush();
        return new JsonResponse([
            'id' => $recettechef->getId(),
            'message' => 'Created new recette chef successfully',
        ]);
    }
    /**
     * Uploads the file to the server and returns the generated file name.
     */
    private function uploadFile($file, $slugger,$uploadDirectory)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        try {
            $file->move($uploadDirectory, $fileName);
        } catch (FileException $e) {
            throw new \Exception('Failed to upload file');
        }
        return $fileName;
    }
    #[Route('/getdbaretchef', name: 'getdbaretchef',methods: ["GET"])]
    public function getdbaretchef():Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Dbaretelchef::class)
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
                'ingredients'=>$product->getIngredients(),
                'apports_nutritifs'=>$product->getApportsNutritifs(),
                'photo' => $photoUrl,
                'video'=>$videoUrl,
            ];
        }
        return $this->json($data);
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
        $response->headers->set('Content-Type', 'image/jpeg');

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
    #[Route('/showdbaretchef/{id}', name: 'showdbaretchef',methods: ["GET"])]
    public function showdbaretchef(int $id): Response
    {
        // dd($id);
        $dbaretchef = $this->getDoctrine()->getRepository(Dbaretelchef::class)->find($id);
        if (!$dbaretchef) {

            return $this->json('No Dbaretchef found for id' . $id, 404);
        }
        $data[] = [
            'id' => $dbaretchef->getId(),
            'type' => $dbaretchef->getType(),
            'nom' => $dbaretchef->getNom(),
            'description' => $dbaretchef->getDescription(),
            'temps_prepartion'=>$dbaretchef->getTempsPreparation(),
            'nombre_ingredient'=>$dbaretchef->getNombreIngredient(),
            'niv_difficulte'=>$dbaretchef->getNivDifficulte(),
            'ingredients'=>$dbaretchef->getIngredients(),
            'apports_nutritifs'=>$dbaretchef->getApportsNutritifs(),
            'photo'=>$dbaretchef->getPhoto(),
            'video'=>$dbaretchef->getVideo(),
        ];
        return $this->json($data);
    }
    #[Route('/searchdbaretchef/{nom}', name: 'searchdbaretchef', methods: ["GET"])]
    public function searchByName(DbaretelchefRepository $dbaretelchefRepository, $nom): Response
    {
        $dbaretchef = $dbaretelchefRepository->searchByName($nom);
        $data = [];

        foreach ($dbaretchef as $product) {
            $photoFileName = $product->getPhoto();
            $videoFileName = $product->getVideo();
            if (!empty($videoFileName)) {
                $videoUrl = $this->generateUrl('get_uploaded_video', ['fileName' => $videoFileName]);
            } else {
                $videoUrl = null;
            }
            $photoUrl = $this->generateUrl('get_uploaded_image', ['fileName' => $photoFileName]);
            $data[] = [
                'id' => $product->getId(),
                'type' => $product->getType(),
                'nom' => $product->getNom(),
                'description' => $product->getDescription(),
                'temps_prepartion' => $product->getTempsPreparation(),
                'nombre_ingredient' => $product->getNombreIngredient(),
                'niv_difficulte' => $product->getNivDifficulte(),
                'ingredients' => $product->getIngredients(),
                'apports_nutritifs' => $product->getApportsNutritifs(),
                'photo' => $photoUrl,
                'video' => $videoUrl,
            ];
        }

        return $this->json($data, 200, [], ['groups' => 'subcategory:read']);
    }


    #[Route('/editdbaretchef/{id}', name: 'editdbaretchef', methods: ['PUT'])]
    public function editdbaretchef(Request $request, int $id, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $dbaretchef = $entityManager->getRepository(Dbaretelchef::class)->find($id);
        if (!$dbaretchef) {
            return $this->json('No Dbaret chef found for id ' . $id, 404);
        }
        $dbaretchef->setType($request->request->get('type'));
        $dbaretchef->setNom($request->request->get('nom'));
        $dbaretchef->setDescription($request->request->get('description'));
        $dbaretchef->setTempsPreparation($request->request->get('temps_preparation'));
        $dbaretchef->setNombreIngredient($request->request->get('nombre_ingredient'));
        $dbaretchef->setNivDifficulte($request->request->get('niv_difficulte'));
        $dbaretchef->setIngredients($request->request->get('ingredients'));
        $dbaretchef->setApportsNutritifs($request->request->get('apports_nutritifs'));

        $file = $request->files->get('photo');
        if ($file) {
            $fileName = $this->uploadFile($file, $slugger, $this->getParameter('photos_directory'));
            $dbaretchef->setPhoto($fileName);
        }

        $file = $request->files->get('video');
        if ($file) {
            $fileName = $this->uploadFile($file, $slugger, $this->getParameter('uploads_directory'));
            $dbaretchef->setVideo($fileName);
        }
        $entityManager->flush();
        $photoFileName = $dbaretchef->getPhoto();
        $videoFileName = $dbaretchef->getVideo();
        $videoUrl = !empty($videoFileName) ? $this->generateUrl('get_uploaded_video', ['fileName' => $videoFileName]) : null;
        $photoUrl = !empty($photoFileName) ? $this->generateUrl('get_uploaded_image', ['fileName' => $photoFileName]) : null;
        return $this->json([
            'id' => $dbaretchef->getId(),
            'type' => $dbaretchef->getType(),
            'nom' => $dbaretchef->getNom(),
            'description' => $dbaretchef->getDescription(),
            'temps_preparation' => $dbaretchef->getTempsPreparation(),
            'nombre_ingredient' => $dbaretchef->getNombreIngredient(),
            'niv_difficulte' => $dbaretchef->getNivDifficulte(),
            'ingredients' => $dbaretchef->getIngredients(),
            'apports_nutritifs' => $dbaretchef->getApportsNutritifs(),
            'photo' => $photoUrl,
            'video' => $videoUrl,
        ]);
    }
    #[Route('/deletedbaretchef/{id}', name: 'deletedbaretchef',methods: ["DELETE"])]
    public function deletedbaretchef(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dbaretchef = $entityManager->getRepository(Dbaretelchef::class)->find($id);
        if (!$dbaretchef) {
            return $this->json('No Dbaret chef found for id' . $id, 404);
        }
        $entityManager->remove($dbaretchef);
        $entityManager->flush();

        return $this->json('Deleted a Dbaret chef successfully with id ' . $id);
    }
}