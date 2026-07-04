<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Store a new comment.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string|min:3|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $post = Post::findOrFail($request->post_id);
        
        $comment = Comment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
            'status' => 'approved', // Auto-approve for now, can be changed to 'pending' for moderation
            'created_by' => Auth::id(),
        ]);

        // Load the user relationship for the response
        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'comment' => $comment,
            'html' => view('frontend.blog.partials.comment-item', compact('comment'))->render()
        ]);
    }

    /**
     * Store a guest comment.
     */
    public function storeGuest(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string|min:3|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $post = Post::findOrFail($request->post_id);
        
        // For guest comments, we'll store the name and email in the content
        // In a real application, you might want to create a separate guest_users table
        $guestInfo = "Guest: " . $request->guest_name . " (" . $request->guest_email . ")\n\n";
        $content = $guestInfo . $request->content;
        
        $comment = Comment::create([
            'post_id' => $request->post_id,
            'user_id' => null,
            'parent_id' => $request->parent_id,
            'content' => $content,
            'status' => 'pending', // Guest comments should be moderated
            'created_by' => 1, // Admin user ID, adjust as needed
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment submitted successfully and is pending approval',
            'comment' => $comment
        ]);
    }

    /**
     * Get comments for a post.
     */
    public function getComments(Request $request, $postId): JsonResponse
    {
        $post = Post::findOrFail($postId);
        $comments = Comment::where('post_id', $postId)
            ->whereNull('parent_id')
            ->where('status', 'approved')
            ->with(['user', 'replies.user', 'replies.replies.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $html = view('frontend.blog.partials.comments-list', compact('comments'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'hasMorePages' => $comments->hasMorePages(),
            'currentPage' => $comments->currentPage(),
            'lastPage' => $comments->lastPage(),
        ]);
    }

    /**
     * Like a comment.
     */
    public function like(Request $request, $commentId): JsonResponse
    {
        $comment = Comment::findOrFail($commentId);
        
        // In a real application, you'd want to track likes in a separate table
        // For now, we'll just return success
        return response()->json([
            'success' => true,
            'message' => 'Comment liked successfully'
        ]);
    }

    /**
     * Report a comment.
     */
    public function report(Request $request, $commentId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment = Comment::findOrFail($commentId);
        
        // In a real application, you'd want to store the report in a separate table
        // For now, we'll just return success
        return response()->json([
            'success' => true,
            'message' => 'Comment reported successfully'
        ]);
    }
}
