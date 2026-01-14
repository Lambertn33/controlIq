# ControlIq

A modern Laravel application with AI-powered chat support, built with Livewire and LarAgent. Features a beautiful, responsive UI with session-persistent chat history and role-based access control.

## 📹 Demonstration Video

Watch the application in action: [View Demo Video](https://drive.google.com/file/d/1Ysjy2g4u-JSkawlZvj13fCGhykBvPRyN/view?usp=drive_link)

## Features

-   🔐 **Manual Authentication** - Login-based system (no registration)
-   🤖 **AI Chat Support** - Interactive chat with LarAgent and session-based history
-   👥 **Role-Based Access** - Admin/User roles with secure tool access
-   📦 **Product & Category Management** - Full CRUD operations
-   🎨 **Modern UI** - Gradient design with Tailwind CSS, Livewire, and Alpine.js
-   🐳 **Docker Support** - Easy development setup

## Requirements

-   PHP 8.2+, Composer, Node.js/NPM
-   Docker and Docker Compose (recommended)
-   MySQL 8.0

## Installation

### Using Docker (Recommended)

1. Clone and setup:

```bash
git clone <repository-url>
cd ControlIq
cp .env.example .env
```

2. Update `.env` with your database and API keys:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=controliq
DB_USERNAME=controliq
DB_PASSWORD=controliq
OPENAI_API_KEY=your_openai_api_key_here
```

3. Start containers and install:

```bash
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app npm install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
docker-compose exec app npm run build
```

The application will be available at `http://localhost:8000`

## Default Users

After seeding, you'll have:

-   **Admin:** `admin@controliq.com` / `password` (ADMIN role - full access)
-   **User:** `user@controliq.com` / `password` (USER role - limited access)

## Key Components

### AI Chat Support

-   **SupportAgent**: Handles queries with authentication-aware responses
-   **Session-based History**: Persists across navigation, cleared on login/logout
-   **Role-based Tools**: Different tools available for Admin vs User

#### Available AI Tools

**All Users:**

-   `viewCategories`, `viewProducts`, `searchProduct`, `downloadCategoriesFile`, `downloadProductsFile`,
    `checkIfCategoryExists`, `checkIfProductExists`

**Admin Only:**

-   `createCategory`, `createProduct`, `checkIfCategoryExists`, `checkIfProductExists`
-   `getAllUsers`, `getUserByName`

### Authentication

-   Login-only system (no registration)
-   Session-based authentication
-   Role-based access control (ADMIN/USER)
-   Chat history cleared on login/logout

## Technologies

-   **Laravel 12** - PHP framework
-   **Livewire** - Full-stack framework with wire:navigate
-   **LarAgent** - AI agent framework with session storage
-   **Alpine.js** - Lightweight JavaScript framework
-   **Tailwind CSS** - Utility-first CSS framework
-   **MySQL** - Database
-   **Docker** - Containerization

## Development

```bash
# Docker commands
docker-compose up -d          # Start
docker-compose down           # Stop
docker-compose logs -f app    # View logs
```

### Role-Based Access Control

-   Admin users: Full access including user management and creation tools
-   Regular users: View-only access with chat support
-   Tool access restricted at both instruction and code level
