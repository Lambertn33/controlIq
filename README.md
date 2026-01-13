# ControlIq

A modern Laravel application with AI-powered chat support, built with Livewire and LarAgent. Features a beautiful, responsive UI with session-persistent chat history and role-based access control.

## Features

- 🔐 **Manual Authentication** - Login-based authentication system (no registration)
- 🤖 **AI Chat Support** - Interactive chat interface powered by LarAgent with session-based history
- 👥 **User Management** - Role-based access control (Admin/User) with secure tool access
- 📦 **Product & Category Management** - Full CRUD operations for products and categories
- 🎨 **Modern UI** - Beautiful gradient design with Tailwind CSS, Livewire, and Alpine.js
- 🚀 **Livewire Navigation** - Seamless page transitions without full page reloads
- 💬 **Persistent Chat** - Chat history persists across page navigation using session storage
- 🐳 **Docker Support** - Easy development setup with Docker

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and NPM
- Docker and Docker Compose (for containerized development)
- MySQL 8.0 (or use Docker)

## Installation

### Using Docker (Recommended)

1. Clone the repository:
```bash
git clone <repository-url>
cd ControlIq
```

2. Copy the environment file:
```bash
cp .env.example .env
```

3. Update `.env` with your database and API keys:
```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=controliq
DB_USERNAME=controliq
DB_PASSWORD=controliq

OPENAI_API_KEY=your_openai_api_key_here
```

4. Start the Docker containers:
```bash
docker-compose up -d
```

5. Install dependencies inside the container:
```bash
docker-compose exec app composer install
docker-compose exec app npm install
```

6. Generate application key:
```bash
docker-compose exec app php artisan key:generate
```

7. Run migrations and seeders:
```bash
docker-compose exec app php artisan migrate --seed
```

8. Build frontend assets:
```bash
docker-compose exec app npm run build
```

The application will be available at `http://localhost:8000`

### Manual Installation

1. Clone the repository and install dependencies:
```bash
composer install
npm install
```

2. Copy environment file and configure:
```bash
cp .env.example .env
php artisan key:generate
```

3. Configure your database in `.env`

4. Run migrations:
```bash
php artisan migrate --seed
```

5. Build assets:
```bash
npm run build
```

6. Start the development server:
```bash
php artisan serve
```

## Default Users

After running seeders, you'll have these default users:

- **Admin User:**
  - Email: `admin@controliq.com`
  - Password: `password`
  - Role: `ADMIN`
  - Can access all features including user management, category/product creation

- **Regular User:**
  - Email: `user@controliq.com`
  - Password: `password`
  - Role: `USER`
  - Can view products/categories and chat with AI, but cannot create or access user information

## Project Structure

```
app/
├── AiAgents/
│   └── SupportAgent.php          # AI chat agent with tools and authentication
├── Http/
│   └── Controllers/
│       └── AuthController.php    # Authentication controller
├── Livewire/
│   ├── Chat.php                   # Livewire chat component
│   ├── Categories.php             # Categories display component
│   └── Products.php               # Products display component
├── Models/
│   ├── User.php                   # User model with role support
│   ├── Category.php               # Category model
│   └── Product.php                # Product model with category relationship
└── Services/
    ├── AuthServices.php           # Authentication service
    ├── ProductsServices.php       # Product and category business logic
    └── UserServices.php          # User management service

resources/
├── views/
│   ├── auth/
│   │   └── login.blade.php       # Modern login page
│   ├── components/
│   │   └── nav.blade.php         # Reusable navigation component
│   ├── layouts/
│   │   └── app.blade.php         # Main application layout
│   ├── home.blade.php             # Home page with welcome section
│   ├── data.blade.php             # Data page with categories and products
│   ├── livewire/
│   │   ├── chat.blade.php        # Chat interface with modern design
│   │   ├── categories.blade.php  # Categories table view
│   │   └── products.blade.php    # Products table view
│   └── prompts/
│       └── support-agent-instructions.blade.php # AI agent instructions
```

## Key Components

### Authentication

- Manual authentication system (login only)
- No registration endpoint (users must be created via seeders or admin)
- Session-based authentication
- Role-based access control (ADMIN/USER)

### AI Chat Support

The application includes an AI-powered support agent built with LarAgent:

- **SupportAgent**: Handles user queries and provides intelligent support
- **Session-based History**: Chat history persists across page navigation using LarAgent's session storage
- **Authentication-aware**: The agent knows if the user is authenticated and their role
- **Role-based Tool Access**: Different tools available based on user role (Admin vs User)
- **Customizable Instructions**: Instructions are dynamically generated via Blade templates based on user role

#### Available AI Tools

**For All Users:**
- `viewCategories` - View all product categories
- `viewProducts` - View products (optionally filtered by category)
- `searchProduct` - Search for products by name

**Admin Only:**
- `createCategory` - Create new categories
- `checkIfCategoryExists` - Check if a category exists
- `createProduct` - Create new products
- `checkIfProductExists` - Check if a product exists
- `getAllUsers` - Get all users in the system
- `getUserByName` - Search for users by name

**Security Features:**
- Non-admin users cannot access user information tools
- AI agent is instructed to decline non-system related questions
- Tool access is restricted at both instruction and code level

### Models

- **User**: Authentication and user management with role-based access (ADMIN/USER)
- **Category**: Product categories with name field
- **Product**: Products with name, price, quantity, and category relationship

### Pages

- **Home Page (`/`)**: Welcome page with AI chat interface, accessible to all users
- **Data Page (`/data`)**: View categories and products in a two-column layout, requires authentication
- **Login Page (`/login`)**: Modern login form with gradient design, guest-only access

## Configuration

### LarAgent Configuration

Configure your AI provider in `config/laragent.php`:

```php
'providers' => [
    'default' => [
        'api_key' => env('OPENAI_API_KEY'),
        'driver' => \LarAgent\Drivers\OpenAi\OpenAiDriver::class,
        // ...
    ],
]
```

### Database Configuration

Update your database settings in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=controliq
DB_USERNAME=controliq
DB_PASSWORD=controliq
```

## Development

### Running Tests

```bash
php artisan test
```

### Code Style

The project uses Laravel Pint for code formatting:

```bash
./vendor/bin/pint
```

### Docker Commands

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# View logs
docker-compose logs -f app

# Execute commands in container
docker-compose exec app php artisan <command>
```

## Routes

- `GET /` - Home page (accessible to all, shows chat for authenticated users)
- `GET /login` - Login page (guest only)
- `POST /login` - Handle login
- `POST /logout` - Handle logout (authenticated only)
- `GET /data` - Data page with categories and products (authenticated only)

## Technologies

- **Laravel 12** - PHP framework
- **Livewire** - Full-stack framework for Laravel with wire:navigate for seamless navigation
- **LarAgent** - AI agent framework with session-based chat history
- **Alpine.js** - Lightweight JavaScript framework for interactivity
- **Tailwind CSS** - Utility-first CSS framework with modern gradient designs
- **MySQL** - Database
- **Docker** - Containerization for easy development setup

## Key Features Explained

### Chat History Persistence

The chat uses LarAgent's session storage (`protected $history = 'session'`) to persist conversation history:
- Chat messages are stored in the Laravel session
- History persists across page navigation using `wire:navigate`
- History is automatically cleared when user logs out (session destroyed)
- Messages are loaded from session on component mount

### Role-Based Access Control

The AI agent enforces role-based access:
- **Admin users** can create categories/products and access user information
- **Regular users** can only view categories/products and chat
- Tool access is restricted both in instructions and code
- Non-admin users receive clear messages when trying to access restricted features

### Modern UI Design

- Gradient backgrounds (gray → indigo → purple)
- Modern card designs with shadows and rounded corners
- Responsive grid layouts
- Smooth transitions and hover effects
- Professional icon usage throughout
- Consistent color scheme across all pages

