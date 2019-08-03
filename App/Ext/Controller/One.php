<?php
namespace App\Ext\Controller;

use Base\Exception;
use Base\Model\Factory as ModelFactory;
use App\Main\Model\Post as Post;

use Base\View as View;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use Carbon\Carbon;

class One extends \Base\ControllerAbstract
{
    /**
     * @throws Exception
     */
    public function indexAction()
    {
        $this->redirect('/ext/one/twig');
    }

    public function twigAction()
    {
        $this->view->setRenderType(View::RENDER_TYPE_TWIG);
        $this->view->users = [
            ['name' => 'Petja1'],
            ['name' => 'Petja2'],
            ['name' => 'Petja3']
        ];
        $this->view->var = '<b>Ololo</b>';
        $this->tpl = 'index.twig';
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
            $fileSystem->copy('my_super_dir/test.txt', 'my_super_dir/ololo.txt');
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