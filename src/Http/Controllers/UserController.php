<?php

namespace ConsulConfigManager\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Auth\Exceptions\ExceptionHandler;
use ConsulConfigManager\Auth\UseCases\User\UserInputPort;
use ConsulConfigManager\Auth\UseCases\User\UserRequestModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;

/**
 * Class UserController
 * @package ConsulConfigManager\Auth\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * User input port interactor instance
     * @var UserInputPort
     */
    private UserInputPort $interactor;

    /**
     * UserController Constructor.
     *
     * @param UserInputPort $interactor
     */
    public function __construct(UserInputPort $interactor)
    {
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
     */
    public function __invoke(Request $request): ?Response
    {
        $viewModel = $this->interactor->user(
            new UserRequestModel($request)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
