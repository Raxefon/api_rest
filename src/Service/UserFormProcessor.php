<?php

namespace App\Service;

use App\Entity\User;
use App\Form\Model\UserDto;
use App\Form\Type\UserFormType;
use DateTimeImmutable;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class UserFormProcessor
{
    private $userManager;
    private $formFactory;

    public function __construct(

        UserManager $userManager,
        FormFactoryInterface $formFactory
    ) {
        $this->userManager = $userManager;
        $this->formFactory = $formFactory;
    }

    public function __invoke(User $user, Request $request): array
    {
        $date = new DateTimeImmutable();
        $userDto = UserDto::createFromUser($user);

        /*Como los formularios de symfony no se llevan bien con el metodo Put nos toca modificar el codigo*/
        $content = json_decode($request->getContent(), true);
        $form = $this->formFactory->create(UserFormType::class, $userDto);
        $form->submit($content);

        if (!$form->isSubmitted()) {

            return [null, 'Form is not submitted'];
        }

        if ($form->isValid()) {

            if ($userDto->name) {
                $user->setName($userDto->name);
            }

            if ($userDto->email) {
                $user->setEmail($userDto->email);
            }

            if ($user->getCreatedAt()) {

                $user->setCreatedAt($date);
            }

            if (!$userDto->updatedAt) {
                //Convertirmos a DateTimeInmutable
                $updatedAt = new DateTimeImmutable($userDto->updatedAt);
                $user->setUpdatedAt($updatedAt);
            }

            $this->userManager->save($user);
            $this->userManager->reload($user);
            return [$user, null];
        }

        return [null, $form];
    }
}
