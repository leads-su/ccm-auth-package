<?php namespace ConsulConfigManager\Auth\Domain\Presenters\User;

use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Auth\Domain\UseCases\User\UserOutputPort;
use ConsulConfigManager\Auth\Domain\UseCases\User\UserResponseModel;

/**
 * Class UserHttpPresenter
 * @package ConsulConfigManager\Auth\Domain\Presenters\User
 */
class UserHttpPresenter implements UserOutputPort {

    /**
     * @inheritDoc
     */
    public function userDetails(UserResponseModel $userResponseModel): ViewModel {
        $user = $userResponseModel->getUser();
        return new HttpResponseViewModel(response_success(
            $user->append(['scopes', 'role']),
            '',
            Response::HTTP_OK
        ));
    }

    /**
     * @inheritDoc
     */
    public function userNotAuthenticated(UserResponseModel $userResponseModel): ViewModel {
        return new HttpResponseViewModel(response_error(
            [],
            'Not authenticated to access this route',
            Response::HTTP_FORBIDDEN
        ));
    }
}
