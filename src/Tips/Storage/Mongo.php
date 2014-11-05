<?php
/**
 * @author Tim Lytle <tim@timlytle.net>
 */

namespace Tips\Storage;

/**
 * Simple service to collect tips.
 */
class Mongo implements StorageInterface
{
    /**
     * @var \MongoDB
     */
    protected $db;

    public function __construct(\MongoDB $db)
    {
        $this->db = $db;
    }

    public function addTip($tip, $from)
    {
        if(!is_string($tip)){
            throw new \InvalidArgumentException('expected tip to be a string');
        }

        $parts = explode(',', $tip);

        if(count($parts) != 2){
            throw new \RuntimeException('tip must contain a single comma');
        }

        $tip = [
            'when' => trim($parts[0]),
            'what' => trim($parts[1]),
            'from' => $from,
        ];

        $this->getTipCollection()->insert($tip);

        $this->getNumberCollection()->update(
            ['_id' => $from],
            [
                '$inc'   => ['tips' => 1],
                '$set' => [
                    'masked' => substr($from, 0, (strlen($from) - 4)) . '----'
                ]
            ],
            ['upsert' => true]
        );
    }

    public function getNumbers()
    {
        $numbers = $this->getNumberCollection()->find();
        return $numbers;
    }

    /**
     * Get the tips a number added.
     *
     * @param string $number
     * @return string[]
     */
    public function getTips($number)
    {
        $tips = $this->getTipCollection()->find([
            'from' => $number
        ]);

        return iterator_to_array($tips);
    }

    public function getRandom($count)
    {
        $tips = $this->getTipCollection()->find();

        $when = [];
        $what = [];

        foreach($tips as $tip){
            $when[] = $tip['when'];
            $what[] = $tip['what'];
        }

        $random = [];

        for($i = 1; $i <= $count; $i++){
            $random[] = $when[array_rand($when)] . ', ' . $what[array_rand($what)];
        }

        return $random;
    }

    /**
     * Update the number info.
     *
     * @param string $number
     * @param array $data
     */
    public function updateNumber($number, $data)
    {
        $this->getNumberCollection()->update(
            ['_id' => $number],
            [
                '$set'   => ['info' => $data],
            ]
        );
    }

    /**
     * @return \MongoCollection
     */
    protected function getTipCollection()
    {
        return $this->db->selectCollection('tips');
    }

    /**
     * @return \MongoCollection
     */
    protected function getNumberCollection()
    {
        return $this->db->selectCollection('numbers');
    }
}