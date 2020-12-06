<?php

namespace App\Controller;

use App\Repository\ProfilSortieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilsortieController extends AbstractController
{
    /**
     * @Route(
     *     path="api/admin/promo/{id}/profilsorties",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\Controller::ProfilsortieByPromo",
     *          "__api_resource_class"=Groupe::class,
     *          "__api_collection_operation_name"="profilsortiebypromo"
     *     }
     * )
    */

    public function ProfilsortieByPromo (ProfilSortieRepository $repprofil,$id)
    {
        $data=$repprofil->findProfilsortieByPromo($id);
        return $this -> json($data, Response::HTTP_OK,);
    }
}
