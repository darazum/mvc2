<?php
namespace App\Main\Controller;

use Base\Exception;
use Base\Model\Factory as ModelFactory;
use App\Main\Model\Post as Post;

use Base\View as View;

use GUMP;
use Intervention\Image\ImageManager;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use Carbon\Carbon;

class Index extends \Base\ControllerAbstract
{
    /**
     * @throws Exception
     */
    public function indexAction()
    {
        $users = [];
        $posts = ModelFactory::getList(ModelFactory::MODEL_POST, __METHOD__, 10, [], 'id ASC');
        if ($posts) {
            $userIds = array_map(function(Post $post){ return $post->getUserId(); }, $posts);
            $users = ModelFactory::getByIds(ModelFactory::MODEL_USER, __METHOD__, $userIds);
        }

        $this->view->posts = $posts;
        $this->view->users = $users;

        $this->tpl = 'index.phtml';

    }

    public function sendPostAction()
    {
        $text = $this->p('text');

        $post = new Post();
        $post->initByData([
            'text' => $text,
            'user_id' => $this->USER->getId(),
        ]);

        $post->saveToDb();
        $this->redirect('/');
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

    public function gumpAction()
    {
        $this->noRender();

        $data = [
            'username'    => 'sdfasfsadf',
            'password'    =>  'ывраыаф',
            'email'       =>  'darazum@mail.ru',
            'gender'      =>  'f',
            'credit_card' =>  '52df3 2437 3988 8083',
            'url'         => '://vk.com',
            'ip'          => '123.123.123.25225'
        ];

        $validated = GUMP::is_valid($data, array(
            'username'    => 'required|alpha_numeric|max_len,100|min_len,6',
            'password'    => 'required|max_len,100|min_len,6',
            'email'       => 'required|valid_email',
            'gender'      => 'required|exact_len,1|contains,m f',
            'credit_card' => 'required|valid_cc',
            'ip'          => 'valid_ip',
            'url'         => 'valid_url'
        ));

        if($validated === true) {
            echo "Valid Street Address!";
        } else {
            var_dump($validated);
        }
    }

    /**
     * Изменяет размер картинки
     */
    public function resize()
    {
        ImageManager::make($this->origin)
            ->resize(500, null, function ($image) {
                $image->aspectRatio();
            })
            ->rotate(90)
            ->blur(1)
            ->save($this->result, 80);
        echo 'success';
    }
    /**
     *
     */
    public function watermark()
    {
        putenv('GDFONTPATH=' . realpath(PUBLIC_PATH));
        $image = IImage::make('musya_origin.jpg');
        $image->text(
            'ENGLISH TEXT',
            $image->width() / 2,
            $image->height() / 2,
            function ($font) {
//                $font->file('arial.ttf')->size('224'); //требуется расширение freetype
                $font->color(array(255, 0, 0, 0.5));
                $font->align('center');
                $font->valign('center');
            });
        $image->save('musya.jpg');
        echo 'watermarked';
    }
}