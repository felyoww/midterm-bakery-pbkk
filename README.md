# Aetherial Bakery
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
4. **Categories Management** : Admin can add a new categories for the products. Including updating existing categories, and deleting it.
5. **Product Management**: Admins can manage product details, including adding new products, updating existing products, and deleting products.
6.  **Order Management**: Admins can view and filter orders by status, update order status, and view detailed information on each order after the users make an order automatically.


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

### Admin Panel Features and Explanation

1. **Authentication**: Users can sign up, log in, and log out, managed by using Filament.
   - Log in page for admin
     
     ![image](https://github.com/user-attachments/assets/c9310fa2-534f-4e08-a00a-a621b9729928)

   - When User tried to access admin page, it will display "500 Forbidden". It preventing users to login as admin.
     
     ![image](https://github.com/user-attachments/assets/2e84e5af-1b09-4dd7-8a40-d22aa6eb6e04)

     
3. **Dashboard**: Displays statistics on recent orders, total sales, and order statuses. Includes a search feature for quick navigation.
   
   - Admin Dashboard Page
     
     ![Screenshot 2024-10-17 234012](https://github.com/user-attachments/assets/527a6ef2-bb7e-46a7-8e82-47e6c3ca9829)

5. **User Management**: Admins can view a list of all users, create new users, and edit or delete existing users.

   - Users data in admin page
     
     ![Screenshot 2024-10-17 234024](https://github.com/user-attachments/assets/cc56d21f-22ee-4d29-b949-cb889f49da3e)
     
6. **Categories Management** : Admin can add a new categories for the products. Including updating existing categories, and deleting it.
   
   - Categories page in admin
     
     ![Screenshot 2024-10-17 234033](https://github.com/user-attachments/assets/aa377961-02ca-4cf8-9fdb-9da9a525f9f0)


8. **Product Management**: Admins can manage product details, including adding new products, updating existing products, and deleting products.

   - Products data in admin page

     ![Screenshot 2024-10-17 234051](https://github.com/user-attachments/assets/3c583f97-b968-4205-bc7c-977cf92e504b)

10. **Order Management**: Admins can view and filter orders by status, update order status, and view detailed information on each order after the users make an order automatically.

    - Order Management in admin page

      ![Screenshot 2024-10-17 234058](https://github.com/user-attachments/assets/e6b5e115-783b-4570-86a1-498b9d6ca5e8)
      


### User Panels Fetaures and Exolanation


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


    

## Code Explanation for Users Panel
### Models and Livewire
1.  For users panel, we use livewire to act as `Controller`for each features. In Model, we define the fillable coloumn and also the `relationship` between tables to tables.
   
   ![image](https://github.com/user-attachments/assets/411e9bb3-e861-4c30-85ff-e2869f3ebcd9)

   - `SucessPage.php`
     ```php
     ...
             namespace App\Livewire;
        
        use App\Models\Order;
        use Livewire\Component;
        use Livewire\Attributes\Title;
        use Livewire\Attributes\Url;
        use Stripe\Checkout\Session;
        use Stripe\Stripe;
        
        #[Title('Success - Atherial Bakery')]
        class SuccessPage extends Component
        {
            #[Url]
            public $session_id;
        
            public function render()
            {
                $latest_order = Order::with('customer')->where('user_id', auth()->user()->id)->latest()->first();
        
                if ($this->session_id) {
                   
                    $session_info = Session::retrieve($this->session_id);
        
                    if ($session_info->payment_status != 'paid') {
                        $latest_order->payment_status = 'failed';
                        $latest_order->save();
                        return redirect()->route('cancel');
                    } elseif ($session_info->payment_status == 'paid') {
                        $latest_order->payment_status = 'paid';
                        $latest_order->save();
                    }
                }
               
        
                return view('livewire.success-page', [
                    'order' => $latest_order,
                ]);
            }
        }

     ...
     ```

     - `LoginPage.php` Livewire/controller
       
       ```php
       ...
        namespace App\Livewire\Auth;
        
        use Livewire\Component;
        use Livewire\Attributes\Title;
        
        #[Title('login')]
        class LoginPage extends Component
        {
            public $email;
            public $password;
        
            public function save()
            {
                // Validate the email and password inputs
                $this->validate([
                    'email' => 'required|email|max:255|exists:users,email',
                    'password' => 'required|min:6|max:255',
                ]);
        
                // Attempt to log in the user with provided credentials
                if (!auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
                    // If login fails, flash an error message to the session
                    session()->flash('error', 'Invalid credentials');
                    return;
                }
       ...
       ```
       
     - `RegisterPage.php` Livewire/controller
       
       ```php
       ...
       namespace App\Livewire\Auth;
            
            use App\Models\User;
            use Livewire\Component;
            use Livewire\Attributes\Title;
            use Illuminate\Support\Facades\Hash;
            
            #[Title('Register')]
            class RegisterPage extends Component
            {
                public $name;
                public $email;
                public $password;
            
                // Register user
                public function save()
                {
                    $this->validate([
                        'name' => 'required|max:255',
                        'email' => 'required|email|unique:users|max:255',
                        'password' => 'required|min:6|max:255',
                    ]);
            
                    // Save to database
                    $user = User::create([
                        'name' => $this->name,
                        'email' => $this->email,
                        'password' => Hash::make($this->password),
                    ]);
            
                    // Login user
                    auth()->login($user);
            
                    // Redirect to homepage
                    return redirect()->intended();
                }
            
                public function render()
                {
                    return view('livewire.auth.register-page');
                }
            }
       ...
       ```
       
     - `CancelPage.php` Livewire/controller
       
       ```php
       ...
       namespace App\Livewire;
            
            use Livewire\Component;
            
            class CancelPage extends Component
            {
                public function render()
                {
                    return view('livewire.cancel-page');
                }
            }

       ...
       ```
       
     - `Categories.php` Livewire/controller
       
       ```php
       ...
       namespace App\Livewire;
            
            use Livewire\Component;
            use App\Models\Category;
            use Livewire\Attributes\Title;
            
            #[Title('Categories - Atherial Bakery')]
            class CategoriesPage extends Component
            {
                public function render()
                {
                    $categories = Category::where('is_active', 1)->get();
                    return view('livewire.categories-page', [
                        'categories' => $categories,
                    ]);
                }
            }

       ...
       ```
       
     - `CheckoutPage.php` Livewire/controller
       
       ```php
       ...
       namespace App\Livewire;
        
        use App\Mail\OrderPlaced;
        use App\Models\Order;
        use App\Models\Customer;
        use Livewire\Component;
        use Livewire\Attributes\Title;
        use App\Helpers\CartManagement;
        use Illuminate\Support\Facades\Mail;
        
        #[Title('Checkout')]
        class CheckoutPage extends Component
        {
        
            public $first_name;
            public $last_name;
            public $phone;
            public $street_address;
            public $city;
            public $zip_code;
            public $province;
            public $payment_method;
        
            public function mount(){
                $cart_items = CartManagement::getCartItemsFromCookie();
                if(count($cart_items) == 0){
                    return redirect('/products');
                }
            }
        
            public function placeOrder(){
                
                $this->validate([
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'phone' => 'required',
                    'street_address' => 'required',
                    'city' => 'required',
                    'province' => 'required',
                    'zip_code' => 'required',
                    'payment_method' => 'required'
                ]);
                //dump("hit");
            
                $cart_items = CartManagement::getCartItemsFromCookie();
        
                // Use auth()->user() properly
                $order = new Order();
                $order->user_id = \Illuminate\Support\Facades\Auth::user()->id;
                $order->grand_total = CartManagement::calculateGrandTotal($cart_items);
                $order->payment_method = $this->payment_method;
                $order->payment_status = 'pending';
                $order->status = 'new';
                $order->currency = 'idr';
                $order->shipping_amount = 0;
                $order->shipping_method = 'none';
                $order->notes = 'Order placed by ' . \Illuminate\Support\Facades\Auth::user()->name;
       ...
       ```
       
     - `HomePage.php` Livewire/controller
       
       ```php
       ...
       namespace App\Livewire;
        
        use App\Models\Brand;
        use App\Models\Category;
        use Livewire\Component;
        use Livewire\Attributes\Title;
        
        #[Title('Home Page - Aetherial Bakery')]
        class HomePage extends Component
        {
            public function render()
            {
                $categories = Category::where('is_active', 1)->get();
                return view('livewire.home-page', [
                    'categories' => $categories,
                ]);
            }
        }

       ...
       ```
     - `MyOrdersPage.php` Livewire/controller
       
       ```php
       ...
       namespace App\Livewire;
            
            use App\Models\Order;
            use Livewire\Component;
            use Livewire\Attributes\Title;
            use Livewire\WithPagination;
            
            #[Title('My Orders')]
            class MyOrdersPage extends Component
            {
                use WithPagination;
            
            
                public function render()
                {
                    $my_orders = Order::where('user_id', auth()->id())->latest()->paginate(10);
                    return view('livewire.my-orders-page', [
                        'orders' => $my_orders,
                    ]);
                }
            }

       ...
       ```
    
     - `MyOrderDetailPage.php` Livewire/controller
       
       ```php
       ...
       namespace App\Livewire;
        
        use App\Models\Customer;
        use App\Models\Order;
        use App\Models\OrderItem;
        use Livewire\Component;
        use Livewire\Attributes\Title;
        
        #[Title('Order Detail')]
        class MyOrderDetailPage extends Component
        {
            public $order_id;
        
            public function mount($order_id)
            {
                $this->order_id = $order_id;
            }
        
            public function render()
            {
                $order_items = OrderItem::with('product')->where('order_id', $this->order_id)->get();
                $customer = Customer::where('order_id', $this->order_id)->first();
                $order = Order::where('id', $this->order_id)->first();
                //dd($order_items);
                return view('livewire.my-order-detail-page', [
                    'order_items' => $order_items,
                    'customer' => $customer,
                    'order' => $order,
                ]);
            }
        }

       ...
       ```
    
     - `ProductsDetailPage.php` Livewire/controller
       
       ```php
       ...
       namespace App\Livewire;
        
        use App\Models\Product;
        use Livewire\Component;
        use Livewire\Attributes\Title;
        use App\Helpers\CartManagement;
        use App\Livewire\Partials\Navbar;
        use Jantinnerezo\LivewireAlert\LivewireAlert;
        
        #[Title('Product Detail - Atherial Bakery')]
        class ProductDetailPage extends Component
        {
            use LivewireAlert;
        
            public $slug;
            public $quantity = 1;
        
            public function mount($slug)
            {
                $this->slug = $slug;
            }
        
            public function increaseQty()
            {
                $this->quantity++;
            }
        
            public function decreaseQty()
            {
                if ($this->quantity > 1) {
                    $this->quantity--;
                }
            }
        
            public function addToCart($product_id)
            {
                $total_count = CartManagement::addItemToCartWithQty($product_id, $this->quantity);
        
                $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);
        
                $this->alert('success', 'Product added to the cart successfully!', [
                    'position' => 'bottom-end',
                    'timer' => 3000,
                    'toast' => true,
                ]);
            }
       ...
       ```
       
     - `ProductsPage.php` Livewire/controller
       
       ```php
       ...
       namespace App\Livewire;
        
        use App\Models\Product;
        use Livewire\Component;
        use App\Models\Category;
        use Livewire\Attributes\Url;
        use Livewire\WithPagination;
        use Livewire\Attributes\Title;
        use App\Helpers\CartManagement;
        use App\Livewire\Partials\Navbar;
        use Jantinnerezo\LivewireAlert\LivewireAlert;
        
        #[Title('Products - Atherial Bakery')]
        class ProductsPage extends Component
        {
            use LivewireAlert;
            use WithPagination;
        
            #[Url]
            public $selected_categories = [];
        
            #[Url]
            public $on_sale = [];
        
            #[Url]
            public $in_stock = [];
        
            #[Url]
            public $price_range = 400000;
        
            #[Url]
            public $sort = 'latest';
        
            // Add product to cart method
            public function addToCart($product_id)
            {
                $total_count = CartManagement::addItemToCart($product_id);
        
                $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);
        
                $this->alert('success', 'Product added to the cart successfully!', [
                    'position' => 'bottom-end',
                    'timer' => 3000,
                    'toast' => true,
                ]);
            }
       ...
       ```
    
    - `CartPage.php` Livewire/controller
        ```php
       ...
        namespace App\Livewire;
        
        use Livewire\Component;
        use Livewire\Attributes\Title;
        use App\Helpers\CartManagement;
        use App\Livewire\Partials\Navbar;
        
        #[Title('Cart - Atherial Bakery')]
        class CartPage extends Component
        {
            public $cart_items = [];
            public $grand_total;
        
            public function mount()
            {
                $this->cart_items = CartManagement::getCartItemsFromCookie();
                $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
            }
        
            public function removeItem($product_id)
            {
                $this->cart_items = CartManagement::removeCartItem($product_id);
                $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
                $this->dispatch('update-cart-count', total_count: count($this->cart_items))->to(Navbar::class);
            }

       ...
       ```
       

### Cart Management Helpers
         - 
    This `CartManagement` helper manages a shopping cart using cookies. It provides methods to add items (with or without quantity), remove items, increment/decrement item quantities, and clear the cart. It stores cart data in cookies, retrieves it, and updates it accordingly. The `calculateGrandTotal` method sums up the total amounts of all items in the cart.

    - `CartManagement.php` Helpers
        ```php
        ...
                    namespace App\Helpers;
        
        use App\Models\Product;
        use Illuminate\Support\Facades\Cookie;
        
        class CartManagement {
            //add item to cart
        
            static public function addItemToCart($product_id){
                $cart_items = self::getCartItemsFromCookie();
                $existing_item = null;
            
                foreach($cart_items as $key => $item){
                    if($item['product_id'] == $product_id){
                        $existing_item = $key;
                        break;
                    }
                }
            
                if($existing_item !== null){
                    // Increment the quantity and calculate total amount
                    $cart_items[$existing_item]['quantity']++;
                    $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] * $cart_items[$existing_item]['unit_amount'];
                } else {
                    // Add new product to cart
                    $product = Product::where('id', $product_id)->first(['id', 'name', 'price', 'images']);
                    if($product){
                        $cart_items[] = [
                            'product_id' => $product_id,
                            'name' => $product->name,
                            'image' => $product->images[0],
                            'quantity' => 1,
                            'unit_amount' => $product->price,
                            'total_amount' => $product->price * 1 // initial quantity is 1
                        ];
                    }
                }
            
                self::addCartItemsToCookie($cart_items);
                return count($cart_items);
            }
            
        
            //add item to cart with quantity
            static public function addItemToCartWithQty($product_id, $qty = 1){
                $cart_items = self::getCartItemsFromCookie();
                $existing_item = null;
            
                foreach($cart_items as $key => $item){
                    if($item['product_id'] == $product_id){
                        $existing_item = $key;
                        break;
                    }
        }
        ...

        ```

### OrderPlaced Mail
    The `OrderPlaced` mailable sends an email when an order is placed. It takes an `$order` object in the constructor, passes it to the email view using Markdown, and sets the subject as "Order Placed - Atherial Bakery." It also generates a URL to view the order details. There are no attachments in this email.

    - `OrderPlaced.php` Mail
        ```php
        ...
                namespace App\Mail;
        
        use Illuminate\Bus\Queueable;
        use Illuminate\Contracts\Queue\ShouldQueue;
        use Illuminate\Mail\Mailable;
        use Illuminate\Mail\Mailables\Content;
        use Illuminate\Mail\Mailables\Envelope;
        use Illuminate\Queue\SerializesModels;
        
        class OrderPlaced extends Mailable
        {
            use Queueable, SerializesModels;
        
            public $order; // Add this to make $order accessible in the view
        
            /**
             * Create a new message instance.
             */
            public function __construct($order)
            {
                $this->order = $order;
            }
        
            /**
             * Get the message envelope.
             */
            public function envelope(): Envelope
            {
                return new Envelope(
                    subject: 'Order Placed - Atherial Bakery',
                );
            }
        
            /**
             * Get the message content definition.
             */
            public function content(): Content
            {
                return new Content(
                    markdown: 'mail.orders.placed',
                    with: [
                        'order' => $this->order, // Pass the $order to the view
                        'url' => route('my-orders.show', $this->order)
                    ]
                );
            }
        ...
        ```

### Users Display/ View Page
1. These are the codes for User Interface/displayed in the website. It Includes all th features that already mentioned before in the above explanation.

   ![image](https://github.com/user-attachments/assets/ee6f8868-3c0a-4e32-b132-7cd1b975d257)

    - `home-page.blade.php` for Dashboard display

        ```php
        ...
                   {{-- category section start --}}
              <div class="bg-gradient-to-r from-indigo-800 to-rose-500 py-16">
                <div class="max-w-xl mx-auto">
                  <div class="text-center ">
                    <div class="relative flex flex-col items-center">
                      <h1 class="text-5xl font-bold dark:text-gray-200"> Search Our <span class="text-red-300"> Menu
                        </span> </h1>
                      <div class="flex w-40 mt-2 mb-6 overflow-hidden rounded">
                        <div class="flex-1 h-2 bg-pink-300">
                        </div>
                        <div class="flex-1 h-2 bg-pink-400">
                        </div>
                        <div class="flex-1 h-2 bg-pink-500">
                        </div>
                      </div>
                    </div>
                    <p class="mb-12 text-base text-center text-black">
                      Discover your new favorite today!
                    </p>
                  </div>
                </div>
              
                <div class="max-w-[85rem] px-4 sm:px-6 lg:px-8 mx-auto">
                  <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
              
                    @foreach ($categories as $category )
        
                    <a class="group flex flex-col bg-white border shadow-sm rounded-xl hover:shadow-md transition dark:bg-slate-900 dark:border-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" 
                    href="/products?selected_categories[0]={{ $category->id }}" wire::key= "{{ $category->id }}">
                        <div class="p-4 md:p-5">
                          <div class="flex justify-between items-center">
                            <div class="flex items-center">
                              <img class="h-[2.375rem] w-[2.375rem] rounded-full" src="{{ url('storage', $category->image) }}" alt="{{ $category->name }}">
                              <div class="ms-3">
                                <h3 class="group-hover:text-blue-600 font-semibold text-gray-800 dark:group-hover:text-gray-400 dark:text-gray-200">
                                  {{ $category->name }}
                                </h3>
                              </div>
                            </div>
                            <div class="ps-3">
                              <svg class="flex-shrink-0 w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6" />
                              </svg>
                            </div>
                          </div>
                        </div>
                      </a>
                    @endforeach
                  </div>
                </div>
              </div>
              {{-- category section end --}}             

        ...
        ```
  
    - `Categories-page.blade.php` for Categories Page display
      
      ```php
      ...
              <div class="bg-violet-300">
        <div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
          <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
            <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 sm:gap-6">
              @foreach ($categories as $category)
              <a class="group flex flex-col bg-white border shadow-sm rounded-xl hover:shadow-md transition dark:bg-slate-900 dark:border-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" 
              href="/products?selected_categories[0]={{ $category->id }}" wire:key="{{ $category->id }}">
                  <div class="p-4 md:p-5">
                    <div class="flex justify-between items-center">
                      <div class="flex items-center">
                        <img class="h-[5rem] w-[5rem]" src="{{ url('storage', $category->image) }}" alt="{{ $category->name }}">
                        <div class="ms-3">
                          <h3 class="group-hover:text-blue-600 text-2xl font-semibold text-gray-800 dark:group-hover:text-gray-400 dark:text-gray-200">
                            {{ $category->name }}
                          </h3>
                        </div>
                      </div>
                      <div class="ps-3">
                        <svg class="flex-shrink-0 w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <path d="m9 18 6-6-6-6" />
                        </svg>
                      </div>
                    </div>
                  </div>
                </a>
              @endforeach
            </div>
          </div>
        </div>
        </div>
      ...
      ```
    - `Products-page.blade.php` for Products Page display
      
        ```php
      ...
          <div class="  bg-gradient-to-r from-rose-900 to-cyan-500">
    <div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
      <section class="py-10 bg-gray-50 font-poppins dark:bg-gray-800 rounded-lg">
          <div class="px-4 py-4 mx-auto max-w-7xl lg:py-6 md:px-6">
              <div class="flex flex-wrap mb-24 -mx-3">
                  <div class="w-full pr-2 lg:w-1/4 lg:block">
                      <div class="p-4 mb-5 bg-white border border-gray-200 dark:border-gray-900 dark:bg-gray-900">
                          <h2 class="text-2xl font-bold dark:text-gray-400">Categories</h2>
                          <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                          <ul>
                              @foreach ($categories as $category)
                              <li class="mb-4" wire:key="{{ $category->id }}">
                                  <label for="{{ $category->slug }}" class="flex items-center dark:text-gray-400">
                                      <input type="checkbox" wire:model.live="selected_categories" id="{{ $category->slug }}" value="{{ $category->id }}" class="w-4 h-4 mr-2">
                                      <span class="text-lg">{{ $category->name }}</span>
                                  </label>
                              </li>
                              @endforeach
                          </ul>
                      </div>
    
                      <div class="p-4 mb-5 bg-white border border-gray-200 dark:bg-gray-900 dark:border-gray-900">
                          <h2 class="text-2xl font-bold dark:text-gray-400">Product Status</h2>
                          <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                          <ul>
                              <li class="mb-4">
                                  <label for="in_stock" class="flex items-center dark:text-gray-300">
                                      <input type="checkbox" wire:model.live="in_stock" id="in_stock" value="1" class="w-4 h-4 mr-2">
                                      <span class="text-lg dark:text-gray-400">In Stock</span>
                                  </label>
                              </li>
                              <li class="mb-4">
                                  <label for="on_sale" class="flex items-center dark:text-gray-300">
                                      <input type="checkbox" wire:model.live="on_sale" id="on_sale" value="1" class="w-4 h-4 mr-2">
                                      <span class="text-lg dark:text-gray-400">On Sale</span>
                                  </label>
                              </li>
                          </ul>
                      </div>
      ...
      ```

### Route for each pages

    This code defines routes for a web application using Laravel. It includes public routes for pages like home, categories, products, and cart. Guests can access the login and register pages. Authenticated users can log out, access checkout, view their orders, and see success or cancel pages. The routes are managed using Livewire components.

    - `web.php` for routes

    ```php
    ...
    
        use App\Livewire\Auth\LoginPage;
        use App\Livewire\Auth\RegisterPage;
        use App\Livewire\CancelPage;
        use App\Livewire\CartPage;
        use App\Livewire\CategoriesPage;
        use App\Livewire\CheckoutPage;
        use App\Livewire\HomePage;
        use App\Livewire\MyOrderDetailPage;
        use App\Livewire\MyOrdersPage;
        use App\Livewire\ProductDetailPage;
        use App\Livewire\ProductsPage;
        use App\Livewire\SuccessPage;
        use Illuminate\Support\Facades\Route;
        
        Route::get('/', HomePage::class);
        Route::get('/categories', CategoriesPage::class);
        Route::get('/products', ProductsPage::class);
        Route::get('/cart', CartPage::class);
        Route::get('/products/{slug}', ProductDetailPage::class);
        
        Route::middleware('guest')->group(function(){
            Route::get('/login', LoginPage::class)->name('login');
            Route::get('/register', RegisterPage::class);
        
        });
        
        
        
            Route::middleware('auth')->group(function(){
                Route::get('/logout', function(){
                    auth()->logout();
                    return redirect('/');
                });
            
                Route::get('/checkout', CheckoutPage::class);
                Route::get('/my-orders', MyOrdersPage::class);
                Route::get('/my-orders/{order_id}', MyOrderDetailPage::class)->name('my-orders.show');
                Route::get('/success', SuccessPage::class)->name('success');
                Route::get('/cancel', CancelPage::class)->name(name: 'cancel');
            }); 
    
    ...
    ```












