<?php

namespace App\Form\Model;

use App\Entity\WorkEntry;

class WorkEntryDto
{
    public $createdAt;
    public $updatedAt;
    public $deletedAt;
    public $startDate;
    public $endDate;
    public $user;

    public static function createFormWorkEntry(WorkEntry $workEntry): self
    {
        $dto = new self();
        $dto->updatedAt = $workEntry->getUpdatedAt();
        $dto->startDate = $workEntry->getStartDate();
        $dto->endDate = $workEntry->getEndDate();
        $dto->user = $workEntry->getUser();
        return $dto;
    }
}
