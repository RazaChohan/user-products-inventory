<?php

namespace Dal\Interfaces;


use Dal\Entities\User;

interface UserInterface {
    /**
     * Check user authentication
     *
     * @param string $email
     * @return User|null
     */
    public function getUserByEmail(string $email);
}
