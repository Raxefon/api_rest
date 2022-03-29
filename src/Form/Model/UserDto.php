<?php

namespace App\Form\Model;

use App\Entity\User;

class UserDto
{
    public $name;
    public $email;
    public $createdAt;
    public $uploadAt;
    public $deletedAt;

    public static function createFromUser(User $user): self
    {
        $dto = new self();
        $dto->name = $user->getName();
        return $dto;
    }
}
