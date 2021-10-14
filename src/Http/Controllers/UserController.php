<?php namespace ConsulConfigManager\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Auth\Domain\UseCases\User\UserInputPort;
use ConsulConfigManager\Auth\Domain\UseCases\User\UserRequestModel;

/**
 * Class UserController
 * @package ConsulConfigManager\Auth\Http\Controllers
 */
class UserController extends Controller {

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
    public function __construct(UserInputPort $interactor) {
        $this->interactor = $interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param Request $request
     *
     * @return Response|null
     */
    public function __invoke(Request $request): ?Response {
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
