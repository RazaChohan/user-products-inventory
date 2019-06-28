<?php

use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class UserTest extends TestCase
{
    /***
     * Test list products success
     */
    public function testGetUserSuccess()
    {
        $authToken = $this->getToken();
        $this->get("/user" . "?token=$authToken");
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonStructure([
            'id',
            'name',
            'email',
            'created_at',
            'updated_at'
        ]);
    }

    /***
     * Test get user un-authorized
     */
    public function testGetUserUnauthorized()
    {
        $this->get("/user");
        $this->seeStatusCode(Response::HTTP_UNAUTHORIZED);
    }

    /***
     * Test sync user products
     *
     */
    public function testSyncUserProducts()
    {
        //Get already purchased products by user
        $authToken = $this->getToken();
        $this->get("/user/products" . "?token=$authToken");
        $userProducts = json_decode($this->response->getContent());
        $initialUserProductIds = Arr::pluck($userProducts, 'id');

        $productIDPurchase = $this->getProductIDNotPurchased($authToken, $initialUserProductIds);
        //If non zero product id is returned
        if ($productIDPurchase > 0) {
            $initialUserProductIds[] = $productIDPurchase;

            //Remove all products for user
            $this->post("/user/products", ["token" => $authToken, 'product_ids' => $initialUserProductIds]);
            //Get purchased products after update
            $this->get("/user/products" . "?token=$authToken");
            $userProducts = json_decode($this->response->getContent());
            $afterUpdatedProductIds = Arr::pluck($userProducts, 'id');

            $this->assertTrue(in_array($productIDPurchase, $afterUpdatedProductIds));
        } else {
            $this->assertTrue(true);
        }
    }

    /***
     * Test get user products
     *
     */
    public function testGetUserProducts()
    {
        $authToken = $this->getToken();
        $this->get("/user/products" . "?token=$authToken");
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonStructure([
            [
                'id',
                'name',
                'sku'
            ]
        ]);
    }

    /***
     * test user recommendation
     *
     */
    public function testUserRecommendation()
    {
        $authToken = $this->getToken();
        $this->get("/user/recommendation?token=$authToken");
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonStructure(
            [
                'name',
                'sku'
            ]);
    }

    /***
     * get product id not purchased by user
     *
     * @param $authToken
     * @param $alreadyPurchasedProducts
     * @return int
     */
    private function getProductIDNotPurchased($authToken, $alreadyPurchasedProducts)
    {
        $productIDPurchase = 0;
        $this->get("/product" . "?token=$authToken");
        $this->seeStatusCode(Response::HTTP_OK);
        $products = json_decode($this->response->getContent());

        foreach ($products as $product) {
            if (!in_array($product->id, $alreadyPurchasedProducts)) {
                $productIDPurchase = $product->id;
                break;
            }
        }
        return $productIDPurchase;
    }
}
