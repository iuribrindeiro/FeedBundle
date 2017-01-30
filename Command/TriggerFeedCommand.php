<?php

/**
 * Created by Iuri Brindeiro.
 * User: iuri
 * Date: 20/12/16
 * Time: 18:00
 * Email: iuribrindeiro@gmail.com
 */

namespace MauticPlugin\FeedBundle\Command;

use Mautic\CoreBundle\Command\ModeratedCommand;
use MauticPlugin\FeedBundle\Model\FeedModel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TriggerFeedCommand extends ModeratedCommand
{
    protected function configure()
    {
        $this
            ->setName('mautic:feeds:trigger')
            ->setDescription('Envia novo feed se existir');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        if (!$this->checkRunStatus($input, $output)) {
            return 0;
        }

        /** @var FeedModel $feedModel */
        $feedModel = $container->get('mautic.feed.model.feed');
	$output->writeln('<info>teste 1</info>');	
        $envio = $feedModel->sendFeed();

        if($envio) {
            $output->writeln('<info>Feed enviado com sucesso</info>');
        }elseif($envio === false) {
            $output->writeln('<error>Erro ao enviar o feed</error>');
        }else {
            $output->writeln('<info>Não existem novas noticias</info>');
        }
        try {
            $this->completeRun();
            return 0;
        }catch (\Exception $exception) {
           $output->writeln('<info>teste</info>');
           return 0;
        }
    }
}
