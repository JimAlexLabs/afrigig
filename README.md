# Afrigig.work

A freelance marketplace platform connecting African talent with global opportunities.

## Features

- User authentication with role-based access (Freelancers and Clients)
- Job posting and management
- Bidding system
- Milestone-based payments
- Integrated payment systems (M-Pesa and PayPal)
- Real-time notifications
- File attachments
- User profiles and portfolios

## Requirements

- PHP 8.1 or higher
- MySQL 5.7 or higher
- Node.js 16 or higher
- Composer
- NPM

## Installation

1. Clone the repository:

```bash
git clone https://github.com/yourusername/afrigig.git
cd afrigig
```

2. Install PHP dependencies:

```bash
composer install
```

3. Install JavaScript dependencies:

```bash
npm install
```

4. Create environment file:

```bash
cp .env.example .env
```

5. Generate application key:

```bash
php artisan key:generate
```

6. Configure your database in `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=afrigig
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Configure payment providers in `.env`:

```
MPESA_CONSUMER_KEY=your_mpesa_key
MPESA_CONSUMER_SECRET=your_mpesa_secret
MPESA_SHORTCODE=your_mpesa_shortcode
MPESA_PASSKEY=your_mpesa_passkey
MPESA_ENV=sandbox

PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_SECRET=your_paypal_secret
PAYPAL_ENV=sandbox
```

8. Run database migrations:

```bash
php artisan migrate
```

9. Create storage link:

```bash
php artisan storage:link
```

10. Build assets:

```bash
npm run build
```

## Development

1. Start the development server:

```bash
php artisan serve
```

2. Watch for asset changes:

```bash
npm run dev
```

## Testing

Run the test suite:

```bash
php artisan test
```

## Deployment

1. Make the deployment script executable:

```bash
chmod +x deploy.sh
```

2. Run the deployment script:

```bash
./deploy.sh
```

The deployment script will:

- Pull the latest changes
- Install dependencies
- Run migrations
- Clear caches
- Optimize the application
- Run tests
- Restart queue workers
- Check application health

## Health Checks

Monitor the application's health by accessing:

```
/health
```

This endpoint checks:

- Database connection
- Cache system
- File storage
- Overall application status

## Security

- All routes are protected with appropriate middleware
- CSRF protection is enabled
- Input validation is implemented
- Payment data is encrypted
- File uploads are validated and sanitized

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.
