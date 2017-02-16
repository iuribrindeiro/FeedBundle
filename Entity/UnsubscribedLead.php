<?php
/**
 * Created by Iuri Brindeiro.
 * User: iuri
 * Date: 13/02/17
 * Time: 16:59
 * Email: iuribrindeiro@gmail.com
 */

namespace MauticPlugin\FeedBundle\Entity;


use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\LeadBundle\Entity\Lead;
use Doctrine\ORM\Mapping as ORM;

class UnsubscribedLead
{
    /** @var  integer */
    private $id;

    /** @var  Lead */
    private $lead;

    /** @var  Feed */
    private $feed;


    public function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('unsubscribed_leads');

        $builder->addId();

        $builder->createManyToOne('lead', Lead::class)
            ->mappedBy('id')
            ->addJoinColumn('lead_id', 'id', false, false)
            ->build();

        $builder->createManyToOne('feed', Feed::class)
            ->mappedBy('id')
            ->addJoinColumn('feed_id', 'id', false, false)
            ->build();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return UnsubsCribedLead
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Lead
     */
    public function getLead()
    {
        return $this->lead;
    }

    /**
     * @param Lead $lead
     * @return UnsubsCribedLead
     */
    public function setLead($lead)
    {
        $this->lead = $lead;
        return $this;
    }

    /**
     * @return Feed
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * @param Feed $feed
     * @return UnsubsCribedLead
     */
    public function setFeed($feed)
    {
        $this->feed = $feed;
        return $this;
    }
}