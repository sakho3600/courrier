<?php

namespace Mails\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
   * @Security("has_role('ROLE_ADMIN')")
   */
class UserController extends Controller
{
    /**
     * Displays a list of all users
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllUserAction()
    {
        // On récupère notre service lister
        $lister = $this->get('mails_mail.mail_lister');

        // On affiche la liste de tous les utilisateurs
        $listUser = $lister->listAdminUser();

        return $this->render('UserBundle:User:user.html.twig', array(
            'users' => $listUser
            ));
    }

    /**
     * Displays a list of all mail sent by the responsible power
     *
     * @param interger $page page number
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAllMailsentCurrentUserAction($page, Request $request)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }
    
        // On récupère notre service lister
        $lister = $this->get('mails_mail.mail_lister');

        // On récupère notre service calculator
        $nbPageCalculator = $this->get('mails_mail.nbpage_calculator');

        // On récupère la liste de tous les courriers envoyés par l'administrateur courant
        $listMailsSent = $lister->listAdminMailsent($page, $lister::NUM_ITEMS, $this->getUser());

        /* On calcule le nombre total de pages grâce au count($listMailsSent)
        qui retourne le nombre total de courriers envoyé */
        $nombreTotalPages= $nbPageCalculator->calculateTotalNumberPageByUser($listMailsSent, $lister::NUM_ITEMS, $page);

        if ($page > $nombreTotalPages) {
            $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('danger', 'vous n\'avez pour le moment aucune liste de courriers envoyés à gerer !');
            return $this->redirect($this->generateUrl('mails_core_home'));
        }

        return $this->render('UserBundle:User:user_mailsent.html.twig', array(
            'mailsSentByActor' => $listMailsSent,
            'nbPages' => $nombreTotalPages,
            'page' => $page,
            ));
    }

    /**
     * Displays a list of all mail received by the responsible power
     *
     * @param interger $page page number
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAllMailreceivedCurrentUserAction($page, Request $request)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }
        
        // On récupère notre service lister
        $lister = $this->get('mails_mail.mail_lister');

        // On récupère la liste de tous les courriers reçus par l'administrateur courant
        $listMailsReceived = $lister->listAdminMailreceived($page, $lister::NUM_ITEMS, $this->getUser());

        // On récupère notre service calculator
        $nbPageCalculator = $this->get('mails_mail.nbpage_calculator');

        /* On calcule le nombre total de pages grâce au count($listMailsSent)
        qui retourne le nombre total de courriers envoyé */
        $nombreTotalPages = $nbPageCalculator
                          ->calculateTotalNumberPageByUser($listMailsReceived, $lister::NUM_ITEMS, $page);

        if ($page > $nombreTotalPages) {
            $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('danger', 'vous n\'avez pour le moment aucune liste de courrier reçu à gerer !');
            return $this->redirect($this->generateUrl('mails_core_home'));
        }

        return $this->render('UserBundle:User:user_mailreceived.html.twig', array(
            'mailsReceivedByActor' => $listMailsReceived,
            'nbPages' => $nombreTotalPages,
            'page' => $page,
            ));
    }

    /**
     * Delete an user.
     *
     * @param integer $id User id
     * @param Request $request Incoming request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUserAction($id, Request $request)
    {
        // On récupère l'Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        // On récupère l'user par son id
        $user = $em->getRepository('UserBundle:User')->find($id);

        // On récupère tous les courriers envoyés par l'user
        $allMailsentByUser = $em->getRepository('MailsMailBundle:Mail')->findAllMailsentByUser($id);
        
        // On récupère tous les courriers reçus par l'user
        $allMailreceivedByUser = $em->getRepository('MailsMailBundle:Mail')->findAllMailreceivedByUser($id);

        if (null === $user) {
            throw new NotFoundHttpException("L'utilisateur d'id ".$id." n'existe pas.");
        }
                
        // On stocke le nom de l'utilisateur dans une variable tampon
        $tempUserName = $user->getUsername();

        // On récupère notre service eraser
        $eraser = $this->get('mails_mail.eraser');

        // Si l'user est une secretaire
        if (in_array("ROLE_SECRETAIRE", $user->getRoles())) {
            // On récupère la liste de tous les courrier enregistrés par la sécrétaire concernée
            $allMailRegistredBySecretary = $em->getRepository('MailsMailBundle:Mail')->getAllMailRegistred($user);

            // supression de la sécrétaire
            $eraser->deleteSecretaryAndAllHisMails($user, $allMailRegistredBySecretary);
        }

        // supression de l'utilisateur
        $eraser->deleteUserAndAllHisMails($user, $allMailsentByUser, $allMailreceivedByUser);
        
        $request
        ->getSession()
        ->getFlashBag()
        ->add('success', 'L\'utilisateur "'.$tempUserName.'" ainsi que tous ses courriers ont bien été supprimés.');

        // On supprime la variable tampon
        unset($tempUserName);

        // Puis on redirige vers l'accueil
        return $this->redirect($this->generateUrl('mails_core_home'));
    }

    /**
     * Displays all the mails of the specified user.
     *
     * @param integer $id User id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllMailOfUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        // On récupère l'user par son id
        $user = $em->getRepository('UserBundle:User')->find($id);

        // On récupère tous les courriers envoyés par l'user
        $allMailsentByUser = $em->getRepository('MailsMailBundle:Mail')->findAllMailsentByUser($id);
        
        // On récupère tous les courriers reçus par l'user
        $allMailreceivedByUser = $em->getRepository('MailsMailBundle:Mail')->findAllMailreceivedByUser($id);

        if (null === $user) {
            throw new NotFoundHttpException("L'utilisateur d'id ".$id." n'existe pas.");
        }
        
        return $this->render('UserBundle:User:user_mails.html.twig', array(
        'user' => $user,
        'allMailsentByUser' => $allMailsentByUser,
        'allMailreceivedByUser' => $allMailreceivedByUser,
        ));
    }
}
