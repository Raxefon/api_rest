<?php

namespace App\Controller\Api;

use App\Form\Model\WorkEntryDto;
use App\Form\Type\WorkEntryFormType;
use App\Repository\WorkEntryRepository;
use App\Service\UserManager;
use App\Service\WorkEntryFormProcessor;
use App\Service\WorkEntryManager;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkEntryController extends AbstractFOSRestController
{

    /**
     * @Rest\Get(path="/workEntryByUserId/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"workEntry"}, serializerEnableMaxDepthChecks=true)
     */
    public function getActions(
        int $id,
        WorkEntryManager $workEntryManager
    ) {

        return $workEntryManager->findUserId($id);
    }

    /**
     * @Rest\Get(path="/workEntry/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"workEntry"}, serializerEnableMaxDepthChecks=true)
     */
    public function getActionWorkEntryId(
        int $id,
        WorkEntryManager $workEntryManager
    ) {
        return $workEntryManager->find($id);
    }

    /**
     * @Rest\Post(path="/create_workEntry")
     * @Rest\View(serializerGroups={"workEntry"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        WorkEntryFormProcessor $workEntryFormProcessor,
        WorkEntryManager $workEntryManager,
        Request $request,
        UserManager $userManager
    ) {
        $workEntry = $workEntryManager->create();
        [$workEntry, $error] = ($workEntryFormProcessor)($workEntry, $request, $userManager);
        $statusCode = $workEntry ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $workEntry ?? $error;

        return View::create($data, $statusCode);
    }

    /**
     * @Rest\Put(path="/update_workEntry/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"workEntry"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(
        string $id,
        WorkEntryFormProcessor $workEntryFormProcessor,
        WorkEntryManager $workEntryManager,
        Request $request,
        UserManager $userManager
    ) {
        $workEntry = $workEntryManager->find($id);

        if (!$workEntry) {
            return View::create('User not found', Response::HTTP_BAD_REQUEST);
        }

        [$workEntry, $error] = ($workEntryFormProcessor)($workEntry, $request, $userManager);
        $statusCode = $workEntry ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $workEntry ?? $error;
        return View::create($data, $statusCode);
    }

    /**
     * @Rest\Delete(path="/delete_workEntry/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"workEntry"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(

        int $id,
        WorkEntryManager $workEntryManager
    ) {
        $date = new DateTimeImmutable();
        $workEntry = $workEntryManager->find($id);

        if (!$workEntry) {
            return View::create('WorkEntry not found', Response::HTTP_BAD_REQUEST);
        }

        //Estas lineas para guardar el registro deletedAt en vez de borrar el registro
        $workEntry->setDeletedAt($date);
        $workEntryManager->save($workEntry);
        $workEntryManager->reload($workEntry);

        //Con esto se borraria el registro de la BBDD
        //$userManager->delete($user);
        return View::create('User deleted', Response::HTTP_NO_CONTENT);
    }
}
