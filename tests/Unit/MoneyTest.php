<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

class MoneyTest extends TestCase
{
	/** @test */
	public function Creating()
	{
        $money1 = Money::make(12345);
        $money2 = new Money(12345);

        $this->assertEquals($money1, $money2);
	}

    /** @test
	 * @throws CurrencyDoesNotExistException
	 */
	public function BaseOfFormatting()
	{
		$usd = Currency::code('USD');
		$rub = Currency::code('RUB');

		$this->assertEquals('$ 123', Money::make(1230, $usd)->toString());
		$this->assertEquals('$ 123.4', Money::make(1234, $usd)->toString());
		$this->assertEquals('$ 1 234', Money::make(12340, $usd)->toString());
		$this->assertEquals('$ 1 234.5', Money::make(12345, $usd)->toString());

		$this->assertEquals('123 ₽', Money::make(1230, $rub)->toString());
		$this->assertEquals('123.4 ₽', Money::make(1234, $rub)->toString());
		$this->assertEquals('1 234 ₽', Money::make(12340, $rub)->toString());
		$this->assertEquals('1 234.5 ₽', Money::make(12345, $rub)->toString());
	}

	/** @test */
	public function String()
	{
	    $money = Money::make(1234);

	    $this->assertEquals('$ 123.4', $money->toString());
	    $this->assertEquals('$ 123.4', strval($money));
	    $this->assertEquals('$ 123.4', '' . $money);
	    $this->assertEquals('$ 123.4', $money);
	}

	/** @test */
	public function origin_int_money_gets_rid_of_decimals_with_clear_method()
	{
	    $money = new Money(132.76);

	    $this->assertEquals(132.76, $money->getPureNumber());
	    $this->assertEquals('$ 13.3', $money->toString());

	    $money->clear();

	    $this->assertEquals(130, $money->getPureNumber());
	    $this->assertEquals('$ 13', $money->toString());
	}

	/** @test */
	public function origin_float_money_gets_rid_of_decimals_with_clear_method()
	{
	    $settings = new MoneySettings();
        $money = new Money(13.276, $settings->setOrigin(MoneySettings::ORIGIN_FLOAT));

        $this->assertEquals(13.276, $money->getPureNumber());
        $this->assertEquals('$ 13.3', $money->toString());

        $money->clear();

        $this->assertEquals(13, $money->getPureNumber());
        $this->assertEquals('$ 13', $money->toString());
	}
}