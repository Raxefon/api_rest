<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends AbstractFOSRestController
{

    /**
     * @Rest\Get(path="/users")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(
        UserRepository $userRepository
    ) {
        return $userRepository->findAll();
    }

    /**
     * @Rest\Post(path="/users")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        EntityManagerInterface $em
    ) {
        $user = new User();
        $date = new DateTimeImmutable();

        $user->setName('Morgan');
        $user->setEmail('test3@test.com');
        $user->setCreatedAt($date);
        $user->setUpdatedAt($date);


        /**doctrime tiene una entidad de la clase user */
        $em->persist($user);
        $em->flush();

        return $user;
    }
}
