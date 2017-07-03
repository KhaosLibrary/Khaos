<?php

interface Validator
{
    public function validate();
}

class TypeKeyword implements KeywordValidator
{
    const KEYWORD = 'type';

    public function validate()
    {

    }

    public function getKeyword()
    {
        return self::KEYWORD;
    }
}

class PropertyPatternKeyword implements InformativeKeyword
{
    const KEYWORD = 'propertyPattern';

    public function getKeyword()
    {
        return self::KEYWORD;
    }
}

class Keywords
{
    private $keywords = [];

    public function add(Keyword $keyword)
    {
        $this->keywords[$keyword->getKeyword()] = $keyword;
    }

    /**
     * @param string $keyword
     *
     * @return Validator
     *
     * @throws Exception
     */
    public function __get($keyword)
    {
        if (!isset($this->keywords[$keyword]))
            throw new Exception();

        $keyword = $this->keywords[$keyword];

        if ($keyword instanceof KeywordValidator)
            return $this->{$keyword} = $keyword;

        if ($keyword instanceof InformativeKeyword)
            return new class implements Validator
            {
                public function validate()
                {
                    return;
                }
            };

        throw new Exception();
    }
}

interface KeywordValidator extends Keyword, Validator {}
interface InformativeKeyword extends Keyword {}

interface Keyword
{
    public function getKeyword();
}

class MyValidator
{
    public $keywords;

    public function __construct($keywords)
    {
        $this->keywords = $keywords;
    }

    public function validate($schema, $instance)
    {
        foreach (array_keys($schema) as $keyword)
            $this->keywords->{$keyword}->validate();
    }
}

$typeKeyword            = new TypeKeyword();
$propertyPatternKeyword = new PropertyPatternKeyword();

$keywords = new Keywords();
$keywords->add($typeKeyword);
$keywords->add($propertyPatternKeyword);

$myValidator = new MyValidator($keywords);
$myValidator->validate([], []);