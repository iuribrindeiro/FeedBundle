<?php
/**
 * Created by Iuri Brindeiro.
 * User: iuri
 * Date: 19/12/16
 * Time: 14:44
 * Email: iuribrindeiro@gmail.com
 */

namespace MauticPlugin\FeedBundle\Entity;

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

    public function getTableAlias()
    {
        return 'f';
    }
}