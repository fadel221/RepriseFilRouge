<?php

namespace App\Controller;

use App\Repository\GroupeRepository;
use App\Repository\PromoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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


}
