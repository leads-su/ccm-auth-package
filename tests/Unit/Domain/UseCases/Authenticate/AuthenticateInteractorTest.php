<?php namespace ConsulConfigManager\Auth\Test\Unit\Domain\UseCases\Authenticate;

use DomainException;
use ConsulConfigManager\Auth\Test\TestCase;
use ConsulConfigManager\Auth\Services\Authenticator;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Auth\Http\Requests\AuthRequest;
use ConsulConfigManager\Auth\Test\ProvidesUsersRepository;
use ConsulConfigManager\Auth\Domain\UseCases\Authenticate\AuthenticateInputPort;
use ConsulConfigManager\Auth\Domain\UseCases\Authenticate\AuthenticateInteractor;
use ConsulConfigManager\Auth\Domain\UseCases\Authenticate\AuthenticateRequestModel;
use ConsulConfigManager\Auth\Domain\Presenters\Authenticate\AuthenticateHttpPresenter;

/**
 * Class AuthenticateInteractorTest
 *
 * @package ConsulConfigManager\Auth\Test\Unit\Domain\UseCases\Authenticate
 */
class AuthenticateInteractorTest extends TestCase {
    use ProvidesUsersRepository;

    public function testShouldPassIfInitializedCorrectly(): void {
        $this->assertInstanceOf(AuthenticateInputPort::class, $this->createInteractor());
    }

    public function testShouldPassIfViewModelIsReturned(): void {
        $interactor = $this->createInteractor();
        $requestModel = new AuthenticateRequestModel(new AuthRequest([], [
            'emailOrUsername'       =>  'john.doe',
            'password'              =>  'insecurePassword'
        ]));
        $this->assertInstanceOf(ViewModel::class, $interactor->authenticate($requestModel));
    }

    public function testShouldFailIfExceptionIsThrown(): void {
        $interactor = $this->createInteractor();
        $this->expectException(DomainException::class);
        $requestModel = new AuthenticateRequestModel(new AuthRequest());
        $interactor->authenticate($requestModel);
    }

    /**
     * Create interactor instance
     * @return AuthenticateInteractor
     */
    private function createInteractor(): AuthenticateInteractor {
        $presenter = new AuthenticateHttpPresenter();
        $authenticator = new Authenticator($this->bootstrapUsersRepository());
        return new AuthenticateInteractor($presenter, $authenticator);
    }

}