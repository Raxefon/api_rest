<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends AbstractFOSRestController
{

    /**
     * @Rest\Get(path="/users")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function getActions(
        UserRepository $userRepository
    ) {
        return $userRepository->findAll();
    }
}
