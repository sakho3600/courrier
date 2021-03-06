<?php

namespace Mails\MailBundle\Filter;

class MailsFilter
{
    private $em;
    const NUM_ITEMS = 1;

    public function __construct(\Doctrine\ORM\EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }
    
   /**
    * On récupère tous les courriers envoyés, filtrés par date et par reception
    *
    * @param integer $days
    * @param boolean $reception
    */
    public function filtreMailsent($days, $reception, $admin)
    {
        // date d'il y a $days jours
        $date = new \Datetime($days.' days ago');

        //On récupère tous les courriers envoyés, filtrés par date et par reception de l'administrateur concerné
        $allMailsentByFilter = $this
                             ->em
                             ->getRepository('MailsMailBundle:Mail')
                             ->filterAllMailsent($date, $reception, $admin)
                            ;
                            
        // Et on n'oublie pas de faire un flush !
        $this->em->flush();
        
        return $allMailsentByFilter;
    }
    
    /**
    * On récupère tous les courriers reçus, filtrés par date, par reception et par traitement
    *
    * @param integer $days
    * @param boolean $reception
    * @param boolean $traitement
    */
    public function filtreMailreceived($days, $reception, $traitement, $admin)
    {
        // date d'il y a $days jours
        $date = new \Datetime($days.' days ago');

        //On récupère tous les courriers reçus, filtrés par date, par reception et par traitement
        $allMailreceivedByFilter = $this
                             ->em
                             ->getRepository('MailsMailBundle:Mail')
                             ->filterAllMailreceived($date, $reception, $traitement, $admin)
                            ;
                            
        // Et on n'oublie pas de faire un flush !
        $this->em->flush();
        
        return $allMailreceivedByFilter;
    }
    
    /**
    * On récupère tous les utilisateurs, filtrés par roles
    *
    * @param array $roles
    */
    public function filtreUser(array $roles)
    {
        //On récupère tous les utilisateurs, filtrés par roles
        $allUserByFilter = $this
                         ->em
                         ->getRepository('UserBundle:User')
                         ->findAllUserByFilter($roles)
                        ;
                            
        // Et on n'oublie pas de faire un flush !
        $this->em->flush();
        
        return $allUserByFilter;
    }
    
    /**
    * On récupère tous les courriers envoyés, filtrés par date, par reception et par user
    *
    * @param integer $days
    * @param boolean $reception
    * @param integer $id
    */
    public function filtreMailsentByUser($days, $reception, $id)
    {
        // date d'il y a $days jours
        $date = new \Datetime($days.' days ago');

        //On récupère tous les courriers envoyés, filtrés par date, par reception et par user
        $allMailsentFilterByUser = $this
                             ->em
                             ->getRepository('MailsMailBundle:Mail')
                             ->filterAllMailsentByUser($date, $reception, $id)
                            ;
                            
        // Et on n'oublie pas de faire un flush !
        $this->em->flush();
        
        return $allMailsentFilterByUser;
    }
    
    /**
    * On récupère tous les courriers reçus, filtrés par date, par reception par user et par traitement
    *
    * @param integer $days
    * @param boolean $reception
    * @param boolean $traitement
    * @param integer $user
    */
    public function filtreMailreceivedByUser($days, $reception, $user, $traitement)
    {
        // date d'il y a $days jours
        $date = new \Datetime($days.' days ago');

        //On récupère tous les courriers reçus, filtrés par date, par reception par user et par traitement
        $allMailreceivedFilterByUser = $this
                                     ->em
                                     ->getRepository('MailsMailBundle:Mail')
                                     ->filterAllMailreceivedByUser($date, $reception, $user, $traitement)
                                    ;
                            
        // Et on n'oublie pas de faire un flush !
        $this->em->flush();
        
        return $allMailreceivedFilterByUser;
    }
    
    /**
    * On récupère tous les courriers envoyés, filtrés par date, par reception et par contact
    *
    * @param integer $days
    * @param boolean $reception
    * @param integer $id
    */
    public function filtreMailsentByActor($days, $reception, $id)
    {
        // date d'il y a $days jours
        $date = new \Datetime($days.' days ago');

        //On récupère tous les courriers envoyés, filtrés par date, par reception et par contact
        $allMailsentFilterByActor = $this
                             ->em
                             ->getRepository('MailsMailBundle:Mail')
                             ->filterAllMailsentByActor($date, $reception, $id)
                            ;
                            
        // Et on n'oublie pas de faire un flush !
        $this->em->flush();
        
        return $allMailsentFilterByActor;
    }
    
    /**
    * On récupère tous les courriers reçus, filtrés par date, par reception par contact et par traitement
    *
    * @param integer $days
    * @param boolean $reception
    * @param boolean $traitement
    * @param integer $actor
    */
    public function filtreMailreceivedByActor($days, $reception, $actor, $traitement)
    {
        // date d'il y a $days jours
        $date = new \Datetime($days.' days ago');

        //On récupère tous les courriers reçus, filtrés par date, par reception par contact et par traitement
        $allMailreceivedFilterByActor = $this
                                     ->em
                                     ->getRepository('MailsMailBundle:Mail')
                                     ->filterAllMailreceivedByActor($date, $reception, $actor, $traitement)
                                    ;
                            
        // Et on n'oublie pas de faire un flush !
        $this->em->flush();
        
        return $allMailreceivedFilterByActor;
    }
    
    /**
    * On récupère tous les courriers envoyés, filtrés par date et par reception
    *
    * @param integer $days
    * @param boolean $reception
    */
    public function filtreAllMailsent($days, $reception, $expediteur, $destinataire, $page, $nbPerPage)
    {
        // date d'il y a $days jours
        $date = new \Datetime($days.' days ago');

        //On récupère tous les courriers envoyés, filtrés par date et par reception
        $allMailsentFilter = $this
                             ->em
                             ->getRepository('MailsMailBundle:Mail')
                             ->getAllMailsentFilter($date, $reception, $expediteur, $destinataire, $page, $nbPerPage)
                            ;
                            
        // Et on n'oublie pas de faire un flush !
        $this->em->flush();
        
        return $allMailsentFilter;
    }
    
    /**
    * On récupère tous les courriers envoyés, filtrés par date et par reception
    *
    * @param integer $days
    * @param boolean $reception
    */
    public function filtreAllMailreceivedByUser($days, $reception, $expediteur, $destinataire, $traitement, $page, $nbPerPage)
    {
        // date d'il y a $days jours
        $date = new \Datetime($days.' days ago');

        //On récupère tous les courriers envoyés, filtrés par date et par reception
        $allMailreceivedFilter = $this
            ->em
            ->getRepository('MailsMailBundle:Mail')
            ->getAllMailreceivedFilterByUser($date, $reception, $expediteur, $destinataire, $traitement, $page, $nbPerPage)
        ;
                            
        // Et on n'oublie pas de faire un flush !
        $this->em->flush();
        
        return $allMailreceivedFilter;
    }

    /**
    * On récupère tous les courriers envoyés, filtrés par date et par reception
    *
    * @param integer $days
    * @param boolean $reception
    */
    public function filtreAllMailreceived($days, $reception, $expediteur, $traitement, $page, $nbPerPage)
    {
        // date d'il y a $days jours
        $date = new \Datetime($days.' days ago');

        //On récupère tous les courriers envoyés, filtrés par date et par reception
        $allMailreceivedFilter = $this
            ->em
            ->getRepository('MailsMailBundle:Mail')
            ->getAllMailreceivedFilter($date, $reception, $expediteur, $traitement, $page, $nbPerPage)
        ;
                            
        // Et on n'oublie pas de faire un flush !
        $this->em->flush();
        
        return $allMailreceivedFilter;
    }
}
