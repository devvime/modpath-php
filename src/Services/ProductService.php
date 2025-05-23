<?php

namespace Mini\Services;

class ProductService {

    public function getProductInfo($id): string {
        return "Info for product with id: #$id";
    }
    
}
