<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use App\Command\DropClickHouseTableCommand;
use App\Command\InitClickHouseTableCommand;
use App\Command\ParseHandler\ParseHandler;
use App\Command\ParseHandler\Middlewares\DTOMapperMiddleware;
use App\Command\ParseHandler\Middlewares\SendRequestsMiddleware;
use App\Command\ParseHandler\Middlewares\RequestBuilderMiddleware;
use App\Command\ParseHandler\ParseRequestFactory;
use App\Command\ParseProductsCommand;
use App\Contracts\ClickHouseClient;
use App\Model\Table\WbProductsClickHouseTable;
use App\Services\WbParser\Repository\WbProductsRepository;
use App\Services\WbParser\Repository\WbProductsRepositoryInterface;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Client;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Eggheads\CakephpClickHouse\ClickHouse;
use Psr\Http\Client\ClientInterface;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication
{
    /**
     * Load all the application configuration and bootstrap logic.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        } else {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false)
            );
        }

        /*
         * Only try to load DebugKit in development mode
         * Debug Kit should not be installed on a production system
         */
        if (Configure::read('debug')) {
            $this->addPlugin('DebugKit');
        }

        // Load more plugins here
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(Configure::read('Error'), $this))

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Add routing middleware.
            // If you have a large number of routes connected, turning on routes
            // caching in production could improve performance.
            // See https://github.com/CakeDC/cakephp-cached-routing
            ->add(new RoutingMiddleware($this))

            // Parse various types of encoded request bodies so that they are
            // available as array through $request->getData()
            // https://book.cakephp.org/4/en/controllers/middleware.html#body-parser-middleware
            ->add(new BodyParserMiddleware())

            // Cross Site Request Forgery (CSRF) Protection Middleware
            // https://book.cakephp.org/4/en/security/csrf.html#cross-site-request-forgery-csrf-middleware
            ->add(new CsrfProtectionMiddleware([
                'httponly' => true,
            ]));

        return $middlewareQueue;
    }

    /**
     * Register application container services.
     *
     * @param \Cake\Core\ContainerInterface $container The Container to update.
     * @return void
     * @link https://book.cakephp.org/4/en/development/dependency-injection.html#dependency-injection
     */
    public function services(ContainerInterface $container): void
    {
        $container->add(ClientInterface::class, Client::class);
        $container->add(ClickHouseClient::class, ClickHouse::getInstance()->getClient());
        $container->add(ParseRequestFactory::class);
        $container->add(WbProductsRepositoryInterface::class, WbProductsRepository::class)
            ->addArgument(WbProductsClickHouseTable::getInstance());
        $container->add(ParseHandler::class)
            ->addArgument(WbProductsRepositoryInterface::class);
        $container->add(RequestBuilderMiddleware::class);
        $container->add(SendRequestsMiddleware::class)
            ->addArgument(ClientInterface::class);
        $container->add(DTOMapperMiddleware::class);

        $container->add(InitClickHouseTableCommand::class)
            ->addArgument($container->get(ClickHouseClient::class));
        $container->add(DropClickHouseTableCommand::class)
            ->addArgument($container->get(ClickHouseClient::class));
        $container->add(ParseProductsCommand::class)
            ->addArgument($container->get(ParseHandler::class))
            ->addArgument([
                $container->get(RequestBuilderMiddleware::class),
                $container->get(SendRequestsMiddleware::class),
                $container->get(DTOMapperMiddleware::class),
            ]);
    }

    /**
     * Bootstrapping for CLI application.
     *
     * That is when running commands.
     *
     * @return void
     */
    protected function bootstrapCli(): void
    {
        $this->addOptionalPlugin('Bake');

        $this->addPlugin('Migrations');

        // Load more plugins here
    }
}
