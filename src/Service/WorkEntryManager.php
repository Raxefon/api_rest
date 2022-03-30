<?php

namespace App\Service;

use App\Entity\WorkEntry;
use App\Repository\WorkEntryRepository;
use Doctrine\ORM\EntityManagerInterface;

class WorkEntryManager
{

    private $em;
    private $workEntryRepository;

    public function __construct(EntityManagerInterface $em, WorkEntryRepository $workEntryRepository)
    {
        $this->em = $em;
        $this->workEntryRepository = $workEntryRepository;
    }

    public function findUserId(int $id)
    {
        return $this->workEntryRepository->findWorkEntryByUserId($id);
    }

    public function find(int $id): ?WorkEntry
    {
        return $this->workEntryRepository->findWorkEntryById($id);
    }

    public function getRepository(): WorkEntryRepository
    {
        return $this->workEntryRepository;
    }

    public function create(): WorkEntry
    {
        $workEntry = new WorkEntry();
        return $workEntry;
    }

    public function save(WorkEntry $workEntry): WorkEntry
    {
        $this->em->persist($workEntry);
        $this->em->flush();
        return $workEntry;
    }

    public function reload(WorkEntry $workEntry): WorkEntry
    {
        $this->em->refresh($workEntry);
        return $workEntry;
    }

    public function delete(WorkEntry $workEntry)
    {
        $this->em->remove($workEntry);
        $this->em->flush();
    }
}
