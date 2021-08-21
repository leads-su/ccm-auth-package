<?php namespace ConsulConfigManager\Auth\Test\Unit\Domain\Presenters\Authenticate;

use Exception;
use Illuminate\Support\Arr;
use ConsulConfigManager\Auth\Test\TestCase;
use ConsulConfigManager\Auth\Test\ProvidesUsersRepository;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Auth\Domain\UseCases\Authenticate\AuthenticateResponseModel;
use ConsulConfigManager\Auth\Domain\Presenters\Authenticate\AuthenticateHttpPresenter;

/**
 * Class AuthenticateHttpPresenterTest
 *
 * @package ConsulConfigManager\Auth\Test\Unit\Domain\Presenters
 */
class AuthenticateHttpPresenterTest extends TestCase {
    use ProvidesUsersRepository;

    /**
     * @param array $data
     * @dataProvider userDataProvider
     */
    public function testShouldReturnValidViewModelOnAuthenticated(array $data): void {
        $responseModel = new AuthenticateResponseModel($this->createUserEntityFromArray($data));
        $viewModel = $this->createPresenter()->userAuthenticated($responseModel);

        $this->assertInstanceOf(HttpResponseViewModel::class, $viewModel);

        $response = json_decode($viewModel->getResponse()->getContent(), true);

        $this->assertEquals('Successfully authenticated user', Arr::get($response, 'message'));
    }

    /**
     * @param array $data
     * @dataProvider userDataProvider
     */
    public function testShouldReturnValidViewModelOnInvalidCredentials(array $data): void {
        $responseModel = new AuthenticateResponseModel();
        $viewModel = $this->createPresenter()->invalidCredentials($responseModel);

        $this->assertInstanceOf(HttpResponseViewModel::class, $viewModel);

        $response = json_decode($viewModel->getResponse()->getContent(), true);

        $this->assertEquals('Invalid credentials specified', Arr::get($response, 'message'));
    }

    /**
     * @param array $data
     * @dataProvider userDataProvider
     */
    public function testShouldReturnValidViewModelOnUnableToAuthenticate(array $data): void {
        $responseModel = new AuthenticateResponseModel();
        config(['app.debug' => false]); // Check for return value when APP is not in debug mode
        $viewModel = $this->createPresenter()->unableToAuthenticateUser($responseModel, new Exception('dummy exception'));

        $this->assertInstanceOf(HttpResponseViewModel::class, $viewModel);

        $response = json_decode($viewModel->getResponse()->getContent(), true);

        $this->assertEquals([
            'success'       =>  false,
            'code'          =>  500,
            'data'          =>  [
                'code'      =>  0,
                'message'   =>  'dummy exception'
            ],
            'message'       =>  'dummy exception'
        ], $response);

        config(['app.debug' => true]);
        $this->expectException(Exception::class);
        $this->createPresenter()->unableToAuthenticateUser($responseModel, new Exception('dummy exception'));
    }

    /**
     * Create new presenter instance
     * @return AuthenticateHttpPresenter
     */
    private function createPresenter(): AuthenticateHttpPresenter {
        return new AuthenticateHttpPresenter();
    }

}