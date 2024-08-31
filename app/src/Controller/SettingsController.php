<?php
/**
 * Settings controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use App\Form\Type\ChangePasswordType;
use App\Service\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class SettingsController.
 */
class SettingsController extends AbstractController
{
    /**
     * User service.
     */
    private UserServiceInterface $userService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param UserServiceInterface $userService User service
     * @param TranslatorInterface  $translator  Translator
     */
    public function __construct(UserServiceInterface $userService, TranslatorInterface $translator)
    {
        $this->userService = $userService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @return Response HTTP response
     */
    #[Route('settings/{id}', name: 'settings_index', methods: 'GET')]
    public function index(): Response
    {
        return $this->render('settings/index.html.twig');
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param User    $user    User entity
     *
     * @return Response HTTP response
     */
    #[Route('settings/{id}/edit', name: 'settings_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, User $user): Response
    {
        $roles = $this->getUser()->getRoles();

        $form = $this->createForm(UserType::class, $user, [
            'role' => $roles,
            'method' => 'PUT',
            'action' => $this->generateUrl('settings_edit', ['id' => $user->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('settings_index', ['id' => $user->getId()]);
        }

        if ($user->getId() !== $this->getUser()->getId()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.operation_not_permitted')
            );

            return $this->redirectToRoute('settings_index', ['id' => $this->getUser()->getId()]);
        }

        return $this->render('settings/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * Change password action.
     *
     * @param Request                     $request            HTTP request
     * @param User                        $user               User entity
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param EntityManagerInterface      $entityManager
     *
     * @return Response HTTP response
     */
    #[Route('settings/{id}/change_password', name: 'settings_change_password', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function changePassword(Request $request, User $user, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChangePasswordType::class, $user, [
            'method' => 'PUT',
            'action' => $this->generateUrl('settings_change_password', ['id' => $user->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('message.password_changed_successfully')
            );

            return $this->redirectToRoute('settings_index', ['id' => $user->getId()]);
        }

        if ($user->getId() !== $this->getUser()->getId()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.operation_not_permitted')
            );

            return $this->redirectToRoute('settings_index', ['id' => $this->getUser()->getId()]);
        }

        return $this->render('settings/change_password.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}