<?php

namespace App\Tests;

use App\Entity\Screening;
use PHPUnit\Framework\TestCase;

class ScreeningTest extends TestCase
{
    public function cancellations()
    {
         return array(
             "Scenario: Failed" => array(new \DateTime("2022-04-10 10:00:00"), false),
             "Scenario: Success" => array(new \DateTime("2022-04-10 15:00:00"), true),
         );
     }

     /**
      * @test
      * @dataProvider cancellations
      **/
    public function can_cancel($cancel, $result)
    {
        $screening = new Screening();
        $screening->setStart($cancel);
        $can_cancel = $screening->can_cancel();

        $this->assertEquals($result, $can_cancel);
    }
}
