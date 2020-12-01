<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Tags;
use App\Entity\GroupeTags;
use App\Repository\TagRepository;
use App\Repository\GroupeTagsRepository;
use App\Repository\TagsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeTagsController extends AbstractController

{
    

    /**
     * @Route(
     *     path="/api/admin/groupetags",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\GroupeTagsController::addGroupeTags",
     *          "__api_resource_class"=GroupeTags::class,
     *          "__api_collection_operation_name"="add_groupeTags"
     *     }
     * )
    */
    public function addGroupeTags(Request $request,SerializerInterface $serializer,ValidatorInterface $validator,EntityManagerInterface $manager,TagsRepository $reptag)
    {
        $GroupeTags_json= $request->getContent();
        $GroupeTags_tab = $serializer->decode($GroupeTags_json,"json");
        $GroupeTags = new GroupeTags();
        $GroupeTags -> setLibelle($GroupeTags_tab['libelle']);
        foreach ($GroupeTags_tab['tags'] as $value) 
        {
            //Affectation Tag existant
            if (isset ($value['id']))
            {
                
                $GroupeTags->addTag($reptag->find($value['id']));
            }
            // Ajout nouveaux tags
            else
            {
                $tag = new Tags();
                $tag -> setLibelle($value['libelle']);
                $tag -> setDescriptif($value["descriptif"]);
                $tag->setIsDeleted(false);
                $GroupeTags -> addtag($tag);
            }
        }

        

        $errors = $validator->validate($GroupeTags);
        if (count($errors)){
            $errors = $serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        
        $manager->persist($GroupeTags);
        $manager->flush();
        return $this->json($GroupeTags,Response::HTTP_CREATED);
    }

    
    


    /**
     * @Route
     * (
     *     path="/api/admin/groupetags/{id}",
     *     methods={"PUT","PATCH"},
     *     defaults={
     *          "__controller"="App\Controller\GroupeTagsController::updateGroupeTags",
     *          "__api_resource_class"=GroupeTags::class,
     *          "__api_collection_operation_name"="update_GroupeTags"
     *     }
     * )
    */
    public function  updateGroupeTags(Request $request,SerializerInterface $serializer,ValidatorInterface $validator,EntityManagerInterface $manager, $id, GroupeTagsRepository $repgtag, TagsRepository $reptag)
    {
        $GroupeTags_json = $request->getContent();
        $GroupeTags_tab = $serializer->decode($GroupeTags_json,"json");
        if ($GroupeTags = $repgtag -> find($id))
        {
            // Modification libelle de GroupeTag
            if (isset($GroupeTags_tab['libelle'])) 
            {
                $GroupeTags->setLibelle($GroupeTags_tab['libelle']);
            }
            $tag_tab = isset($GroupeTags_tab['tags'])?$GroupeTags_tab['tags']:[];
            if (!empty($tag_tab)) 
            {
                foreach ($tag_tab as $key => $value) {
                        
                        if (isset ($value['id']))
                        {
                            // Affectation de tag
                            if (isset ($value['action']) && ($value['action'])=="affecter")
                            {
                                $GroupeTags->addTag($reptag->find($value['id']));
                            }
                            // Desaffectation de tag
                            else if (isset ($value['action']) && $value['action']=="desaffecter")
                            {
                                $GroupeTags->RemoveTag($reptag->find($value['id']));
                            }
                        
                            // Modification attributs tag
                            else
                            {
                                $tag=$reptag->find ($value['id']);
                                if (isset($value['libelle'])) {
                                    $tag -> setLibelle($value['libelle']);
                                }

                                if (isset($value['descriptif'])){
                                    $tag -> setDescriptif($value['descriptif']);
                                }
                            }
                        }
                            // Création d'un nouveau tag
                        else 
                            if (isset($value['libelle']))
                        {
                            $tag=new Tags();
                            $tag->setLibelle($value['libelle']);
                            $tag -> setDescriptif($value['descriptif']);
                            $GroupeTags->addTag($tag);
                            $manager->persist($tag);
                            $manager->flush();  
                        }
                    
                    }
        }
        $errors = $validator->validate($GroupeTags);
        if (count($errors))
        {
            $errors = $serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $manager->persist($GroupeTags);
        $manager->flush();
    }
        return $this->json($GroupeTags,Response::HTTP_CREATED);
    }

}
