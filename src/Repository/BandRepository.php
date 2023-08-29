<?php

declare(strict_types=1);

namespace App\Repository;
use App\Entity\Band;
use Doctrine\ORM\EntityRepository;

class BandRepository extends EntityRepository
{
    public function save(Band $band): void
    {
        $this->_em->persist($band);
        $this->_em->flush();
    }

    public function transform(Band $band)
    {
        return [
                'id'    => (int) $band->getId(),
                'name' => (string) $band->getName(),
        ];
    }

    public function transformAll()
    {
        $bands = $this->findAll();
        $bandsArray = [];

        foreach ($bands as $band) {
            $bandsArray[] = $this->transform($band);
        }

        return $bandsArray;
    }
}