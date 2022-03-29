<?php

namespace App\Controller\Api;

use App\Form\Model\WorkEntryDto;
use App\Form\Type\WorkEntryFormType;
use App\Repository\WorkEntryRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkEntryController extends AbstractFOSRestController
{

    /**
     * @Rest\Get(path="/all_workEntry")
     * @Rest\View(serializerGroups={"workEntry"}, serializerEnableMaxDepthChecks=true)
     */
    public function getActions(
        WorkEntryRepository $workEntryRepository
    ) {

        return $workEntryRepository->findAll();
    }

    /**
     * @Rest\Get(path="/workEntry/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"workEntry"}, serializerEnableMaxDepthChecks=true)
     */
    public function getActionWorkEntryId(
        string $id,
        WorkEntryRepository $workEntryRepository

    ) {
        return $workEntryRepository->find($id);
    }

    /**
     * @Rest\Get(path="/workEntryUser/{userid}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"workEntry"}, serializerEnableMaxDepthChecks=true)
     */
    public function getActionWorkEntryUserId(
        string $userid,
        WorkEntryRepository $workEntryRepository

    ) {
        return $workEntryRepository->findBy(array('user' => $userid));
    }

    /**
     * @Rest\Put(path="/update_workEntry/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"workEntry"}, serializerEnableMaxDepthChecks=true)
     */
    public function editActionUpdateWorkEntry(
        string $id,
        EntityManagerInterface $em,
        WorkEntryRepository $workEntryRepository,
        Request $request
    ) {
        $date = new DateTimeImmutable();

        $workEntry = $workEntryRepository->find($id);
        if (!$workEntry) {
            throw $this->createNotFoundException('WorkEntry not found');
        }

        $workEntryDto = new WorkEntryDto();
        $workEntryDto = WorkEntryDto::createFormWorkEntry($workEntry);

        $content = json_decode($request->getContent(), true);
        $form = $this->createForm(WorkEntryFormType::class, $workEntryDto);
        $form->submit($content);

        $workEntryDto->createdAt = $workEntry->getCreatedAt();
        $workEntryDto->updatedAt = $date;

        if (!$form->isSubmitted()) {

            return new Response('', Response::HTTP_BAD_REQUEST);
        }

        if ($form->isValid()) {

            $workEntry->setCreatedAt($workEntryDto->createdAt);
            $workEntry->setUpdatedAt($workEntryDto->updatedAt);
            $workEntry->setUpdatedAt($workEntryDto->startDate);
            $em->persist($workEntry);
            $em->flush();
            return $workEntry;
        }

        return $form;
    }
}
