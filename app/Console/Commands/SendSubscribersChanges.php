<?php

namespace App\Console\Commands;

use App\UserSubscribe;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class SendSubscribersChanges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-subscribers-changes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'SendSubscribersChanges';

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
        $user_subscribes = UserSubscribe::where('sync_date', '>', 0)->get();
        if(count($user_subscribes) == 0) {
            abort(422, 'Нет подписчиков в базе данных');
        }

        $members = [];
        if(count($user_subscribes) > 0) {
            foreach ($user_subscribes as $subscribe) {
                $members[] = [
                    "email_address" => $subscribe->email,
                    "status" => $subscribe->is_subscribed == true ? "subscribed" : "unsubscribed",
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
            "members" => $members,
            "update_existing" => true
        ]);

        UserSubscribe::whereIn('id', Arr::pluck($user_subscribes, 'id', 'id'))->update(['sync_date' => date('Y-m-d H:i:s')]);

        print_r($response);
    }
}
