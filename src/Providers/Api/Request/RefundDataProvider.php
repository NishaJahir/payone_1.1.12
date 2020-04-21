<?php

namespace Payone\Providers\Api\Request;

use Plenty\Modules\Order\Models\Order;
use Plenty\Plugin\Log\Loggable;

/**
 * Class RefundDataProvider
 */
class RefundDataProvider extends DataProviderAbstract implements DataProviderOrder
{
   use Loggable;

    /**
     * {@inheritdoc}
     */
    public function getDataFromOrder(string $paymentCode, Order $order, string $requestReference = null)
    {
        $requestParams = $this->getDefaultRequestData($paymentCode);
        $requestParams['context']['sequencenumber'] = $this->getSequenceNumber($order);
        $requestParams['basket'] = $this->getBasketDataFromOrder($order);
        $requestParams['order'] = $this->getOrderData($order);

        $requestParams['referenceId'] = $requestReference;

        $this->validator->validate($requestParams);

        return $requestParams;
    }

    /**
     * @param $paymentCode
     * @param Order $order
     * @param Order $refund
     * @param $preAuthUniqueId
     *
     * @return array
     */
    public function getPartialRefundData($paymentCode, Order $order, Order $refund, $preAuthUniqueId)
    {
        
        
        $requestParams = $this->getDataFromOrder($paymentCode, $order, $preAuthUniqueId);

        $requestParams['order'] = $this->getOrderData($refund);
      
        $this->validator->validate($requestParams);
          $this->getLogger(__METHOD__)->error('refunddata', $requestParams);
          
        return $requestParams;
    }
}
