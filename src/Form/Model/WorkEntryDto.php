<?php

namespace App\Form\Model;

use App\Entity\WorkEntry;

class WorkEntryDto
{
    public $createdAt;
    public $uploadAt;
    public $deletedAt;
    public $startDate;
    public $endDate;

    public static function createFormWorkEntry(WorkEntry $workEntry): self
    {
        $dto = new self();
        $dto->startDate = $workEntry->getStartDate();
        return $dto;
    }
}
