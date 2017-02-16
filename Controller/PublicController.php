<?php
/**
 * Created by Iuri Brindeiro.
 * User: iuri
 * Date: 13/02/17
 * Time: 19:14
 * Email: iuribrindeiro@gmail.com
 */

namespace MauticPlugin\FeedBundle\Controller;


use Mautic\CoreBundle\Controller\FormController;
use Mautic\LeadBundle\Model\LeadModel;
use MauticPlugin\FeedBundle\Model\FeedModel;
use Symfony\Component\HttpFoundation\File\File;

class PublicController extends FormController
{
    public function indexAction($feedId = 0)
    {
        /** @var FeedModel $feedModel */
        $feedModel = $this->getModel('feed');
        /** @var LeadModel $leadModel */
        $leadModel = $this->getModel('lead');
        $lead = $leadModel->getEntity(23);

        $url = $this->generateUrl(
            'mautic_public_unsubscribe',
            [
                'leadId' => $lead->getId(),
                'feedId' => $feedId
            ]
        );

        $feed = $feedModel->getEntity($feedId);

        if ($feed->getLogoEmail()) {
            //$feed->setLogoEmail(new File($this->getParameter('mautic.image_path') . '/' . $feed->getLogoEmail()));
        }


        if ($feed && $lead) {
            if ($feedModel->leadSubscribedToFeed($lead, $feed)) {
                return $this->delegateView([
                    'contentTemplate' => 'FeedBundle:Public:index.html.php',
                    'viewParameters' => [
                        'feed' => $feed,
                        'lead' => $lead,
                        'url'  => $url
                    ]
                ]);
            }
        }

        return $this->notFound();
    }

    public function unsubscribeAction($feedId = 0)
    {
        /** @var LeadModel $leadModel */
        $leadModel = $this->getModel('lead');

        $lead = $leadModel->getCurrentLead();
        /** @var FeedModel $feedModel */
        $feedModel = $this->getModel('feed');

        if ($lead) {
            $feedModel->unsubscribeLead($feedId, $lead->getId());
        }

        return $this->redirect($feedModel->getEntity($feedId)->getUrlSite());
    }
}