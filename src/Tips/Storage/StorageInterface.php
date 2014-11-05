<?php
/**
 * Created by PhpStorm.
 * User: tjlytle
 * Date: 11/3/14
 * Time: 12:17 PM
 */

namespace Tips\Storage;


interface StorageInterface
{
    /**
     * Add a tip to the system, ID the user by phone number.
     *
     * Track the inbound number, for context later.
     *
     * @param string $tip
     * @param string $from
     * @param string $to
     * @return null
     */
    public function addTip($tip, $from, $to);

    /**
     * Get the tips a number added.
     *
     * @param string $number
     * @return string[]
     */
    public function getTips($number);

    /**
     * Get an array of random tips.
     *
     * @param int $count
     * @return string[]
     */
    public function getRandom($count);

    /**
     * Get an array of numbers, and any information about them.
     *
     * @return array[]
     */
    public function getNumbers();

    /**
     * Update the number info.
     *
     * @param string $number
     * @param array $data
     */
    public function updateNumber($number, $data);
}