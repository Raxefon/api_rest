<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\Model\UserDto;
use App\Form\Type\UserFormType;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends AbstractFOSRestController
{

    /**
     * @Rest\Get(path="/all_users")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(
        UserRepository $userRepository
    ) {
        return $userRepository->findAll();
    }

    /**
     * @Rest\Get(path="/user/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function getActionId(
        string $id,
        UserRepository $userRepository

    ) {
        return $userRepository->find($id);
    }

    /**
     * @Rest\Post(path="/create_user")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        EntityManagerInterface $em,
        Request $request
    ) {
        $userDto = new UserDto();
        $date = new DateTimeImmutable();

        //Creamos el obj de la clase UserBookType
        $form = $this->createForm(UserFormType::class, $userDto);
        //Comprueba si se realiza un POST y maneja el form
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {

            return new Response('', Response::HTTP_BAD_REQUEST);
        }

        if ($form->isValid()) {

            $user = new User();
            $user->setName($userDto->name);
            $user->setEmail($userDto->email);
            $user->setCreatedAt($date);
            $user->setUpdatedAt($date);
            $em->persist($user);
            $em->flush();
            return $user;
        }

        return $form;
    }

    /**
     * @Rest\Put(path="/update_user/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(
        string $id,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        Request $request
    ) {
        $date = new DateTimeImmutable();

        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $userDto = new UserDto();
        $userDto = UserDto::createFromUser($user);

        /*Como los formularios de symfony no se llevan bien con el metodo Put nos toca modificar el codigo*/
        $content = json_decode($request->getContent(), true);
        $form = $this->createForm(UserFormType::class, $userDto);
        $form->submit($content);

        $userDto->createdAt = $user->getCreatedAt();
        $userDto->updatedAt = $date;

        if (!$form->isSubmitted()) {

            return new Response('', Response::HTTP_BAD_REQUEST);
        }

        if ($form->isValid()) {

            $user->setName($userDto->name);
            $user->setEmail($userDto->email);
            $user->setCreatedAt($userDto->createdAt);
            $user->setUpdatedAt($userDto->updatedAt);
            $em->persist($user);
            $em->flush();
            return $user;
        }

        return $form;
    }
}
