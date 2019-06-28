<?php

namespace App\Http\Controllers;

use Dal\Interfaces\ProductRepository;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;
    /***
     * Product repository object
     *
     * @var ProductRepository
     */
    private $productRepository;

    /***
     * ProductController constructor.
     *
     * @param Request $request
     * @param ProductRepository $productRepository
     */
    public function __construct(Request $request, ProductRepository $productRepository)
    {
        $this->request = $request;
        $this->productRepository = $productRepository;
    }

    /***
     * List products
     */
    public function list()
    {
        $response = [];
        $responseCode = null;
        try {
            $response = $this->productRepository->getAllProducts();
            $responseCode = Response::HTTP_OK;
        } catch (Exception $exception) {
            $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            parent::log($exception, ProductController::class);
        }
        // send response
        return response()->json($response, $responseCode);
    }
}
