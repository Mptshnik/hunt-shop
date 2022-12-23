<?php

namespace Tests\Unit;

use App\Http\Requests\Post;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function test_can_create_post() {

        $data = [
            'name' => 'PostName',
            'description' => 'PostDescription',
        ];


        $this->post('POST', 'api/post/create', $data, ['Accept' => 'application/json'])
            ->assertStatus(201);
    }

    public function test_can_update_post() {

        $data = [
            'name' => 'PostName',
            'description' => 'PostDescription',
        ];


        $this->post('POST', 'api/post/update/1', $data, ['Accept' => 'application/json'])
            ->assertStatus(201);
    }

    public function test_can_get_posts() {

        $this->get('GET', 'api/post/all', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_can_get_post() {

        $this->get('GET', 'api/post/1', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_can_delete_post() {

        $this->post('POST', 'api/post/delete/1', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }
}
