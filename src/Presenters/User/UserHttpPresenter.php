<?php

namespace ConsulConfigManager\Auth\Presenters\User;

use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Auth\UseCases\User\UserOutputPort;
use ConsulConfigManager\Auth\UseCases\User\UserResponseModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;

/**
 * Class UserHttpPresenter
 * @package ConsulConfigManager\Auth\Presenters\User
 */
class UserHttpPresenter implements UserOutputPort
{
    /**
     * @inheritDoc
     */
    public function userDetails(UserResponseModel $userResponseModel): ViewModel
    {
        $user = $userResponseModel->getUser();
        return new HttpResponseViewModel(response_success(
            $user->append(['scopes', 'role']),
            '',
            Response::HTTP_OK
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function userNotAuthenticated(UserResponseModel $userResponseModel): ViewModel
    {
        return new HttpResponseViewModel(response_error(
            [],
            'Not authenticated to access this route',
            Response::HTTP_UNAUTHORIZED
        ));
    }
    // @codeCoverageIgnoreEnd
}
