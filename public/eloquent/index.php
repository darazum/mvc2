<?php
include 'init.php';

// получаем пользователей и их посты (неправильный вариант)
//$users = User::query()->limit(10)->get();
//foreach ($users as $user) {
//    $posts = $user->posts;
//}

// получим всех юзеров и все их посты (правильный вариант)
// $data = User::with('posts')->limit(10)->get();


// получим юзеров старше 18 лет
//$users = User::query()->where('age', '>', 18)->get();
//
//$data = User::with('posts')
//    ->where('id', 12)
//    ->first() // в дате сразу будет объект юзера с постами
//    //->get()   // в дате будет массив, а в 0м элементе объект юзера
//    ->toArray();
//var_dump($data);


// добавим нового пользователя
$data = [
    'name' => $_POST['name'] ?? 'dimasss',
    'password' => $_POST['password'] ?? '123',
    'info' => $_POST['info'] ?? '',
];
// $user = User::create($data);
//$user = User::firstOrCreate($data);
//var_dump($user->id);
// $user = User::firstOrNew($data);
// var_dump($user->id);

// обновим существующего
//$user = User::find(34); //User::update($array);
//$user->name = 'Vasja123';
//$user->password = 456;
//$user->save();
//
// ** удалим пользователя
//$user = User::find(32); //User::update($array);
//$user->delete();
User::destroy(32); //DELETE where id 123
//
printLog();