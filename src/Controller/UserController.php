<?php

namespace App\Controller;


use App\Entity\Profil;
use App\Services\UserServices;
use App\Repository\UserRepository;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{    
     /**
     * @Route(
     *     path="/api/admin/users",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::addUser",
     *          "__api_resource_class"=User::class,
     *          "__api_collection_operation_name"="add_user",
     *          "normalization_context"={"groups"={"user_read","user_details_read"}}
     *     }
     * )
    */
    public function addUser(UserServices $userservice,Request $request,UserPasswordEncoderInterface $encoder,SerializerInterface $serializer,ValidatorInterface $validator,ProfilRepository $profil,EntityManagerInterface $manager)
    {
        $user=$userservice->addUser($request, $encoder, $serializer, $validator, $profil, $manager);
        
    }

    /**
     * @Route(
     *     path="/api/admin/users/{id}",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::UpdateUser",
     *          "__api_resource_class"=User::class,
     *          "__api_collection_operation_name"="update_user"
     *         }
     * )
    */
    public function UpdateUser(Request $request,UserPasswordEncoderInterface $encoder,SerializerInterface $serializer,ValidatorInterface $validator,UserRepository $userrep,EntityManagerInterface $manager,$id,UserServices $userservice)
    {
        $userservice->updateUser($request, $encoder, $serializer, $validator, $userrep,$manager,$id);
    }

    /**
     * @Route(
     *     path="/api/admin/users/{id}",
     *     methods={"DELETE"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::DeleteUser",
     *          "__api_resource_class"=User::class,
     *          "__api_collection_operation_name"="delete_user"
     *         }
     * )
    */
    public function DeleteUser(Request $request,UserPasswordEncoderInterface $encoder,SerializerInterface $serializer,ValidatorInterface $validator,UserRepository $userrep,EntityManagerInterface $manager,$id)
    {
        $data = $request->request->all();
        $user= $userrep->find($id);
        $user->setIsDeleted(true);
        $errors= $validator->validate($user);
        /*if (count($errors)){
            $errors = $serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }*/
        $user->setLastUpdate(new \DateTime());
        $manager->persist($user);
        $manager->flush(); 
        return $this->json($user,Response::HTTP_CREATED);
    }

    
    
}
