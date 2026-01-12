# ControlIq

A Laravel application with AI-powered chat support, built with Livewire and LarAgent.

## Features

- 🔐 **Manual Authentication** - Login-based authentication system (no registration)
- 🤖 **AI Chat Support** - Interactive chat interface powered by LarAgent
- 👥 **User Management** - Role-based access control (Admin/User)
- 📦 **Product & Category Management** - Manage products and categories
- 🎨 **Modern UI** - Built with Tailwind CSS and Livewire
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
  - Email: `admin@example.com`
  - Password: `password`
  - Role: `ADMIN`

- **Regular User:**
  - Email: `user@example.com`
  - Password: `password`
  - Role: `USER`

## Project Structure

```
app/
├── AiAgents/
│   └── SupportAgent.php      # AI chat agent configuration
├── Http/
│   └── Controllers/
│       └── AuthController.php # Authentication controller
├── Livewire/
│   └── Chat.php               # Livewire chat component
├── Models/
│   ├── User.php
│   ├── Category.php
│   └── Product.php
└── Services/
    └── AuthServices.php      # Authentication service

resources/
├── views/
│   ├── auth/
│   │   └── login.blade.php   # Login page
│   ├── components/
│   │   └── nav.blade.php     # Navigation component
│   ├── home.blade.php        # Home page
│   ├── livewire/
│   │   └── chat.blade.php    # Chat interface
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

- **SupportAgent**: Handles user queries and provides support
- Authentication-aware: The agent knows if the user is authenticated and their role
- Customizable instructions via Blade templates

### Models

- **User**: Authentication and user management
- **Category**: Product categories
- **Product**: Products with category relationships

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

- `GET /` - Home page (accessible to all)
- `GET /login` - Login page (guest only)
- `POST /login` - Handle login
- `POST /logout` - Handle logout (authenticated only)

## Technologies

- **Laravel 12** - PHP framework
- **Livewire** - Full-stack framework for Laravel
- **LarAgent** - AI agent framework
- **Tailwind CSS** - Utility-first CSS framework
- **MySQL** - Database
- **Docker** - Containerization

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Support

For support, please open an issue in the repository.

