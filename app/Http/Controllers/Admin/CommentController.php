<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;

class CommentController extends Controller
{
    /**
     * Display a listing of comments.
     */
    public function index(): View
    {
        $comments = Comment::with(['post', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Show the form for editing the specified comment.
     */
    public function edit(Comment $comment): View
    {
        return view('admin.comments.edit', compact('comment'));
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, Comment $comment): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'status' => 'required|in:pending,approved,spam',
        ]);

        $comment->update($validated);

        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment updated successfully');
    }

    /**
     * Approve a comment.
     */
    public function approve(Comment $comment): RedirectResponse
    {
        $comment->update(['status' => 'approved']);

        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment approved successfully');
    }

    /**
     * Mark a comment as spam.
     */
    public function markAsSpam(Comment $comment): RedirectResponse
    {
        $comment->update(['status' => 'spam']);

        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment marked as spam');
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment deleted successfully');
    }

    /**
     * Bulk actions on comments.
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,spam,delete',
            'comments' => 'required|array',
            'comments.*' => 'exists:comments,id',
        ]);

        $comments = Comment::whereIn('id', $validated['comments']);

        switch ($validated['action']) {
            case 'approve':
                $comments->update(['status' => 'approved']);
                $message = 'Comments approved successfully';
                break;
            case 'spam':
                $comments->update(['status' => 'spam']);
                $message = 'Comments marked as spam';
                break;
            case 'delete':
                $comments->delete();
                $message = 'Comments deleted successfully';
                break;
        }

        return redirect()->route('admin.comments.index')
            ->with('success', $message);
    }
}
