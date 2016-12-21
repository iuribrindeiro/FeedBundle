<?php
/**
 * Created by Iuri Brindeiro.
 * User: iuri
 * Date: 19/12/16
 * Time: 14:44
 * Email: iuribrindeiro@gmail.com
 */

namespace MauticPlugin\FeedBundle\Entity;


use Doctrine\ORM\Query\Expr\Join;
use Mautic\CoreBundle\Entity\CommonRepository;

class FeedRepository extends CommonRepository
{
    public function getEntities($args = [])
    {
        $q = $this->getEntityManager()
            ->createQueryBuilder()
            ->select($this->getTableAlias())
            ->from('FeedBundle:Feed', $this->getTableAlias(), $this->getTableAlias().'.id');

        $args['qb'] = $q;

        return parent::getEntities($args);
    }

    public function getStatusEmails($args = [])
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('s')
            ->from('MauticEmailBundle:Stat', 's')
            ->innerJoin('FeedBundle:Feed', $this->getTableAlias(), 'WITH',
                $qb->expr()->eq('s.id', $this->getTableAlias() . '.email'))
            ->where($qb->expr()->eq($this->getTableAlias() . '.id', $args['id']));

        $result = $qb->getQuery()->getResult();

        return $result;

    }

    public function getFeeds()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('f')
            ->from('FeedBundle:Feed', 'f');

        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function getFeedsNotSend()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb
            ->select($this->getTableAlias())
            ->from('FeedBundle:Feed', $this->getTableAlias())
            ->where($qb->expr()->isNull($this->getTableAlias() . '.lastSend'));

        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function getTableAlias()
    {
        return 'f';
    }
}