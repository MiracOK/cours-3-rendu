<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Product;

class ProductTest extends TestCase
{
    public function testCreationValid(): void
    {
        $product = new Product('Apple', ['USD' => 1.5, 'EUR' => 1.2], 'food');
        $this->assertEquals('Apple', $product->getName());
        $this->assertEquals(['USD' => 1.5, 'EUR' => 1.2], $product->getPrices());
        $this->assertEquals('food', $product->getType());
    }

    public function testInvalidProduct(): void
    {
        $this->expectException(\Exception::class);
        new Product('Laptop', ['USD' => 1000], 'invalid');
    }

    public function testSetPricesInvalid(): void
    {
        $product = new Product('Test', ['USD' => 10, 'BTC' => 100, 'EUR' => -5], 'tech');
        $this->assertEquals(['USD' => 10], $product->getPrices());
    }

    public function testGetTVA(): void
    {
        $food = new Product('Bread', ['USD' => 2], 'food');
        $tech = new Product('Phone', ['USD' => 500], 'tech');
        $this->assertEquals(0.1, $food->getTVA());
        $this->assertEquals(0.2, $tech->getTVA());
    }

    public function testListCurrencies(): void
    {
        $product = new Product('Book', ['USD' => 5, 'EUR' => 4.5], 'other');
        $this->assertEquals(['USD', 'EUR'], $product->listCurrencies());
    }

    public function testGetPriceValid(): void
    {
        $product = new Product('Pen', ['USD' => 1], 'other');
        $this->assertEquals(1, $product->getPrice('USD'));
    }

    public function testGetPriceInvalid(): void
    {
        $product = new Product('Pen', ['USD' => 1], 'other');
        $this->expectException(\Exception::class);
        $product->getPrice('BTC');
    }

    public function testGetPriceUnavailable(): void
    {
        $product = new Product('Pen', ['USD' => 1], 'other');
        $this->expectException(\Exception::class);
        $product->getPrice('EUR');
    }
}