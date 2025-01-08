# BreizhSport Product Microservice

The `product` microservice is responsible for managing all product-related functionalities within the BreizhSport application. This includes products, categories, inventories, and orders.

## Features

- Product management
- Category management
- Inventory management
- Order and order detail management

## Prerequisites

Ensure the following are installed on your machine:

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- PHP 8.3+
- Composer

## Repository Structure

```
BreizhSport-product/
├── config/
├── src/
├── .env
├── Dockerfile
├── composer.json
├── README.md
```

## Getting Started

### Environment Variables

The `.env` file contains all necessary configurations:

```
APP_ENV=dev
APP_SECRET=<your_secret>
DATABASE_URL="postgresql://postgres:postgres@database:5432/breizhsport_product_db"
JWT_PUBLIC_KEY="/app/config/jwt/public.pem"
```

Ensure the `JWT_PUBLIC_KEY` path is correct and points to the public key shared with the `auth` microservice.

### Building and Running the Service

To build and run the `product` microservice:

```bash
docker build -t breizhsport-product .
docker run --name product -p 8082:80 --env-file .env breizhsport-product
```

Alternatively, use Docker Compose from the `infra` repository:

```bash
docker-compose up --build product
```

### Database Setup

Run the following commands to create and migrate the database:

```bash
docker exec -it product bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### Loading Fixtures

To load dummy data for testing:

```bash
php bin/console doctrine:fixtures:load
```

## API Endpoints

### Get Products

**GET** `/api/products`

- **Description**: Retrieves a list of products. Requires a valid JWT token.
- **Headers**:

```json
{
  "Authorization": "Bearer <jwt_token>"
}
```

- **Response**:

```json
[
  {
    "id": "<uuid>",
    "name": "Product Name",
    "price": 100.00,
    "categories": ["Category 1", "Category 2"]
  }
]
```

### Create Product

**POST** `/api/products`

- **Description**: Creates a new product. Requires `ROLE_ADMIN`.
- **Payload**:

```json
{
  "name": "New Product",
  "price": 200.00,
  "categories": ["/api/categories/<uuid>"]
}
```

- **Response**:

```json
{
  "id": "<uuid>",
  "name": "New Product",
  "price": 200.00
}
```

### Other Endpoints

- `/api/categories`
- `/api/inventories`
- `/api/orders`

## Testing

Run the tests with:

```bash
php bin/phpunit
```

## Contribution

To contribute to this microservice, fork the repository, make your changes, and submit a pull request.

## License

This project is licensed under the MIT License.

