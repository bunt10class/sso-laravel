###Библиотека для межпроектной аутентификации пользователей

Библиотека должна быть установлена на оба приложения для нормальной работы аутентификации.

####Порядок установки и настройки

######Установить пакет командой:

    composer require edu-platform/lms-sso

######Добавить переменные окружения в файл ".env":

(здесь и далее: sso-приложение - приложение, с которым будет происходить аутентификация)
- SSO_ENABLED=true - включение/выключения функции межпроектной аутентификации;
- SSO_APP_NAME=sso_app_name - название sso-приложения;
- SSO_APP_REDIRECT_ENDPOINT=http://sso_app.xx/sso/app_name/callback - endpoint, по которому sso-приложение возвращается после получения разрешения от данного приложения;
- SSO_APP_HOST=http://lms.backend.xx - адрес sso-приложения;
- SSO_URI_GET_LOGGED_USER=api/v1/sso/me - путь, по которому sso-приложение отдает авторизованного пользователя данному приложению.

######Опубликовать конфиг из пакета командой:

    php artisan vendor:publish --provider="Edu\Sso\Providers\SsoServiceProvider" --tag="config"

######Добавить маршрут в "routes/api.php"
По данному маршруту sso-приложение должно получить авторизованного пользователя. На маршрут должна быть навешена api мидлвара. Пример маршрута:
	
	Route::get('v1/sso/me', 'AuthController@me')->middleware(['api']);

Если uri маршрута отличается от примера, то необходимо изменить параметр 'uri_give_logged_user' в файле "config/sso" на свой (учитывая, что это api маршрут в начале нужно добавить: 'api/', перед которым не нужно указывать слэш).

######Создать репозиторий для поиска и создания пользователя
Данный класс-репозиторий должен реализовывать интерфейс Edu\Sso\Interfaces\UserRepositoryInterface и соответственно содержать три функции:
* поиск пользователя по id;
* поиск пользователя по email;
* создание и получение пользователя.

Если расположение данного класса отличается от прописанного в "config/sso" в параметре 'user_repository', массива 'classes', заменить его на актуальный.

######Создать сервис для аутентификации
Данный класс-сервис должен реализовывать интерфейс Edu\Sso\Interfaces\AuthServiceInterface и соответственно содержать две функции:
* получение Illuminate\Http\RedirectResponse объекта для перехода на домашнюю страницу приложения;
* аутентификация пользователя в приложении.

Если расположение данного класса отличается от прописанного в "config/sso" в параметре 'auth_service', массива 'classes', заменить его на актуальный.

######Добавить ограничение на доступ к межпроектной аутентификации
Если нет необходимости ограничивать кого-то из пользователей возможностью межпроектной аутентификациии, то отредактируйте массив 'access_middleware' в "config/sso" следующим образом:
    
    'access_middleware' => [
        'class' => '',
        'enable_to' => '',
    ],

Если ограничение нужно, то в параметр 'class' этого массива необходимо передать расположение класса-мидлвары, с методом handle, который принимает три параметра в такой последовательности: 
* \Illuminate\Http\Request;
* \Closure;
* mixed - параметр, какой сущности доступно действие.

Соответственно в параметр 'enable_to' массива 'access_middleware' нужно прописать объект (строка, число и т.д.) или массив объектов (в зависимости от того, что обрабатывает ваша мидлвара) для которого(ых) будет доступна межпроектная аутентификация.

#####Создать и прописать клиента
Выполнить команду создания клиента:

    php artisan passport:sso_client

В результате выполнения команды будут получены параметры идентификатора и секретного ключа созданного клиента. Их нужно прописать в файле ".env" sso-приложения:
- SSO_CLIENT_ID=
- SSO_CLIENT_SECRET=

Аналогично выполнить эту команду в sso-приложении и прописать полученные параметры созданного клиента в файле ".env" данного приложения.

######Расширить конфиг файл "config/services" следующим:

    return [
        ...
        
        'laravelpassport' => [
            'host' => env('SSO_APP_HOST', 'http://sso_app.xx'),
            'client_id' => env('SSO_CLIENT_ID', 0),
            'client_secret' => env('SSO_CLIENT_SECRET', 'secret'),
            'redirect' => env('APP_URL', 'http://app.xx') . '/sso/' . env('SSO_APP_NAME', 'sso_app_name') . '/callback',
            'userinfo_uri' => env('SSO_URI_GET_LOGGED_USER', 'api/v1/sso/me'),
        ],
    ];

######Настроить "app/Http/Kernel"
В группу web добавить мидлвару "\Edu\Sso\Http\Middleware\CheckSsoRoutes":

	protected $middlewareGroups = [
        'web' => [
            ...
            \Edu\Sso\Http\Middleware\CheckSsoRoutes::class,
        ],
	    ...
	];
	
Если данное приложение не использует oauth аутентификацию с помощью Bearer token, то необходимо:
- чтобы данная мидлвара сработала до аутентификации, дать приоритет данной мидлваре выше чем у "\App\Http\Middleware\Authenticate":


    protected $middlewarePriority = [
        ...
        \Edu\Sso\Http\Middleware\CheckSsoRoutes::class,
        \App\Http\Middleware\Authenticate::class,
        ...
    ];
      
- чтобы socialite библиотека sso-приложения могла аутентифицироваться и получить пользователя, добавить мидлвару "\Edu\Sso\Http\Middleware\LoginUserByBearer" в группу api до аутентификации в приложении стандартным способом:


    protected $middlewareGroups = [
        ...
        'api' => [
            \Edu\Sso\Http\Middleware\LoginUserByBearer::class,
            ...
        ],
        ...
    ];

Если данное приложение не использует сессии, необходимо
- добавить новую мидлвар группу:


    protected $middlewareGroups = [
        ...
        'sessions' => [
            \Illuminate\Session\Middleware\StartSession::class,
        ],
	];
	
- добавить приоритет для мидлвары "\App\Http\Middleware\EncryptCookies":


    protected $middlewarePriority = [
        ...
        \App\Http\Middleware\EncryptCookies::class,
    ];
		
- навесить созданную мидлвар группу на маршрут аутентификации пользователя в приложении стандартным способом, например:


	Route::post('/login', 'UserController@login')->middleware('sessions');
	
######Правка файла "config/app"
Если приложение уже использует библиотеку socialite и ее провайдер прописан в 'providers' файла "config/app", то необходимо удалить или закомментировать эту строку и вместо нее прописать:

    'providers' => [
    //    Laravel\Socialite\SocialiteServiceProvider::class,
        \SocialiteProviders\Manager\ServiceProvider::class,
		