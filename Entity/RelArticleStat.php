<?php
/**
 * Created by Iuri Brindeiro.
 * User: iuri
 * Date: 21/12/16
 * Time: 10:23
 * Email: iuribrindeiro@gmail.com
 */

namespace MauticPlugin\FeedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\EmailBundle\Entity\Stat;

class RelArticleStat
{
    /** @var  integer */
    private $id;

    /** @var  Article */
    private $article;

    /** @var  Stat */
    private $stat;

    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('article_stat_xrfe');

        $builder->addId();

        $builder->createManyToOne('article', Article::class)
            ->inversedBy('id')
            ->addJoinColumn('article_id', 'id', false, false, 'CASCADE')
            ->build();

        $builder->createManyToOne('stat', Stat::class)
            ->inversedBy('id')
            ->addJoinColumn('stat_id', 'id', true, false, 'CASCADE')
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
     * @return RelArticleStat
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param Article $article
     * @return RelArticleStat
     */
    public function setArticle($article)
    {
        $this->article = $article;
        return $this;
    }

    /**
     * @return Stat
     */
    public function getStat()
    {
        return $this->stat;
    }

    /**
     * @param Stat $stat
     * @return RelArticleStat
     */
    public function setStat($stat)
    {
        $this->stat = $stat;
        return $this;
    }

}