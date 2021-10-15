<?php namespace ConsulConfigManager\Auth\Test\Unit\Domain\UseCases\User;

use DomainException;
use Illuminate\Http\Request;
use ConsulConfigManager\Auth\Test\TestCase;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Auth\Test\ProvidesUsersRepository;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;
use ConsulConfigManager\Auth\Domain\UseCases\User\UserInputPort;
use ConsulConfigManager\Auth\Domain\UseCases\User\UserInteractor;
use ConsulConfigManager\Auth\Domain\UseCases\User\UserRequestModel;
use ConsulConfigManager\Auth\Domain\Presenters\User\UserHttpPresenter;

/**
 * Class UserInteractorTest
 * @package ConsulConfigManager\Auth\Test\Unit\Domain\UseCases\User
 */
class UserInteractorTest extends TestCase {
    use ProvidesUsersRepository;

    public function testShouldPassIfInitializedCorrectly(): void {
        $this->assertInstanceOf(UserInputPort::class, $this->createInteractor());
    }

    public function testShouldPassIfViewModelIsReturned(): void {
        $interactor = $this->createInteractor();
        $requestModel = new UserRequestModel($this->createNewRequest());
        $this->assertInstanceOf(ViewModel::class, $interactor->user($requestModel));
    }

    public function testShouldFailIfExceptionIsThrown(): void {
        $interactor = $this->createInteractor();
        $requestModel = new UserRequestModel(new Request());
        $response = $interactor->user($requestModel);
        $this->assertEquals(401, $response->getResponse()->status());
    }

    /**
     * Create interactor instance
     * @return UserInteractor
     */
    private function createInteractor(): UserInteractor {
        $presenter = new UserHttpPresenter();
        return new UserInteractor($presenter);
    }

    /**
     * Create new instance of Auth Request
     * @return Request
     */
    private function createNewRequest(): Request {
        $request = new Request();
        $request->setUserResolver(function(): UserEntity {
            return $this->createUserEntityFromArray([
                'id'            =>  1,
                'guid'          =>  'some-random-guid-string-1',
                'domain'        =>  'company',
                'first_name'    =>  'John',
                'last_name'     =>  'Doe',
                'username'      =>  'john.doe',
                'email'         =>  'john.doe@example.com',
            ]);
        });
        return $request;
    }

}
