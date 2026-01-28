<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Wallet;

class WalletTest extends TestCase
{
    public function testCreation(): void
    {
        $wallet = new Wallet('USD');
        $this->assertEquals(0, $wallet->getBalance());
        $this->assertEquals('USD', $wallet->getCurrency());
    }

    public function testCreationInvalidCurrency(): void
    {
        $this->expectException(\Exception::class);
        new Wallet('BTC');
    }

    public function testSetAndGetBalance(): void
    {
        $wallet = new Wallet('EUR');
        $wallet->setBalance(100.5);
        $this->assertEquals(100.5, $wallet->getBalance());
    }

    public function testSetBalanceNegative(): void
    {
        $wallet = new Wallet('USD');
        $this->expectException(\Exception::class);
        $wallet->setBalance(-10);
    }


    public function testAddFund(): void
    {
        $wallet = new Wallet(currency: 'USD');
        $amount = 50;
        $wallet->addFund($amount);
        $this->assertEquals($amount , $wallet->getBalance());
    }


    public function testAddFundNegativeThrows(): void
    {
        $wallet = new Wallet('USD');
        $this->expectException(\Exception::class);
        $wallet->addFund(-5);
    }

    public function testRemoveFund(): void
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(50);
        $wallet->removeFund(20);
        $this->assertEquals(30, $wallet->getBalance());
    }

    public function testRemoveNegative(): void
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(10);
        $this->expectException(\Exception::class);
        $wallet->removeFund(-1);
    }

    public function testInsufficientRemoveFund(): void
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(10);
        $this->expectException(\Exception::class);
        $wallet->removeFund(20);
    }
}