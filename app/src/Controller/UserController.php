<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use App\Form\Type\ChangeEntitlementsType;
use App\Form\Type\ChangeEntitlementsDisabledType;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserController.
 */
#[\Symfony\Component\Routing\Attribute\Route('/user')]
class UserController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param UserServiceInterface $userService User service
     * @param TranslatorInterface  $translator  Translator
     */
    public function __construct(private readonly UserServiceInterface $userService, private readonly TranslatorInterface $translator)
    {
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[\Symfony\Component\Routing\Attribute\Route(name: 'user_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        $pagination = $this->userService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render('user/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param User $user User entity
     *
     * @return Response HTTP response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/{id}', name: 'user_show', requirements: ['id' => '[1-9]\d*'], methods: 'GET')]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param User    $user    User entity
     *
     * @return Response HTTP response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/{id}/edit', name: 'user_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, User $user): Response
    {
        $roles = $this->getUser()->getRoles();

        $form = $this->createForm(UserType::class, $user, [
            'role' => $roles,
            'method' => 'PUT',
            'action' => $this->generateUrl('user_edit', ['id' => $user->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * Change entitlements action.
     *
     * @param Request $request HTTP request
     * @param User    $user    User entity
     *
     * @return Response HTTP response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/{id}/change_entitlements', name: 'user_change_entitlements', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function changeEntitlements(Request $request, User $user): Response
    {
        $roles = $this->getUser()->getRoles();

        if ($this->userService->getAdminsNumber() > 1) {
            $form = $this->createForm(ChangeEntitlementsType::class, $user, [
                'role' => $roles,
                'method' => 'PUT',
                'action' => $this->generateUrl('user_change_entitlements', ['id' => $user->getId()]),
            ]);
            $form->handleRequest($request);
        } else {
            $form = $this->createForm(ChangeEntitlementsDisabledType::class, $user, [
                'role' => $roles,
                'method' => 'PUT',
                'action' => $this->generateUrl('user_change_entitlements', ['id' => $user->getId()]),
            ]);
            $form->handleRequest($request);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/change_entitlements.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
