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
}
