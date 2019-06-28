<?php

namespace Dal\Interfaces;

interface ProductRepository
{
    /***
     * Insert new product
     *
     * @param string $name
     * @param string $sku
     * @return mixed
     */
    public function insertProduct(string $name, string $sku) : int;

    /***
     * Get all products
     *
     * @return array
     */
    public function getAllProducts() : array;

    /***
     * get product by sku
     *
     * @param string sku
     *
     * @return mixed
     */
    public function getProductBySku(string $sku);
}
