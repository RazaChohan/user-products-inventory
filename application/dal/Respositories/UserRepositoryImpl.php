<?php

namespace Dal\Repositories;

use Dal\Entities\User;
use Dal\Interfaces\UserRepository;
use Illuminate\Support\Facades\DB;

class UserRepositoryImpl implements UserRepository {
    /***
     * User entity
     *
     * @var User
     */
    private $eloquentEntity;

    /***
     * Constructor
     *
     * UserRepositoryImpl constructor.
     */
    public function __construct()
    {
        $this->eloquentEntity = new User();
    }

    /***
     * Make new entity object
     */
    public function makeEntity()
    {
        return new User();
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
    /***
     * Insert new user
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @return mixed
     */
    public function insertUser(string $name, string $email, string $password) : int {
        $newUser = $this->makeEntity();
        $newUser->name = $name;
        $newUser->email = $email;
        $newUser->password = $password;
        $newUser->save();
        return $newUser->id;
    }
    /***
     * Add user products
     *
     * @param $data
     * @return mixed
     */
    public function addUserProducts($data)
    {
        //Using query builder to insert records for optimized solution otherwise records can be inserted using attach
        //method of eloquent
        DB::table('user_products')->insert($data);
    }
}
