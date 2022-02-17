<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusMolliePlugin\Action\Api;

use BitBag\SyliusMolliePlugin\Action\Api\BaseApiAwareAction;
use BitBag\SyliusMolliePlugin\Action\Api\CancelRecurringSubscriptionAction;
use BitBag\SyliusMolliePlugin\Client\MollieApiClient;
use BitBag\SyliusMolliePlugin\Entity\MollieSubscriptionConfigurationInterface;
use BitBag\SyliusMolliePlugin\Entity\MollieSubscriptionInterface;
use BitBag\SyliusMolliePlugin\Logger\MollieLoggerActionInterface;
use BitBag\SyliusMolliePlugin\Request\Api\CancelRecurringSubscription;
use Mollie\Api\Endpoints\CustomerEndpoint;
use Mollie\Api\Resources\Customer;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use PhpSpec\ObjectBehavior;

final class CancelRecurringSubscriptionActionSpec extends ObjectBehavior
{
    function let(MollieLoggerActionInterface $loggerAction): void
    {
        $this->beConstructedWith(
            $loggerAction
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CancelRecurringSubscriptionAction::class);
    }

    function it_implements_action_interface(): void
    {
        $this->shouldHaveType(ActionInterface::class);
    }

    function it_implements_api_aware_interface(): void
    {
        $this->shouldHaveType(ApiAwareInterface::class);
    }

    function it_extends_base_api_aware(): void
    {
        $this->shouldHaveType(BaseApiAwareAction::class);
    }

    function it_executes(
        CancelRecurringSubscription $request,
        MollieApiClient $mollieApiClient,
        MollieSubscriptionConfigurationInterface  $subscription,
        CustomerEndpoint $customerEndpoint,
        Customer $customer
    ): void {
        $mollieApiClient->customers = $customerEndpoint;
        $this->setApi($mollieApiClient);
        $customerEndpoint->get('id_1')->willReturn($customer);
        $subscription->getSubscriptionId()->willReturn('id_1');
        $subscription->getCustomerId()->willReturn('id_1');
        $request->getModel()->willReturn($subscription);


        $customer->cancelSubscription('id_1')->shouldBeCalled();

        $this->execute($request);
    }

    function it_supports_cancel_recurring_subscription_request_and_subscription_model(
        CancelRecurringSubscription $request,
        MollieSubscriptionInterface $subscription
    ): void {
        $request->getModel()->willReturn($subscription);

        $this->supports($request)->shouldReturn(true);
    }
}
