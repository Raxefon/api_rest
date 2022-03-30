<?php

namespace App\Form\Model;

use App\Entity\User;

class UserDto
{
    public $name;
    public $email;
    public $createdAt;
    public $updatedAt;
    public $deletedAt;

    public static function createFromUser(User $user): self
    {
        $dto = new self();
        $dto->name = $user->getName();
        $dto->email = $user->getEmail();
        $dto->updatedAt = $user->getUpdatedAt();
        return $dto;
    }
}
