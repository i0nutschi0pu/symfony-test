<?php
namespace App\Controller;

use App\Entity\Band;
use App\Repository\BandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class BandsController extends ApiController
{

    /** @var BandRepository */
    private $bandRepository;


    public function __construct(BandRepository $bandRepository)
    {
        $this->bandRepository = $bandRepository;
    }


    /**
    * @Route("/bands", methods="GET")
    */
    public function index()
    {
        $bands = $this->bandRepository->transformAll();

        return $this->respond($bands);
    }

    /**
    * @Route("/bands", methods="POST")
    */
    public function create(Request $request)
    {
        $request = $this->transformJsonBody($request);

        if (! $request) {
            return $this->respondValidationError('Please provide a valid request!');
        }

        if (! $request->get('name')) {
            return $this->respondValidationError('Please provide a name!');
        }

        $band = new Band;
        $band->setName($request->get('name'));
        $this->bandRepository->save($band);

        return $this->respondCreated($this->bandRepository->transform($band));
    }
}