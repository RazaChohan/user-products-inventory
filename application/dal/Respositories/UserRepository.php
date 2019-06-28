<?php

namespace Dal\Repositories;

use Dal\Entities\User;
use Dal\Interfaces\UserInterface;

class UserRepository implements UserInterface {
    /***
     * User entity
     *
     * @var User
     */
    private $eloquentEntity;

    /***
     * Constructor
     *
     * UserRepository constructor.
     */
    public function __construct()
    {
        $this->eloquentEntity = new User();
    }
    /**
     * get user by email
     *
     * @param string $email
     * @return User|null
     */
    public function getUserByEmail(string $email) {
        return $this->eloquentEntity->where('email', '=', $email)->first();

    }
}
