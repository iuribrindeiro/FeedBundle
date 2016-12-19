<?php

/**
 * Created by Iuri Brindeiro.
 * User: iuri
 * Date: 19/12/16
 * Time: 14:46
 * Email: iuribrindeiro@gmail.com
 */

namespace MauticPlugin\FeedBundle\Model;

use Mautic\CoreBundle\Model\FormModel as CommonFormModel;
use Mautic\FormBundle\Model\FormModel;
use Mautic\LeadBundle\Model\ListModel;
use MauticPlugin\FeedBundle\Entity\Feed;
use MauticPlugin\FeedBundle\Entity\FeedRepository;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class FeedModel extends CommonFormModel
{
    /** @var  ListModel */
    private $leadListModel;

    /** @var  FormModel */
    private $formModel;


    public function __construct(ListModel $leadListModel, FormModel $formModel)
    {
        $this->formModel = $formModel;
        $this->leadListModel = $leadListModel;
    }

    /**
     * @return FeedRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('FeedBundle:Feed');
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
}