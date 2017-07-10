<?php

class MyA
{
    private $b;

    public function __construct(MyB $b)
    {
        $this->b = $b;
    }

    public function speak()
    {
        echo 'Hello';
    }
}

class MyB
{
    private $a;

    public function __construct(MyA $a)
    {
        $this->a = $a;
    }

    public function speak()
    {
        echo $this->a->speak().' '.'World';
    }
}

class ProxyMyA extends MyA
{
    private $myB;

    public function __construct() {}

    public function setMyB(MyB $b)
    {
        $this->myB = $b;
    }

    public function speak()
    {
        return $this->MyA->speak();
    }

    public function __get($key)
    {
        return $this->MyA = new MyA($this->myB);
    }
}

class ProxyMyB extends MyB
{
    private $myA;

    public function __construct() {}

    public function setMyA(MyA $a)
    {
        $this->myA = $a;
    }

    public function speak()
    {
        return $this->MyB->speak();
    }

    public function __get($key)
    {
        return $this->MyB = new MyB($this->myA);
    }
}

$lazyA = new ProxyMyA();
$lazyB = new ProxyMyB();

$lazyA->setMyB($lazyB);
$lazyB->setMyA($lazyA);

$lazyB->speak();


passthru('sudo ls');