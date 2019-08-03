<?php
require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

const CONNECTION_DEFAULT = 'default';
const CONNECTION_SECOND = 'second';

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'eloquent',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
], CONNECTION_DEFAULT);

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'eloquent2',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
], CONNECTION_SECOND);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

$capsule->getConnection(CONNECTION_DEFAULT)->enableQueryLog();
$capsule->getConnection(CONNECTION_SECOND)->enableQueryLog();

class User extends Illuminate\Database\Eloquent\Model
{
    public $table = "users";
    protected $primaryKey = 'id';
    protected $connection = CONNECTION_DEFAULT;

    protected $fillable = ['name', 'password', 'info'];//разрешено редактировать только это, остальное запрещено
//    protected $guarded = ['id']; //запрещено редактировать только это, все остальное разрешено

    public function posts()
    {
        // users.id == posts.user_id
        return $this->hasMany('Post', 'user_id', 'id');
    }
}

class Post extends Illuminate\Database\Eloquent\Model
{
    protected $connection = CONNECTION_SECOND;

    public function userdata()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

function printLog()
{
    echo '<pre>';
    foreach ([CONNECTION_DEFAULT, CONNECTION_SECOND] as $name) {
        $log = Capsule::connection($name)->getQueryLog();
        foreach ($log as $elem) {
            echo $name . ':' . 0.01 * $elem['time'] . ': ' . $elem['query'] . ' bind: ' . json_encode($elem['bindings']) . '<br>';
        }
    }
    echo '</pre>';
}

include 'menu.php';