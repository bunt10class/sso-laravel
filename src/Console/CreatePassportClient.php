<?php

namespace Edu\Sso\Console;

use Illuminate\Console\Command;
use Laravel\Passport\ClientRepository;

class CreatePassportClient extends Command
{
    protected $signature = 'passport:sso_client';

    protected $description = 'Создание пассорт клиента для межпроектной авторизации';

    public function handle(ClientRepository $oauthClientRepo)
    {
        $client = $oauthClientRepo->create(
            null,
            config('sso.app_name'),
            config('sso.app_redirect_endpoint'),
        );

        $this->info('Client data for ' . config('sso.app_name') . ':');
        $this->info('id: ' . $client->id);
        $this->info('secret: ' . $client->secret);
    }
}
