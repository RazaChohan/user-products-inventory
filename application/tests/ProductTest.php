<?php

use Illuminate\Http\Response;

class ProductTest extends TestCase
{
    /***
     * Test list products success
     */
    public function testListProductsSuccess()
    {
        $authToken = $this->getToken();
        $this->get("/product" . "?token=$authToken");
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonStructure([
            [
                'id',
                'name',
                'sku',
                'created_at',
                'updated_at'
            ]
        ]);
    }

    /***
     * Test unauthorized call
     */
    public function testUnAuthorized()
    {
        $this->get('/product');
        $this->seeStatusCode(Response::HTTP_UNAUTHORIZED);
    }

}
