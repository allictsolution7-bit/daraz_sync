<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = Post::all();
        $users = User::all();

        if ($posts->count() === 0 || $users->count() === 0) {
            $this->command->info('No posts or users found. Skipping comment seeding.');
            return;
        }

        $sampleComments = [
            'Great article! Very informative and well-written.',
            'Thanks for sharing this valuable information.',
            'I learned a lot from this post. Keep up the good work!',
            'This is exactly what I was looking for. Thank you!',
            'Interesting perspective on this topic.',
            'Well researched and presented. Looking forward to more content.',
            'I have a different opinion on this, but appreciate the insights.',
            'This helped me understand the concept better.',
            'Excellent explanation of a complex topic.',
            'I would love to see more posts like this.',
        ];

        foreach ($posts as $post) {
            // Add 2-5 top-level comments per post
            $numComments = rand(2, 5);
            
            for ($i = 0; $i < $numComments; $i++) {
                $user = $users->random();
                $comment = Comment::create([
                    'post_id' => $post->id,
                    'user_id' => $user->id,
                    'parent_id' => null,
                    'content' => $sampleComments[array_rand($sampleComments)],
                    'status' => 'approved',
                    'created_by' => $user->id,
                ]);

                // Add 1-3 replies to some comments
                if (rand(0, 1) && $i < 3) {
                    $numReplies = rand(1, 3);
                    for ($j = 0; $j < $numReplies; $j++) {
                        $replyUser = $users->random();
                        Comment::create([
                            'post_id' => $post->id,
                            'user_id' => $replyUser->id,
                            'parent_id' => $comment->id,
                            'content' => $sampleComments[array_rand($sampleComments)],
                            'status' => 'approved',
                            'created_by' => $replyUser->id,
                        ]);
                    }
                }
            }

            // Add some guest comments
            if (rand(0, 1)) {
                Comment::create([
                    'post_id' => $post->id,
                    'user_id' => null,
                    'parent_id' => null,
                    'content' => "Guest: John Doe (john@example.com)\n\n" . $sampleComments[array_rand($sampleComments)],
                    'status' => 'pending',
                    'created_by' => 1, // Admin user
                ]);
            }
        }

        $this->command->info('Comments seeded successfully!');
    }
}
