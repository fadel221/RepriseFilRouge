<?php

namespace App\Controller;

use App\Entity\Referentiel;
use App\Entity\Groupecompetence;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReferentielRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupecompetenceRepository;
use App\Services\FileUpload;
use Doctrine\Common\DataFixtures\ReferenceRepository;
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
     *          "__api_collection_operation_name"="referentiel_groupecompetence_competence"
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
                    if ($ref==$Referentiel)
                    { 
                        return $this -> json($Referentiel, Response::HTTP_OK,);
                    }
                }
            }
        }
        return $this -> json("Ce référentiel n'appartient pas à ce groupe de compétences", Response::HTTP_BAD_REQUEST,);
    }


    /**
     * @Route(
     *     path="/api/admin/referentiels",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\ReferentielController::AddReferentiel",
     *          "__api_resource_class"=Referentiel::class,
     *          "__api_collection_operation_name"="add_referentiel"
     *     }
     * )
    */

    public function addReferentiel(Request $request,SerializerInterface $serializer,GroupecompetenceRepository $grp,FileUpload $upload,ValidatorInterface $validator,EntityManagerInterface $manager)
    {
        $Referentiel_tab = $request->request->all();
        $presentation =$upload->UploadFile("presentation",$request);
        $Referentiel_tab["presentation"]=$presentation;
        $groupecompetence_tab=$Referentiel_tab["groupecompetence_array"];
        unset($Referentiel_tab["groupecompetence_array"]);
        $Referentiel=$serializer->denormalize($Referentiel_tab,"App\Entity\Referentiel");
        foreach ($groupecompetence_tab as $groupecompetence)
        {
            if ($grp->find($groupecompetence)!=null)
            {
                $Referentiel->addGroupecompetence($grp->find($groupecompetence));
            }
        }

        $errors = $validator->validate($Referentiel);
        if (count($errors))
        {
            $errors = $serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }

        $manager->persist($Referentiel);
        $manager->flush();
        fclose($presentation);
        return $this->json($Referentiel,Response::HTTP_CREATED);
    }
        
        
    

    /**
     * @Route(
     *     path="/api/admin/referentiels/{id}",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\ReferentielController::updateReferentiel",
     *          "__api_resource_class"=Referentiel::class,
     *          "__api_collection_operation_name"="update_referentiel"
     *     }
     * )
    */

    public function updateReferentiel (Request $request,SerializerInterface $serializer,GroupecompetenceRepository $grp,FileUpload $upload,ValidatorInterface $validator,EntityManagerInterface $manager,$id,ReferentielRepository $repref) 
    {
        $Referentiel=$repref->find($id);
        $Referentiel_tab = $request->request->all();
        if (isset($Referentiel_tab['libelle'])) {
            $Referentiel -> setLibelle($Referentiel_tab['libelle']);
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
        if ($presentation =$upload->UploadFile("presentation",$request))
        {
            $Referentiel_tab["presentation"]=$presentation;
        }  
        $groupecompetence_tab=$Referentiel_tab["groupecompetence_array"];
        foreach ($groupecompetence_tab as $groupecompetence)
        {
            if ($Groupecompetence=$grp->find($groupecompetence))
            {
                $action=false;
                foreach($Referentiel->getGroupecompetences() as $ref_groupe)
                {
                    if ($Groupecompetence == $ref_groupe)
                    {
                        $Referentiel->removeGroupecompetence($Groupecompetence);
                        $action=true;   
                    }
                }
                if (!($action))
                {
                    $Referentiel->addGroupecompetence($Groupecompetence);
                }
            }
        }
        $errors = $validator->validate($Referentiel);
        if (count($errors))
        {
            $errors = $serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $manager->flush();
        return $this->json($Referentiel,Response::HTTP_OK);
    }
        
    }

