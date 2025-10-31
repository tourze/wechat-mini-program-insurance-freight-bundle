# WeChat Mini Program Insurance Freight Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/wechat-mini-program-insurance-freight-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-insurance-freight-bundle)
[![Quality Score](https://img.shields.io/scrutinizer/g/tourze/wechat-mini-program-insurance-freight-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/tourze/wechat-mini-program-insurance-freight-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/wechat-mini-program-insurance-freight-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-insurance-freight-bundle)
[![PHP Version](https://img.shields.io/packagist/php-v/tourze/wechat-mini-program-insurance-freight-bundle?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-insurance-freight-bundle)
[![License](https://img.shields.io/packagist/l/tourze/wechat-mini-program-insurance-freight-bundle?style=flat-square)](LICENSE)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/tourze/wechat-mini-program-insurance-freight-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/tourze/wechat-mini-program-insurance-freight-bundle/?branch=master)

A comprehensive Symfony bundle that integrates WeChat Mini Program freight insurance functionality, 
providing seamless order management, return processing, and automated data synchronization with 
WeChat APIs.

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Console Commands](#console-commands)
- [Architecture](#architecture)
- [Advanced Usage](#advanced-usage)
- [Configuration](#configuration)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Changelog](#changelog)
- [License](#license)

## Features

- **Insurance Order Management**: Complete lifecycle management of freight insurance orders
- **Return Order Processing**: Automated handling of return orders with status tracking
- **Data Synchronization**: Real-time synchronization with WeChat APIs
- **Summary Statistics**: Comprehensive reporting and analytics
- **Command-Line Tools**: Automated tasks and manual operations via console commands
- **Admin Interface**: EasyAdmin-powered administrative controllers for data management
- **Event-Driven Architecture**: Flexible event subscribers for custom business logic
- **Repository Pattern**: Clean data access layer with custom repositories

## Requirements

- PHP 8.1 or higher
- Symfony 6.4 or higher
- Doctrine ORM 3.0 or higher
- WeChat Mini Program Bundle ecosystem

## Installation

```bash
composer require tourze/wechat-mini-program-insurance-freight-bundle
```

### Bundle Registration

Add the bundle to your `config/bundles.php`:

```php
return [
    // ...
    WechatMiniProgramInsuranceFreightBundle\WechatMiniProgramInsuranceFreightBundle::class => ['all' => true],
];
```

## Quick Start

### Basic Usage

```php
<?php

use WechatMiniProgramInsuranceFreightBundle\Service\InsuranceFreightService;
use WechatMiniProgramInsuranceFreightBundle\Request\CreateInsuranceOrderRequest;

// Inject the service
public function __construct(
    private readonly InsuranceFreightService $insuranceService
) {}

// Create an insurance order
$request = new CreateInsuranceOrderRequest();
$request->setOrderNo('ORDER_12345');
$request->setEstimateAmount(10000); // Amount in cents

$order = $this->insuranceService->createInsuranceOrder($request);
```

### Entity Usage

```php
<?php

use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;
use WechatMiniProgramInsuranceFreightBundle\Enum\InsuranceOrderStatus;

// Create a new insurance order
$order = new InsuranceOrder();
$order->setOrderNo('ORDER_12345');
$order->setStatus(InsuranceOrderStatus::CREATED);
$order->setEstimateAmount(10000);

// Persist to database
$entityManager->persist($order);
$entityManager->flush();
```

## Console Commands

This bundle provides several console commands for managing freight insurance:

### Data Synchronization Commands

#### `wechat-insurance:get-summary`
Fetches summary data from WeChat API.
- **Schedule**: Runs every 6 hours (30 */6 * * *)
- **Description**: Pulls summary interface data for the last 3 days

#### `wechat-insurance:sync-insurance-order-list`
Synchronizes insurance orders to local database.
- **Schedule**: Runs every 15 minutes (*/15 * * * *)
- **Description**: Fetches insurance order information from WeChat API and stores locally

#### `wechat-insurance:sync-valid-return-orders`
Synchronizes all valid return orders to local database.
- **Schedule**: Runs every 15 minutes (*/15 * * * *)
- **Description**: Synchronizes valid return order information to local storage

### Manual Operation Commands

#### `wechat-insurance:query-open`
Queries the opening status of freight insurance.
- **Usage**: `php bin/console wechat-insurance:query-open`
- **Description**: Checks the opening status for all valid accounts

#### `wechat-insurance:sync-single-return-order`
Synchronizes a single return order to local database.
- **Usage**: `php bin/console wechat-insurance:sync-single-return-order <shopOrderId>`
- **Arguments**:
  - `shopOrderId`: Internal return order ID used by the merchant system
- **Description**: Synchronizes individual return order information to local storage

#### `wechat-insurance:unbind-return-order`
Unbinds a single return order.
- **Usage**: `php bin/console wechat-insurance:unbind-return-order <shopOrderId>`
- **Arguments**:
  - `shopOrderId`: Internal return order ID used by the merchant system
- **Description**: Unbinds individual return order information

## Architecture

### Entities

- **InsuranceOrder**: Core entity managing insurance order data with lifecycle tracking
- **ReturnOrder**: Manages return order information and status updates
- **Summary**: Stores aggregated statistics and reporting data

### Services

- **InsuranceFreightService**: Main service class handling all freight insurance operations
  - Order creation and management
  - Return order processing
  - Data synchronization with WeChat APIs

### Event Subscribers

- **InsuranceOrderListener**: Handles pre-persist events for insurance orders
- **ProcessingEventSubscriber**: Manages order processing events

### Repositories

- **InsuranceOrderRepository**: Provides data access methods for insurance orders
- **ReturnOrderRepository**: Handles return order data operations
- **SummaryRepository**: Manages summary and statistics data

## Advanced Usage

### Custom Event Subscribers

```php
<?php

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WechatMiniProgramInsuranceFreightBundle\Event\InsuranceOrderCreatedEvent;

class CustomInsuranceOrderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            InsuranceOrderCreatedEvent::class => 'onOrderCreated',
        ];
    }

    public function onOrderCreated(InsuranceOrderCreatedEvent $event)
    {
        $order = $event->getOrder();
        // Custom logic here
    }
}
```

### Repository Usage

```php
<?php

use WechatMiniProgramInsuranceFreightBundle\Repository\InsuranceOrderRepository;

public function __construct(
    private readonly InsuranceOrderRepository $orderRepository
) {}

// Find orders by status
$orders = $this->orderRepository->findByStatus(InsuranceOrderStatus::SECURING);

// Find orders within date range
$orders = $this->orderRepository->findOrdersInDateRange($startDate, $endDate);
```

## Configuration

### Environment Variables

```bash
# WeChat Mini Program Configuration
WECHAT_MINI_PROGRAM_APP_ID=your_app_id
WECHAT_MINI_PROGRAM_APP_SECRET=your_app_secret
```

### Bundle Configuration

Create `config/packages/wechat_mini_program_insurance_freight.yaml`:

```yaml
wechat_mini_program_insurance_freight:
    api_timeout: 30
    retry_attempts: 3
    batch_size: 100
```

## Testing

Run the test suite:

```bash
# Run all tests
vendor/bin/phpunit

# Run with coverage
vendor/bin/phpunit --coverage-html coverage
```

## Security

### Reporting Security Vulnerabilities

If you discover a security vulnerability, please send an email to security@example.com. 
All security vulnerabilities will be promptly addressed.

### Security Best Practices

- Always validate input data before processing
- Use HTTPS for all API communications
- Implement proper rate limiting
- Regularly update dependencies
- Monitor for unusual activity patterns

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, 
and the process for submitting pull requests.

### Development Setup

```bash
# Clone the repository
git clone https://github.com/tourze/wechat-mini-program-insurance-freight-bundle.git

# Install dependencies
composer install

# Run tests
vendor/bin/phpunit
```

## Changelog

All notable changes to this project will be documented in [CHANGELOG.md](CHANGELOG.md).

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.