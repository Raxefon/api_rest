<?php

namespace App\Service;

use App\Entity\WorkEntry;
use App\Form\Model\WorkEntryDto;
use App\Form\Type\WorkEntryFormType;
use DateTimeImmutable;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class WorkEntryFormProcessor
{
    private $workEntryManager;
    private $formFactory;

    public function __construct(

        WorkEntryManager $workEntryManager,
        FormFactoryInterface $formFactory,
    ) {
        $this->workEntryManager = $workEntryManager;
        $this->formFactory = $formFactory;
    }

    public function __invoke(WorkEntry $workEntry, Request $request, UserManager $userManager): array
    {
        $date = new DateTimeImmutable();
        $workEntryDto = WorkEntryDto::createFormWorkEntry($workEntry);

        /*Como los formularios de symfony no se llevan bien con el metodo Put nos toca modificar el codigo*/
        $content = json_decode($request->getContent(), true);
        $form = $this->formFactory->create(WorkEntryFormType::class, $workEntryDto);
        $form->submit($content);

        if (!$form->isSubmitted()) {

            return [null, 'Form is not submitted'];
        }

        if ($form->isValid()) {

            //Con estas dos lineas se podría modificar el ID de usuario//
            //Obtenemos el obj User
            //$user = $userManager->find($workEntryDto->user);
            //$workEntry->setUser($user);

            if (!$workEntry->getCreatedAt()) {

                $workEntry->setCreatedAt($date);
            }

            $workEntry->setUpdatedAt($date);

            if ($workEntryDto->startDate) {
                //Convertirmos a DateTimeInmutable
                $startDate = new DateTimeImmutable($workEntryDto->startDate);

                //Comprobamos que exista endDate
                if ($workEntry->getEndDate()) {
                    //Comprobamos que no sea mayor que endDate
                    if ($startDate > $workEntry->getEndDate()) {
                        return [null, 'startDate cannot be greater than endDate'];
                    }
                }

                $workEntry->setStartDate($startDate);
            } else {
                $workEntry->setStartDate($date);
            }

            if ($workEntryDto->endDate) {

                //Convertirmos a DateTimeInmutable
                $endDate = new DateTimeImmutable($workEntryDto->endDate);

                //Comprobamos que no sea menos que startDate
                if ($endDate < $workEntry->getStartDate()) {
                    return [null, 'endDate cannot be less than startDate'];
                }

                $workEntry->setEndDate($endDate);
            }

            $this->workEntryManager->save($workEntry);
            $this->workEntryManager->reload($workEntry);

            return [$workEntry, null];
        }

        return [null, $form];
    }
}
