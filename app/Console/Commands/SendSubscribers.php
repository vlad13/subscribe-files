<?php

namespace App\Console\Commands;

use App\UserSubscribe;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class SendSubscribers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-subscribers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'SendSubscribers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user_subscribes = UserSubscribe::where('sync_date', null)->get();
        if(count($user_subscribes) == 0) {
             abort(422, 'Нет подписчиков в базе данных');
        }

        $members = [];
        if(count($user_subscribes) > 0) {
            foreach ($user_subscribes as $subscribe) {
                $members[] = [
                    "email_address" => $subscribe->email,
                    "status" => $subscribe->is_subscribed == true ? "subscribed" : "unsubscribed",
                    "merge_fields" => [
                        "FNAME" => $subscribe->name,
                        "LNAME" => $subscribe->lastname,
                    ],
                ];
            }
        }


        $mailchimp = new \MailchimpMarketing\ApiClient();
        $mailchimp->setConfig([
            'apiKey' => env('MAILCHIMP_APIKEY'),
            'server' => env('MAILCHIMP_SERVER')
        ]);
        $list_id = env('MAILCHIMP_LIST_ID');

        $response = $mailchimp->lists->batchListMembers($list_id, [
            "members" => $members
        ]);

        UserSubscribe::whereIn('id', Arr::pluck($user_subscribes, 'id', 'id'))->update(['sync_date' => date('Y-m-d H:i:s')]);

        print_r($response);
    }
}
