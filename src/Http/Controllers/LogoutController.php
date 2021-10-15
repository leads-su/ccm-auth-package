<?php namespace ConsulConfigManager\Auth\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Auth\Exceptions\ExceptionHandler;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Auth\Domain\UseCases\Logout\LogoutInputPort;
use ConsulConfigManager\Auth\Domain\UseCases\Logout\LogoutRequestModel;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;

/**
 * Class LogoutController
 * @package ConsulConfigManager\Auth\Http\Controllers
 */
class LogoutController extends Controller {

    /**
     * Logout input port instance
     * @var LogoutInputPort
     */
    private LogoutInputPort $interactor;

    /**
     * LogoutController constructor.
     * @param LogoutInputPort $interactor
     * @return void
     */
    public function __construct(LogoutInputPort $interactor) {
        $this->interactor = $interactor;
        App::singleton(
            ExceptionHandlerContract::class,
            ExceptionHandler::class
        );
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param Request $request
     *
     * @return Response|null
     * @throws Throwable
     */
    public function __invoke(Request $request): ?Response {
        $viewModel = $this->interactor->logout(
            new LogoutRequestModel($request)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd

}
