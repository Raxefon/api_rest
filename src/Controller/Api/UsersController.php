<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use App\Service\UserFormProcessor;
use App\Service\UserManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends AbstractFOSRestController
{

    /**
     * @Rest\Get(path="/all_users")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function getActionUsers(
        UserManager $userManager
    ) {
        return $userManager->getRepository()->findAll();
    }

    /**
     * @Rest\Get(path="/user/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function getActionUserId(
        int $id,
        UserManager $userManager

    ) {
        return $userManager->find($id);
    }

    /**
     * @Rest\Post(path="/create_user")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        UserFormProcessor $userFormProcessor,
        UserManager $userManager,
        Request $request
    ) {
        $user = $userManager->create();
        [$user, $error] = ($userFormProcessor)($user, $request);
        $statusCode = $user ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $user ?? $error;
        return View::create($data, $statusCode);
    }

    /**
     * @Rest\Put(path="/update_user/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(

        int $id,
        UserFormProcessor $userFormProcessor,
        UserManager $userManager,
        Request $request
    ) {
        $user = $userManager->find($id);
        if (!$user) {
            return View::create('User not found', Response::HTTP_BAD_REQUEST);
        }
        [$user, $error] = ($userFormProcessor)($user, $request);
        $statusCode = $user ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $user ?? $error;
        return View::create($data, $statusCode);
    }

    /**
     * @Rest\Delete(path="/delete_user/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(

        int $id,
        UserManager $userManager
    ) {
        $user = $userManager->find($id);
        if (!$user) {
            return View::create('User not found', Response::HTTP_BAD_REQUEST);
        }
        $userManager->delete($user);
        return View::create('User deleted', Response::HTTP_NO_CONTENT);
    }
}
