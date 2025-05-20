<?php

namespace Tests\Service;

class UserService {

    public function getUserInfo($id): string {
        return "User info for user #$id";
    }
    
}
