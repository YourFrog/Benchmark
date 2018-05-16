<?php

namespace YourFrog\Benchmark;

/**
 *  Klasa wspomagająca wyliczanie czasu
 */
class Timer
{
    /**
     *  Przechowywane elementy
     *
     * @var array
     */
    private $elements = [];

    /**
     *  Domyślna nazwa pod którą zapisujemy informacje
     *
     * @var string
     */
    private $defaultName;

    /**
     *  Konstruktor
     *
     * @param string|null $defaultName
     */
    public function __construct($defaultName = null)
    {
        $this->defaultName = $defaultName;
    }

    /**
     *  Rozpoczęcie liczenia czasu
     *
     * @param string|null $name
     */
    public function start($name = null)
    {
        $name = $this->getName($name);
        $this->elements[$name]['start'] = microtime(true);
    }

    /**
     *  Zakończenie liczenia czasu
     *
     * @param string|null $name
     */
    public function finish($name = null)
    {
        $name = $this->getName($name);
        $this->elements[$name]['finish'] = microtime(true);
    }

    /**
     *  Różnica czasu
     *
     * @param string|null $name
     *
     * @return double
     */
    public function diff($name = null)
    {
        $name = $this->getName($name);
        $data = $this->elements[$name];

        if( array_key_exists('finish', $data) === false ) {
            $this->finish($name);
        }

        return $data['finish'] - $data['start'];
    }

    /**
     *  Pobranie nazwy pod którą szukamy informacji
     *
     * @param string $name
     *
     * @return string
     *
     * @throws BenchmarkException
     */
    private function getName($name)
    {
        if( $name !== null ) {
            return $name;
        }

        if( $this->defaultName !== null ) {
            return $this->defaultName;
        }

        throw new BenchmarkException('not defined "$name" in Benchmark\Timer');
    }
    /**
     *  Rzucenie wyników do postaci tablicy
     *
     * @return array
     */
    public function toArray()
    {
        $data = [];

        foreach($this->elements as $name => $element) {
            $data[$name] = [
                'start' => $element['start'],
                'finish' => $element['finish'],
                'difference' => $this->diff($name)
            ];
        }

        return $data;
    }
}