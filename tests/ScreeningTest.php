<?php

namespace App\Tests;

use App\Entity\Screening;
use PHPUnit\Framework\TestCase;

class ScreeningTest extends TestCase
{
    public function cancellations()
    {
         $futureDate = new \DateTime();
         $tosub = new \DateInterval('PT2H');
         $futureDate->sub($tosub);

         $pastDate = new \DateTime();
         $tosub = new \DateInterval('PT1H');
         $pastDate->sub($tosub);

         return array(
             "Scenario: Failed" => array($pastDate, false),
             "Scenario: Success" => array($futureDate, true),
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
