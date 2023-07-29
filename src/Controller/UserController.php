<?php

namespace App\Controller;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use function PHPUnit\Framework\throwException;
use \Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/api', name: 'api_')]
class UserController extends AbstractController
{
    private ManagerRegistry $managerRegistry;
    private JWTTokenManagerInterface $jwtManager;
    private TokenStorageInterface $tokenStorageInterface;
    public function __construct(ManagerRegistry $managerRegistry,JWTTokenManagerInterface $jwtManager,TokenStorageInterface $tokenStorageInterface )
    {
        $this->managerRegistry = $managerRegistry;
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
    }

    /*#[Route('/users', name: 'users_show_all',methods: "GET")]
    public function showAllUsers()
    {
        try {
            $users = $this->managerRegistry->getRepository(User::class)->findAll();
            return $users;
        } catch (Exception $exception) {
            $view = $this->View($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->handleView($view);

        }
    }
    #[Route('/users/{id}', name: 'user_id_show',methods: "GET")]
    public function showUser(User $user)
    {
        try {
            return $user;
        } catch (Exception $exception) {
            $view = $this->view($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->handleView($view);
        }
    }
    #[Route('/users/email/{email}', name: 'user_show',methods: "GET")]
    public function showCurrentUser(User $user)
    {
        try {
            return $user;
        } catch (Exception $exception) {
            $view = $this->view($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->handleView($view);
        }
    }
    #[Route('/users/{id}', name: 'user_delete',methods: "DELETE")]
    public function deletUser(User $user)
    {
        try {
            $entity_manager = $this->managerRegistry->getManager();
            $entity_manager->remove($user);
            $entity_manager->flush();
            $view = $this->view('User deleted with success');
        } catch (Exception $exception) {
            $view = $this->view($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->handleView($view);
    }
    #[Route('/addusers', name: 'user_add',methods: "POST")]
    public function createUser(User $user, UserPasswordHasherInterface $passwordHasher, Request $request)
    {
        try {
            $params = json_decode($request->getContent());
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $params->plainPassword);
            $user->setPassword($hashedPassword);
            $user->setPin($params->pin);
            $user->setRoles(["ROLE_USER"]);
            $user->setEmail($params->email);
            $user->setNom($params->nom);
            $user->setPrenom($params->prenom);
            $user->setSexe($params->sexe);
            $this->managerRegistry->getRepository(User::class)->add($user,true);
            return $this->view(
                $user,
                Response::HTTP_CREATED,
                [
                    'Location' => $this->generateUrl('user_id_show', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
                ],
            );
        } catch (Exception $exception) {
            $view = $this->view($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->handleView($view);
        }
    }
    #[Route('/updateuser', name: 'user_update',methods: "PUT")]
    public function updateUser(User $user, UserPasswordHasherInterface $passwordHasher, Request $request)
    {
        try {
            $params = json_decode($request->getContent());
            if($this->isGranted("ROLE_ADMIN"))
            {
                if(strtolower($params->role) === "admin" )
                {
                    $user->setRoles(["ROLE_ADMIN"]);
                }  else {
                    $user->setRoles(["ROLE_USER"]);
                }
                $entity_manager = $this->managerRegistry->getManager();
                $entity_manager->flush();
                $view = $this->view('User role updated with success');
                return $this->handleView($view);
            } elseif ($this->isGranted("ROLE_USER"))
            {
                $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());
                if($decodedJwtToken["username"] !== $user->getEmail())
                {
                    throw new Exception("You're not authorized to access this data.", 403);
                } else {
                    if(isset($params->plainPassword))
                    {
                        $hashedPassword = $passwordHasher->hashPassword(
                            $user,
                            $params->plainPassword);
                        $user->setPassword($hashedPassword);
                    }
                    foreach ($params as $key => $value){
                        $setterName = 'set'.ucfirst($key);
                        $user->$setterName($value);
                    }
                    $user->setRoles(["ROLE_USER"]);
                    $entity_manager = $this->managerRegistry->getManager();
                    $entity_manager->flush();
                    $view = $this->view('User updated with success');
                    return $this->handleView($view);
                }
            } else
            {
                throw new Exception("You're not authorized to access this data.", 403);
            }
        } catch (\Exception $exception)
        {
            $view = $this->view($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->handleView($view);
        }
    }
    #[Route('/logout', name: 'app_logout',methods: "PUT")]
    public function logout()
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }*/

}
