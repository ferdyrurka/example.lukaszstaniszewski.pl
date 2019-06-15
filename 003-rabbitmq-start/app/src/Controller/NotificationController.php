<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\Model\NewNotificationsModel;
use App\Form\NewNotificationsForm;
use App\Repository\NotificationRepository;
use App\Service\AddNotificationToQueueService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class NotificationController
 * @package App\Controller
 */
class NotificationController extends AbstractController
{
    /**
     * @param Request $request
     * @return array
     * @Route("/", methods={"GET"})
     * @Template("notification/index.html.twig")
     */
    public function notification(Request $request): array
    {
        $form = $this->createForm(NewNotificationsForm::class, new NewNotificationsModel());
        $form->handleRequest($request);

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @param AddNotificationToQueueService $addNotificationToQueueService
     * @return Response
     * @throws \App\Exception\CompositeStorageIsEmptyException
     * @throws \App\Exception\InvalidArgsException
     * @Route("/", methods={"POST"})
     */
    public function addNotification(
        Request $request,
        AddNotificationToQueueService $addNotificationToQueueService
    ): Response {
        $form = $this->createForm(NewNotificationsForm::class, new NewNotificationsModel());
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $notificationsModel = $form->getData();
            $addNotificationToQueueService->addToQueue(\json_decode($notificationsModel->getJsonData(), true));

            return new RedirectResponse('/');
        }

        return $this->forward(\get_class($this) . '::notification', [
            'request' => $request
        ]);
    }

    /**
     * @param NotificationRepository $notificationRepository
     * @return array
     * @Route("/all", methods={"GET"})
     * @Template("notification/allNotifications.html.twig")
     */
    public function allNotification(NotificationRepository $notificationRepository): array
    {
        return [
            'notifications' => $notificationRepository->findAll()
        ];
    }
}
