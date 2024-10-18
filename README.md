![Screenshot 2024-10-17 235728](https://github.com/user-attachments/assets/3e3fcc01-21e6-470e-993f-7a3f2f782bcc)# Aetherial Bakery
**A Laravel project for Midterm of Framework Programming (IUP Class)**

## Team Members
| Name        | NRP         |
| --------------- | ---------------- |
| Shafa Kirana Mulia       | 5025221078         |
| Fellyla Fiorenza Wilianto       | 5025221110        |

---
### YouTube Presentation Recording
https://youtu.be/Ky3afU6_Pbs?si=cbJfzJVT4eeM5Mo4.

### Project Explanation

For our Laravel Final Project, our group developed a web application modeled after an E-Commerce platform, which we named "Atherial Bakery." This project is designed as an online bakery shop, offering users a seamless and interactive experience when browsing through a wide variety of bakery products. Customers can explore different menu catalogs, featuring detailed product descriptions, images, and prices. For those interested in making a purchase, a user-friendly checkout system has been integrated, which is accessible upon logging in as a customer.

What sets this project apart is the dual point-of-view approach, allowing access for both customers and admin. Customers can enjoy a straightforward shopping experience, while administrators are provided with tools to manage the platform efficiently. As admins, they can add and categorize new product types, ensuring the menu remains up-to-date. They also have access to manage customer orders by tracking the delivery and payment statuses, enabling smoother operations from behind the scenes.

By blending these essential e-commerce features with a polished user interface, "Atherial Bakery" provides an appealing and functional platform for both customers and administrators, designed to enhance the overall experience of running and interacting with an online bakery.



### CDM and PDM
CDM 

![PBKK-2024-10-16_13-43](https://github.com/user-attachments/assets/beb7e7d4-e489-4924-b803-d7c769e5e338)

PDM

![PBKK_Physical_Export-2024-10-16_13-43](https://github.com/user-attachments/assets/5681c113-41a6-40fe-9cbf-6f00fa201087)






### Admin Panel

1. **Authentication**: Users can sign up, log in, and log out, managed by using Filament.
2. **Dashboard**: Displays statistics on recent orders, total sales, and order statuses. Includes a search feature for quick navigation.
3. **User Management**: Admins can view a list of all users, create new users, and edit or delete existing users.
4. **Product Management**: Admins can manage product details, including adding new products, updating existing products, and deleting products.
5. **Order Management**: Admins can view and filter orders by status, update order status, and view detailed information on each order.

### User Panel
1. **Authentication**: Users can sign up, log in, and log out, managed by using Livewire.
2. **Homepage**: Displays a list of products with filtering by category, product status, and price range.
3. **Category**: List all of categories and can direct to the products page which has been filtered with the category selected before.
4. **Product**: User can browse for products, select, and add products to cart.
5. **Cart Management**: Users can add products to the cart, adjust quantities, and remove products. Cart data is stored using browser cookies for persistence.
6. **Checkout**: Users can view an order summary, enter their personal data for shipping purposes, and place an order with provided payment methods.
7. **Success Page**: Users can get the success page after checkouting that includes the summary of the order with the order ID.
8. **My Orders** : When users click the login/user bottom, it will display three options and one of them it's my orders page. Here, users can see all of their order with the status of payment and shipping.
9. **My Oders Detail** : Users can see the order summary detail when clicking the "view page" button in My Orders page. It will display the order summary in more detail.

### Database Migrations
#### 1. `create_categories_table`
```php
...
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
...
```
#### 2. `create_products_table`
```php
...
 public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->json('images')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('in_stock')->default(true);
            $table->boolean('on_sale')->default(false);
            $table->timestamps();
        });
    }
...
```
#### 3. `create_orders_table`
```php
...
public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('grand_total', 10,2)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->nullable();
            $table->enum('status', ['new', 'processing', 'shipped', 'delivered','canceled'])
            ->default('new');
            $table->string('currency')->nullable();
            $table->decimal('shipping_amount', 10,2)->nullable();
            $table->string('shipping_method')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
...
```
#### 4. `create_order_items_table`
```php
...
public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_amount', 10,2)->nullable();
            $table->decimal('total_amount', 10,2)->nullable();
            $table->timestamps();
        });
    }
...
```
#### 5. `create_customers_table`
```php
...
public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete(); 
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->text('street_address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('zip_code')->nullable();
            $table->timestamps();
        });
    }
...
```

### Admin Panel Explanation
In Filament, a resource represents a data model (such as Category, Order, Product, or User) and provides functionality for CRUD. Each resource typically contains pages for listing, creating, editing, and viewing the data related to that model.

#### a. `CategoryResource`
- The `CreateCategory.php`, `EditCategory.php` and `ListCategories.php` are responsible for the creation, editing, and listing of categories respectively.
- Then, the `CategoryResource.php` defines the overall configuration of how the category model is represented within the Filament admin panel.

```php
// CategoryResource.php

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?int $navigationSort = 3;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Section::make([
                Grid::make()->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => 
                            $operation === 'create' ? $set('slug', Str::slug($state)) : null
                        ),

                    TextInput::make('slug')
                        ->maxLength(255)
                        ->disabled()
                        ->required()
                        ->dehydrated()
                        ->unique(Category::class, 'slug', ignoreRecord: true),

                    FileUpload::make('image')
                        ->image()
                        ->directory('categories'),

                    Toggle::make('is_active')
                        ->required()
                        ->default(true),
                ]),
            ]),
        ]);
    }
    ...
}
```

#### b. `OrderResource`
- The  `CreateOrder.php`, `EditOrder.php`, `ListOrders.php`, and `ViewOrder.php` inside `Pages` folder handle order creation, editing, listing, and viewing. The `AddressRelationManager.php` and `CustomerRelationManager.php` inside the `RelationManagers` folder manage the relationships between orders and their respective addresses and customers. Furthermore, the `OrderStats.php` inside the `Widgets` folder will displays statistical data for orders.
- Furthermore, the `OrderResource.php` will organizes all the CRUD operations and relations for the Order model.

```php
// OrderResource.php

...
class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 4;
    public static function form(Form $form): Form
    {
        return $form->schema([
            Group::make()->schema([
                Section::make('Order Information')->schema([
                    Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('payment_method')
                        ->options([
                            'ewallet' => 'E-Wallet',
                            'cod' => 'Cash On Delivery',
                        ])
                        ->required(), 
                    ...
                ])
            ]);
    }
}
...
```

#### c. `ProductResource`
- The `CreateProduct.php`, `EditProduct.php`, and `ListProducts.php` manage product creation, editing, and listing.
- Then, the `ProductResource.php` defines the CRUD operations and relationships for the Product model.

```php
// ProductResource.php

...
class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?int $navigationSort = 3;
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Product Information')->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, Set $set) {
                                if ($operation !== 'create') {
                                    return;
                                }
                                $set('slug', Str::slug($state));
                            }),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated()
                            ->unique(Product::class, 'slug', ignoreRecord: true),

                        MarkdownEditor::make('description')
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('products')

                    ])->columns(2),
                    ...
                    ]),
            ]);
    }
...
```

#### d. `UserResource`
- The `UserResource.php` is responsible for managing CRUD operations for users. This includes the management for listing, creating, and editing users, with pages like `CreateUser`, `EditUser`, and `ListUsers`

```php
// UserResource.php 

...
public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),

                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->required(),

                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('Email Verified At')
                    ->default(now()),

                TextInput::make('password')
                    ->password()
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn($livewire): bool => $livewire instanceof Pages\CreateUser),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
...
```

---

### User Panels Explanation


1. **Authentication**: Users can sign up, log in, and log out, managed by using Filament.
   
   - Login Page
  
     ![Screenshot 2024-10-17 234304](https://github.com/user-attachments/assets/b3aacf5c-4d31-42c5-b09b-a3b8fb7b17f2)

   - Register Page

     ![Screenshot 2024-10-17 234304](https://github.com/user-attachments/assets/45ad1905-0ef6-4d51-9c43-1e1bdd9cfb1e)
     

2. **Homepage**: Displays the homepage of Aetherial Bakery along with browse categories, and customer reviews.
   
   - Dashboard
   
   ![Screenshot 2024-10-17 234359](https://github.com/user-attachments/assets/734a68f3-5e1c-447b-9ec4-3c8d87508498)


3. **Category Page**: List all of categories and can direct to the products page which has been filtered with the category selected before.

   - Browse categories page

     ![Screenshot 2024-10-17 234427](https://github.com/user-attachments/assets/38abe1be-50ba-4af0-b61e-172da3cc7e8c)

4.  **Product**: User can browse for products, select, view product detail and add the quantity before adding products to cart.
   
   - Products Page

     ![Screenshot 2024-10-17 234451](https://github.com/user-attachments/assets/7e74408a-c320-4bb5-8d30-8e4a90944121)

   - View Product Detail, adding quantity

     ![image](https://github.com/user-attachments/assets/d560a444-2ec4-452c-87dc-2bf28f3ea552)

   - Add to cart. The cart quantity automatically updated according the amount of products added to the cart.
     
     ![image](https://github.com/user-attachments/assets/64e5808c-d18c-4e46-82d3-189e5e953de0)

     ![image](https://github.com/user-attachments/assets/6d38a340-9bb5-441d-9da8-c22451189610)



   
5.  **Cart Management**: Users can add products to the cart, adjust quantities, and remove products. Cart data is stored using browser cookies for persistence. In this state, it will display the subtotal of each product and the grand total.
   
  -  Cart
    ![Screenshot 2024-10-17 235256](https://github.com/user-attachments/assets/b8adebc4-15fb-40b8-95c8-fc71dc41633c)

  - When there's nothing on the cart
    
    ![image](https://github.com/user-attachments/assets/d14462ee-841d-4057-8e45-c0bc5a4dda75)


6.  **Checkout**: Users can view an order summary, enter their personal data for shipping purposes, and place an order with provided payment methods.
   
   - Checkout Page
     
     ![Screenshot 2024-10-17 235719](https://github.com/user-attachments/assets/3f5e7f90-0b25-40c9-a617-675064d5f92a)
     

7.  **Success Page**: Users can get the success page after checkouting that includes the summary of the order with the order ID.
   
   - Success Order Summary
     
     ![Screenshot 2024-10-17 235728](https://github.com/user-attachments/assets/307de810-7379-43ed-9c48-4b1e3b168137)
     

9. **My Orders** : When users click the login/user bottom, it will display three options and one of them it's my orders page. Here, users can see all of their order with the status of payment and shipping.
    
    - My Orders Page
      
      ![Screenshot 2024-10-17 235758](https://github.com/user-attachments/assets/8e9a025c-4168-4131-b761-8b88abdd7ec7)
   
    - My Orders Page location
      
      ![Screenshot 2024-10-17 235739](https://github.com/user-attachments/assets/7e7d55b0-d386-4cc9-b36f-170ed7af7f69)


10.  **My Oders Detail** : Users can see the order summary detail when clicking the "view page" button in My Orders page. It will display the order summary in more detail.
    
  - My Orders Detail
    
    ![WhatsApp Image 2024-10-19 at 01 11 25](https://github.com/user-attachments/assets/cd259ae4-7eec-4f20-a25d-67c3be3a1660)


    


    
12.  
    







