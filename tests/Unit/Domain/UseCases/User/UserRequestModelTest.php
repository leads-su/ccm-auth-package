<?php namespace ConsulConfigManager\Auth\Test\Unit\Domain\UseCases\User;

use Illuminate\Http\Request;
use ConsulConfigManager\Auth\Test\TestCase;
use ConsulConfigManager\Auth\Test\ProvidesUsersRepository;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;
use ConsulConfigManager\Auth\Domain\UseCases\User\UserRequestModel;

/**
 * Class UserRequestModelTest
 * @package ConsulConfigManager\Auth\Test\Unit\Domain\UseCases\User
 */
class UserRequestModelTest extends TestCase {
    use ProvidesUsersRepository;

    public function testShouldPassIfInitializedCorrectly(): void {
        $this->assertInstanceOf(UserRequestModel::class, new UserRequestModel($this->createNewRequest()));
    }

    public function testShouldPassIfInstanceofRequestIsReturned(): void {
        $instance = new UserRequestModel($this->createNewRequest());
        $this->assertInstanceOf(Request::class, $instance->getRequest());
    }

    public function testShouldPassIfUserExistsOnRequest(): void {
        $instance = new UserRequestModel($this->createNewRequest());
        $this->assertNotNull($instance->getUser());
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
