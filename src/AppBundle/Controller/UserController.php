<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     *
     * @Rest\View()
     * @Rest\Get("/users")
     */
    public function getUsersAction ()
    {
        $users = $this->getDoctrine()
            ->getManager()
            ->getRepository("AppBundle:User")
            ->findAll();

        return $users;
    }

    /**
     * @param User $user
     *
     * @Rest\View()
     * @Rest\Get("/users/{id}")
     */
    public function getUserAction (User $user)
    {
        $user = $this->getDoctrine()
            ->getManager()
            ->getRepository("AppBundle:User")
            ->find($user);

        if(empty($user)) {
            return View::create(['message' => 'Place Not Found'], Response::HTTP_NOT_FOUND);
        }

        return $user;
    }

    /**
     * @param Request $request
     *
     * @Rest\View()
     * @Rest\Put("/users/{id}")
     */
    public function putUserAction (Request $request)
    {
        $this->updateUserAction($request, true);
    }

    /**
     * @param Request $request
     *
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Patch("/users/{id}")
     */
    public function patchUserAction (Request $request)
    {
        $this->updateUserAction($request, false);
    }

    /**
     * @param Request $request
     * @return User|\Symfony\Component\Form\FormInterface
     *
     * @Rest\View()
     * @Rest\Post(path="/users")
     */
    public function postUsersAction (Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['validation_groups' => ['Default', 'New']]);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $encoder = $this->get('security.password_encoder');
            // Le mot de passe en claire est encodé avant la sauvegarde
            $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $user;
        }
        else {
            return $form;
        }
    }

    /**
     * @param Request $request
     *
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("users/{id}")
     */
    public function removeUserAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository("AppBundle:User")
                    ->find($request->get('id'));
        /** @var $user User */

        if ($user) {
            $em->remove($user);
            $em->flush();
        }
    }

    public function updateUserAction (Request $request, $clearMissing)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository("AppBundle:User")
            ->find($request->get('id'));
        /** @var $user User */

        if(empty($user)) {
            return $this->userNotFound();
        }

        if ($clearMissing) { // Si une mise à jour complète, le mot de passe doit être validé
            $option = ['validation_groups' => ['Default, FullUpdate']];
        }
        else {
            $option = []; // Le groupe de validation par défaut de Symfony est Default
        }

        $form = $this->createForm(UserType::class, $user, $option);

        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()){
            //Si l'utilisateur veut changer son mot de passe
            if (!empty($user->getPlainPassword())) {
                $encoder = $this->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($encoded);
            }

            $em->merge($user);
            $em->flush();

            return $user;
        }
        else {
            return $form;
        }
    }

    public function userNotFound()
    {
        return View::create(['message' => 'User not found '], Response::HTTP_NOT_FOUND);
    }
}