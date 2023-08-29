<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Band;
use App\Repository\BandRepository;


Class SaveBandInfo 
{

    /** @var BandRepository */
    private $bandRepo;


    public function __construct(BandRepository $bandRepo)
    {
        $this->bandRepo = $bandRepo;
    }

    public function saveBandInfo(array $bandInfo): void
    {
        foreach ($bandInfo as $key => $value) {
            if(is_array($value['columnValues'])){
                foreach ($value['columnValues'] as $k => $v) {
                    $band = new Band();
                    $band->setName($v[0]);
                    $this->bandRepo->save($band);
                }
            }
        }
    }
}