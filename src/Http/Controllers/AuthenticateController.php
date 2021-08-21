<?php namespace ConsulConfigManager\Auth\Http\Controllers;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Auth\Http\Requests\AuthRequest;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Auth\Domain\UseCases\Authenticate\AuthenticateInputPort;
use ConsulConfigManager\Auth\Domain\UseCases\Authenticate\AuthenticateRequestModel;

/**
 * Class AuthenticateController
 *
 * @package ConsulConfigManager\Auth\Http\Controllers
 */
class AuthenticateController extends Controller {

    /**
     * Authenticate input port interactor instance
     * @var AuthenticateInputPort
     */
    private AuthenticateInputPort $interactor;

    /**
     * AuthenticateController Constructor.
     *
     * @param AuthenticateInputPort $interactor
     */
    public function __construct(AuthenticateInputPort $interactor) {
        $this->interactor = $interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param AuthRequest $request
     *
     * @return Response|null
     */
    public function __invoke(AuthRequest $request): ?Response {
        $viewModel = $this->interactor->authenticate(
            new AuthenticateRequestModel($request)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd

}