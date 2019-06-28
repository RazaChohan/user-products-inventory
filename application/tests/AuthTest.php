<?php

use Illuminate\Http\Response;

class AuthTest extends TestCase
{
    /***
     * Successful auth call
     */
    public function testAuthCallSuccess()
    {
        $this->post("/auth", ['email' => 'roselyn62@gmail.com', 'password' => 'secret']);
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonStructure(['token']);
    }

    /***
     * Test missing
     */
    public function testMissingValue()
    {
        $this->post('/auth', []);
        $this->seeStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /***
     * test invalid email
     */
    public function testInvalidEmail()
    {
        $this->post("/auth", ['email' => 'email@gmail.com', 'password' => 'secret']);
        $this->seeStatusCode(Response::HTTP_UNAUTHORIZED);
    }
}
