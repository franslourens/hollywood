<?php

namespace App\Tests;

use App\Entity\Screening;
use PHPUnit\Framework\TestCase;

class ScreeningTest extends TestCase
{
    public function cancellations()
    {
         $pastDate1 = new \DateTime();
         $tosub = new \DateInterval('PT1H');
         $pastDate1->sub($tosub);

         $pastDate2 = new \DateTime();
         $tosub = new \DateInterval('PT2H');
         $pastDate2->sub($tosub);

         return array(
             "Scenario: Failed" => array($pastDate1, false),
             "Scenario: Success" => array($pastDate2, true),
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
