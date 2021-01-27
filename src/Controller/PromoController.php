<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Groupe;
use App\Entity\Promo;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use App\Repository\GroupeRepository;
use App\Repository\PromoRepository;
use App\Repository\ReferentielRepository;
use App\Services\FileUpload;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PromoController extends AbstractController
{
    /**
     * @Route(
     *     path="/api/admin/promos/{idp}/groupes/{idg}/apprenants",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\PromoController::GroupeApprenantsOnPromo",
     *          "__api_resource_class"=Promo::class,
     *          "__api_collection_operation_name"="groupe_apprenants_on_promo"
     *         }
     * )
    */
    public function GroupeApprenantsOnPromo ( GroupeRepository $grp,PromoRepository $promo,$idp,$idg)
    {
        if ($promo->find($idp)!=null && $grp->find($idg)!=null)
        {
                $Groupe=$grp->find($idg);
                return $this->json($Groupe,Response::HTTP_OK);
        }
    }

    /**
     * @Route(
     *     path="/api/admin/promos",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\PromoController::AddPromo",
     *          "__api_resource_class"=Promo::class,
     *          "__api_collection_operation_name"="add_promo"
     *         }
     * )
    */
    public function AddPromo ( Request $request,SerializerInterface $serializer,FileUpload $upload,ValidatorInterface $validator,EntityManagerInterface $manager,ReferentielRepository $ref,FormateurRepository $formateur,ApprenantRepository $app)
    {
        $promo_tab = $request->request->all();
        $avatar =$upload->UploadFile("avatar",$request);
        $file=$upload->UploadFile("file",$request);
        $Groupe=new Groupe();
        $Groupe->setType("Principal");
        $referentiel_tab=$promo_tab["referentiel_array"];
        unset($promo_tab["referentiel_array"]);
        if (isset($promo_tab["email_array"]))
        {
          $email_tab=$promo_tab["email_array"];
          unset($promo_tab["email_array"]);
          foreach ($email_tab as $email)
        { if ($apprenant=$app->findOneApprenantByEmail($email))
          {
            $Groupe->addApprenant($apprenant);
          }
        }
        }
        $promo=$serializer->denormalize($promo_tab,"App\Entity\Promo");
        foreach ($referentiel_tab as $referentiel)
        {
          $promo->addReferentiel($ref->findOneReferentielByLibelle($referentiel));
        }
        
        foreach ($formateur->findAll() as $Formateur)
        {
          $promo->addFormateur($Formateur);
        }

       //$Groupe->setFormateur($this->get('security.token_storage')->getToken()->getUser());
        $promo->setAvatar($avatar);
        $promo->setFile($file);
        $promo->addGroupe($Groupe);
        $manager->persist($promo);
        $manager->flush();
        return $this->json($promo,Response::HTTP_CREATED);
    }


}
