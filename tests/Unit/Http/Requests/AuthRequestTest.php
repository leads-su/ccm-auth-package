<?php

namespace ConsulConfigManager\Auth\Test\Unit\Http\Requests;

use Illuminate\Support\Facades\Validator;
use ConsulConfigManager\Auth\Test\TestCase;
use ConsulConfigManager\Auth\Http\Requests\AuthRequest;
use Illuminate\Contracts\Validation\Validator as ValidatorInterface;

/**
 * Class AuthRequestTest
 * @package ConsulConfigManager\Auth\Test\Unit\Http\Requests
 */
class AuthRequestTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfTrueReturnedFromAuthorizeMethod(): void
    {
        $request = new AuthRequest();
        $this->assertIsBool($request->authorize());
        $this->assertTrue($request->authorize());
    }

    /**
     * @return void
     */
    public function testShouldPassIfRequestPerformedWithValidEmailAddress(): void
    {
        $validator = $this->createValidatorInstance([
            'emailOrUsername'   =>  'john.doe@example.com',
            'password'          =>  'InsecurePassword',
        ]);
        $this->assertTrue($validator->passes());
    }

    /**
     * @return void
     */
    public function testShouldPassIfRequestPerformedWithValidUsername(): void
    {
        $validator = $this->createValidatorInstance([
            'emailOrUsername'   =>  'john.doe',
            'password'          =>  'InsecurePassword',
        ]);
        $this->assertTrue($validator->passes());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidatorFailsWithMissingUsernameAndEmail(): void
    {
        $validator = $this->createValidatorInstance([
            'password'          =>  'InsecurePassword',
        ]);

        $this->assertFalse($validator->passes());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidatorFailsWithMissingPassword(): void
    {
        $validator = $this->createValidatorInstance([
            'emailOrUsername'   =>  'john.doe',
        ]);

        $this->assertFalse($validator->passes());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidatorFailsWithInsecurePassword(): void
    {
        $validator = $this->createValidatorInstance([
            'emailOrUsername'   =>  'username',
            'password'          =>  'pwd',
        ]);

        $this->assertFalse($validator->passes());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidatorFailsWithLongPassword(): void
    {
        $validator = $this->createValidatorInstance([
            'emailOrUsername'   =>  'username',
            'password'          =>  'InsecurePassword1',
        ]);

        $this->assertFalse($validator->passes());
    }

    /**
     * Create new validator instance based on provided data
     * @param array $data
     * @return ValidatorInterface
     */
    private function createValidatorInstance(array $data): ValidatorInterface
    {
        $request = new AuthRequest();
        return Validator::make($data, $request->rules());
    }
}
