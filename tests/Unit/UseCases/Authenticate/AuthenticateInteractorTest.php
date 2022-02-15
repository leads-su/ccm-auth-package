<?php

namespace ConsulConfigManager\Auth\Test\Unit\UseCases\Authenticate;

use DomainException;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Auth\Http\Requests\AuthRequest;
use ConsulConfigManager\Auth\Test\Unit\UseCases\AbstractUseCaseTest;
use ConsulConfigManager\Auth\UseCases\Authenticate\AuthenticateInputPort;
use ConsulConfigManager\Auth\UseCases\Authenticate\AuthenticateInteractor;
use ConsulConfigManager\Auth\UseCases\Authenticate\AuthenticateRequestModel;
use ConsulConfigManager\Auth\Presenters\Authenticate\AuthenticateHttpPresenter;

/**
 * Class AuthenticateInteractorTest
 *
 * @package ConsulConfigManager\Auth\Test\Unit\UseCases\Authenticate
 */
class AuthenticateInteractorTest extends AbstractUseCaseTest
{
    /**
     * @return void
     */
    public function testShouldPassIfInitializedCorrectly(): void
    {
        $this->assertInstanceOf(AuthenticateInputPort::class, $this->createInteractor());
    }

    /**
     * @return void
     */
    public function testShouldPassIfViewModelIsReturned(): void
    {
        $interactor = $this->createInteractor();
        $requestModel = new AuthenticateRequestModel(new AuthRequest([], [
            'emailOrUsername'       =>  'john.doe',
            'password'              =>  'insecurePassword',
        ]));
        $this->assertInstanceOf(ViewModel::class, $interactor->authenticate($requestModel));
    }

    /**
     * @return void
     */
    public function testShouldFailIfExceptionIsThrown(): void
    {
        $interactor = $this->createInteractor();
        $this->expectException(DomainException::class);
        $requestModel = new AuthenticateRequestModel(new AuthRequest());
        $interactor->authenticate($requestModel);
    }

    /**
     * Create interactor instance
     * @return AuthenticateInteractor
     */
    private function createInteractor(): AuthenticateInteractor
    {
        $presenter = new AuthenticateHttpPresenter();
        $authenticator = $this->authenticator();
        return new AuthenticateInteractor($presenter, $authenticator);
    }
}
