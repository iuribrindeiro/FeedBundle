<?php
/**
 * Created by Iuri Brindeiro.
 * User: iuri
 * Date: 21/12/16
 * Time: 09:12
 * Email: iuribrindeiro@gmail.com
 */

namespace MauticPlugin\FeedBundle\Entity;


use Mautic\CoreBundle\Entity\CommonRepository;
use Mautic\EmailBundle\Entity\Stat;

class ArticleRepository extends CommonRepository 
{
    public function getNewStats($emailId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('s')
            ->from('MauticEmailBundle:Stat', 's')
            ->innerJoin('MauticLeadBundle:Lead', 'l', 'WITH',
                's.lead = l.id')
            ->innerJoin('MauticLeadBundle:ListLead', 'll', 'WITH',
                'll.lead = l.id')
            ->innerJoin('FeedBundle:RelFeedLeadList', 'fll', 'WITH',
                'fll.leadList = ll.list')
            ->innerJoin('MauticEmailBundle:Email', 'e', 'WITH',
                'e.id = s.email')
            ->leftJoin('FeedBundle:RelArticleStat', 'ras', 'WITH',
                'ras.stat = s.id')
            ->where($qb->expr()->eq('e.id', ':emailId'))
            ->andWhere($qb->expr()->isNull('ras.stat'))
            ->setParameter('emailId', $emailId);

        $result = $qb->getQuery()->getResult();


        return $result;
    }
}