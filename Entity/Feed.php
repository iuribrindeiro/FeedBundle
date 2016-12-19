<?php

namespace MauticPlugin\FeedBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\EmailBundle\Entity\Email;
use Mautic\LeadBundle\Entity\LeadList;

class Feed
{
    private $id;

    private $name;

    private $urlFeed;

    /** @var  ArrayCollection */
    private $leadLists;

    /** @var  Email */
    private $email;

    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('feed')
            ->setCustomRepositoryClass(FeedRepository::class);

        $builder->addId();

        $builder->addNamedField('name', 'string', 'name');
        $builder->addNamedField('urlFeed', 'string', 'url_feed');

        $builder->createManyToMany('leadLists', LeadList::class)
            ->setJoinTable('feed_lead_list_xrfe')
            ->setIndexBy('id')
            ->addInverseJoinColumn('leadlist_id', 'id', false, false, 'CASCADE')
            ->addJoinColumn('feed_id', 'id', false, false, 'CASCADE')
            ->build();

        $builder->createManyToOne('email', Email::class)
            ->mappedBy('id')
            ->addJoinColumn('email_id', 'id', false, false)
            ->cascadeRemove()
            ->build();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Feed
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Feed
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrlFeed()
    {
        return $this->urlFeed;
    }

    /**
     * @param mixed $urlFeed
     * @return Feed
     */
    public function setUrlFeed($urlFeed)
    {
        $this->urlFeed = $urlFeed;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return Feed
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getLeadLists()
    {
        return $this->leadLists;
    }

    /**
     * @param ArrayCollection $leadLists
     * @return Feed
     */
    public function setLeadLists($leadLists)
    {
        $this->leadLists = $leadLists;
        return $this;
    }
}