<?php

namespace App\Http\Controllers;

use Dal\Interfaces\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class UserController extends Controller
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

    /***
     * Get user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser()
    {
        $user = 'User not found';
        $responseCode = null;
        try {
            $user = $this->userRepository->getUserByID($this->request->auth->id);
            $responseCode = is_null($user) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK;
        } catch (Exception $exception) {
            $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            parent::log($exception, UserController::class);
        }
        // send response
        return response()->json($user, $responseCode);
    }

    /***
     * Get user products
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserProducts()
    {
        $userProducts = 'User products not found';
        $responseCode = null;
        try {
            $userProducts = $this->userRepository->getUserProducts($this->request->auth->id);
            $responseCode = is_null($userProducts) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK;
        } catch (Exception $exception) {
            $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            parent::log($exception, UserController::class);
        }
        // send response
        return response()->json($userProducts, $responseCode);
    }

    /***
     * Sync user products
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncUserProducts()
    {
        $response = ['message' => 'Sync products failed'];
        $responseCode = null;
        try {
            $productIds = $this->request->get('product_ids');
            $this->userRepository->syncUserProducts($this->request->auth->id, $productIds);
            $responseCode = Response::HTTP_OK;
            $response['message'] = 'Products synched successfully!';
        } catch (Exception $exception) {
            $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            parent::log($exception, UserController::class);
        }
        // send response
        return response()->json($response, $responseCode);
    }
}
