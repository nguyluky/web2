<?php

namespace Database\Seeders;

use App\Models\ProductReview;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProductReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reviews for Gaming Pro X1
        ProductReview::create([
            'id' => 1,
            'product_id' => 1,
            'user_id' => 4,
            'rating' => 5,
            'comment' => 'This laptop exceeds all my gaming expectations. Graphics are incredible and games run smoothly even on high settings.',
            'status' => 'approved',
            'created_at' => Carbon::now()->subDays(28),
            'updated_at' => Carbon::now()->subDays(28)
        ]);

        ProductReview::create([
            'id' => 2,
            'product_id' => 1,
            'user_id' => 5,
            'rating' => 4,
            'comment' => 'Great performance but the laptop tends to get quite hot during extended gaming sessions.',
            'status' => 'approved',
            'created_at' => Carbon::now()->subDays(23),
            'updated_at' => Carbon::now()->subDays(23)
        ]);

        // Reviews for Business Elite B5
        ProductReview::create([
            'id' => 3,
            'product_id' => 2,
            'user_id' => 5,
            'rating' => 5,
            'comment' => 'Battery life is exceptional and performance is smooth. Security features give me peace of mind.',
            'status' => 'approved',
            'created_at' => Carbon::now()->subDays(22),
            'updated_at' => Carbon::now()->subDays(22)
        ]);

        // Reviews for Galaxy Ultra S25
        ProductReview::create([
            'id' => 4,
            'product_id' => 3,
            'user_id' => 6,
            'rating' => 5,
            'comment' => 'The 200MP camera is incredible. Photo quality is top-notch even in low light conditions.',
            'status' => 'approved',
            'created_at' => Carbon::now()->subDays(18),
            'updated_at' => Carbon::now()->subDays(18)
        ]);

        // Reviews for iPhone 16 Pro
        ProductReview::create([
            'id' => 5,
            'product_id' => 4,
            'user_id' => 6,
            'rating' => 5,
            'comment' => 'iOS runs flawlessly and the build quality is exceptional as always. Camera system is best in class.',
            'status' => 'approved',
            'created_at' => Carbon::now()->subDays(15),
            'updated_at' => Carbon::now()->subDays(15)
        ]);

        ProductReview::create([
            'id' => 6,
            'product_id' => 4,
            'user_id' => 7,
            'rating' => 4,
            'comment' => 'Overall great phone but battery life is slightly disappointing for the premium price.',
            'status' => 'approved',
            'created_at' => Carbon::now()->subDays(10),
            'updated_at' => Carbon::now()->subDays(10)
        ]);

        // Reviews for accessories
        ProductReview::create([
            'id' => 7,
            'product_id' => 5,
            'user_id' => 8,
            'rating' => 5,
            'comment' => 'These earbuds have incredible sound quality and the noise cancellation works very well.',
            'status' => 'approved',
            'created_at' => Carbon::now()->subDays(8),
            'updated_at' => Carbon::now()->subDays(8)
        ]);

        ProductReview::create([
            'id' => 8,
            'product_id' => 6,
            'user_id' => 9,
            'rating' => 5,
            'comment' => 'The health tracking features are accurate and the battery lasts for days.',
            'status' => 'approved',
            'created_at' => Carbon::now()->subDays(5),
            'updated_at' => Carbon::now()->subDays(5)
        ]);

        ProductReview::create([
            'id' => 9,
            'product_id' => 7,
            'user_id' => 10,
            'rating' => 4,
            'comment' => 'This gaming mouse is extremely responsive and fits perfectly in my hand. RGB lighting is customizable.',
            'status' => 'approved',
            'created_at' => Carbon::now()->subDays(3),
            'updated_at' => Carbon::now()->subDays(3)
        ]);
        
        // Pending review
        ProductReview::create([
            'id' => 10,
            'product_id' => 2,
            'user_id' => 7,
            'rating' => 3,
            'comment' => 'Good laptop but I expected better battery life for the price.',
            'status' => 'pending',
            'created_at' => Carbon::now()->subDays(1),
            'updated_at' => Carbon::now()->subDays(1)
        ]);
    }
}