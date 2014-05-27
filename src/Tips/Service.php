<?php
/**
 * @author Tim Lytle <tim@timlytle.net>
 */

namespace Tips;

/**
 * Simple service to collect tips.
 */
class Service
{
    /**
     * @var Mongo
     */
    protected $mongo;

    /**
     * @var \MongoDB
     */
    protected $db;

    /**
     * @var \MongoCollection
     */
    protected $collection;

    public function __construct(\MongoClient $mongo, $dbname = 'nexmo_demo', $collection = 'tips')
    {
        $this->mongo = $mongo;
        $this->db = $mongo->selectDB($dbname);
        $this->collection = $this->db->selectCollection('tips');
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
            'from' => $from
        ];

        $this->collection->insert($tip);
    }

    public function getNumbers()
    {
        $numbers = $this->collection->distinct('from');
        return $numbers;
    }

    public function getRandom($count)
    {
        $tips = $this->collection->find();

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
} 