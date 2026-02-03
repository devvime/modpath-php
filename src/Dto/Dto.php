<?php

namespace ModPath\Dto;

use ModPath\Http\Request;
use ModPath\Http\Response;
use Valitron\Validator;

class Dto
{
  public array $allowed;
  public array $rules;

  public function __construct(
    protected Request $request,
    protected Response $response
  ) {}

  public function validateOnly(array $data, array $allowedFields, array $rules)
  {
    // Reject unexpected fields
    $unexpectedFields = array_diff(array_keys($data), $allowedFields);
    if (!empty($unexpectedFields)) {
      return [
        "error" => [
          'invalid_fields' => ['Fields not allowed: ' . implode(', ', $unexpectedFields)]
        ]
      ];
    }
    // Validates only allowed fields
    $filtered = array_intersect_key($data, array_flip($allowedFields));
    $v = new Validator($filtered);
    foreach ($rules as $field => $fieldRules) {
      foreach ($fieldRules as $rule) {
        if (is_array($rule)) {
          // Example: ['length', 3, 20]
          $method = array_shift($rule);
          $v->rule($method, $field, ...$rule);
        } else {
          $v->rule($rule, $field);
        }
      }
    }
    return $v->validate() ? true : ["error" => $v->errors()];
  }

  public function handle()
  {
    $data = json_decode(json_encode($this->request->body), true);
    $result = $this->validateOnly(
      $data,
      $this->allowed,
      $this->rules
    );
    if (isset($result['error'])) {
      $this->response->json($result);
      exit;
    }
  }
}

