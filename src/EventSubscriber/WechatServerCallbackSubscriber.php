<?php

namespace WechatMiniProgramInsuranceFreightBundle\EventSubscriber;

use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
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

        $OrderNo = $data['upload_event']['OrderNo'];
        $Status = $data['upload_event']['Status'];
        $FinishTime = $data['upload_event']['FinishTime'];
        $order = $this->orderRepository->findOneBy(['orderNo' => $OrderNo]);
        if ($order === null) {
            $this->logger->error('收到微信运费险回调，但是找不到订单信息', [
                'data' => $data,
            ]);

            return;
        }

        $order->setStatus(InsuranceOrderStatus::from($Status));
        $order->setPayFinishTime(CarbonImmutable::createFromTimestamp($FinishTime, date_default_timezone_get()));
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }
}
