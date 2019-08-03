<?php
namespace App\Ext\Controller;

use Base\Exception;
use GUMP;
use Intervention\Image\ImageManagerStatic as Image;

class Two extends \Base\ControllerAbstract
{

    protected static $_imagePath;

    public function preAction()
    {
        parent::preAction();
        $this->noRender();
        self::$_imagePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'images/';
    }

    /**
     * @throws Exception
     */
    public function indexAction()
    {
        $this->redirect('/ext/two/gump');
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
    public function imageAction()
    {
        $source = self::$_imagePath . 'ava.jpg';
        $result = self::$_imagePath . 'ava_new.jpg';
        $image = Image::make($source)
            ->resize(null, 500, function ($image) {
                $image->aspectRatio();
            })
            //->rotate(45)
            ->blur(1)
            ->crop(200, 250)
            //->invert()
            //->fit(400, 100)
            ->save($result, 80);

        //$image->save($result, 80);
        //echo 'success';

        self::watermark($image);

        echo $image->response('png');
    }

    /**
     * Наносит watermark
     */
    public static function watermark(\Intervention\Image\Image $image)
    {
        $image->text(
            "Язык тролля\nТюсседал\nНорвегия",
            5,
            15,
            function ($font) {
                $font->file(self::$_imagePath . 'arial.ttf')->size('24'); //требуется расширение freetype
                $font->color(array(255, 0, 0, 0.5));
                $font->align('left');
                $font->valign('top');
            });
    }
}