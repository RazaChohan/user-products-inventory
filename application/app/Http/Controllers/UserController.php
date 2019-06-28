<?php

namespace App\Http\Controllers;

use Dal\Interfaces\ProductRepository;
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
    /***
     * Product Repository
     *
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request $request
     * @param UserRepository $userRepository
     * @param ProductRepository $productRepository
     *
     * @return void
     */
    public function __construct(Request $request, UserRepository $userRepository, ProductRepository $productRepository)
    {
        $this->request = $request;
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;
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
        $userProducts = ['message' => 'User products not found'];
        $responseCode = null;
        try {
            $userProducts = $this->userRepository->getUserProducts($this->request->auth->id);
            $userProducts = !empty($userProducts->products) ? $userProducts->products : [];
            $responseCode = !empty($userProducts) ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
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

    /***
     * Remove user product
     *
     * @param string $sku
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeUserProduct(string $sku)
    {
        $response = ['message' => 'User product deletion failed'];
        $responseCode = null;
        try {
            $product = $this->productRepository->getProductBySku($sku);
            if (!is_null($product)) {
                $this->userRepository->removeUserProduct($this->request->auth->id, $product->id);
                $responseCode = Response::HTTP_OK;
                $response['message'] = 'Product detached successfully!';
            }
        } catch (Exception $exception) {
            $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            parent::log($exception, UserController::class);
        }
        // send response
        return response()->json($response, $responseCode);
    }

    /***
     * User recommendation
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userRecommendation()
    {
        $response = ['message' => 'No recommendation found!'];
        $responseCode = Response::HTTP_NOT_FOUND;
        try {
            $recommendedProduct = $this->productRepository->getRecommendedProduct($this->request->auth->id);
            if (!is_null($recommendedProduct)) {
                $responseCode = Response::HTTP_OK;
                $response = $recommendedProduct;
            }
        } catch (Exception $exception) {
            $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            parent::log($exception, UserController::class);
        }
        // send response
        return response()->json($response, $responseCode);
    }
}
