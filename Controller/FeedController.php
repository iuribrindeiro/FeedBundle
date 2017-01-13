<?php

/**
 * Created by Iuri Brindeiro.
 * User: iuri
 * Date: 19/12/16
 * Time: 14:40
 * Email: iuribrindeiro@gmail.com
 */

namespace MauticPlugin\FeedBundle\Controller;

use Doctrine\ORM\EntityManager;
use Mautic\CoreBundle\Controller\FormController;
use Mautic\EmailBundle\Entity\Stat;
use Mautic\EmailBundle\Model\EmailModel;
use Mautic\PageBundle\Entity\Hit;
use MauticPlugin\FeedBundle\Entity\Article;
use MauticPlugin\FeedBundle\Entity\Feed;
use MauticPlugin\FeedBundle\Model\FeedModel;
use Symfony\Component\Form\FormError;

class FeedController extends FormController
{
    public function indexAction($page = 1)
    {
        /** @var FeedModel $model */
        $model   = $this->getModel('feed');
        $session = $this->get('session');

        //set some permissions
        $permissions = $this->get('mautic.security')->isGranted(
            [
                'feed:feeds:view',
                'feed:feeds:create',
                'feed:feeds:edit',
                'feed:feeds:delete',
                'feed:feeds:publish',

            ],
            'RETURN_ARRAY'
        );

        if (!$permissions['feed:feeds:view']) {
            return $this->accessDenied();
        }

        if ($this->request->getMethod() == 'POST') {
            $this->setListFilters();
        }

        //set limits
        $limit = $session->get('mautic.feed.limit', $this->get('mautic.helper.core_parameters')->getParameter('default_pagelimit'));
        $start = ($page === 1) ? 0 : (($page - 1) * $limit);
        if ($start < 0) {
            $start = 0;
        }

        $search = $this->request->get('search', $session->get('mautic.feed.filter', ''));
        $session->set('mautic.feed.filter', $search);

        $filter = ['string' => $search, 'force' => []];

        $currentFilters = $session->get('mautic.feed.list_filters', []);
        $updatedFilters = $this->request->get('filters', false);

        $sourceLists = $model->getSourceLists();
        $listFilters = [
            'filters' => [
                'placeholder' => 'Segmentos',
                'multiple'    => true,
                'groups'      => [
                    'mautic.feed.leadsource.form' => [
                        'options' => $sourceLists['forms'],
                        'prefix'  => 'form',
                    ],
                    'mautic.feed.leadsource.list' => [
                        'options' => $sourceLists['lists'],
                        'prefix'  => 'list',
                    ],
                ],
            ],
        ];

        if ($updatedFilters) {
            // Filters have been updated

            // Parse the selected values
            $newFilters     = [];
            $updatedFilters = json_decode($updatedFilters, true);

            if ($updatedFilters) {
                foreach ($updatedFilters as $updatedFilter) {
                    list($clmn, $fltr) = explode(':', $updatedFilter);

                    $newFilters[$clmn][] = $fltr;
                }

                $currentFilters = $newFilters;
            } else {
                $currentFilters = [];
            }
        }
        $session->set('mautic.feed.list_filters', $currentFilters);

        if (!empty($currentFilters)) {
            $listIds = $catIds = [];
            foreach ($currentFilters as $type => $typeFilters) {
                $listFilters['filters'] ['groups']['mautic.feed.leadsource.'.$type]['values'] = $typeFilters;

                foreach ($typeFilters as $fltr) {
                    if ($type == 'list') {
                        $listIds[] = (int) $fltr;
                    } else {
                        $formIds[] = (int) $fltr;
                    }
                }
            }
        }

        $orderBy    = $session->get('mautic.feed.orderby', 'f.name');
        $orderByDir = $session->get('mautic.feed.orderbydir', 'ASC');

        $campaigns = $model->getEntities(
            [
                'start'      => $start,
                'limit'      => $limit,
                'filter'     => $filter,
                'orderBy'    => $orderBy,
                'orderByDir' => $orderByDir,
            ]
        );

        $count = count($campaigns);
        if ($count && $count < ($start + 1)) {
            //the number of entities are now less then the current page so redirect to the last page
            if ($count === 1) {
                $lastPage = 1;
            } else {
                $lastPage = (ceil($count / $limit)) ?: 1;
            }
            $session->set('mautic.feed.page', $lastPage);
            $returnUrl = $this->generateUrl('mautic_feed_index', ['page' => $lastPage]);

            return $this->postActionRedirect(
                [
                    'returnUrl'       => $returnUrl,
                    'viewParameters'  => ['page' => $lastPage],
                    'contentTemplate' => 'FeedBundle:Feed:index',
                    'passthroughVars' => [
                        'activeLink'    => '#mautic_feed_index',
                        'mauticContent' => 'feed',
                    ],
                ]
            );
        }

        //set what page currently on so that we can return here after form submission/cancellation
        $session->set('mautic.feed.page', $page);

        $tmpl = $this->request->isXmlHttpRequest() ? $this->request->get('tmpl', 'index') : 'index';

        return $this->delegateView(
            [
                'viewParameters' => [
                    'searchValue' => $search,
                    'items'       => $campaigns,
                    'page'        => $page,
                    'limit'       => $limit,
                    'permissions' => $permissions,
                    'tmpl'        => $tmpl,
                    'filters'     => $listFilters,
                ],
                'contentTemplate' => 'FeedBundle:Feed:list.html.php',
                'passthroughVars' => [
                    'activeLink'    => '#mautic_feed_index',
                    'mauticContent' => 'feed',
                    'route'         => $this->generateUrl('mautic_feed_index', ['page' => $page]),
                ],
            ]
        );
    }

    public function newAction()
    {
        /** @var FeedModel $model */
        $model = $this->getModel('feed');

        $entity  = $model->getEntity();

        if (!$this->get('mautic.security')->isGranted('campaign:campaigns:create')) {
            return $this->accessDenied();
        }

        //set the page we came from
        $page = $this->get('session')->get('mautic.feed.page', 1);

        //setup the form
        $action = $this->generateUrl('mautic_feed_action', ['objectAction' => 'new']);
        $form   = $model->createForm($entity, $this->get('form.factory'), $action);

        $feedSegments = $this->request->request->get('feed')['leadLists'];

        ///Check for a submitted form and process it
        if ($this->request->getMethod() == 'POST') {
            $valid = false;
            if (!$cancelled = $this->isFormCancelled($form)) {
                if ($valid = $this->isFormValid($form)) {
                    //make sure that at least one segment is selected
                    if (empty($feedSegments)) {
                        //set the error
                        $form->addError(
                            new FormError(
                                'Selecione pelo menos um Segmento'
                            )
                        );
                        $valid = false;
                    } else {
                        // Persist to the database before building connection so that IDs are available
                        $model->saveEntity($entity);

                        $this->addFlash(
                            'mautic.core.notice.created',
                            [
                                '%name%'      => $entity->getName(),
                                '%menu_link%' => 'mautic_feed_index',
                                '%url%'       => $this->generateUrl(
                                    'mautic_feed_action',
                                    [
                                        'objectAction' => 'edit',
                                        'objectId'     => $entity->getId(),
                                    ]
                                ),
                            ]
                        );

                        if ($form->get('buttons')->get('save')->isClicked()) {
                            $viewParameters = [
                                'objectAction' => 'view',
                                'objectId'     => $entity->getId(),
                            ];
                            $returnUrl = $this->generateUrl('mautic_feed_action', $viewParameters);
                            $template  = 'FeedBundle:Campaign:view';
                        } else {
                            //return edit view so that all the session stuff is loaded
                            return $this->editAction($entity->getId(), true);
                        }
                    }
                }
            }else {
                $viewParameters = ['page' => $page];
                $returnUrl      = $this->generateUrl('mautic_feed_index', $viewParameters);
                $template       = 'FeedBundle:Feed:index';
            }

            if ($cancelled || ($valid && $form->get('buttons')->get('save')->isClicked())) {

                return $this->postActionRedirect(
                    [
                        'returnUrl'       => $returnUrl,
                        'viewParameters'  => $viewParameters,
                        'contentTemplate' => $template,
                        'passthroughVars' => [
                            'activeLink'    => '#mautic_campaign_index',
                            'mauticContent' => 'campaign',
                        ],
                    ]
                );
            }
        }

        return $this->delegateView(
            [
                'viewParameters' => [
                    'tmpl'            => $this->request->isXmlHttpRequest() ? $this->request->get('tmpl', 'index') : 'index',
                    'entity'          => $entity,
                    'form'            => $form->createView(),
                ],
                'contentTemplate' => 'FeedBundle:Feed:form.html.php',
                'passthroughVars' => [
                    'activeLink'    => '#mautic_feed_index',
                    'mauticContent' => 'feed',
                    'route'         => $this->generateUrl(
                        'mautic_feed_action',
                        [
                            'objectAction' => (!empty($valid) ? 'edit' : 'new'), //valid means a new form was applied
                            'objectId'     => $entity->getId(),
                        ]
                    ),
                ],
            ]
        );
    }

    public function viewAction($objectId)
    {
        $page = $this->get('session')->get('mautic.feed.page', 1);

        /** @var FeedModel $model */
        $model = $this->getModel('feed');

        $security = $this->get('mautic.security');
        /** @var Feed $entity */
        $entity   = $model->getRepository()->getFeedOrderByDateSent($objectId);


        /** @var Article $article */
        foreach($entity->getArticles() as $article) {
            $articleDetails[$article->getId()]['emailsEnviados'] = count($article->getStats());
            $countEmailsLids = 0;
            $countEmailsClicados = 0;
            /** @var Stat $stat */
            foreach($article->getStats() as $stat) {
                if(true) {
                    $hits[$stat->getLead()->getId()]['hit'] = $stat->getOpenDetails();
                    $countEmailsClicados++;
                }

                if($stat->isRead()) {
                    $countEmailsLids++;
                }
            }
            $articleDetails[$article->getId()]['emailsLidos'] = $countEmailsLids;
            $articleDetails[$article->getId()]['emailsClicados'] = $countEmailsClicados;
        }

        $permissions = $security->isGranted(
            [
                'campaign:campaigns:view',
                'campaign:campaigns:create',
                'campaign:campaigns:edit',
                'campaign:campaigns:delete',
                'campaign:campaigns:publish',
            ],
            'RETURN_ARRAY'
        );

        if ($entity === null) {
            //set the return URL
            $returnUrl = $this->generateUrl('mautic_feed_index', ['page' => $page]);

            return $this->postActionRedirect(
                [
                    'returnUrl'       => $returnUrl,
                    'viewParameters'  => ['page' => $page],
                    'contentTemplate' => 'FeedBundle:Feed:index',
                    'passthroughVars' => [
                        'activeLink'    => '#mautic_feed_index',
                        'mauticContent' => 'feed',
                    ],
                    'flashes' => [
                        [
                            'type'    => 'error',
                            'msg'     => 'mautic.feed.error.notfound',
                            'msgVars' => ['%id%' => $objectId],
                        ],
                    ],
                ]
            );
        } elseif (!$permissions['campaign:campaigns:view']) {
            return $this->accessDenied();
        }

        // Init the date range filter form
        $dateRangeValues = $this->request->get('daterange', []);
        $action          = $this->generateUrl('mautic_feed_action', ['objectAction' => 'view', 'objectId' => $objectId]);
        $dateRangeForm   = $this->get('form.factory')->create('daterange', $dateRangeValues, ['action' => $action]);

        return $this->delegateView(
            [
                'viewParameters' => [
                    'feed'      => $entity,
                    'hits' => isset($hits) ? $hits : null,
                    'articleDetails' => isset($articleDetails) ? $articleDetails : null,
                    'permissions'   => $permissions,
                    'security'      => $security,
                    'dateRangeForm' => $dateRangeForm->createView(),
                ],
                'contentTemplate' => 'FeedBundle:Feed:details.html.php',
                'passthroughVars' => [
                    'activeLink'    => '#mautic_feed_index',
                    'mauticContent' => 'feed',
                    'route'         => $this->generateUrl(
                        'mautic_feed_action',
                        [
                            'objectAction' => 'view',
                            'objectId'     => $entity->getId(),
                        ]
                    ),
                ],
            ]
        );
    }

    public function deleteAction($objectId)
    {
        /** @var FeedModel $model */
        $model = $this->getModel('feed');
        $entity = $model->getEntity($objectId);

        if($entity) {
            $model->deleteEntity($entity);
        }
    }
}