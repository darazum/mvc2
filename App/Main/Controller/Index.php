<?php
namespace App\Main\Controller;

use Base\Exception;
use Base\Model\Factory as ModelFactory;
use App\Main\Model\Post as Post;

class Index extends \Base\ControllerUser
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
}