<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfilTest extends WebTestCase {
    protected function createAuthenticatedClient(string $username, string $password):KernelBrowser{
        $client = static::createClient();
        $infos = [
            'username' => $username,
            'password' => $password
        ];
        $client -> request('POST', '/api/login_check',[],[],[
            'CONTENT_TYPE'=>'application/json'
        ], json_encode($infos));
        $this -> assertResponseStatusCodeSame((Response::HTTP_OK));
        $data = \json_decode($client -> getResponse() -> getContent(),true);
        $client -> setServerParameter('HTTP_Authorization', \sprintf('Bearer %s', $data['token']));
        $client -> setServerParameter('CONTENT_TYPE','application/json');
        return $client;
    }

    public function testShowProfil(){
        $client = $this -> createAuthenticatedClient('gabriel le martins','passe');
        $client -> request('GET','/api/admin/profils/1');
        $this -> assertEquals(Response::HTTP_OK, $client -> getResponse() -> getStatusCode());
    }

    public function testCreateProfil(){
        $profil = [
            'libelle' => 'DESIGNER',
            'isDeleted' => false
        ];
        $client = $this -> createAuthenticatedClient('gabriel le martins','passe');
        $client -> request('POST','/api/admin/profils',[],[],[
            'CONTENT_TYPE'=>'application/json'
        ],json_encode($profil));
        $responseContent = $client -> getResponse();
        //$responseDecode = json_decode($responseContent);
        $this -> assertEquals(Response::HTTP_OK,$responseContent -> getStatusCode());
        //$this -> assertJson($responseContent);
        //assertNotEmpty($responseDecode);
    }

    public function testPutProfil(){
        $profil = [
            'libelle' => 'ADMIN2.0',
            'isDeleted' => false
        ];
        $client = $this -> createAuthenticatedClient('gabriel le martins','passe');
        $client -> request('PUT','/api/admin/profils/1',[],[],[
            'CONTENT_TYPE'=>'application/json'
        ],json_encode($profil));
        $responseContent = $client -> getResponse();
        //$responseDecode = json_decode($responseContent);
        $this -> assertEquals(Response::HTTP_OK,$responseContent -> getStatusCode());
        //$this -> assertJson($responseContent);
        //assertNotEmpty($responseDecode);
    }
}