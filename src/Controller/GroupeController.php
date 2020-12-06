<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Repository\GroupeRepository;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Apprenant;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeController extends AbstractController
{
    /**
     * @Route(
     *     path="/api/admin/groupes/{idg}/apprenants/{ida}",
     *     methods={"DELETE"},
     *     defaults={
     *          "__controller"="App\Controller\Controller::DeleteApprenant",
     *          "__api_resource_class"=Groupe::class,
     *          "__api_collection_operation_name"="delete_apprenant"
     *     }
     * )
    */

   public function DeleteApprenant (Request $request,SerializerInterface $serializer,ValidatorInterface $validator,EntityManagerInterface $manager,ApprenantRepository $repapp,GroupeRepository $grprep,$ida,$idg)
{
    if($Groupe=$grprep->find($idg))
    {
        if ($apprenant=$repapp->find($ida))
        {
            $Groupe->removeApprenant($apprenant);
        }       
       $manager->persist($Groupe);
       $manager->flush();
       return $this->json($Groupe,Response::HTTP_CREATED);
    }
}
}

