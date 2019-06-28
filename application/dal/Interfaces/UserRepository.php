<?php

namespace Dal\Interfaces;


use Dal\Entities\User;

interface UserRepository
{
    /**
     * Check user authentication
     *
     * @param string $email
     * @return User|null
     */
    public function getUserByEmail(string $email);

    /***
     * Insert new user
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @return mixed
     */
    public function insertUser(string $name, string $email, string $password): int;

    /***
     * Add user products
     *
     * @param $data
     * @return mixed
     */
    public function addUserProducts($data);

    /***
     * Get user by id
     *
     * @param $id
     * @return mixed
     */
    public function getUserByID($id);

    /***
     * get user products
     *
     * @param $id
     * @return mixed
     */
    public function getUserProducts($id);

    /***
     * sync user products
     *
     * @param $userID
     * @param $productIds
     * @return mixed
     */
    public function syncUserProducts($userID, $productIds);

    /***
     * remove user product
     *
     * @param $userID
     * @param $productID
     * @return mixed
     */
    public function removeUserProduct($userID, $productID);
}
