# 微信小程序运费险包

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/wechat-mini-program-insurance-freight-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-insurance-freight-bundle)
[![Quality Score](https://img.shields.io/scrutinizer/g/tourze/wechat-mini-program-insurance-freight-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/tourze/wechat-mini-program-insurance-freight-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/wechat-mini-program-insurance-freight-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-insurance-freight-bundle)
[![PHP Version](https://img.shields.io/packagist/php-v/tourze/wechat-mini-program-insurance-freight-bundle?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-insurance-freight-bundle)
[![License](https://img.shields.io/packagist/l/tourze/wechat-mini-program-insurance-freight-bundle?style=flat-square)](LICENSE)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/tourze/wechat-mini-program-insurance-freight-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/tourze/wechat-mini-program-insurance-freight-bundle/?branch=master)

一个全面的 Symfony 扩展包，集成微信小程序运费险功能，提供无缝的订单管理、退货处理和与微信 API 
的自动数据同步。

## 目录

- [功能特性](#功能特性)
- [系统要求](#系统要求)
- [安装](#安装)
- [快速开始](#快速开始)
- [控制台命令](#控制台命令)
- [架构设计](#架构设计)
- [高级用法](#高级用法)
- [配置](#配置)
- [测试](#测试)
- [安全](#安全)
- [贡献](#贡献)
- [更新日志](#更新日志)
- [许可证](#许可证)

## 功能特性

- **保险订单管理**：运费险订单的完整生命周期管理
- **退货单处理**：自动化退货单处理和状态跟踪
- **数据同步**：与微信 API 实时数据同步
- **汇总统计**：全面的报告和分析功能
- **命令行工具**：通过控制台命令进行自动化任务和手动操作
- **管理界面**：基于 EasyAdmin 的数据管理控制器
- **事件驱动架构**：灵活的事件订阅者支持自定义业务逻辑
- **仓储模式**：清晰的数据访问层和自定义仓储

## 系统要求

- PHP 8.1 或更高版本
- Symfony 6.4 或更高版本
- Doctrine ORM 3.0 或更高版本
- 微信小程序包生态系统

## 安装

```bash
composer require tourze/wechat-mini-program-insurance-freight-bundle
```

### 注册包

在 `config/bundles.php` 中添加包：

```php
return [
    // ...
    WechatMiniProgramInsuranceFreightBundle\WechatMiniProgramInsuranceFreightBundle::class => ['all' => true],
];
```

## 快速开始

### 基本用法

```php
<?php

use WechatMiniProgramInsuranceFreightBundle\Service\InsuranceFreightService;
use WechatMiniProgramInsuranceFreightBundle\Request\CreateInsuranceOrderRequest;

// 注入服务
public function __construct(
    private readonly InsuranceFreightService $insuranceService
) {}

// 创建保险订单
$request = new CreateInsuranceOrderRequest();
$request->setOrderNo('ORDER_12345');
$request->setEstimateAmount(10000); // 金额（分）

$order = $this->insuranceService->createInsuranceOrder($request);
```

### 实体使用

```php
<?php

use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;
use WechatMiniProgramInsuranceFreightBundle\Enum\InsuranceOrderStatus;

// 创建新的保险订单
$order = new InsuranceOrder();
$order->setOrderNo('ORDER_12345');
$order->setStatus(InsuranceOrderStatus::CREATED);
$order->setEstimateAmount(10000);

// 持久化到数据库
$entityManager->persist($order);
$entityManager->flush();
```

## 控制台命令

此包提供了多个用于管理运费险的控制台命令：

### 数据同步命令

#### `wechat-insurance:get-summary`
从微信 API 获取汇总数据。
- **调度**：每 6 小时运行一次 (30 */6 * * *)
- **描述**：拉取最近 3 天的汇总接口数据

#### `wechat-insurance:sync-insurance-order-list`
同步保险订单到本地数据库。
- **调度**：每 15 分钟运行一次 (*/15 * * * *)
- **描述**：从微信 API 获取保险订单信息并本地存储

#### `wechat-insurance:sync-valid-return-orders`
同步所有有效的退货订单到本地数据库。
- **调度**：每 15 分钟运行一次 (*/15 * * * *)
- **描述**：同步有效的退货订单信息到本地存储

### 手动操作命令

#### `wechat-insurance:query-open`
查询运费险开通状态。
- **用法**：`php bin/console wechat-insurance:query-open`
- **描述**：检查所有有效账户的开通状态

#### `wechat-insurance:sync-single-return-order`
同步单个退货订单到本地数据库。
- **用法**：`php bin/console wechat-insurance:sync-single-return-order <shopOrderId>`
- **参数**：
  - `shopOrderId`：商家系统使用的内部退货订单 ID
- **描述**：同步单个退货订单信息到本地存储

#### `wechat-insurance:unbind-return-order`
解绑单个退货订单。
- **用法**：`php bin/console wechat-insurance:unbind-return-order <shopOrderId>`
- **参数**：
  - `shopOrderId`：商家系统使用的内部退货订单 ID
- **描述**：解绑单个退货订单信息

## 架构设计

### 实体

- **InsuranceOrder**：管理保险订单数据和生命周期跟踪的核心实体
- **ReturnOrder**：管理退货订单信息和状态更新
- **Summary**：存储聚合统计和报告数据

### 服务

- **InsuranceFreightService**：处理所有运费险操作的主要服务类
  - 订单创建和管理
  - 退货订单处理
  - 与微信 API 的数据同步

### 事件订阅者

- **InsuranceOrderListener**：处理保险订单的预持久化事件
- **ProcessingEventSubscriber**：管理订单处理事件

### 仓储

- **InsuranceOrderRepository**：为保险订单提供数据访问方法
- **ReturnOrderRepository**：处理退货订单数据操作
- **SummaryRepository**：管理汇总和统计数据

## 高级用法

### 自定义事件订阅者

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
        // 自定义逻辑
    }
}
```

### 仓储使用

```php
<?php

use WechatMiniProgramInsuranceFreightBundle\Repository\InsuranceOrderRepository;

public function __construct(
    private readonly InsuranceOrderRepository $orderRepository
) {}

// 根据状态查找订单
$orders = $this->orderRepository->findByStatus(InsuranceOrderStatus::SECURING);

// 在日期范围内查找订单
$orders = $this->orderRepository->findOrdersInDateRange($startDate, $endDate);
```

## 配置

### 环境变量

```bash
# 微信小程序配置
WECHAT_MINI_PROGRAM_APP_ID=your_app_id
WECHAT_MINI_PROGRAM_APP_SECRET=your_app_secret
```

### 包配置

创建 `config/packages/wechat_mini_program_insurance_freight.yaml`：

```yaml
wechat_mini_program_insurance_freight:
    api_timeout: 30
    retry_attempts: 3
    batch_size: 100
```

## 测试

运行测试套件：

```bash
# 运行所有测试
vendor/bin/phpunit

# 运行覆盖率测试
vendor/bin/phpunit --coverage-html coverage
```

## 安全

### 报告安全漏洞

如果您发现安全漏洞，请发送邮件至 security@example.com。所有安全漏洞都会得到及时处理。

### 安全最佳实践

- 处理前始终验证输入数据
- 所有 API 通信使用 HTTPS
- 实施适当的速率限制
- 定期更新依赖项
- 监控异常活动模式

## 贡献

请阅读 [CONTRIBUTING.md](CONTRIBUTING.md) 了解我们的行为准则和提交拉取请求的流程。

### 开发环境设置

```bash
# 克隆仓库
git clone https://github.com/tourze/wechat-mini-program-insurance-freight-bundle.git

# 安装依赖
composer install

# 运行测试
vendor/bin/phpunit
```

## 更新日志

此项目的所有重要更改都将记录在 [CHANGELOG.md](CHANGELOG.md) 中。

## 许可证

此项目采用 MIT 许可证 - 有关详细信息，请参阅 [LICENSE](LICENSE) 文件。