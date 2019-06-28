<?php

namespace Dal\Repositories;

use Dal\Entities\Product;
use Dal\Interfaces\ProductRepository;

class ProductRepositoryImpl implements ProductRepository
{
    /***
     * Make new entity object
     */
    public function makeEntity()
    {
        return new Product();
    }

    /***
     * Insert new product
     *
     * @param string $name
     * @param string $sku
     * @return mixed
     */
    public function insertProduct(string $name, string $sku): int
    {
        $newProduct = $this->makeEntity();
        $newProduct->name = $name;
        $newProduct->sku = $sku;
        $newProduct->save();
        return $newProduct->id;
    }

    /***
     * Get all products
     *
     * @return array
     */
    public function getAllProducts(): array
    {
        return $this->makeEntity()->get()->toArray();
    }

    /***
     * get product by sku
     *
     * @param string $sku
     * @return mixed
     */
    public function getProductBySku(string $sku)
    {
        return $this->makeEntity()->where('sku', '=', $sku)->first();
    }
}
