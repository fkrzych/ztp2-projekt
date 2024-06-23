<?php
/**
 * Main controller.
 */

namespace App\Controller;

use App\Repository\EventRepository;
use App\Service\MainServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class MainController.
 */
#[\Symfony\Component\Routing\Attribute\Route('/main')]
class MainController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param MainServiceInterface $mainService MainServiceInterface
     * @param TranslatorInterface  $translator  TranslatorInterface
     */
    public function __construct(private readonly MainServiceInterface $mainService, private readonly TranslatorInterface $translator)
    {
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[\Symfony\Component\Routing\Attribute\Route(
        name: 'main_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $pagination = $this->mainService->getPaginatedList(
            $request->query->getInt('page', 1),
            $this->getUser()
        );

        return $this->render('main/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param EventRepository $repository Event repository
     * @param int             $id         Event id
     *
     * @return Response HTTP response
     */
    #[\Symfony\Component\Routing\Attribute\Route(
        '/{id}',
        name: 'main_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(EventRepository $repository, int $id): Response
    {
        $event = $repository->findOneById($id);

        return $this->render(
            'event/show.html.twig',
            ['event' => $event]
        );
    }
}
