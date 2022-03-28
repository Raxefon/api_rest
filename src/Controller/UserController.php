<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="users_get")
     */
    public function list(Request $request, UserRepository $userRepository)
    {
        $name = $request->get('name', 'Alberto');

        $users = $userRepository->findAll();

        $usersAsArray = [];

        foreach ($users as $user) {
            $usersAsArray[] = [
                'id'        => $user->getId(),
                'name'      => $user->getName(),
                'email'     => $user->getEmail(),
                'createdAt' => $user->getCreatedAt(),
                'updatedAt' => $user->getUpdatedAt(),
                'deletedAt' => $user->getDeletedAt()
            ];
        };

        $response = new JsonResponse();
        $response->setData([
            'success' => true,
            'data' => $usersAsArray
        ]);
        return $response;
    }

    /**
     * @Route("/user/create", name="create_user")
     */
    public function createUser(Request $request, EntityManagerInterface $em)
    {

        $user = new User();
        $response = new JsonResponse();
        $date = new DateTimeImmutable();

        $name = $request->get('name', null);
        $email = $request->get('email', null);
        if (empty($name) || empty($email)) {
            $response->setData([
                'success'   => false,
                'error'     => 'Name or email cannot be empty',
                'data' => null
            ]);
            return $response;
        }

        $user->setName($name);
        $user->setEmail($email);
        $user->setCreatedAt($date);
        $user->setUpdatedAt($date);


        /**doctrime tiene una entidad de la clase user */
        $em->persist($user);
        $em->flush();


        $response->setData([
            'success' => true,
            'data' => [
                [
                    'id'   => $user->getId(),
                    'name' => $user->getName()
                ]
            ]
        ]);

        return $response;
    }
}
