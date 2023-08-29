<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\SaveBandInfo;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


Class HomeController extends AbstractController
{
    /** @var SaveBandInfo */
    private $saveBandInfo;


    public function __construct(SaveBandInfo $saveBandInfo)
    {
        $this->saveBandInfo = $saveBandInfo;
    }

   
    const FILENAME = 'test';

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/import", name="import")
     */
    public function importAction(Request $request)
    {
        $filename = $this->getParameter('kernel.project_dir').'/export/'.self::FILENAME.'.xlsx';
        if (!file_exists($filename)) {
            throw new \Exception('File does not exist');
        }

        $spreadsheet = $this->readFile($filename);
        $data = $this->createDataFromSpreadsheet($spreadsheet);

        return $this->render('import.html.twig', [
            'data' => $data,
        ]);
    }

    protected function loadFile($filename)
    {
        return IOFactory::load($filename);
    }

    protected function readFile($filename)
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'xlsx':
                $reader = new ReaderXlsx();
                break;
            default:
                throw new \Exception('Invalid extension');
        }
        $reader->setReadDataOnly(true);
        return $reader->load($filename);
    }

    protected function createDataFromSpreadsheet($spreadsheet)
    {
        $data = [];
        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            $worksheetTitle = $worksheet->getTitle();
            $data[$worksheetTitle] = [
                'columnNames' => [],
                'columnValues' => [],
            ];

            foreach ($worksheet->getRowIterator() as $row) {
                $rowIndex = $row->getRowIndex();
                if ($rowIndex > 1) {
                    $data[$worksheetTitle]['columnValues'][$rowIndex] = [];
                }
                $cellIterator = $row->getCellIterator();
                
                $cellIterator->setIterateOnlyExistingCells(false); 
                foreach ($cellIterator as $cell) {
                    if ($rowIndex === 1) {
                        $data[$worksheetTitle]['columnNames'][] = $cell->getCalculatedValue();
                    }
                    if ($rowIndex > 1) {
                        $data[$worksheetTitle]['columnValues'][$rowIndex][] = $cell->getCalculatedValue();
                    }
                }
            }
        }

        $this->saveBandInfo->saveBandInfo($data);

        return $data;
    }
    
}

