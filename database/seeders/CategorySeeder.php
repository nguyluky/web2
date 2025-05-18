<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Class CategorySeeder
 * Tạo dữ liệu danh mục sản phẩm cho hệ thống
 */
class CategorySeeder extends Seeder
{
    /**
     * Danh sách các danh mục chính và danh mục con
     * Cấu trúc: [tên danh mục => [mô tả danh mục, [các danh mục con]]]
     * 
     * @var array
     */
    protected $categories = [
        'Điện thoại' => [
            'description' => 'Điện thoại di động thông minh đa dạng mẫu mã, phù hợp với mọi nhu cầu và ngân sách',
            'require_fields' => [
                'processor', 'display', 'memory', 'storage', 'camera', 'battery', 'os'
            ],
            'subcategories' => [
                'iPhone' => [
                    'description' => 'Điện thoại iPhone chính hãng Apple với iOS mượt mà và ổn định',
                    'require_fields' => [
                        'chip', 'display', 'memory', 'storage', 'camera', 'battery', 'os'
                    ]
                ],
                'Samsung' => [
                    'description' => 'Điện thoại Samsung với màn hình AMOLED sắc nét cùng công nghệ camera tiên tiến',
                    'require_fields' => [
                        'processor', 'display', 'ram', 'storage', 'camera', 'battery', 'os'
                    ]
                ],
                'Xiaomi' => [
                    'description' => 'Điện thoại Xiaomi giá rẻ với cấu hình cao và nhiều tính năng hấp dẫn',
                    'require_fields' => [
                        'processor', 'display', 'ram', 'storage', 'camera', 'battery', 'os'
                    ]
                ],
                'OPPO' => [
                    'description' => 'Điện thoại OPPO với camera selfie đỉnh cao và sạc nhanh VOOC',
                    'require_fields' => [
                        'processor', 'display', 'ram', 'storage', 'camera', 'battery', 'os', 'charging'
                    ]
                ]
            ]
        ],
        'Tablet' => [
            'description' => 'Máy tính bảng đa năng phù hợp giải trí, học tập và làm việc',
            'require_fields' => [
                'processor', 'display', 'memory', 'storage', 'camera', 'battery', 'os'
            ],
            'subcategories' => [
                'iPad' => [
                    'description' => 'Máy tính bảng iPad chính hãng Apple với hiệu năng mạnh mẽ và ổn định',
                    'require_fields' => [
                        'chip', 'display', 'memory', 'storage', 'camera', 'battery', 'os'
                    ]
                ],
                'Samsung Galaxy Tab' => [
                    'description' => 'Máy tính bảng Samsung với màn hình sắc nét và đa dạng kích thước',
                    'require_fields' => [
                        'processor', 'display', 'ram', 'storage', 'camera', 'battery', 'os'
                    ]
                ],
                'Lenovo Tab' => [
                    'description' => 'Máy tính bảng Lenovo giá cả phải chăng, phù hợp cho học tập và giải trí',
                    'require_fields' => [
                        'processor', 'display', 'ram', 'storage', 'battery', 'os'
                    ]
                ]
            ]
        ],
        'Laptop' => [
            'description' => 'Máy tính xách tay đa dạng từ gaming đến văn phòng với nhiều phân khúc giá',
            'require_fields' => [
                'processor', 'graphics', 'display', 'memory', 'storage', 'os', 'weight'
            ],
            'subcategories' => [
                'Laptop Gaming' => [
                    'description' => 'Laptop chuyên dụng cho game thủ với card đồ họa mạnh và hệ thống tản nhiệt hiệu quả',
                    'require_fields' => [
                        'processor', 'graphics', 'display', 'memory', 'storage', 'cooling', 'os', 'keyboard'
                    ]
                ],
                'Laptop Văn phòng' => [
                    'description' => 'Laptop mỏng nhẹ, thời lượng pin cao phù hợp cho công việc và học tập',
                    'require_fields' => [
                        'processor', 'graphics', 'display', 'memory', 'storage', 'battery', 'os', 'weight'
                    ]
                ],
                'Macbook' => [
                    'description' => 'Laptop cao cấp từ Apple với hiệu năng mạnh mẽ và thiết kế sang trọng',
                    'require_fields' => [
                        'processor', 'memory', 'storage', 'display', 'battery', 'os', 'weight'
                    ]
                ]
            ]
        ],
        'Âm thanh' => [
            'description' => 'Các thiết bị âm thanh chất lượng cao cho trải nghiệm nghe nhạc tuyệt vời',
            'require_fields' => [
                'brand', 'connectivity', 'power', 'battery_life'
            ],
            'subcategories' => [
                'Loa' => [
                    'description' => 'Loa bluetooth, loa di động và loa âm thanh nổi chất lượng cao',
                    'require_fields' => [
                        'brand', 'power', 'connectivity', 'battery_life', 'waterproof'
                    ]
                ],
                'Tai nghe' => [
                    'description' => 'Tai nghe có dây và không dây đa dạng mẫu mã, phong cách',
                    'require_fields' => [
                        'brand', 'type', 'connectivity', 'noise_cancellation', 'battery_life'
                    ]
                ]
            ]
        ],
        'Mic thu âm' => [
            'description' => 'Micro chuyên nghiệp dùng cho thu âm, livestream và karaoke',
            'require_fields' => [
                'brand', 'type', 'connectivity'
            ],
            'subcategories' => [
                'Micro có dây' => [
                    'description' => 'Micro có dây chất lượng cao, phù hợp cho phòng thu và sự kiện',
                    'require_fields' => [
                        'brand', 'type', 'connectivity', 'impedance'
                    ]
                ],
                'Micro không dây' => [
                    'description' => 'Micro không dây linh hoạt, tiện lợi cho người dùng di chuyển',
                    'require_fields' => [
                        'brand', 'type', 'wireless_range', 'battery_life'
                    ]
                ]
            ]
        ],
        'Đồng hồ' => [
            'description' => 'Đồng hồ đeo tay thời trang và thông minh với nhiều tính năng hiện đại',
            'require_fields' => [
                'brand', 'display', 'material'
            ],
            'subcategories' => [
                'Đồng hồ thông minh' => [
                    'description' => 'Smartwatch với nhiều tính năng theo dõi sức khỏe và kết nối không dây',
                    'require_fields' => [
                        'brand', 'display', 'os', 'connectivity', 'battery_life', 'health_features', 'water_resistance'
                    ]
                ],
                'Đồng hồ thời trang' => [
                    'description' => 'Đồng hồ thời trang với thiết kế đẹp mắt và thương hiệu uy tín',
                    'require_fields' => [
                        'brand', 'movement', 'material', 'strap_material', 'water_resistance'
                    ]
                ]
            ]
        ],
        'Phụ kiện' => [
            'description' => 'Các phụ kiện thiết yếu cho thiết bị điện tử thông minh của bạn',
            'require_fields' => [
                'brand', 'compatibility', 'material'
            ],
            'subcategories' => [
                'Sạc dự phòng' => [
                    'description' => 'Pin dự phòng với nhiều dung lượng, hỗ trợ sạc nhanh cho các thiết bị',
                    'require_fields' => [
                        'brand', 'capacity', 'input', 'output', 'ports', 'weight'
                    ]
                ],
                'Cáp sạc' => [
                    'description' => 'Cáp sạc bền bỉ cho các loại cổng kết nối phổ biến',
                    'require_fields' => [
                        'brand', 'type', 'length', 'power_delivery'
                    ]
                ],
                'Ốp lưng' => [
                    'description' => 'Ốp lưng bảo vệ cho điện thoại với nhiều mẫu mã và chất liệu',
                    'require_fields' => [
                        'brand', 'compatibility', 'material', 'features'
                    ]
                ]
            ]
        ],
        'PC' => [
            'description' => 'Máy tính để bàn mạnh mẽ cho công việc, giải trí và chơi game',
            'require_fields' => [
                'processor', 'graphics', 'memory', 'storage', 'os'
            ],
            'subcategories' => [
                'PC Gaming' => [
                    'description' => 'Máy tính chơi game hiệu năng cao với card đồ họa mạnh mẽ',
                    'require_fields' => [
                        'processor', 'graphics', 'memory', 'storage', 'cooling', 'power_supply', 'case', 'os'
                    ]
                ],
                'PC Văn phòng' => [
                    'description' => 'Máy tính để bàn phù hợp cho nhu cầu văn phòng và học tập',
                    'require_fields' => [
                        'processor', 'graphics', 'memory', 'storage', 'os'
                    ]
                ],
                'Màn hình Gaming' => [
                    'description' => 'Màn hình tần số quét cao, thời gian phản hồi thấp cho trải nghiệm chơi game tuyệt vời',
                    'require_fields' => [
                        'brand', 'size', 'resolution', 'refresh_rate', 'response_time', 'panel_type', 'ports'
                    ]
                ],
                'Màn hình đồ họa' => [
                    'description' => 'Màn hình độ phủ màu cao, chính xác cho công việc đồ họa và thiết kế',
                    'require_fields' => [
                        'brand', 'size', 'resolution', 'color_gamut', 'panel_type', 'ports'
                    ]
                ]
            ]
        ],
    ];

    /**
     * Tạo dữ liệu danh mục sản phẩm
     * Mỗi danh mục có các danh mục con tương ứng
     * 
     * @return void
     */
    public function run(): void
    {
        // Reset the auto-increment counter
        \DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1');

        // Tạo danh mục từ mảng định nghĩa
        foreach ($this->categories as $categoryName => $categoryData) {
            // Tạo danh mục chính
            $parentCategory = $this->createCategory(
                $categoryName, 
                null, 
                $categoryData['description'],
                $categoryData['require_fields'] ?? []
            );
            
            // Tạo các danh mục con
            foreach ($categoryData['subcategories'] as $subName => $subData) {
                // Kiểm tra cấu trúc dữ liệu (chuỗi đơn giản hoặc mảng với description và require_fields)
                if (is_array($subData)) {
                    $this->createCategory(
                        $subName, 
                        $parentCategory->id, 
                        $subData['description'],
                        $subData['require_fields'] ?? []
                    );
                } else {
                    // Trường hợp cũ: $subData chỉ là description
                    $this->createCategory($subName, $parentCategory->id, $subData);
                }
            }
        }
    }
    
    /**
     * Tạo một danh mục mới
     * 
     * @param string $name Tên danh mục
     * @param int|null $parentId ID của danh mục cha (null nếu là danh mục chính)
     * @param string $description Mô tả của danh mục
     * @param array $requireFields Các trường bắt buộc cho danh mục
     * @return Category
     */
    protected function createCategory(string $name, ?int $parentId = null, string $description = '', array $requireFields = []): Category
    {
        $slug = Str::slug($name);
        
        return Category::create([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'require_fields' => $requireFields,
            'status' => 'active',
            'parent_id' => $parentId,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}