<?php

namespace Mini\Services;

class UserService {

    public function getUserInfo($id): string {
        return "User info for user #$id";
    }
    
}
