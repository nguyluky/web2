. API cho phần User

### 1. Quản lý sản phẩm và danh mục

#### 1.1. Lấy thông tin sản phẩm ✅

**Endpoint:** `GET /api/products/:id`  
**Response:** Chi tiết sản phẩm bao gồm thông tin cơ bản, đặc điểm kỹ thuật, biến thể, hình ảnh, giá cả, rating, v.v.

#### 1.2. Lấy danh sách sản phẩm theo danh mục ✅

**Endpoint:** `GET /api/categories/:id/products`  
**Parameters:** `page`, `limit`, `sort`, `filter_*`  
**Response:** Danh sách sản phẩm thuộc danh mục

#### 1.3. Lấy danh sách danh mục ✅

**Endpoint:** `GET /api/categories`  
**Response:** Danh sách các danh mục sản phẩm

#### 1.4. Lấy sản phẩm nổi bật

**Endpoint:** `GET /api/products/featured`  
**Parameters:** `limit`  
**Response:** Danh sách sản phẩm nổi bật

#### 1.5. Lấy sản phẩm liên quan _KHÓ_

**Endpoint:** `GET /api/products/:id/related`  
**Parameters:** `limit`  
**Response:** Danh sách sản phẩm liên quan

#### 1.6. Tìm kiếm sản phẩm ✅

**Endpoint:** `GET /api/products/search`  
**Parameters:** `query`, `page`, `limit`, `sort`, `filter_*`  
**Response:** Kết quả tìm kiếm sản phẩm

### 2. Đánh giá sản phẩm

#### 2.1. Lấy đánh giá sản phẩm ✅

**Endpoint:** `GET /api/products/:id/reviews`  
**Parameters:** `page`, `limit`, `sort`  
**Response:** Danh sách đánh giá và thống kê rating

#### 2.2. Thêm đánh giá sản phẩm ✅

**Endpoint:** `POST /api/products/:id/reviews`  
**Request:** Rating, nội dung, hình ảnh  
**Response:** Thông tin đánh giá vừa thêm

### 3. Giỏ hàng & Thanh toán

#### 3.1. Thêm sản phẩm vào giỏ hàng ✅

**Endpoint:** `POST /api/cart`  
**Request:** ID sản phẩm, số lượng, biến thể  
**Response:** Thông tin giỏ hàng cập nhật

#### 3.2. Lấy thông tin giỏ hàng ⚠️ (Nghi ngờ bị sai ở phần tham số truyền vào)

**Endpoint:** `GET /api/cart`  
**Response:** Chi tiết giỏ hàng hiện tại

#### 3.3. Cập nhật sản phẩm trong giỏ hàng ⚠️ (Nghi ngờ bị sai ở phần tham số truyền vào)

**Endpoint:** `PUT /api/cart/:itemId`  
**Request:** Số lượng mới  
**Response:** Thông tin giỏ hàng cập nhật

#### 3.4. Xóa sản phẩm khỏi giỏ hàng ⚠️ (Nghi ngờ bị sai ở phần tham số truyền vào)

**Endpoint:** `DELETE /api/cart/:itemId`  
**Response:** Thông tin giỏ hàng cập nhật

#### 3.5. Áp dụng mã giảm giá

**Endpoint:** `POST /api/cart/apply-coupon`  
**Request:** Mã giảm giá  
**Response:** Thông tin giỏ hàng đã áp dụng mã giảm giá

#### 3.6. Tạo đơn hàng ✅

**Endpoint:** `POST /api/orders`  
**Request:** Thông tin giao hàng, phương thức thanh toán  
**Response:** Thông tin đơn hàng đã tạo

#### 3.7. Kiểm tra tình trạng thanh toán _KHÓ_

**Endpoint:** `GET /api/orders/:id/payment-status`  
**Response:** Trạng thái thanh toán

#### 3.8. Lấy thông tin vận chuyển

**Endpoint:** `POST /api/shipping/estimate`  
**Request:** Sản phẩm, địa chỉ giao hàng  
**Response:** Chi phí vận chuyển, thời gian dự kiến

#### 3.9. Mua ngay sản phẩm

**Endpoint:** `POST /api/checkout/buy-now`  
**Request:** Thông tin sản phẩm, số lượng, biến thể  
**Response:** URL chuyển hướng đến trang thanh toán

### 4. Tài khoản người dùng

#### 4.1. Đăng ký tài khoản ✅

**Endpoint:** `POST /api/auth/register`  
**Request:** Thông tin người dùng  
**Response:** Token và thông tin người dùng

#### 4.2. Đăng nhập ✅

**Endpoint:** `POST /api/auth/login`  
**Request:** Email, mật khẩu  
**Response:** Token và thông tin người dùng

#### 4.3. Lấy thông tin người dùng ✅

**Endpoint:** `GET /api/users/profile`  
**Response:** Thông tin chi tiết người dùng

#### 4.4. Cập nhật thông tin cá nhân

**Endpoint:** `PUT /api/users/profile`  
**Request:** Thông tin cần cập nhật  
**Response:** Thông tin người dùng đã cập nhật

#### 4.5. Thay đổi mật khẩu

**Endpoint:** `PUT /api/users/change-password`  
**Request:** Mật khẩu cũ và mới  
**Response:** Thông báo thành công

#### 4.6. Quên mật khẩu

**Endpoint:** `POST /api/auth/forgot-password`  
**Request:** Email  
**Response:** Thông báo đã gửi email khôi phục

#### 4.7. Đặt lại mật khẩu

**Endpoint:** `POST /api/auth/reset-password`  
**Request:** Token, mật khẩu mới  
**Response:** Thông báo thành công

### 5. Quản lý đơn hàng

#### 5.1. Lấy danh sách đơn hàng của người dùng

**Endpoint:** `GET /api/users/orders`  
**Parameters:** `page`, `limit`, [status](vscode-file://vscode-app/opt/visual-studio-code/resources/app/out/vs/code/electron-sandbox/workbench/workbench.html)  
**Response:** Danh sách đơn hàng

#### 5.2. Lấy chi tiết đơn hàng

**Endpoint:** `GET /api/orders/:id`  
**Response:** Chi tiết đơn hàng

#### 5.3. Hủy đơn hàng

**Endpoint:** `PUT /api/orders/:id/cancel`  
**Request:** Lý do hủy  
**Response:** Thông tin đơn hàng đã cập nhật

### 6. Yêu thích & Lưu trữ _Bỏ_

#### 6.1. Thêm/xóa sản phẩm yêu thích

**Endpoint:** `POST /api/favorites/toggle`  
**Request:** ID sản phẩm  
**Response:** Trạng thái đã lưu hoặc hủy

#### 6.2. Lấy danh sách sản phẩm yêu thích

**Endpoint:** `GET /api/favorites`  
**Parameters:** `page`, `limit`  
**Response:** Danh sách sản phẩm yêu thích

### 7. Địa chỉ giao hàng

#### 7.1. Thêm địa chỉ mới

**Endpoint:** `POST /api/users/addresses`  
**Request:** Thông tin địa chỉ  
**Response:** Địa chỉ đã thêm

#### 7.2. Lấy danh sách địa chỉ

**Endpoint:** `GET /api/users/addresses`  
**Response:** Danh sách địa chỉ của người dùng

#### 7.3. Cập nhật địa chỉ

**Endpoint:** `PUT /api/users/addresses/:id`  
**Request:** Thông tin cần cập nhật  
**Response:** Địa chỉ đã cập nhật

#### 7.4. Xóa địa chỉ

**Endpoint:** `DELETE /api/users/addresses/:id`  
**Response:** Thông báo thành công

## II. API cho phần Admin

### 1. Quản lý sản phẩm

#### 1.1. Lấy danh sách sản phẩm (admin)

**Endpoint:** `GET /api/admin/products`  
**Parameters:** `page`, `limit`, `search`, `category_id`, [status](vscode-file://vscode-app/opt/visual-studio-code/resources/app/out/vs/code/electron-sandbox/workbench/workbench.html), `date_start`, `date_end`  
**Response:** Danh sách sản phẩm với thông tin chi tiết

#### 1.2. Thêm sản phẩm mới

**Endpoint:** `POST /api/admin/products`  
**Request:** Thông tin sản phẩm, biến thể, hình ảnh  
**Response:** Thông tin sản phẩm đã tạo

#### 1.3. Lấy chi tiết sản phẩm (admin)

**Endpoint:** `GET /api/admin/products/:id`  
**Response:** Chi tiết đầy đủ của sản phẩm

#### 1.4. Cập nhật sản phẩm

**Endpoint:** `PUT /api/admin/products/:id`  
**Request:** Thông tin cần cập nhật  
**Response:** Thông tin sản phẩm đã cập nhật

#### 1.5. Xóa sản phẩm

**Endpoint:** `DELETE /api/admin/products/:id`  
**Response:** Thông báo thành công

#### 1.6. Thêm biến thể sản phẩm

**Endpoint:** `POST /api/admin/products/:id/variants`  
**Request:** Thông tin biến thể mới  
**Response:** Thông tin biến thể đã tạo

#### 1.7. Cập nhật biến thể sản phẩm

**Endpoint:** `PUT /api/admin/products/:id/variants/:variantId`  
**Request:** Thông tin cần cập nhật  
**Response:** Thông tin biến thể đã cập nhật

#### 1.8. Xóa biến thể sản phẩm

**Endpoint:** `DELETE /api/admin/products/:id/variants/:variantId`  
**Response:** Thông báo thành công

### 2. Quản lý danh mục

#### 2.1. Lấy danh sách danh mục (admin)

**Endpoint:** `GET /api/admin/categories`  
**Parameters:** `search`, [status](vscode-file://vscode-app/opt/visual-studio-code/resources/app/out/vs/code/electron-sandbox/workbench/workbench.html)  
**Response:** Danh sách danh mục đầy đủ

#### 2.2. Thêm danh mục mới

**Endpoint:** `POST /api/admin/categories`  
**Request:** Tên, mô tả, trạng thái  
**Response:** Thông tin danh mục đã tạo

#### 2.3. Cập nhật danh mục

**Endpoint:** `PUT /api/admin/categories/:id`  
**Request:** Thông tin cần cập nhật  
**Response:** Thông tin danh mục đã cập nhật

#### 2.4. Xóa danh mục

**Endpoint:** `DELETE /api/admin/categories/:id`  
**Response:** Thông báo thành công

### 3. Quản lý đơn hàng

#### 3.1. Lấy danh sách đơn hàng (admin)

**Endpoint:** `GET /api/admin/orders`  
**Parameters:** `page`, `limit`, `search`, [status](vscode-file://vscode-app/opt/visual-studio-code/resources/app/out/vs/code/electron-sandbox/workbench/workbench.html), `date_start`, `date_end`  
**Response:** Danh sách đơn hàng

#### 3.2. Lấy chi tiết đơn hàng (admin)

**Endpoint:** `GET /api/admin/orders/:id`  
**Response:** Chi tiết đơn hàng đầy đủ

#### 3.3. Cập nhật trạng thái đơn hàng

**Endpoint:** `PUT /api/admin/orders/:id/status`  
**Request:** Trạng thái mới, ghi chú  
**Response:** Thông tin đơn hàng đã cập nhật

#### 3.4. Hủy đơn hàng (admin)

**Endpoint:** `PUT /api/admin/orders/:id/cancel`  
**Request:** Lý do hủy  
**Response:** Thông tin đơn hàng đã cập nhật

### 4. Quản lý nhà cung cấp

#### 4.1. Lấy danh sách nhà cung cấp

**Endpoint:** `GET /api/admin/suppliers`  
**Parameters:** `page`, `limit`, `search`, [status](vscode-file://vscode-app/opt/visual-studio-code/resources/app/out/vs/code/electron-sandbox/workbench/workbench.html)  
**Response:** Danh sách nhà cung cấp

#### 4.2. Thêm nhà cung cấp mới

**Endpoint:** `POST /api/admin/suppliers`  
**Request:** Thông tin nhà cung cấp  
**Response:** Thông tin nhà cung cấp đã tạo

#### 4.3. Lấy chi tiết nhà cung cấp

**Endpoint:** `GET /api/admin/suppliers/:id`  
**Response:** Chi tiết nhà cung cấp

#### 4.4. Cập nhật nhà cung cấp

**Endpoint:** `PUT /api/admin/suppliers/:id`  
**Request:** Thông tin cần cập nhật  
**Response:** Thông tin nhà cung cấp đã cập nhật

#### 4.5. Xóa nhà cung cấp

**Endpoint:** `DELETE /api/admin/suppliers/:id`  
**Response:** Thông báo thành công

### 5. Quản lý phiếu nhập hàng

#### 5.1. Lấy danh sách phiếu nhập

**Endpoint:** `GET /api/admin/imports`  
**Parameters:** `page`, `limit`, `search`, [status](vscode-file://vscode-app/opt/visual-studio-code/resources/app/out/vs/code/electron-sandbox/workbench/workbench.html), `date_start`, `date_end`  
**Response:** Danh sách phiếu nhập

#### 5.2. Thêm phiếu nhập mới

**Endpoint:** `POST /api/admin/imports`  
**Request:** Thông tin phiếu nhập, danh sách sản phẩm  
**Response:** Thông tin phiếu nhập đã tạo

#### 5.3. Lấy chi tiết phiếu nhập

**Endpoint:** `GET /api/admin/imports/:id`  
**Response:** Chi tiết phiếu nhập

#### 5.4. Cập nhật trạng thái phiếu nhập

**Endpoint:** `PUT /api/admin/imports/:id/status`  
**Request:** Trạng thái mới, ghi chú  
**Response:** Thông tin phiếu nhập đã cập nhật

#### 5.5. Hủy phiếu nhập

**Endpoint:** `PUT /api/admin/imports/:id/cancel`  
**Request:** Lý do hủy  
**Response:** Thông tin phiếu nhập đã cập nhật

### 6. Quản lý bảo hành

#### 6.1. Lấy danh sách bảo hành

**Endpoint:** `GET /api/admin/warranty`  
**Parameters:** `page`, `limit`, `search`, [status](vscode-file://vscode-app/opt/visual-studio-code/resources/app/out/vs/code/electron-sandbox/workbench/workbench.html), `date_start`, `date_end`  
**Response:** Danh sách bảo hành

#### 6.2. Thêm thông tin bảo hành

**Endpoint:** `POST /api/admin/warranty`  
**Request:** Thông tin bảo hành  
**Response:** Thông tin bảo hành đã tạo

#### 6.3. Lấy chi tiết bảo hành

**Endpoint:** `GET /api/admin/warranty/:id`  
**Response:** Chi tiết bảo hành

#### 6.4. Cập nhật thông tin bảo hành

**Endpoint:** `PUT /api/admin/warranty/:id`  
**Request:** Thông tin cần cập nhật  
**Response:** Thông tin bảo hành đã cập nhật

#### 6.5. Xóa thông tin bảo hành

**Endpoint:** `DELETE /api/admin/warranty/:id`  
**Response:** Thông báo thành công

### 7. Thống kê

#### 7.1. Thống kê thu chi

**Endpoint:** `GET /api/admin/statistics/revenue-cost`  
**Parameters:** `year`, [type](vscode-file://vscode-app/opt/visual-studio-code/resources/app/out/vs/code/electron-sandbox/workbench/workbench.html) (year, month, day)  
**Response:** Dữ liệu thống kê thu chi theo thời gian

#### 7.2. Thống kê tồn kho

**Endpoint:** `GET /api/admin/statistics/inventory`  
**Parameters:** `page`, `limit`, `search`, `sort`  
**Response:** Dữ liệu thống kê tồn kho sản phẩm

#### 7.3. Thống kê dashboard

**Endpoint:** `GET /api/admin/statistics/dashboard`  
**Response:** Dữ liệu tổng quan cho dashboard

#### 7.4. Thống kê doanh thu theo sản phẩm

**Endpoint:** `GET /api/admin/statistics/revenue-by-products`  
**Parameters:** `year`, `month`, `limit`  
**Response:** Dữ liệu doanh thu theo từng sản phẩm

#### 7.5. Thống kê doanh thu theo danh mục

**Endpoint:** `GET /api/admin/statistics/revenue-by-categories`  
**Parameters:** `year`, `month`, `limit`  
**Response:** Dữ liệu doanh thu theo từng danh mục

### 8. Quản lý người dùng và phân quyền

#### 8.1. Lấy danh sách người dùng

**Endpoint:** `GET /api/admin/users`  
**Parameters:** `page`, `limit`, `search`, [status](vscode-file://vscode-app/opt/visual-studio-code/resources/app/out/vs/code/electron-sandbox/workbench/workbench.html), [role](vscode-file://vscode-app/opt/visual-studio-code/resources/app/out/vs/code/electron-sandbox/workbench/workbench.html)  
**Response:** Danh sách người dùng

#### 8.2. Thêm người dùng mới

**Endpoint:** `POST /api/admin/users`  
**Request:** Thông tin người dùng  
**Response:** Thông tin người dùng đã tạo

#### 8.3. Lấy chi tiết người dùng

**Endpoint:** `GET /api/admin/users/:id`  
**Response:** Chi tiết người dùng

#### 8.4. Cập nhật thông tin người dùng

**Endpoint:** `PUT /api/admin/users/:id`  
**Request:** Thông tin cần cập nhật  
**Response:** Thông tin người dùng đã cập nhật

#### 8.5. Xóa người dùng

**Endpoint:** `DELETE /api/admin/users/:id`  
**Response:** Thông báo thành công

#### 8.6. Lấy danh sách nhóm quyền

**Endpoint:** `GET /api/admin/roles`  
**Parameters:** `search`, [status](vscode-file://vscode-app/opt/visual-studio-code/resources/app/out/vs/code/electron-sandbox/workbench/workbench.html)  
**Response:** Danh sách nhóm quyền

#### 8.7. Thêm nhóm quyền mới

**Endpoint:** `POST /api/admin/roles`  
**Request:** Thông tin nhóm quyền  
**Response:** Thông tin nhóm quyền đã tạo

#### 8.8. Cập nhật nhóm quyền

**Endpoint:** `PUT /api/admin/roles/:id`  
**Request:** Thông tin cần cập nhật  
**Response:** Thông tin nhóm quyền đã cập nhật

#### 8.9. Xóa nhóm quyền

**Endpoint:** `DELETE /api/admin/roles/:id`  
**Response:** Thông báo thành công

### 9. Quản lý mã giảm giá

#### 9.1. Lấy danh sách mã giảm giá

**Endpoint:** `GET /api/admin/coupons`  
**Parameters:** `page`, `limit`, `search`, [status](vscode-file://vscode-app/opt/visual-studio-code/resources/app/out/vs/code/electron-sandbox/workbench/workbench.html), `date_start`, `date_end`  
**Response:** Danh sách mã giảm giá

#### 9.2. Thêm mã giảm giá mới

**Endpoint:** `POST /api/admin/coupons`  
**Request:** Thông tin mã giảm giá  
**Response:** Thông tin mã giảm giá đã tạo

#### 9.3. Cập nhật mã giảm giá

**Endpoint:** `PUT /api/admin/coupons/:id`  
**Request:** Thông tin cần cập nhật  
**Response:** Thông tin mã giảm giá đã cập nhật

#### 9.4. Xóa mã giảm giá

**Endpoint:** `DELETE /api/admin/coupons/:id`  
**Response:** Thông báo thành công

### 10. API Hệ thống và Cấu hình

#### 10.1. Lấy thông tin cấu hình website

**Endpoint:** `GET /api/admin/settings`  
**Response:** Thông tin cấu hình hệ thống

#### 10.2. Cập nhật cấu hình website

**Endpoint:** `PUT /api/admin/settings`  
**Request:** Thông tin cấu hình cần thay đổi  
**Response:** Thông tin cấu hình đã cập nhật

#### 10.3. Xem log hệ thống

**Endpoint:** `GET /api/admin/logs`  
**Parameters:** `page`, `limit`, `level`, `date_start`, `date_end`  
**Response:** Danh sách log hệ thống
