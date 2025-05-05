<?php

/**
 * @OA\Info(
 *   title="Web2 API Documentation",
 *   version="1.0.0",
 *   description="Tài liệu API cho web2. Tất cả các request API phải có header Accept: application/json và Content-Type: application/json",
 *   @OA\Contact(
 *     email="admin@example.com",
 *     name="Admin"
 *   )
 * ),
 * @OA\Consumes({"application/json"}),
 * @OA\Produces({"application/json"})
 */

/**
 * @OA\Schema(
 *   schema="Product",
 *   required={"name", "category_id", "supplier_id"},
 *   @OA\Property(property="id", type="integer", format="int64"),
 *   @OA\Property(property="name", type="string", maxLength=255),
 *   @OA\Property(property="description", type="string"),
 *   @OA\Property(property="category_id", type="integer"),
 *   @OA\Property(property="supplier_id", type="integer"),
 *   @OA\Property(property="status", type="integer"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *   schema="Category",
 *   required={"name"},
 *   @OA\Property(property="id", type="integer", format="int64"),
 *   @OA\Property(property="name", type="string", maxLength=255),
 *   @OA\Property(property="image", type="string"),
 *   @OA\Property(property="description", type="string"),
 *   @OA\Property(property="parent_id", type="integer"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *   schema="Order",
 *   required={"account_id", "address_id", "amount"},
 *   @OA\Property(property="id", type="integer", format="int64"),
 *   @OA\Property(property="account_id", type="integer"),
 *   @OA\Property(property="address_id", type="integer"),
 *   @OA\Property(property="amount", type="number", format="float"),
 *   @OA\Property(property="status", type="integer"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *   schema="OrderDetail",
 *   required={"order_id", "product_id", "variant_id", "amount", "price"},
 *   @OA\Property(property="id", type="integer", format="int64"),
 *   @OA\Property(property="order_id", type="integer"),
 *   @OA\Property(property="product_id", type="integer"),
 *   @OA\Property(property="variant_id", type="integer"),
 *   @OA\Property(property="amount", type="integer"),
 *   @OA\Property(property="price", type="number", format="float"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *   schema="Supplier",
 *   required={"name", "email", "phone", "address"},
 *   @OA\Property(property="id", type="integer", format="int64"),
 *   @OA\Property(property="name", type="string", maxLength=255),
 *   @OA\Property(property="email", type="string", format="email"),
 *   @OA\Property(property="phone", type="string"),
 *   @OA\Property(property="address", type="string"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *   schema="Import",
 *   required={"supplier_id", "amount"},
 *   @OA\Property(property="id", type="integer", format="int64"),
 *   @OA\Property(property="supplier_id", type="integer"),
 *   @OA\Property(property="amount", type="number", format="float"),
 *   @OA\Property(property="status", type="integer"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *   schema="Warranty",
 *   required={"product_id", "period"},
 *   @OA\Property(property="id", type="integer", format="int64"),
 *   @OA\Property(property="product_id", type="integer"),
 *   @OA\Property(property="period", type="integer"),
 *   @OA\Property(property="description", type="string"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *   schema="Account",
 *   required={"email", "password"},
 *   @OA\Property(property="id", type="integer", format="int64"),
 *   @OA\Property(property="email", type="string", format="email"),
 *   @OA\Property(property="password", type="string", format="password"),
 *   @OA\Property(property="status", type="integer"),
 *   @OA\Property(property="rule_id", type="integer"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *   schema="Profile",
 *   required={"account_id", "fullname"},
 *   @OA\Property(property="id", type="integer", format="int64"),
 *   @OA\Property(property="account_id", type="integer"),
 *   @OA\Property(property="fullname", type="string"),
 *   @OA\Property(property="phone", type="string"),
 *   @OA\Property(property="avatar", type="string"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *   schema="Address",
 *   required={"account_id", "address"},
 *   @OA\Property(property="id", type="integer", format="int64"),
 *   @OA\Property(property="account_id", type="integer"),
 *   @OA\Property(property="fullname", type="string"),
 *   @OA\Property(property="phone", type="string"),
 *   @OA\Property(property="address", type="string"),
 *   @OA\Property(property="is_default", type="boolean"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *   schema="Rule",
 *   required={"name"},
 *   @OA\Property(property="id", type="integer", format="int64"),
 *   @OA\Property(property="name", type="string"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *   schema="ProductVariant",
 *   required={"product_id", "name", "price"},
 *   @OA\Property(property="id", type="integer", format="int64"),
 *   @OA\Property(property="product_id", type="integer"),
 *   @OA\Property(property="name", type="string"),
 *   @OA\Property(property="price", type="number", format="float"),
 *   @OA\Property(property="quantity", type="integer"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *   schema="Cart",
 *   required={"account_id", "variant_id", "amount"},
 *   @OA\Property(property="account_id", type="integer"),
 *   @OA\Property(property="variant_id", type="integer"),
 *   @OA\Property(property="amount", type="integer"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *   schema="ProductReview",
 *   required={"product_id", "account_id", "rating", "comment"},
 *   @OA\Property(property="id", type="integer", format="int64"),
 *   @OA\Property(property="product_id", type="integer"),
 *   @OA\Property(property="account_id", type="integer"),
 *   @OA\Property(property="rating", type="integer", minimum=1, maximum=5),
 *   @OA\Property(property="comment", type="string"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="JWT"
 * )
 */