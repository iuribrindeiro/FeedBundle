<?php

/**
 * Created by Iuri Brindeiro.
 * User: iuri
 * Date: 19/12/16
 * Time: 14:46
 * Email: iuribrindeiro@gmail.com
 */

namespace MauticPlugin\FeedBundle\Model;

use FeedIo\Factory;
use Mautic\CoreBundle\Model\FormModel as CommonFormModel;
use Mautic\EmailBundle\Entity\Email;
use Mautic\EmailBundle\Model\EmailModel;
use Mautic\FormBundle\Model\FormModel;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\LeadBundle\Entity\ListLead;
use Mautic\LeadBundle\Model\ListModel;
use MauticPlugin\FeedBundle\Entity\Article;
use MauticPlugin\FeedBundle\Entity\ArticleRepository;
use MauticPlugin\FeedBundle\Entity\Feed;
use MauticPlugin\FeedBundle\Entity\FeedRepository;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Templating\PhpEngine;

class FeedModel extends CommonFormModel
{
    /** @var  ListModel */
    private $leadListModel;

    /** @var  FormModel */
    private $formModel;

    /** @var  EmailModel */
    private $emailModel;

    private $engine;

    public function __construct(ListModel $leadListModel, FormModel $formModel, EmailModel $emailModel, $engine)
    {
        $this->formModel = $formModel;
        $this->leadListModel = $leadListModel;
        $this->emailModel = $emailModel;
        $this->engine = $engine;
    }

    public function getArticleRepository()
    {
        return $this->em->getRepository('FeedBundle:Article');
    }

    /**
     * @return FeedRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('FeedBundle:Feed');
    }

    public function sendEmailFeed(array $objFeeds)
    {
        $feedIo = Factory::create()->getFeedIo();
        $newArticles = null;

        /** @var ArticleRepository $articleRepo */
        $articleRepo = $this->getArticleRepository();

        /** @var Feed[] $objFeeds */
        foreach($objFeeds as $objFeed) {
            $onlyOne = false;
            if (!$objFeed->getLastSend()) {
                $feeds = $feedIo->readSince($objFeed->getUrlFeed(), $objFeed->getLastSend());
            } else {
                $onlyOne = true;
                $feeds = $feedIo->read($objFeed->getUrlFeed())->getFeed();
            }

            foreach ($feeds as $key => $feed) {
                $newArticles = true;
                /** @var Email $email */
                $email = $objFeed->getEmail();
                $email->setSubject($feed->getTitle());

                $objArticles[] = (new Article())->setTitle($feed->getTitle())->setFeed($objFeed);

                foreach($objFeed->getLeadLists() as $leadList) {
                    /** @var ListLead $lead */
                    foreach($leadList->getLeads() as $lead) {
                        /** @var Lead $objLead */
                        $objLead = $lead->getLead();
                        $id = $objLead->getId();
                        $fields = $this->em->getRepository(Lead::class)->getFieldValues($objLead->getId());
                        $objLead->setFields($fields);
                        $emailLead = $objLead->getEmail();
                        $firstname = $objLead->getName();
                        $dadosLeads[$id] = ['id' => $id, 'email' => $emailLead, 'firstname' => $firstname, 'lastname' => ''];
                    }
                }
                $feedsToSendEmail[] = $feed;
                if($onlyOne) {
                    $onlyOne = false;
                    continue;
                }

                if(!$onlyOne) {
                    break;
                }
            }

            if(isset($email) && isset($dadosLeads) && isset($objArticles)) {
                $email->setCustomHtml($this->engine->render('FeedBundle:Feed:email-feed.html.php', ['feeds' => $feedsToSendEmail]));
                $this->emailModel->sendEmail($email, $dadosLeads, ['ignoreDNC' => true, 'sendBatchMail' => true]);

                $objStats = $articleRepo->getNewStats($objFeed->getEmail()->getId());

                if(count($objStats) > 0) {
                    foreach($objArticles as $article) {
                        $article->setStats($objStats);
                        $this->saveEntity($article);
                        $objFeed->getArticles()->add($article);
                        $objFeed->setLastSend(new \DateTime());
                        $this->saveEntity($objFeed);

                    }
                }
            }
        }

        return $newArticles;
    }

    public function sendFeed()
    {
        $objFeedsNeverSent = $this->getRepository()->getFeedsNotSend();

        $objFeeds = $this->getRepository()->getFeeds();

        try {
            $noticiasEnviadas = false;
            if(count($objFeeds) > 0) {
                $result = $this->sendEmailFeed($objFeeds);
                $noticiasEnviadas = $result;
            }
            if(count($objFeedsNeverSent) > 0) {
                $result = $this->sendEmailFeed($objFeeds);
                if(!$noticiasEnviadas) {
                    return $result;
                }
            }
            return $noticiasEnviadas;
        } catch (\Exception $ex) {
            $this->logger->addError(
                $ex->getMessage(),
                ['exception' => $ex]
            );
            return false;
        }
    }

    /**
     * Get a list of source choices.
     *
     * @param $sourceType
     *
     * @return array
     */
    public function getSourceLists($sourceType = null)
    {
        $choices = [];
        switch ($sourceType) {
            case 'lists':
            case null:
                $choices['lists'] = [];

                $lists = (empty($options['global_only'])) ? $this->leadListModel->getUserLists() : $this->leadListModel->getGlobalLists();

                foreach ($lists as $list) {
                    $choices['lists'][$list['id']] = $list['name'];
                }

            case 'forms':
            case null:
                $choices['forms'] = [];

                $viewOther = $this->security->isGranted('form:forms:viewother');
                $repo      = $this->formModel->getRepository();
                $repo->setCurrentUser($this->userHelper->getUser());

                $forms = $repo->getFormList('', 0, 0, $viewOther, 'feed');
                foreach ($forms as $form) {
                    $choices['forms'][$form['id']] = $form['name'];
                }
        }

        foreach ($choices as &$typeChoices) {
            asort($typeChoices);
        }

        return ($sourceType == null) ? $choices : $choices[$sourceType];
    }

    /**
     * {@inheritdoc}
     *
     * @param       $entity
     * @param       $formFactory
     * @param null  $action
     * @param array $options
     *
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function createForm($entity, $formFactory, $action = null, $options = [])
    {
        if (!$entity instanceof Feed) {
            throw new MethodNotAllowedHttpException(['Feed']);
        }

        if (!empty($action)) {
            $options['action'] = $action;
        }

        return $formFactory->create('feed', $entity, $options);
    }

    public function getEntity($id = null)
    {
        if ($id === null) {
            return new Feed();
        }

        $entity = parent::getEntity($id);

        return $entity;
    }
}