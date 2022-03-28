<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\Type\UserFormType;
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
        EntityManagerInterface $em,
        Request $request
    ) {
        $user = new User();
        $date = new DateTimeImmutable();

        //Creamos el obj de la clase UserBookType
        $form = $this->createForm(UserFormType::class, $user);
        //Comprueba si se realiza un POST y maneja el form
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user->setCreatedAt($date);
            $user->setUpdatedAt($date);
            $em->persist($user);
            $em->flush();
            return $user;
        }

        return $form;
    }
}
