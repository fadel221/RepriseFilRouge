<?php

namespace App\Controller;

use App\Entity\Referentiel;
use App\Entity\Groupecompetence;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReferentielRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupecompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReferentielController extends AbstractController
{
    /**
     * @Route(
     *     path="/api/admin/referentiels",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\ReferentielController::addReferentiel",
     *          "__api_resource_class"=Referentiel::class,
     *          "__api_collection_operation_name"="add_referentiel"
     *     }
     * )
    */
    public function addReferentiel(Request $request,SerializerInterface $serializer,ValidatorInterface $validator,EntityManagerInterface $manager,GroupecompetenceRepository $grp)
    {
        $Referentiel_json = $request -> getContent();
        $Referentiel_tab = $serializer -> decode($Referentiel_json,"json");
        $Referentiel = new Referentiel();
        $Referentiel -> setLibelle($Referentiel_tab['libelle']);
        $Referentiel -> setPresentation($Referentiel_tab['presentation']);
        $Referentiel -> setProgramme($Referentiel_tab['programme']);
        $Referentiel -> setCritereAdmission($Referentiel_tab['critereAdmission']);
        $Referentiel -> setCritereEvaluation($Referentiel_tab['critereEvaluation']);
        
        $Groupecompetence_tab = $Referentiel_tab['groupecompetences'];
        foreach ($Groupecompetence_tab as $key => $value) {
            if (isset ($value['id'])) 
            {

                if ($grp->find($value['id'])!=null)
                {
                    $groupecompetence=$grp->find($value['id']);
                    $Referentiel -> addGroupecompetence($groupecompetence);
                    $user = $this->getUser();
                    $groupecompetence-> setUser($user);
                }
                else
                {
                    return $this -> json(["message" =>"Impossibe d'ajouter un groupe de compétences non existant"],Response::HTTP_BAD_REQUEST);
                }
            }
    
        }
        //dd($Referentiel);
        $errors = $validator->validate($Referentiel);
        if (count($errors)){
            $errors = $serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $manager->persist($Referentiel);
        $manager->flush();
        return $this->json($Referentiel,Response::HTTP_CREATED);
    }

     /**
     * @Route(
     *     path="/api/admin/referentiels/{id}",
     *     methods={"PUT","PATCH"},
     *     defaults={
     *          "__controller"="App\Controller\ReferentielController::updateReferentiel",
     *          "__api_resource_class"=Referentiel::class,
     *          "__api_collection_operation_name"="update_referentiel"
     *     }
     * )
    */
    public function updateReferentiel(Request $request,SerializerInterface $serializer,ValidatorInterface $validator,EntityManagerInterface $manager, $id, GroupecompetenceRepository $cmp, ReferentielRepository $grpcmp)
    {
        $Referentiel_json = $request -> getContent();
        $Referentiel_tab = $serializer -> decode($Referentiel_json,"json");
        $Referentiel = new Referentiel();
        if (!($Referentiel = $grpcmp -> find($id))) {
            return $this ->json(null, Response::HTTP_NOT_FOUND,);
        }
        if (isset($Referentiel_tab['libelle'])) {
            $Referentiel -> setLibelle($Referentiel_tab['libelle']);
        }
        if (isset($Referentiel_tab['presentation'])) {
            $Referentiel -> setPresentation($Referentiel_tab['presentation']);
        }
        if (isset($Referentiel_tab['programme'])) {
            $Referentiel -> setProgramme($Referentiel_tab['programme']);
        }
        if (isset($Referentiel_tab['critereAdmission'])) {
            $Referentiel -> setCritereAdmission($Referentiel_tab['critereAdmission']);
        }
        if (isset($Referentiel_tab['critereEvaluation'])) {
            $Referentiel -> setcritereEvaluation($Referentiel_tab['critereEvaluation']);
        }
        $Groupecompetence_tab = isset($Referentiel_tab['groupecompetence'])?$Referentiel_tab['groupecompetence']:[];
        if (!empty($Groupecompetence_tab)) {
            foreach ($Groupecompetence_tab as $key => $value) {
                $groupecompetences = new Groupecompetence();
                if (isset($value['id'])) 
                {
                    if (!($groupecompetences =  $cmp -> find($value['id']))) {
                        return $this ->json(null, Response::HTTP_NOT_FOUND,);
                    }
                    if (isset($value['libelle'])) {
                        $groupecompetences -> setLibelle($value['libelle']);
                    }
                    if (isset($value['descriptif'])) {
                        $groupecompetences -> setDescriptif($value['descriptif']);
                    }
                    else {
                        $Referentiel -> removeGroupecompetence($groupecompetences);
                    }
                   
                }
                else{
                if(isset($value['libelle'])) {
                   $groupecompetences -> setLibelle($value['libelle']);
                   
                   $Referentiel -> addGroupecompetence($groupecompetences);
                }
                if(isset($value['descriptif'])){
                    $groupecompetences -> setDescriptif($value['descriptif']);
                    $Referentiel -> addGroupecompetence($groupecompetences);
                }
                $user = $this->get('security.token_storage')->getToken()->getUser();
                $groupecompetences -> setUser($user);
                   }
            }
        }

        $errors = $validator->validate($Referentiel);
        if (count($errors)){
            $errors = $serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        
        $manager->persist($Referentiel);
        $manager->flush();
        return $this->json($Referentiel,Response::HTTP_CREATED);
    }

    /**
     * @Route(
     *     path="/api/admin/referentiels/groupecompetences",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\ReferentielController::showReferentiel",
     *          "__api_resource_class"=Referentiel::class,
     *          "__api_collection_operation_name"="show_referentiel_groupecompetence"
     *     }
     * )
    */
    public function showReferentiel(ReferentielRepository $Referentiel){
        $Referentiel = $Referentiel -> findAll();
        return $this -> json($Referentiel, Response::HTTP_OK,);
    }
    

    /**
     * @Route(
     *     path="/api/admin/referentiels/{idr}groupecompetences/{idg}",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\ReferentielController::showReferentielwithGroupecompetence",
     *          "__api_resource_class"=Referentiel::class,
     *          "__api_collection_operation_name"="show_referentiel_groupecompetence_competence"
     *     }
     * )
    */
    public function ShowReferentielwithGroupecompetence(ReferentielRepository $repref,GroupecompetenceRepository $grpref,$idr,$idg)
    {
        if ($Referentiel=$repref->find($idr))
        {
            if ($Groupecompetence=$grpref->find($idg))
            {
                foreach ($Groupecompetence->getReferentiels() as $ref)
                {
                    if ($ref===$Referentiel)
                    {
                        return $this -> json($Groupecompetence, Response::HTTP_OK,);
                    }
                }
            }
        }
        return $this -> json("Ce référentiel n'appartient pas à ce groupe de compétences", Response::HTTP_BAD_REQUEST,);
    }
}
