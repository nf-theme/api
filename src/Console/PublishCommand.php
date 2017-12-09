<?php

namespace NightFury\RestApi\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PublishCommand extends Command
{
    protected function configure()
    {
        $this->setName('restapi:publish')
            ->setDescription('Publish configuration for nf/restapi');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!is_dir('routes')) {
            mkdir('routes', 0755);
        }
        if (!file_exists('routes/api.php')) {
            copy('vendor/nf/restapi/resources/routes/api.php', 'routes/api.php');
        }

        if (!is_dir('app/Http')) {
            mkdir('app/Http', 0755);
        }

        if (!is_dir('app/Http/Controllers')) {
            mkdir('app/Http/Controllers', 0755);
        }
        if (!file_exists('app/Http/Controllers/TestController')) {
            copy('vendor/nf/restapi/resources/Http/Controllers/TestController.php', 'app/Http/Controllers/TestController.php');
        }
    }
}
