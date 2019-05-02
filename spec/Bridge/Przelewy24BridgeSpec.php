<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusPrzelewy24Plugin\Bridge;

use BitBag\SyliusPrzelewy24Plugin\Bridge\Przelewy24Bridge;
use BitBag\SyliusPrzelewy24Plugin\Bridge\Przelewy24BridgeInterface;
use GuzzleHttp\ClientInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class Przelewy24BridgeSpec extends ObjectBehavior
{
    function let(ClientInterface $client): void
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(Przelewy24Bridge::class);
    }

    function it_implements_przelewy24_bridge_interface(): void
    {
        $this->shouldHaveType(Przelewy24BridgeInterface::class);
    }

    function it_set_authorization_data(): void
    {
        $this->setAuthorizationData('test', 'test', 'sandbox');
    }

    function it_get_trn_register_url(): void
    {
        $this->getTrnRegisterUrl()->shouldReturn(Przelewy24BridgeInterface::SANDBOX_HOST . 'trnRegister');
    }

    function it_get_trn_request_url(): void
    {
        $this->getTrnRequestUrl('token')->shouldReturn(Przelewy24BridgeInterface::SANDBOX_HOST . 'trnRequest/token');
    }

    function it_get_trn_verify_url(): void
    {
        $this->getTrnVerifyUrl()->shouldReturn(Przelewy24BridgeInterface::SANDBOX_HOST . 'trnVerify');
    }

    function it_create_sign(): void
    {
        $this->setAuthorizationData('test', 'test', 'sandbox');

        $this->createSign(['test'])->shouldReturn(md5(implode('|', ['test', 'test'])));
    }

    function it_trn_register(
        ClientInterface $client,
        ResponseInterface $response,
        StreamInterface $stream
    ): void {
        $posData = [
            'p24_session_id' => 'test',
            'p24_merchant_id' => 'test',
            'p24_amount' => 'test',
            'p24_currency' => 'test',
        ];

        $stream->__toString()->willReturn('error=0&token=test');

        $response->getBody()->willReturn($stream);

        $client->request('POST', $this->getTrnRegisterUrl(), Argument::any())->willReturn($response);

        $this->trnRegister($posData)->shouldReturn('test');
    }

    function it_trn_verify(
        ClientInterface $client,
        ResponseInterface $response,
        StreamInterface $stream
    ): void {
        $posData = [
            'p24_session_id' => 'test',
            'p24_order_id' => 'test',
            'p24_amount' => 'test',
            'p24_currency' => 'test',
        ];

        $stream->__toString()->willReturn('error=0');

        $response->getBody()->willReturn($stream);

        $client->request('POST', $this->getTrnVerifyUrl(), Argument::any())->willReturn($response);

        $this->trnVerify($posData)->shouldReturn(true);
    }

    function it_failed_trn_verify(
        ClientInterface $client,
        ResponseInterface $response,
        StreamInterface $stream
    ): void {
        $posData = [
            'p24_session_id' => 'test',
            'p24_order_id' => 'test',
            'p24_amount' => 'test',
            'p24_currency' => 'test',
        ];

        $stream->__toString()->willReturn('error=1');

        $response->getBody()->willReturn($stream);

        $client->request('POST', $this->getTrnVerifyUrl(), Argument::any())->willReturn($response);

        $this
            ->shouldThrow(\Exception::class)
            ->during('trnVerify', [$posData])
        ;
    }
}
