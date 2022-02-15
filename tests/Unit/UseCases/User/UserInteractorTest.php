<?php

namespace ConsulConfigManager\Auth\Test\Unit\UseCases\User;

use Illuminate\Http\Request;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Users\Factories\UserFactory;
use ConsulConfigManager\Users\Interfaces\UserInterface;
use ConsulConfigManager\Auth\UseCases\User\UserInputPort;
use ConsulConfigManager\Auth\UseCases\User\UserInteractor;
use ConsulConfigManager\Auth\UseCases\User\UserRequestModel;
use ConsulConfigManager\Auth\Presenters\User\UserHttpPresenter;
use ConsulConfigManager\Auth\Test\Unit\UseCases\AbstractUseCaseTest;

/**
 * Class UserInteractorTest
 * @package ConsulConfigManager\Auth\Test\Unit\UseCases\User
 */
class UserInteractorTest extends AbstractUseCaseTest
{
    public function testShouldPassIfInitializedCorrectly(): void
    {
        $this->assertInstanceOf(UserInputPort::class, $this->createInteractor());
    }

    public function testShouldPassIfViewModelIsReturned(): void
    {
        $interactor = $this->createInteractor();
        $requestModel = new UserRequestModel($this->createNewRequest());
        $this->assertInstanceOf(ViewModel::class, $interactor->user($requestModel));
    }

    public function testShouldFailIfExceptionIsThrown(): void
    {
        $interactor = $this->createInteractor();
        $requestModel = new UserRequestModel(new Request());
        $response = $interactor->user($requestModel);
        $this->assertEquals(401, $response->getResponse()->status());
    }

    /**
     * Create interactor instance
     * @return UserInteractor
     */
    private function createInteractor(): UserInteractor
    {
        $presenter = new UserHttpPresenter();
        return new UserInteractor($presenter);
    }

    /**
     * Create new instance of Auth Request
     * @return Request
     */
    private function createNewRequest(): Request
    {
        $request = new Request();
        $request->setUserResolver(function (): UserInterface {
            return UserFactory::new()->make([
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
