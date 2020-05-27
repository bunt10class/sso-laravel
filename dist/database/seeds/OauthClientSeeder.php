<?php

use Illuminate\Database\Seeder;
use Laravel\Passport\ClientRepository;

class OauthClientSeeder extends Seeder
{
    /**
     * создание auth клиента для авторизации в другом приложении
     * @return void
     */
    public function run()
    {
        /** @var Laravel\Passport\ClientRepository $oauthClientRepo */
        $oauthClientRepo = app(ClientRepository::class);
        $oauthClientRepo->create(
            null,
            config('sso.application_name'),
            config('sso.application_host') . config('sso.routes.callback')
        );
    }
}
