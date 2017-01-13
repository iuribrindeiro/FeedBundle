<?php
/**
 * Created by Iuri Brindeiro.
 * User: iuri
 * Date: 21/12/16
 * Time: 08:21
 * Email: iuribrindeiro@gmail.com
 */

namespace MauticPlugin\FeedBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\EmailBundle\Entity\Stat;

class Article
{
    /** @var  integer */
    private $id;

    /** @var  string */
    private $title;

    /** @var  ArrayCollection */
    private $stats;

    /** @var  Feed */
    private $feed;

    /** @var  \DateTime */
    private $dateSent;

    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('articles')
            ->setCustomRepositoryClass(ArticleRepository::class);

        $builder->addId();

        $builder->addNamedField('title', 'string', 'title');

        $builder->addNamedField('dateSent', 'datetime', 'date_sent');

        $builder->createManyToMany('stats', Stat::class)
            ->setJoinTable('article_stat_xrfe')
            ->setIndexBy('id')
            ->addInverseJoinColumn('stat_id', 'id', true, false, 'CASCADE')
            ->addJoinColumn('article_id', 'id', false, false)
            ->build();

        $builder->createManyToOne('feed', Feed::class)
            ->inversedBy('feed')
            ->addJoinColumn('feed_id', 'id', false, false, 'CASCADE')
            ->build();
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
     * @return Article
     */
    public function setFeed($feed)
    {
        $this->feed = $feed;
        return $this;
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
     * @return Article
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     * @param ArrayCollection $stats
     * @return Article
     */
    public function setStats($stats)
    {
        $this->stats = $stats;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateSent()
    {
        return $this->dateSent;
    }

    /**
     * @param \DateTime $dateSent
     * @return Article
     */
    public function setDateSent($dateSent)
    {
        $this->dateSent = $dateSent;
        return $this;
    }
}