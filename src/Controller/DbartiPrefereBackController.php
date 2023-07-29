<?php

namespace App\Controller;

use App\Entity\Dbaretchefback;
use App\Entity\Dbaretelchef;
use App\Entity\DbartElPrefereBack;
use App\Entity\DbartiElPrefere;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/api', name: 'api_')]
class DbartiPrefereBackController extends AbstractController
{
    private $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    #[Route('/dbaretchefback/{id}/ajout-to-preferred', name: 'ajout_to_preferred',methods: ["POST"])]
    public function addToPreferredback(Request $request, $id,SluggerInterface $slugger): Response
    {
        $dbaretchef = $this->getDoctrine()->getRepository(Dbaretchefback::class)->find($id);
        if (!$dbaretchef) {

            return $this->json('No Dbaretchef found for id' . $id, 404);
        }
        $preferredRecipe = new DbartElPrefereBack();
        $preferredRecipe->setRecipe($dbaretchef);
        $preferredRecipe->setType($dbaretchef->getType('type'));
        $preferredRecipe->setNom($dbaretchef->getNom('nom'));
        $preferredRecipe->setDescription($dbaretchef->getDescription('description'));;
        $preferredRecipe->setTempsPreparation($dbaretchef->getTempsPreparation('temps_preparation'));
        $preferredRecipe->setNombreIngredient($dbaretchef->getNombreIngredient('nombre_ingredient'));
        $preferredRecipe->setNivDifficulte($dbaretchef->getNivDifficulte('niv_difficulte'));
        $preferredRecipe->setIngredients($dbaretchef->getIngredients('ingredients'));
        $preferredRecipe->setApportsNutritifs($dbaretchef->getApportsNutritifs('apports_nutritifs'));
        $file = $request->files->get('photo');
        if ($file) {
            $fileName = $this->uploadFile($file, $slugger);
            $preferredRecipe->setPhoto($fileName);
        }
        $file = $request->files->get('video');
        if ($file) {
            $fileName = $this->uploadFile($file, $slugger);
            $preferredRecipe->setVideo($fileName);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($preferredRecipe);
        $entityManager->flush();
        return $this->json(['message' => 'Recipe added to preferred list']);
    }
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
    #[Route('/dbaretchefback/{id}/delete-preferred', name: 'deletedbaraprefere',methods: ["DELETE"])]
    public function deletedbaraprefere(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $preferredRecipe = $entityManager->getRepository(DbartElPrefereBack::class)->find($id);
        if (!$preferredRecipe) {
            return $this->json('No Dbara Prefere found for id' . $id, 404);
        }
        $entityManager->remove($preferredRecipe);
        $entityManager->flush();

        return $this->json('Deleted a Dbara Prefere successfully with id ' . $id);
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
    #[Route('/dbaretchefback/get-preferred', name: 'getdbaraprefereback',methods: ["GET"])]
    public function getdbaraprefereback():Response
    {
        $products = $this->getDoctrine()
            ->getRepository(DbartElPrefereBack::class)
            ->findAll();
        $data = [];
        foreach ($products as $product) {
            $photoFileName = $product->getPhoto();
            $photoUrl = null;
            if (!empty($photoFileName)) {
                $photoUrl = $this->generateUrl('get_uploaded_image', ['fileName' => $photoFileName]);
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
                'video'=>$product->getVideo(),
            ];
        }
        return $this->json($data);

    }
}
