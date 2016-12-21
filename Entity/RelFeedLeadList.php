<?php
/**
 * Created by Iuri Brindeiro.
 * User: iuri
 * Date: 21/12/16
 * Time: 09:59
 * Email: iuribrindeiro@gmail.com
 */

namespace MauticPlugin\FeedBundle\Entity;


use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\LeadBundle\Entity\LeadList;
use Doctrine\ORM\Mapping as ORM;

class RelFeedLeadList
{
    /** @var  integer */
    private $id;

    /** @var  LeadList */
    private $leadList;

    /** @var  Feed */
    private $feed;

    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('feed_lead_list_xrfe');

        $builder->addId();

        $builder->createManyToOne('leadList', LeadList::class)
            ->inversedBy('id')
            ->addJoinColumn('leadlist_id', 'id', false, false, 'CASCADE')
            ->build();

        $builder->createManyToOne('feed', Feed::class)
            ->inversedBy('id')
            ->addJoinColumn('leadlist_id', 'id', false, false, 'CASCADE')
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
     * @return RelFeedLeadList
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return LeadList
     */
    public function getLeadList()
    {
        return $this->leadList;
    }

    /**
     * @param LeadList $leadList
     * @return RelFeedLeadList
     */
    public function setLeadList($leadList)
    {
        $this->leadList = $leadList;
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
     * @return RelFeedLeadList
     */
    public function setFeed($feed)
    {
        $this->feed = $feed;
        return $this;
    }
}