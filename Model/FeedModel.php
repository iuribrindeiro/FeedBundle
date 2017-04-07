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
use MauticPlugin\FeedBundle\Entity\UnsubscribedLead;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class FeedModel extends CommonFormModel
{
    /** @var  ListModel */
    private $leadListModel;

    /** @var  FormModel */
    private $formModel;

    /** @var  EmailModel */
    private $emailModel;

    private $engine;

    /** @var  FeedRepository */
    private $repository;

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

    public function getArticlesByIdFeed($idFeed)
    {
        /** @var ArticleRepository $repoAticle */
        $repoAticle = $this->em->getRepository(Article::class);

        $articles = $repoAticle->getArticlesByFeed($idFeed);

        return $articles;
    }

    /**
     * @return FeedRepository
     */
    public function getRepository()
    {
        if (!$this->repository) {
            $this->repository = $this->em->getRepository('FeedBundle:Feed');
        }
        return $this->repository;
    }

    public function sendEmailFeed(array $objFeeds)
    {
        $newArticles = null;

        /** @var ArticleRepository $articleRepo */
        $articleRepo = $this->getArticleRepository();

        /** @var Feed[] $objFeeds */
        foreach($objFeeds as $objFeed) {
            $feedIo = Factory::create()->getFeedIo();
            $onlyOne = false;
            if ($objFeed->getLastSend()) {
                $feeds = $feedIo->readSince($objFeed->getUrlFeed(), $objFeed->getLastSend());
                $countItens = iterator_count($feeds->getFeed());
                if ($countItens <= 2 && $countItens !== 0) {
                    $olderDate = date_sub($objFeed->getLastSend(), new \DateInterval('P30D'));
                    $olderFeeds = $feedIo->readSince($objFeed->getUrlFeed(), $olderDate);
                    foreach ($olderFeeds->getFeed() as $itemFeed) {
                        $feeds->getFeed()->add($itemFeed);
                        if (iterator_count($feeds->getFeed())== 3) {
                            break;
                        }
                    }
                }
            } else {
                $onlyOne = true;
                $feeds = $feedIo->read($objFeed->getUrlFeed());
            }

            /** @var \FeedIo\Feed $feed */
            foreach ($feeds->getFeed() as $key => $feed) {
                /** @var Email $email */
                $email = $objFeed->getEmail();
                $email->setSubject($feed->getTitle());

                $objArticles[] = (new Article())->setTitle($feed->getTitle())
                                    ->setFeed($objFeed)->setDateSent($feed->getLastModified())
                                    ->setUrl($feed->getLink());

                foreach($objFeed->getLeadLists() as $leadList) {
                    /** @var ListLead $lead */
                    foreach($leadList->getLeads() as $lead) {
                        /** @var Lead $objLead */
                        $objLead = $lead->getLead();
                        $unsubLead = $this->em->getRepository(UnsubscribedLead::class)->findOneBy(['lead' => $objLead, 'feed' => $objFeed]);
                        if (!$unsubLead) {
                            $id = $objLead->getId();
                            $fields = $this->em->getRepository(Lead::class)->getFieldValues($objLead->getId());
                            $objLead->setFields($fields);
                            $emailLead = $objLead->getEmail();
                            $firstname = $objLead->getName();
                            $dadosLeads[$id] = ['id' => $id, 'email' => $emailLead, 'firstname' => $firstname, 'lastname' => ''];
                        }
                    }
                }
                $feedsToSendEmail[] = $feed;

                if($onlyOne) {
                    break;
                }
            }

            if(isset($email) && isset($dadosLeads) && isset($objArticles)) {
                $newArticles = true;

                if ($objFeed->getLogoEmail()) {
                    $nameLogoEmail = $objFeed->getLogoEmail();
                    $objFeed->setLogoEmail(new File("media/images/" . $objFeed->getLogoEmail()));
                }

                $email->setCustomHtml($this->engine->render('FeedBundle:Feed:email-feed.html.php',
                    ['feeds' => $feedsToSendEmail, 'objFeed' => $objFeed]));

                $this->emailModel->sendEmail($email, $dadosLeads, ['sendBatchMail' => true]);

                $objStats = $articleRepo->getNewStats($objFeed->getEmail()->getId());

                if (isset($nameLogoEmail)) {
                    $objFeed->setLogoEmail($nameLogoEmail);
                }

                if(count($objStats) > 0) {
                    foreach($objArticles as $article) {
                        $article->setStats($objStats);
                        $this->saveEntity($article);
                        $objFeed->getArticles()->add($article);
                        $objFeed->setLastSend(new \DateTime());
                        $this->saveEntity($objFeed);

                    }
                }
                unset($email); unset($dadosLeads); unset($objArticles);
            }
        }

        return $newArticles;
    }

    public function sendFeed()
    {
        $feeds = $this->getRepository()->getFeeds();

        try {
            if(count($feeds) > 0) {
                $result = $this->sendEmailFeed($feeds);
                return $result;
            }
            return null;
        } catch (\Exception $ex) {
		print $ex->getMessage();
            $this->logger->addError(
                $ex->getMessage(),
                ['exception' => $ex]
            );
            return false;
        }
    }

    public function leadSubscribedToFeed($lead, $feed)
    {
        if (!$lead instanceof Lead) {
            $lead = $this->em->getRepository(Lead::class)
                ->findOneBy(['id' => $lead]);
        }

        if (!$feed instanceof Feed) {
            $feed = $this->getEntity($feed);
        }

        if ($feed && $lead) {
            return $this->getRepository()->leadSubscribedToFeed($lead, $feed);
        }

        return null;
    }

    public function unsubscribeLead($feedId, $leadId)
    {
        $objFeed = $this->getEntity($feedId);
        /** @var Lead|null $objLead */
        $objLead = $this->em->getRepository(Lead::class)->findOneBy(['id' => $leadId]);

        if ($objLead && $objFeed) {
            $objUnsubscribed = $this->em->getRepository(UnsubscribedLead::class)
                ->findOneBy(['feed' => $objFeed, 'lead' => $objLead]);
            if (!$objUnsubscribed) {
                $this->em->persist(
                    (new UnsubscribedLead())
                        ->setFeed($objFeed)
                        ->setLead($objLead)
                );
                $this->em->flush();
            }
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
