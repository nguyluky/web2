<?php

namespace Database\Seeders;

use App\Models\ProductReview;
use App\Models\Product;
use App\Models\Account;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;

class ProductReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get all products and accounts
        $products = Product::all();
        $accounts = Account::all();
        
        // If no accounts, we'll create reviews without user association
        $hasAccounts = $accounts->count() > 0;
        
        // Vietnamese product review phrases for more authentic data
        $positiveReviewsVi = [
            'Sản phẩm rất tốt, đúng như mô tả.',
            'Tôi rất hài lòng với chất lượng sản phẩm này.',
            'Đã mua và sử dụng trong 1 tháng, hoàn toàn ưng ý.',
            'Giao hàng nhanh, đóng gói cẩn thận, sản phẩm chất lượng.',
            'Giá cả hợp lý cho chất lượng như vậy.',
            'Rất đáng đồng tiền, sẽ mua lại lần sau.',
            'Thiết kế đẹp, chức năng tốt, rất đáng mua.',
            'Chất lượng vượt mong đợi với mức giá này.',
            'Sản phẩm hoạt động tốt, pin trâu.',
            'Rất hài lòng, sẽ giới thiệu cho bạn bè.',
        ];
        
        $neutralReviewsVi = [
            'Sản phẩm tạm ổn, còn một số điểm chưa hoàn thiện.',
            'Chất lượng khá, nhưng giá hơi cao so với tính năng.',
            'Sản phẩm đúng như mô tả, nhưng không có gì đặc biệt.',
            'Giao hàng hơi chậm, nhưng sản phẩm không có vấn đề.',
            'Đóng gói cẩn thận, sản phẩm chạy ổn định.',
            'Sử dụng được nhưng không như kỳ vọng.',
            'Không tệ nhưng cũng không quá xuất sắc.',
            'Thiết kế đẹp nhưng hiệu năng chỉ ở mức trung bình.',
            'Sản phẩm tốt nhưng pin hơi yếu.',
            'Dùng tạm được, chưa có vấn đề gì nghiêm trọng.',
        ];
        
        $negativeReviewsVi = [
            'Sản phẩm không như mô tả, rất thất vọng.',
            'Chất lượng kém, không đáng với số tiền bỏ ra.',
            'Gặp nhiều vấn đề sau khi sử dụng một thời gian.',
            'Hàng dễ hỏng, dịch vụ bảo hành phức tạp.',
            'Cấu hình không đúng như quảng cáo.',
            'Pin yếu, xạc nhanh hết.',
            'Thiết kế không tiện dụng, khó sử dụng.',
            'Phần mềm không ổn định, hay bị lỗi.',
            'Giá cao nhưng chất lượng thì không tương xứng.',
            'Sẽ không mua lại hay giới thiệu cho bạn bè.',
        ];
        
        // English product review phrases
        $positiveReviewsEn = [
            'Great product, exactly as described.',
            'I am very satisfied with this product\'s quality.',
            'Been using it for a month, completely satisfied.',
            'Fast delivery, careful packaging, quality product.',
            'Reasonable price for this quality.',
            'Worth every penny, would buy again.',
            'Beautiful design, good functionality, worth buying.',
            'Quality exceeds expectations for this price.',
            'Product works well, battery lasts long.',
            'Very satisfied, will recommend to friends.',
        ];
        
        $neutralReviewsEn = [
            'Product is okay, still has some issues to iron out.',
            'Quality is decent, but price is a bit high for features.',
            'Product is as described, but nothing special.',
            'Delivery was a bit slow, but product has no issues.',
            'Careful packaging, product runs stably.',
            'Usable but not as expected.',
            'Not bad but not outstanding either.',
            'Nice design but performance is just average.',
            'Good product but battery is a bit weak.',
            'Works fine, no major issues yet.',
        ];
        
        $negativeReviewsEn = [
            'Product not as described, very disappointed.',
            'Poor quality, not worth the money.',
            'Many issues after using for a while.',
            'Easy to break, warranty service is complicated.',
            'Specs don\'t match what was advertised.',
            'Weak battery, drains quickly.',
            'Design is not user-friendly, difficult to use.',
            'Software is unstable, prone to errors.',
            'High price but quality doesn\'t match.',
            'Would not buy again or recommend to friends.',
        ];
        
        // Category-specific review comments
        $categoryReviews = [
            'laptop-gaming' => [
                'Hiệu năng chơi game rất tốt, FPS cao và ổn định.',
                'Card đồ họa xử lý game nặng một cách mượt mà.',
                'Hệ thống tản nhiệt hoạt động hiệu quả, không bị quá nóng khi chơi game lâu.',
                'Great gaming performance, high and stable FPS.',
                'Graphics card handles heavy games smoothly.',
                'Cooling system works efficiently, doesn\'t overheat during long gaming sessions.',
            ],
            'laptop-van-phong' => [
                'Pin trâu, dùng được cả ngày làm việc.',
                'Bàn phím gõ rất êm, phù hợp để làm việc lâu.',
                'Nhẹ và dễ mang đi, rất thích hợp cho công việc.',
                'Battery lasts all workday.',
                'Keyboard is comfortable for long typing sessions.',
                'Lightweight and portable, very suitable for work.',
            ],
            'macbook' => [
                'Hệ điều hành macOS mượt mà và ổn định.',
                'Pin dùng được cả ngày, hiệu năng M-series rất mạnh mẽ.',
                'Màn hình Retina hiển thị sắc nét, màu sắc chính xác.',
                'macOS runs smoothly and stably.',
                'Battery lasts all day, M-series performance is very powerful.',
                'Retina display shows sharp images with accurate colors.',
            ],
            'iphone' => [
                'Camera chụp ảnh rất đẹp, đặc biệt trong điều kiện thiếu sáng.',
                'Face ID nhận diện nhanh và chính xác.',
                'iOS mượt mà, cập nhật thường xuyên và an toàn.',
                'Camera takes beautiful photos, especially in low light.',
                'Face ID recognizes quickly and accurately.',
                'iOS is smooth, regularly updated, and secure.',
            ],
            'samsung' => [
                'Màn hình AMOLED rất sắc nét và màu sắc sống động.',
                'Camera chụp rất tốt, nhiều tùy chỉnh hữu ích.',
                'One UI dễ sử dụng và có nhiều tính năng.',
                'AMOLED display is very sharp with vibrant colors.',
                'Camera takes great photos with many useful settings.',
                'One UI is easy to use and feature-rich.',
            ],
            'sac-du-phong' => [
                'Sạc nhanh và hiệu quả, dung lượng đúng như mô tả.',
                'Nhỏ gọn dễ mang theo, sạc được nhiều lần.',
                'Có đèn báo dung lượng tiện lợi.',
                'Charges quickly and efficiently, capacity as described.',
                'Compact and portable, charges multiple times.',
                'Has convenient capacity indicator lights.',
            ],
            'dong-ho-thong-minh' => [
                'Theo dõi sức khỏe chính xác, pin dùng được vài ngày.',
                'Nhiều mặt đồng hồ đẹp để lựa chọn.',
                'Chống nước tốt, đeo khi bơi không vấn đề.',
                'Health tracking is accurate, battery lasts several days.',
                'Many beautiful watch faces to choose from.',
                'Good water resistance, no issues when swimming.',
            ],
        ];

        foreach ($products as $product) {
            // Determine how many reviews to create
            $reviewCount = $faker->numberBetween(3, 15);
            
            // Category slug for specific reviews
            $categorySlug = $product->category->slug ?? '';
            
            for ($i = 1; $i <= $reviewCount; $i++) {
                // Determine rating (weighted towards positive)
                $ratingDistribution = [5, 5, 5, 4, 4, 4, 4, 3, 3, 2, 1];
                $rating = $faker->randomElement($ratingDistribution);
                
                // Select language (70% Vietnamese, 30% English)
                $isVietnamese = $faker->boolean(70);
                
                // Select appropriate review comment based on rating and language
                if ($rating >= 4) {
                    $reviews = $isVietnamese ? $positiveReviewsVi : $positiveReviewsEn;
                } elseif ($rating >= 3) {
                    $reviews = $isVietnamese ? $neutralReviewsVi : $neutralReviewsEn;
                } else {
                    $reviews = $isVietnamese ? $negativeReviewsVi : $negativeReviewsEn;
                }
                
                $comment = $faker->randomElement($reviews);
                
                // Add category-specific comment for some reviews
                if ($faker->boolean(40) && !empty($categorySlug) && isset($categoryReviews[$categorySlug])) {
                    $specificReviews = $categoryReviews[$categorySlug];
                    $specificComment = $faker->randomElement($specificReviews);
                    
                    // 50% chance to replace comment, 50% chance to append
                    if ($faker->boolean) {
                        $comment = $specificComment;
                    } else {
                        $comment = $comment . ' ' . $specificComment;
                    }
                }
                
                // Associated user (if available)
                $userId = null;
                if ($hasAccounts && $faker->boolean(80)) {
                    $randomAccount = $accounts->random();
                    $userId = $randomAccount->id;
                }
                
                // Create the review
                ProductReview::create([
                    'product_id' => $product->id,
                    'user_id' => $userId,
                    'rating' => $rating,
                    'comment' => $comment,
                    'status' => $faker->randomElement(['approved', 'approved', 'approved', 'pending', 'rejected']), // Weighted towards approved
                    'meta_data' => json_encode([
                        'verified_purchase' => $faker->boolean(70),
                        'helpful_votes' => $faker->numberBetween(0, 50),
                        'purchase_date' => $faker->dateTimeBetween('-6 months', '-1 week')->format('Y-m-d'),
                    ]),
                    'created_at' => $faker->dateTimeBetween('-3 months', 'now'),
                    'updated_at' => $faker->dateTimeBetween('-1 month', 'now')
                ]);
            }
        }
    }
}