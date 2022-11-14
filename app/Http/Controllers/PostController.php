<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\CreatePostFormRequest;
use App\Http\Requests\Post\UpdatePostFormRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(CreatePostFormRequest $request)
    {
        $data = $request->validated();

        $post = Post::create($data);

        $response = [
            'post' => $post,
            'message' => 'Должность успешно создана'
        ];

        return response($response, 201);
    }

    public function update(UpdatePostFormRequest $request, $id)
    {
        $post = Post::find($id);

        if($post == null)
        {
            return response(['message' => "Должность с id=$id не найдена"], 404);
        }

        $data = $request->validated();

        $post->update($data);
        $post->save();

        $response = [
            'post' => $post,
            'message' => 'Должность успешно обновлена'
        ];

        return response($response, 201);
    }

    public function delete($id)
    {
        $post = Post::find($id);

        if($post == null)
        {
            return response(['message' => "Должность с id=$id не найдена"], 404);
        }

        $post->delete();

        return response(['message' => 'Должность успешно удалена'], 201);
    }

    public function getAll()
    {
        return response(Post::all(), 201);
    }

    public function getOne($id)
    {
        $post = Post::find($id);

        if($post == null)
        {
            return response(['message' => "Должность с id=$id не найдена"], 404);
        }

        return response($post, 201);
    }
}
