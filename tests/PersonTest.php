<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Person;
use App\Entity\Wallet;
use App\Entity\Product;

class PersonTest extends TestCase
{
    public function testCreation(): void
    {
        $person = new Person('Alice', 'USD');
        $this->assertEquals('Alice', $person->getName());
        $this->assertInstanceOf(Wallet::class, $person->getWallet());
        $this->assertEquals('USD', $person->getWallet()->getCurrency());
    }

    public function testSetName(): void
    {
        $person = new Person('Bob', 'EUR');
        $person->setName('Robert');
        $this->assertEquals('Robert', $person->getName());
    }

    public function testSetWallet(): void
    {
        $person = new Person('Alice', 'USD');
        $wallet = new Wallet('EUR');
        $person->setWallet($wallet);
        $this->assertEquals('EUR', $person->getWallet()->getCurrency());
    }

    public function testHasFund(): void
    {
        $person = new Person('Alice', 'USD');
        $this->assertFalse($person->hasFund());
        $person->getWallet()->addFund(10);
        $this->assertTrue($person->hasFund());
    }

    public function testTransfertFund(): void
    {
        $alice = new Person('Alice', 'USD');
        $bob = new Person('Bob', 'USD');
        $alice->getWallet()->addFund(100);
        $alice->transfertFund(40, $bob);
        $this->assertEquals(60, $alice->getWallet()->getBalance());
        $this->assertEquals(40, $bob->getWallet()->getBalance());
    }

    public function testTransfertFundByCurrency(): void
    {
        $alice = new Person('Alice', 'USD');
        $bob = new Person('Bob', 'EUR');
        $alice->getWallet()->addFund(100);
        $this->expectException(\Exception::class);
        $alice->transfertFund(10, $bob);
    }

    public function testInsufficientTransfertFund(): void
    {
        $alice = new Person('Alice', 'USD');
        $bob = new Person('Bob', 'USD');
        $alice->getWallet()->addFund(5);
        $this->expectException(\Exception::class);
        $alice->transfertFund(10, $bob);
    }

    public function testDivideWallet(): void
    {
        $alice = new Person('Alice', 'USD');
        $bob = new Person('Bob', 'USD');
        $carol = new Person('Carol', 'USD');
        $alice->getWallet()->addFund(100);
        $alice->divideWallet([$bob, $carol]);
        $this->assertEquals(0, $alice->getWallet()->getBalance());
        $this->assertEquals(50, $bob->getWallet()->getBalance());
        $this->assertEquals(50, $carol->getWallet()->getBalance());
    }

    public function testWalletByCurrency(): void
    {
        $alice = new Person('Alice', 'USD');
        $bob = new Person('Bob', 'EUR');
        $carol = new Person('Carol', 'USD');
        $alice->getWallet()->addFund(90);
        $alice->divideWallet([$bob, $carol]);
        $this->assertEquals(0, $alice->getWallet()->getBalance());
        $this->assertEquals(0, $bob->getWallet()->getBalance());
        $this->assertEquals(90, $carol->getWallet()->getBalance());
    }

    public function testUnavailableBuyProduct(): void
    {
        $person = new Person('Alice', 'USD');
        $person->getWallet()->addFund(100);
        $product = new Product('Wine', ['EUR' => 20], 'alcohol');
        $this->expectException(\Exception::class);
        $person->buyProduct($product);
    }

    public function testInsufficientFundBuyProduct(): void
    {
        $person = new Person('Alice', 'USD');
        $person->getWallet()->addFund(5);
        $product = new Product('Bread', ['USD' => 10], 'food');
        $this->expectException(\Exception::class);
        $person->buyProduct($product);
    }
}