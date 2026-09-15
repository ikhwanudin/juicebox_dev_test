<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Dedoc\Scramble\Attributes\PathParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Get all posts.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    #[PathParameter('pageSize', description: 'query parameter used in offset-based pagination', required: false, type: 'string', default: 15)]
    public function index(Request $request): AnonymousResourceCollection
    {
        return PostResource::collection(
            Post::paginate($request->pageSize ?? 15)
        );
    }

    /**
     * Store new post.
     *
     * @param PostRequest $request
     * @return PostResource
     */
    public function store(PostRequest $request): PostResource
    {
        return new PostResource(
            Post::create($request)
        );

    }

    /**
     * Get the specified post.
     *
     * @param Post $post
     * @return PostResource
     */
    #[PathParameter('post', description: 'post id', type: 'string', format: 'ulid', example: '01m2g2903m4pm5v4s2ef1ywgeb')]
    public function show(Post $post): PostResource
    {
        return new PostResource($post);
    }


    /**
     * Update post.
     *
     * @param PostRequest $request
     * @param Post $post
     * @return PostResource
     */
    #[PathParameter('post', description: 'post id', type: 'string', format: 'ulid', example: '01m2g2903m4pm5v4s2ef1ywgeb')]
    public function update(PostRequest $request, Post $post)
    {
        $post->update($request->toArray());
        return new PostResource($post);
    }

    /**
     * Delete post.
     *
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    #[PathParameter('post', description: 'post id', type: 'string', format: 'ulid', example: '01m2g2903m4pm5v4s2ef1ywgeb')]
    public function destroy(Post $post): JsonResponse
    {
        try{
            $post->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }catch (\Throwable $e){

            return response()->json([
                'status' => 'error',
                'message' => 'The resource could not be deleted. Please try again later.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
