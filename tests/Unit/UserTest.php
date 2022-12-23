<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_can_create_user() {

        $data = [
            'login' => 'LoginTest',
            'password' => 'qwerty',
            'agreement' => '1'
        ];


        $this->post('POST', 'api/user/create', $data, ['Accept' => 'application/json'])
            ->assertStatus(201);
    }

    public function test_can_update_user() {

        $data = [
            'login' => 'LoginTest2',
            'password' => 'qwerty',
            'agreement' => '1'
        ];


        $this->post('POST', 'api/user/update/1', $data, ['Accept' => 'application/json'])
            ->assertStatus(201);
    }

    public function test_can_delete_user() {

        $this->post('POST', 'api/user/delete/1', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_can_get_users() {

        $this->post('POST', 'api/user/all', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_can_get_user() {

        $this->post('POST', 'api/user/1', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }
}
