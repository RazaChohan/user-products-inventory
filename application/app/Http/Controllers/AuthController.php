<?php

namespace App\Http\Controllers;

use Dal\Interfaces\UserRepository;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Exception;

class AuthController extends Controller
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;
    /***
     * User Repository
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request $request
     * @param UserRepository $userRepository
     * @return void
     */
    public function __construct(Request $request, UserRepository $userRepository)
    {
        $this->request = $request;
        $this->userRepository = $userRepository;
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @throws \Exception
     * @return mixed
     */
    public function authenticate()
    {
        $response = [];
        $responseCode = null;

        try {
            $this->validate($this->request, [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $user = $this->userRepository->getUserByEmail($this->request->input('email'));

            if (is_null($user)) {
                $response = ['error' => 'Email does not exist.'];
                $responseCode = Response::HTTP_UNAUTHORIZED;
            } elseif (Hash::check($this->request->input('password'), $user->password)) {
                // Verify the password and generate the token
                $response = ['token' => $this->jwt($user->id)];
                $responseCode = Response::HTTP_OK;
            } else {
                $response = ['error' => 'Email or password is wrong.'];
                $responseCode = Response::HTTP_UNAUTHORIZED;
            }
        } catch (Exception $exception) {
            $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            parent::log($exception, AuthController::class);
        }
        // send response
        return response()->json($response, $responseCode);
    }

    /**
     * Create a new token.
     *
     * @param integer $userID
     * @return string
     */
    protected function jwt(int $userID): string
    {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $userID, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60 * 60 // Expiration time
        ];

        return JWT::encode($payload, env('JWT_SECRET'));
    }
}
