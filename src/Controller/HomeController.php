<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[Route('/api', name: 'api_')]
class HomeController extends AbstractController
{
    #[Route('/register', name: 'app_register',methods: ["POST"])]
    public function index(Request $request,UserPasswordEncoderInterface $encoder): Response
    {
        $em = $this->getDoctrine()->getManager();
        $decoded = json_decode($request->getContent());

        $email = $decoded->email;
        $password = $decoded->password;
        $tel = $decoded->tel;
        $pin = $decoded->pin;
        $nom = $decoded->nom;
        $prenom = $decoded->prenom;
        $sexe = $decoded->sexe;
        $user = new User();
        $user->setPassword($encoder->encodePassword($user, $password));
        $user->setEmail($email);
        $user->setTel($tel);
        $user->setPin($pin);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setSexe($sexe);
        $em->persist($user);
        $em->flush();
        return $this->json(['message' => 'Registered Successfully']);
    }

   #[Route('/login_check', name: 'login',methods: ["POST"])]
    public function testLogin(){

        $user = static::createClient();
        $user->request('POST', '/api/login_check', [], [],
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
            json_encode([
                'username' => 'user@gmail.com',
                'password' => 'password123'
            ])
        );

        $this->assertEquals(200, $user->getResponse()->getStatusCode());

    }

    #[Route('/getusers', name: 'users_show_all',methods: ["GET"])]
    public function showAllUsers(): Response
    {
        $products = $this->getDoctrine()->getRepository(User::class)->findAll();
        $data =[];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'nom' => $product->getNom(),
                'prenom' => $product->getPrenom(),
                'tel'=>$product->getTel(),
                'pin'=>$product->getPin(),
                'sexe'=>$product->getSexe(),
                'email'=>$product->getEmail(),
            ];
        }
        return $this->json($data);

    }
    #[Route('/showuser/{id}', name: 'user_id_show',methods: ["GET"])]
    public function showabonnes(int $id): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->find($id);
        if (!$users) {

            return $this->json('No User found for id' . $id, 404);
        }
        $data[] = [
            'id' => $users->getId(),
            'nom' => $users->getNom(),
            'prenom' => $users->getPrenom(),
            'tel'=>$users->getTel(),
            'pin'=>$users->getPin(),
            'sexe'=>$users->getSexe(),
            'email'=>$users->getEmail(),
        ];
        return $this->json($data);
    }
    #[Route('/updateuser/{id}', name: 'user_update',methods: ["PUT"])]
    public function updateUser(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            return $this->json('No User found for id' . $id, 404);
        }
        $user->setPassword($request->request->get('password'));
        $user->setEmail($request->request->get('email'));
        $user->setTel($request->request->get('tel'));
        $user->setPin($request->request->get('pin'));
        $user->setNom($request->request->get('nom'));
        $user->setPrenom($request->request->get('prenom'));
        $user->setSexe($request->request->get('sexe'));
        $entityManager->flush();
        $data[] = [
            'id' => $user->getId(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'tel'=>$user->getTel(),
            'pin'=>$user->getPin(),
            'sexe'=>$user->getSexe(),
            'email'=>$user->getEmail(),
        ];
        return $this->json($data);
    }
    
}
