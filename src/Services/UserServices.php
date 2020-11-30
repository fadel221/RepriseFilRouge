<?php 

namespace App\Services;


use App\Repository\UserRepository;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserServices
{

    public function getPathEntity($profil)
    {
        switch ($profil->getLibelle())
        {
            case "ADMIN": return "App\Entity\User";
            case "FORMATEUR": return "App\Entity\Formateur";
            case "APPRENANT": return "App\Entity\Apprenant";
            case "CM": return "App\Entity\CM";
        }
    }

    public function addUser(Request $request,UserPasswordEncoderInterface $encoder,SerializerInterface $serializer,ValidatorInterface $validator,ProfilRepository $profil,EntityManagerInterface $manager)
    {
        $user = $request->request->all();
        $avatar = $request->files->get("avatar");
        $avatar = fopen($avatar->getRealPath(),"rb");
        $user["avatar"] = $avatar;
        $profil = $profil->find($user["profil_id"]);
        //Service qui determine le chemin de la classe à instancier 
        $userservice=new UserServices();
        $path=$userservice->getPathEntity($profil);
        $user = $serializer->denormalize($user,$path);
        $errors= $validator->validate($user);
        if (count($errors))
        {
            $errors = $serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $user->setProfil($profil);
        $user->setisDeleted(false);
        $password = $user->getPassword();
        $user->setPassword($encoder->encodePassword($user,$password));
        $manager->persist($user);
        $manager->flush();
        fclose($avatar);
        return $user;
    }

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
        $user->setLastUpdate(new \DateTime());
        $manager->persist($user);
        $manager->flush();
        fclose($avatar);
        return $user;
    }
}