<?php

namespace App\Main\Controller;

use Base\Context as Context;
use Base\View as View;
use Symfony\Component\DomCrawler\Crawler;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use Carbon\Carbon;

class Storages extends \Base\ControllerAbstract
{
    public function indexAction()
    {
        echo 'Мы здесь:<br>';

        $context = Context::getInstance();
        echo 'Запрошенный модуль: ' . $context->getRequest()->getRequestModule() . '<br>';
        echo 'Запрошенный контроллер: ' . $context->getRequest()->getRequestController() . '<br>';
        echo 'Запрошенный экшен: ' . $context->getRequest()->getRequestAction() . '<br><br><br>';
        echo '<br>';
        echo 'Вызванный модуль: ' . $context->getDispatcher()->getModuleName() . '<br>';
        echo 'Вызванный контроллер: ' . $context->getDispatcher()->getControllerName() . '<br>';
        echo 'Вызванный экшен: ' . $context->getDispatcher()->getActionName() . '<br><br><br>';

        $this->view->setRenderType(View::RENDER_TYPE_TWIG);
        $this->view->users = [
            ['name' => 'Petja1'],
            ['name' => 'Petja2'],
            ['name' => 'Petja3']
        ];
        $this->view->var = '<b>Ololo</b>';
    }

    public function parserAction()
    {
        $this->noRender();

//        $html = file_get_contents("../kinopoisk.html");
//        $crawler = new Crawler($html);
//        $parsed = $crawler->filterXPath('//table/tr');
//        // $crawler->filter('.class .subclass'); // jquery style with php 7.2 and crawler 4.2
//        echo '<pre>';
//        foreach ($parsed as $item) {
//            /** @var DOMElement $item */
//            $a = $item->getElementsByTagName('a')[1];
//            if ($a) {
//                echo $a->getAttribute('href') . '<br>';
//            }
//        }

        $html = file_get_contents('https://www.anekdot.ru/');
        $crawler = new Crawler($html);
        $parsed = $crawler->filter('.texts .text');
        /** @var \DOMElement $item */
        foreach ($parsed as $item) {
            echo $item->textContent . '<br>';
        }
    }

    public function fsAction()
    {
        $this->noRender();

        $fileSystem = new Filesystem();

        try {
            $fileSystem->mkdir('my_super_dir');
            $fileSystem->touch('my_super_dir/test.txt');
            var_dump($fileSystem->exists('my_super_dir'));
            var_dump($fileSystem->exists('my_super_dir/ololo.txt'));
            $fileSystem->copy('my_super_dir/test.txt','my_super_dir/ololo.txt');
            $fileSystem->remove('my_super_dir');
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while creating your directory at ".$exception->getPath();
        }
    }

    public function carbonAction()
    {
        $this->noRender();

        $oldTime = Carbon::now();
        $oldTime->subDay(3)->subHour(3)->subMinute(13);
        $curTime = Carbon::now();

        Carbon::setLocale('ru');

        echo $oldTime->diffForHumans($curTime, null, false, 3) . '<br>';

        $futureTime = Carbon::parse(date('Y-m-d H:i:s', time() + 86400 + 2*3600 + 156));
        echo $futureTime->diffForHumans($curTime, true, false, 4);

    }
}