<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSubscribesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_subscribe')->insert(
            [
                [
                    'name' => "Влад",
                    'lastname' => "Щетинин",
                    'email' => "vlad.shetinin@gmail.com",
                ],
                [
                    'name' => "Нара",
                    'lastname' => "Дресс",
                    'email' => "nara-dress@yandex.ru",
                ],
                [
                    'name' => "Мит",
                    'lastname' => "Субиши",
                    'email' => "mitsubishi_ev0-9@mail.ru",
                ],
                [
                    'name' => "Влад",
                    'lastname' => "Аутлук",
                    'email' => "vlad.shetinin@outlook.com",
                ],
                [
                    'name' => "Тест",
                    'lastname' => "Щетинин",
                    'email' => "test.shetinin@gmail.com",
                ],

                // несуществующие адреса
                [
                    'name' => "Тест",
                    'lastname' => "Яндексов",
                    'email' => "test@yandex.ru",
                ],
                [
                    'name' => "Тест",
                    'lastname' => "Яндексов2",
                    'email' => "test@yandex2.ru",
                ],
                [
                    'name' => "Тест",
                    'lastname' => "Яндексов3",
                    'email' => "test@yandex3.ru",
                ],
                [
                    'name' => "Тест",
                    'lastname' => "Яндексов4",
                    'email' => "test@yandex4.ru",
                ],
                [
                    'name' => "Тест",
                    'lastname' => "Яндексов5",
                    'email' => "test@yandex5.ru",
                ],
            ]
        );
    }
}
