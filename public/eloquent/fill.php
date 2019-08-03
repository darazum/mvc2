<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.08.2019
 * Time: 22:38
 */
include 'init.php';

$faker = Faker\Factory::create('ru_RU');

for($i=0;$i<30;$i++)
{
    $user = new User();
    $user->name = $faker->name;
    $user->password = $faker->password;
    $user->info = $faker->text;
    $user->created_at = $faker->dateTime;
    $user->age = mt_rand(18, 45);
    $user->save();

}

$users = User::all();

foreach ($users as $user) {
    for($i=0;$i<5;$i++) {
        $post = new Post();
        $post->title = $faker->title;
        $post->content = $faker->realText();
        $post->user_id = $user->id;
        $post->save();
    }
}

printLog();