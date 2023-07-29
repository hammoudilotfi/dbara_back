<?php

namespace App\Controller;

use App\Entity\Jeuxfront;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/api', name: 'api_')]
class JeuxFrontController extends AbstractController
{
    private $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
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
    #[Route('/ajoutjeuxfront', name: 'jeux_add',methods: ["POST"])]
    public function createJeux(Request $request ,EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $jeuxfront = new Jeuxfront();
        $jeuxfront->setName($request->request->get('name'));
        $jeuxfront->setVote($request->request->get('vote'));
        $file = $request->files->get('photo');
        if ($file) {
            $fileName = $this->uploadFile($file, $slugger);
            $jeuxfront->setPhoto($fileName);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($jeuxfront);
        $entityManager->flush();
        return $this->json('Created new Jeux Front successfully with id ' . $jeuxfront->getId());
    }
    #[Route('/getjeuxFront', name: 'getjeuxfront', methods: ["GET"])]
    public function getjeux(): Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Jeuxfront::class)
            ->findAll();
        $data = [];
        foreach ($products as $product) {
            $photoFileName = $product->getPhoto();
            $photoUrl = $this->generateUrl('get_uploaded_image', ['fileName' => $photoFileName]);
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'vote' => $product->getVote(),
                'photo' => $photoUrl,
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

        // Return the file as a response
        $fileContent = file_get_contents($filePath);
        $response = new Response($fileContent, 200);
        $response->headers->set('Content-Type', 'image/jpeg'); // Adjust the content type based on the file type

        return $response;
    }

    #[Route('/showjeuxfront/{id}', name: 'showjeuxfront',methods: ["GET"])]
    public function showJeuxfront(int $id): Response
    {
        $jeuxfront = $this->getDoctrine()->getRepository(Jeuxfront::class)->find($id);
        if (!$jeuxfront) {

            return $this->json('No jeux Front found for id' . $id, 404);
        }
        $photoFileName = $jeuxfront->getPhoto();
        $photoUrl = $this->generateUrl('get_uploaded_image', ['fileName' => $photoFileName]);
        $data[] = [
            'id' => $jeuxfront->getId(),
            'name' => $jeuxfront->getName(),
            'vote' => $jeuxfront->getVote(),
            'photo' => $photoUrl,
        ];
        return $this->json($data);
    }
}
