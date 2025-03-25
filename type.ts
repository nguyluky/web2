// Base entities

interface Item {
  id: string;
  name: string;
  description: string;
  price: number;
  user_id?: string;
  created_at?: string;
  updated_at?: string;
}

interface User {
  id: string;
  display_name?: string;
  email: string;
  user_name?: string;
  password?: string;
  rule?: string;
  fullname?: string;
  address?: string;
  phone_number?: string;
  avatar?: string;
}

interface Supplier {
  id: string;
  name: string;
  email: string;
  phone_number: string;
  status: string;
  created_at?: string;
  updated_at?: string;
}

interface Order {
  id: string;
  users_id: string;
  item_id: string;
  supplier_id: string;
  employee_id: string;
  order_detail: string;
  status: string;
  total?: number;
  account_id?: string;
  created_at?: string;
}

interface Import {
  id: string;
  item_id: string;
  supplier_id: string;
  status: string;
  import_detail: string;
  employee_id?: string;
  created_at?: string;
}

interface Warranty {
  id: string;
  item_id: string;
  user_id: string;
  supplier_id: string;
  issue_date: string;
  expiration_date: string;
  product_id?: string;
  account_id?: string;
  start_date?: string;
  end_date?: string;
  status?: string;
  created_at?: string;
}

interface Cart {
  user_id: string;
  item_id: string;
  quantity: number;
  account_id?: string;
  product_id?: string;
}

// Request types

// Item requests
type CreateItemRequest = {
  name: string;
  description: string;
};

type UpdateItemRequest = Partial<CreateItemRequest>;

// User requests
type CreateUserRequest = {
  fullname: string;
  address: string;
  phone_number: string;
  email: string;
  avatar: string;
  rule: string;
};

type UpdateUserRequest = Partial<CreateUserRequest>;

// Supplier requests
type CreateSupplierRequest = {
  name: string;
  phone_number: string;
  email: string;
  status: string;
};

type UpdateSupplierRequest = Partial<CreateSupplierRequest>;

// Order requests
type CreateOrderRequest = {
  account_id: string;
  status: string;
  employee_id: string;
  order_detail: string;
  total?: number;
};

// Import requests
type CreateImportRequest = {
  supplier_id: string;
  employee_id: string;
  status: string;
  import_detail: string;
};

// Warranty requests
type UpdateWarrantyRequest = {
  product_id: string;
  account_id: string;
  supplier_id: string;
  start_date: string;
  end_date: string;
  status: string;
  issue_date?: string;
  expiration_date?: string;
};

// Cart requests
type CreateCartRequest = {
  account_id: string;
  product_id: string;
  quantity: number;
};

type UpdateCartRequest = {
  product_id: string;
  quantity: number;
};

// Response types

// Item responses
type GetItemResponse = {
  items: Array<Item>;
};

type GetItemByIdResponse = {
  item: Item;
};

type CreateItemResponse = {
  message: string;
  item: Item;
};

type UpdateItemResponse = {
  message: string;
  item: Item;
};

type DeleteItemResponse = {
  message: string;
};

// User responses
type GetUsersResponse = {
  users: Array<User>;
};

type GetUserByIdResponse = {
  user: User;
};

type GetUserByRuleResponse = {
  users: User | Array<User>;
};

type CreateUserResponse = {
  message: string;
  item: User;
};

type UpdateUserResponse = {
  message: string;
  user: User;
};

type DeleteUserResponse = {
  message: string;
};

// Supplier responses
type GetSuppliersResponse = {
  suppliers: Array<Supplier>;
};

type GetSupplierByIdResponse = {
  supplier: Supplier;
};

type CreateSupplierResponse = {
  message: string;
  supplier: Supplier;
};

type UpdateSupplierResponse = {
  message: string;
  supplier: Supplier;
};

type DeleteSupplierResponse = {
  message: string;
};

// Order responses
type GetOrdersResponse = {
  orders: Array<Order>;
};

type GetOrderByIdResponse = {
  order: Order;
};

type CreateOrderResponse = {
  message: string;
  order: Order;
};

// Import responses
type GetImportsResponse = {
  "Import orders": Array<Import>;
};

type GetImportByIdResponse = {
  order: Import;
};

type CreateImportResponse = {
  message: string;
  order: Import;
};

// Warranty responses
type GetWarrantiesResponse = {
  warranty: Array<Warranty>;
};

type GetWarrantyByIdResponse = {
  warranty: Warranty;
};

type GetWarrantyByAccountIdResponse = {
  warranty: Warranty;
};

type GetWarrantyByProductIdResponse = {
  warranty: Warranty;
};

type UpdateWarrantyResponse = {
  message: string;
  warranty: Warranty;
};

// Cart responses
type GetCartByAccountIdResponse = {
  carts: Cart | Array<Cart>;
};

type GetCartByProductIdResponse = {
  carts: Cart;
};

type CreateCartResponse = {
  message: string;
};

type UpdateCartResponse = {
  message: string;
};

type DeleteCartResponse = {
  message: string;
};

// Error responses
type ErrorResponse = {
  error: string;
  fields?: Array<string>;
};
