<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Entity\Groupecompetence;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupecompetenceRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class GroupecompetenceController extends AbstractController
{
    /**
     * @Route(
     *     path="/api/admin/groupecompetences",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\GroupecompetenceController::addGroupecompetence",
     *          "__api_resource_class"=Groupecompetence::class,
     *          "__api_collection_operation_name"="add_groupecompetence"
     *     }
     * )
    */
    public function addGroupecompetence(Request $request,SerializerInterface $serializer,ValidatorInterface $validator,EntityManagerInterface $manager,CompetenceRepository $repcomp,UserRepository $repuser)
    {
        $Groupecompetence_json = $request->getContent();
        $Groupecompetence_tab = $serializer->decode($Groupecompetence_json,"json");
        //dd($Groupecompetence_tab);
        $Groupecompetence = new Groupecompetence();
        $Groupecompetence -> setLibelle($Groupecompetence_tab['libelle']);
        $Groupecompetence -> setDescriptif($Groupecompetence_tab['descriptif']);
        $Groupecompetence -> setType($Groupecompetence_tab['type']);
        $Groupecompetence -> setNom($Groupecompetence_tab['nom']);
        $Competence_tab = $Groupecompetence_tab['competences'];
        foreach ($Competence_tab as $key => $value) {
            if (isset ($value['id']))
            {
                if($Competence=$repcomp->find($value['id']))
                {
                    $Groupecompetence->addCompetence($Competence);
                }
            }
            else 
                if (isset ($value['libelle']))
                {
                    $competence = new Competence();
                    $competence -> setLibelle($value['libelle']);
                    $Groupecompetence -> addCompetence($competence);
                    
                }
        }
        
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $Groupecompetence -> setUser($user);
        $errors = $validator->validate($Groupecompetence);
        if (count($errors))
        {
            $errors = $serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $manager->persist($Groupecompetence);
        $manager->flush();
        return $this->json($Groupecompetence,Response::HTTP_CREATED);
    }



    /**
     * @Route(
     *     path="/api/admin/groupecompetences/{id}",
     *     methods={"PUT","PATCH"},
     *     defaults={
     *          "__controller"="App\Controller\GroupecompetenceController::updateGroupecompetence",
     *          "__api_resource_class"=Groupecompetence::class,
     *          "__api_collection_operation_name"="update_groupecompetence"
     *     }
     * )
    */
    public function UpdateGroupecompetence(Request $request,SerializerInterface $serializer,ValidatorInterface $validator,EntityManagerInterface $manager,CompetenceRepository $repcomp,UserRepository $repuser,GroupecompetenceRepository $repgrpecompet,CompetenceRepository $repcompet,$id)
    {
        $Groupecompetence_json = $request->getContent();
        $Groupecompetence_tab = $serializer->decode($Groupecompetence_json,"json");
        if ($Groupecompetence = $repgrpecompet->find($id))
        {
            // Modification libelle de Groupecompetence
            if (isset($Groupecompetence_tab['libelle'])) 
            {
                $Groupecompetence->setLibelle($Groupecompetence_tab['libelle']);
            }
            $grpecompetence_tab = isset($Groupecompetence_tab['competences'])?$Groupecompetence_tab['competences']:[];
            if (!empty($grpecompetence_tab)) 
            {
                foreach ($grpecompetence_tab as $key => $value) {  
                    
                        if (isset ($value['id']))
                        { 
                            // Affectation d'une competence
                            if (isset ($value['action']) && ($value['action'])=="affecter")
                            {  
                                $Groupecompetence->addcompetence($repcompet->find($value['id']));
                            }
                            // Desaffectation d'une competence
                            else if (isset ($value['action']) && $value['action']=="desaffecter")
                            {  
                                $Groupecompetence->Removecompetence($repcompet->find($value['id']));
                            }
                        
                            // Modification attributs competence
                            else
                            {
                                $competence=$repcompet->find ($value['id']);
                                if (isset($value['libelle'])) {
                                    $competence -> setLibelle($value['libelle']);
                                }
                                
                            }
                        }
                            // Création d'une nouvelle competence
                        else 
                            if (isset($value['libelle']))
                        {
                            $competence=new Competence();
                            $competence->setLibelle($value['libelle']);
                            $Groupecompetence->addcompetence($competence);  
                        }
                    
                    }
        }
        $errors = $validator->validate($Groupecompetence);
        if (count($errors))
        {
            $errors = $serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $manager->persist($Groupecompetence);
        $manager->flush();
    }
        return $this->json($Groupecompetence,Response::HTTP_CREATED);
    }


    }
