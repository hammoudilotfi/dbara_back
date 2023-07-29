<?php

namespace App\Controller;

use ApiPlatform\Validator\ValidatorInterface;
use App\Entity\Dbaretchefback;
use App\Form\DbaretchefbackType;
use App\Repository\SubcategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\FormError;

#[Route('/api', name: 'api_')]
class DbaretchefController extends AbstractController
{
    private $SubcategoryRepository;
    private $slugger;
    public function __construct(SubcategoryRepository $SubcategoryRepository, SluggerInterface $slugger)
    {
        $this->SubcategoryRepository = $SubcategoryRepository;
        $this->slugger = $slugger;
    }
  /*  #[Route('/recettes/chef', name: 'dbaretchef_add', methods: ["POST"])]
    public function createDbaretchef(Request $request, ValidatorInterface $validator): Response
    {
        $dbaretchef = new Dbaretchefback();
        $form = $this->createForm(DbaretchefbackType::class, $dbaretchef);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            /*$uploadedFile = $form->get('photoFile')->getData(); // Update 'photo' to 'photoFile'
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/photos';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move($destination, $newFilename);
            // Set the photo property with the new file name
            $dbaretchef->setPhoto($newFilename); // Use setPhoto() instead of setPhotoFile()
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dbaretchef);
            $entityManager->flush();
            return $this->json([
                'success' => true,
                'message' => 'Data added to the database successfully.',
                'data' => [
                    'id' => $dbaretchef->getId(), // Assuming you have an 'id' property in the Dbaretchefback entity
                    // Include any other relevant data you want to return in the response
                ],
            ]);
        }
// If the form is not valid, return an error JSON response
        $errors = $this->getFormErrors($form);
        return $this->json([
            'success' => false,
            'message' => 'Meesage derrors',
            'errors' => $errors,
        ]);
    }
    private function getFormErrors($form): array
    {
        $errors = [];
        foreach ($form->getErrors(true, true) as $error) {
            if ($error instanceof FormError) {
                // Get the name of the form field that caused the error
                $fieldName = $error->getOrigin()->getName();
                // Get the error message for the field
                $errorMessage = $error->getMessage();
                $errors[$fieldName][] = $errorMessage;
            }
        }
        return $errors;
    }*/
    #[Route('/recettes/chef', name: 'dbaretchef_add', methods: ["POST"])]
    public function createDbaretchef(Request $request, ValidatorInterface $validator): Response
    {
        $dbaretchef = new Dbaretchefback();
        $form = $this->createForm(DbaretchefbackType::class, $dbaretchef);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form->get('photoFile')->getData();
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/photos';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move($destination, $newFilename);

            $dbaretchef = $form->getData();
            $dbaretchef->setPhoto($newFilename);
            $em = $this->getDoctrine()->getManager();
            $em->persist($dbaretchef);
            $em->flush();
            return $this->json([
                'success' => true,
                'message' => 'Data added to the database successfully.',
                'data' => [
                    'id' => $dbaretchef->getId(), // Assuming you have an 'id' property in the Dbaretchefback entity
                    // Include any other relevant data you want to return in the response
                ],
            ]);
        }
// If the form is not valid, return an error JSON response
        $errors = $this->getFormErrors($form);
        return $this->json([
            'success' => false,
            'message' => 'Meesage derrors',
            'errors' => $errors,
        ]);
    }
    private function getFormErrors($form): array
    {
        $errors = [];
        foreach ($form->getErrors(true, true) as $error) {
            if ($error instanceof FormError) {
                // Get the name of the form field that caused the error
                $fieldName = $error->getOrigin()->getName();
                // Get the error message for the field
                $errorMessage = $error->getMessage();
                $errors[$fieldName][] = $errorMessage;
            }
        }
        return $errors;
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
    #[Route('/showalldbaretchef', name: 'dbaretchef_show_all',methods: ["GET"])]
    public function showalldbaretchef():Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Dbaretchefback::class)
            ->findAll();
        $data = [];
        foreach ($products as $product) {
            $photoFileName = $product->getPhoto();
            $photoUrl = $this->generateUrl('get_uploaded_image', ['fileName' => $photoFileName]);
            $data[] = [
                'id' => $product->getId(),
                'type'=>$product->getType(),
                'nom' => $product->getNom(),
                'description' => $product->getDescription(),
                'temps_prepartion'=>$product->getTempsPreparation(),
                'niv_difficulte'=>$product->getNivDifficulte(),
                'nombre_ingredient'=>$product->getNombreIngredient(),
                'photo' => $photoUrl,
                'video'=>$product->getVideo(),
            ];
        }
        return $this->json($data);

    }
    #[Route('/getdbaretchef/{id}', name: 'dbaretchef_show',methods: ["GET"])]
    public function getdbaretcheff(int $id): Response
    {
        // dd($id);
        $dbaretchef = $this->getDoctrine()->getRepository(Dbaretchefback::class)->find($id);
        if (!$dbaretchef) {

            return $this->json('No Dbaretchef found for id' . $id, 404);
        }
        $data[] = [
            'id' => $dbaretchef->getId(),
            'type'=>$dbaretchef->getType(),
            'nom' => $dbaretchef->getNom(),
            'description' => $dbaretchef->getDescription(),
            'temps_prepartion'=>$dbaretchef->getTempsPreparation(),
            'niv_difficulte'=>$dbaretchef->getNivDifficulte(),
            'nombre_ingredient'=>$dbaretchef->getNombreIngredient(),
            'photo'=>$dbaretchef->getPhoto(),
            'video'=>$dbaretchef->getVideo(),
        ];
        return $this->json($data);
    }
    #[Route('/updatedbaretchef/{id}', name: 'dbaretchef_update',methods: ["PUT"])]
    public function updatedbaretchef(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dbaretchef = $entityManager->getRepository(Dbaretchefback::class)->find($id);
        if (!$dbaretchef) {
            return $this->json('No Dbaret chef found for id' . $id, 404);
        }
        $dbaretchef->setType($request->request->get('type'));
        $dbaretchef->setNom($request->request->get('nom'));
        $dbaretchef->setDescription($request->request->get('description'));
        $dbaretchef->setTempsPreparation($request->request->get('temps_preparation'));
        $dbaretchef->setNivDifficulte($request->request->get('niv_difficulte'));
        $dbaretchef->setNombreIngredient($request->request->get('nombre_ingredient'));
        $dbaretchef->setPhoto($request->request->get('photo'));
        $dbaretchef->setVideo($request->request->get('video'));
        $entityManager->flush();
        $data[] = [
            'id' => $dbaretchef->getId(),
            'type'=>$dbaretchef->getType(),
            'nom' => $dbaretchef->getNom(),
            'description' => $dbaretchef->getDescription(),
            'temps_prepartion'=>$dbaretchef->getTempsPreparation(),
            'niv_difficulte'=>$dbaretchef->getNivDifficulte(),
            'nombre_ingredient'=>$dbaretchef->getNombreIngredient(),
            'photo'=>$dbaretchef->getPhoto(),
            'video'=>$dbaretchef->getVideo(),
        ];
        return $this->json($data);
    }
    #[Route('/removedbaretchef/{id}', name: 'dbaretchef_delete',methods: ["DELETE"])]
    public function removedbaretchef(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dbaretchef = $entityManager->getRepository(Dbaretchefback::class)->find($id);
        if (!$dbaretchef) {
            return $this->json('No Dbaret chef found for id' . $id, 404);
        }
        $entityManager->remove($dbaretchef);
        $entityManager->flush();

        return $this->json('Deleted a Dbaret chef successfully with id ' . $id);
    }
}
