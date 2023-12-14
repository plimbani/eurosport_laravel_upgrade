<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    */

    'name' => 'Eurosport',

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application DOMAIN
    |--------------------------------------------------------------------------
    |
    */

    'domain' => env('APP_DOMAIN', 'www.localhost.com'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'Europe/London',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log settings for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Settings: "single", "daily", "syslog", "errorlog"
    |
    */

    'app_scheme' => env('APP_SCHEME', ''),

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Foundation\Providers\ArtisanServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
        Laracasts\Flash\FlashServiceProvider::class,
        Tymon\JWTAuth\Providers\JWTAuthServiceProvider::class,
        Dingo\Api\Provider\LaravelServiceProvider::class,
        //  Duro85\Roles\RolesServiceProvider::class,
       // Maatwebsite\Excel\ExcelServiceProvider::class,
        Intervention\Image\ImageServiceProvider::class,
        Geocoder\Laravel\Providers\GeocoderService::class,
        LaravelFCM\FCMServiceProvider::class,
       // HipsterJazzbo\Landlord\LandlordServiceProvider::class,
        Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider::class,
        Laracasts\Utilities\JavaScript\JavaScriptServiceProvider::class,
        Mariuzzo\LaravelJsLocalization\LaravelJsLocalizationServiceProvider::class,

        /*
         *  Custom Service Providers
         */
        App\Providers\UserServiceProvider::class,
        App\Providers\TeamServiceProvider::class,
        App\Providers\AgeGroupServiceProvider::class,
        App\Providers\RefereeServiceProvider::class,
        App\Providers\MatchServiceProvider::class,
        App\Providers\TournamentServiceProvider::class,
        App\Providers\ApiServiceProvider::class,
        App\Providers\PitchServiceProvider::class,
        Barryvdh\Snappy\ServiceProvider::class,

        /*
         *  Api Service Providers
         */
        App\Api\Providers\UserServiceProvider::class,
        App\Api\Providers\TeamServiceProvider::class,
        App\Api\Providers\AgeGroupServiceProvider::class,
        App\Api\Providers\RefereeServiceProvider::class,
        App\Api\Providers\MatchServiceProvider::class,
        App\Api\Providers\TournamentServiceProvider::class,
        App\Api\Providers\PitchServiceProvider::class,
        App\Api\Providers\RoleServiceProvider::class,
        App\Api\Providers\VenueServiceProvider::class,
        App\Api\Providers\WebsiteServiceProvider::class,
        App\Api\Providers\HomeServiceProvider::class,
        App\Api\Providers\StayServiceProvider::class,
        App\Api\Providers\VisitorServiceProvider::class,
        App\Api\Providers\WebsiteTournamentServiceProvider::class,
        App\Api\Providers\WebsiteTeamServiceProvider::class,
        App\Api\Providers\ProgramServiceProvider::class,
        App\Api\Providers\ConfigServiceProvider::class,
        App\Api\Providers\MediaServiceProvider::class,
        App\Api\Providers\WebsiteVenueServiceProvider::class,
        App\Api\Providers\ContactServiceProvider::class,
        App\Api\Providers\UploadMediaServiceProvider::class,
        App\Providers\ComposerServiceProvider::class,
        Collective\Html\HtmlServiceProvider::class,
        Spatie\UrlSigner\Laravel\UrlSignerServiceProvider::class,
        App\Api\Providers\TemplateServiceProvider::class,
        Laravel\Socialite\SocialiteServiceProvider::class,
        VerumConsilium\Browsershot\BrowsershotServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'JavaScript' => Laracasts\Utilities\JavaScript::class,
       // 'Landlord' => HipsterJazzbo\Landlord\Facades\Landlord::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'LaravelLocalization' => Mcamara\LaravelLocalization\Facades\LaravelLocalization::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'JWTAuth' => Tymon\JWTAuth\Facades\JWTAuth::class,
       // 'Excel' => Maatwebsite\Excel\Facades\Excel::class,
        'Image' => Intervention\Image\Facades\Image::class,
     //   'Geotools' => Toin0u\Geotools\Facade\Geotools::class,
        'PDF' => Barryvdh\Snappy\Facades\SnappyPdf::class,
        'FCM' => LaravelFCM\Facades\FCM::class,
        'FCMGroup' => LaravelFCM\Facades\FCMGroup::class, // Optional
        'Form' => Collective\Html\FormFacade::class,
        'Html' => Collective\Html\HtmlFacade::class,
        'UrlSigner' => Spatie\UrlSigner\Laravel\UrlSignerFacade::class,
        'Uuid' => Webpatser\Uuid\Uuid::class,
        'Socialite' => Laravel\Socialite\Facades\Socialite::class,
        'GuzzleHttpClient' => GuzzleHttp\Client::class,
    ],

];
