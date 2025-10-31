<?php

namespace WechatMiniProgramInsuranceFreightBundle\EventSubscriber;

use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use WechatMiniProgramInsuranceFreightBundle\Enum\InsuranceOrderStatus;
use WechatMiniProgramInsuranceFreightBundle\Repository\InsuranceOrderRepository;
use WechatMiniProgramServerMessageBundle\Event\ServerMessageRequestEvent;
use Yiisoft\Arrays\ArrayHelper;

/**
 * 理赔结果推送
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/industry/express/business/freight_insurance.html#%E7%90%86%E8%B5%94%E7%BB%93%E6%9E%9C%E6%8E%A8%E9%80%81
 */
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'wechat_mini_program_insurance_freight')]
class WechatServerCallbackSubscriber
{
    public function __construct(
        private readonly InsuranceOrderRepository $orderRepository,
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[AsEventListener]
    public function onServerMessageRequest(ServerMessageRequestEvent $event): void
    {
        $data = $event->getMessage();
        $msgEvent = ArrayHelper::getValue($data, 'Event');
        if ('wxainsurance_claim_result' !== $msgEvent) {
            return;
        }

        // 安全地提取上传事件数据
        $uploadEvent = $this->extractUploadEventData($data);
        if (null === $uploadEvent) {
            return;
        }

        $order = $this->orderRepository->findOneBy(['orderNo' => $uploadEvent['orderNo']]);
        if (null === $order) {
            $this->logger->error('收到微信运费险回调，但是找不到订单信息', [
                'data' => $data,
            ]);

            return;
        }

        $order->setStatus(InsuranceOrderStatus::from($uploadEvent['status']));
        $order->setPayFinishTime(CarbonImmutable::createFromTimestamp($uploadEvent['finishTime'], date_default_timezone_get()));
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    /**
     * @param mixed $data
     * @return array{orderNo: string, status: int|string, finishTime: int}|null
     */
    private function extractUploadEventData($data): ?array
    {
        if (!is_array($data) || !isset($data['upload_event']) || !is_array($data['upload_event'])) {
            $this->logger->error('微信运费险回调数据格式错误：缺少upload_event', [
                'data' => $data,
            ]);

            return null;
        }

        $uploadEvent = $data['upload_event'];

        if (!isset($uploadEvent['OrderNo']) || !is_string($uploadEvent['OrderNo'])) {
            $this->logger->error('微信运费险回调数据格式错误：缺少OrderNo或类型不正确', [
                'uploadEvent' => $uploadEvent,
            ]);

            return null;
        }

        if (!isset($uploadEvent['Status']) || (!is_int($uploadEvent['Status']) && !is_string($uploadEvent['Status']))) {
            $this->logger->error('微信运费险回调数据格式错误：缺少Status或类型不正确', [
                'uploadEvent' => $uploadEvent,
            ]);

            return null;
        }

        if (!isset($uploadEvent['FinishTime']) || !is_int($uploadEvent['FinishTime'])) {
            $this->logger->error('微信运费险回调数据格式错误：缺少FinishTime或类型不正确', [
                'uploadEvent' => $uploadEvent,
            ]);

            return null;
        }

        return [
            'orderNo' => $uploadEvent['OrderNo'],
            'status' => $uploadEvent['Status'],
            'finishTime' => $uploadEvent['FinishTime'],
        ];
    }

    /**
     * @return array<string, array<int|string, int|string>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ServerMessageRequestEvent::class => ['onServerMessageRequest', 0],
        ];
    }
}
