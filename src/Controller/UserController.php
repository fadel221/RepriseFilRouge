<?php

namespace App\Controller;


use App\Entity\Profil;
use App\Repository\UserRepository;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
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
     *          "__api_collection_operation_name"="add_user"
     *     }
     * )
    */
    public function addUser(Request $request,UserPasswordEncoderInterface $encoder,SerializerInterface $serializer,ValidatorInterface $validator,ProfilRepository $profil,EntityManagerInterface $manager)
    {
        $user = $request->request->all();
        $avatar = $request->files->get("avatar");
        $avatar = fopen($avatar->getRealPath(),"rb");
        $user["avatar"] = $avatar;
        $user = $serializer->denormalize($user,"App\Entity\User");
        $errors = $validator->validate($user);
        /*if (count($errors)){
            $errors = $serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }*/
        $profil = $profil->find(1);
        $profil->setLibelle("ADMIN");
        $profil->setIsDeleted(false);
        $user -> setProfil($profil);
        $user->setisDeleted(false);
        $password = $user->getPassword();
        $user->setPassword($encoder->encodePassword($user,$password));
        $manager->persist($user);
        $manager->flush();
        fclose($avatar);
        return $this->json($user,Response::HTTP_CREATED);
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
    public function UpdateUser(Request $request,UserPasswordEncoderInterface $encoder,SerializerInterface $serializer,ValidatorInterface $validator,UserRepository $userrep,EntityManagerInterface $manager,$id)
    {
        $data = $request->request->all();
        $user= $userrep->find($id);
        if ($request->files->get("avatar"))
        {
            $avatar = $request->files->get("avatar");
            $avatar = fopen($avatar->getRealPath(),"rb");
            $data["avatar"] = $avatar;
        }
        if (isset ($data['prenom']))
        {
            $user->setPrenom($data['prenom']);
        }
        if (isset ($data['nom']))
        {
            $user->setnom($data['nom']);
        }
        if (isset ($data['password']))
        {
            $user->setPassword($encoder->encodePassword($user,$data['password']));
        }
        if (isset ($data['email']))
        {
            $user->setEmail($data['email']);
        }
        if (isset ($data['username']))
        {
            $user->setUsername($data['username']);
        }
        $errors= $validator->validate($user);
        /*if (count($errors)){
            $errors = $serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }*/
        //dd($user);
        $manager->persist($user);
        $manager->flush();
        fclose($avatar);
        return $this->json($user,Response::HTTP_CREATED);
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
    public function DeleteUser(Request $request,SerializerInterface $serializer,ValidatorInterface $validator,UserRepository $userrep,EntityManagerInterface $manager,$id)
    {
        $user= $userrep->find($id);
        $user->setisDeleted(true);
        $manager->persist($user);
        $manager->flush();
        return $this->json($user,Response::HTTP_OK);
    }
    
}
