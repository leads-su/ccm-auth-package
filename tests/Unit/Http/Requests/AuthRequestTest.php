<?php namespace ConsulConfigManager\Auth\Test\Unit\Http\Requests;

use Illuminate\Support\Facades\Validator;
use ConsulConfigManager\Auth\Test\TestCase;
use ConsulConfigManager\Auth\Http\Requests\AuthRequest;

/**
 * Class AuthRequestTest
 *
 * @package ConsulConfigManager\Auth\Test\Unit\Http\Requests
 */
class AuthRequestTest extends TestCase {

    public function testShouldReturnBooleanFromAuthorizeMethod(): void {
        $request = new AuthRequest();
        $this->assertIsBool($request->authorize());
    }

    public function testShouldPassIfValidDataWithEmail(): void {
        $validator = $this->createValidatorInstance([
            'emailOrUsername'   =>  'email@internet.org',
            'password'          =>  'InsecurePassword',
        ]);

        $this->assertTrue($validator->passes());
    }

    public function testShouldPassIfValidDataWithUsername(): void {
        $validator = $this->createValidatorInstance([
            'emailOrUsername'   =>  'username',
            'password'          =>  'InsecurePassword',
        ]);

        $this->assertTrue($validator->passes());
    }

    public function testShouldFailIfMissingUsernameOrEmailProperty(): void {
        $validator = $this->createValidatorInstance([
            'password'          =>  'InsecurePassword',
        ]);

        $this->assertFalse($validator->passes());
    }

    public function testShouldFailIfMissingPasswordProperty(): void {
        $validator = $this->createValidatorInstance([
            'emailOrUsername'   =>  'username',
        ]);

        $this->assertFalse($validator->passes());
    }

    public function testShouldFailIfPasswordIsLessThan8Symbols(): void {
        $validator = $this->createValidatorInstance([
            'emailOrUsername'   =>  'username',
            'password'          =>  'pwd',
        ]);

        $this->assertFalse($validator->passes());
    }

    public function testShouldFailIfPasswordIsLongerThan16Symbols(): void {
        $validator = $this->createValidatorInstance([
            'emailOrUsername'   =>  'username',
            'password'          =>  'InsecurePassword1',
        ]);

        $this->assertFalse($validator->passes());
    }

    /**
     * Create new validator instance based of provided data
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function createValidatorInstance(array $data): \Illuminate\Contracts\Validation\Validator {
        $request = new AuthRequest();
        return Validator::make($data, $request->rules());
    }

}