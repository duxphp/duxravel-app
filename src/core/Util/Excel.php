<?php

namespace Duxravel\Core\Util;

use Duxravel\Core\Exceptions\ErrorException;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class Excel
{

    /**
     * @param     $url
     * @param int $start
     * @return array|null
     * @throws ErrorException
     */
    public static function import($url, int $start = 1): array
    {
        $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
        $extArr = ['xlsx', 'xls', 'csv'];
        if (!in_array($ext, $extArr)) {
            app_error('上传文件类型错误');
        }

        $client = new \GuzzleHttp\Client();
        $fileTmp = $client->request('get', $url)->getBody()->getContents();
        $tmpFile = tempnam(sys_get_temp_dir(), 'upload_');
        $tmp = fopen($tmpFile, 'w');
        fwrite($tmp, $fileTmp);
        fclose($tmp);

        try {
            $objRead = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(ucfirst($ext));
            $obj = $objRead->load($tmpFile);
            $currSheet = $obj->getSheet(0);
            $data = $currSheet->toArray();
            @unlink($tmpFile);
            return $data;
        } catch (\Exception $e) {
            @unlink($tmpFile);
            app_error($e->getMessage());
        }
        return [];
    }

    /**
     * @param $title
     * @param $subtitle
     * @param $label
     * @param $data
     */
    public static function export($title, $subtitle, $label, $data): void
    {
        $count = count($label);
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $worksheet = $spreadsheet->getSheet(0);
        //标题
        $worksheet->setCellValueByColumnAndRow(1, 1, $title)->mergeCellsByColumnAndRow(1, 1, $count, 1);
        $styleCenter = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'font' => [
                'size' => 16,
            ],
        ];
        $worksheet->getStyleByColumnAndRow(1, 1)->applyFromArray($styleCenter);

        foreach ($label as $key => $vo) {
            $worksheet->getColumnDimensionByColumn($key + 1)->setWidth($vo['width']);
        }

        $worksheet->setCellValueByColumnAndRow(1, 2, $subtitle)->mergeCellsByColumnAndRow(1, 2, $count, 2);
        $worksheet->getStyleByColumnAndRow(1, 2)->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        //表头
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'font' => [
                'size' => 12,
            ],
        ];
        $headRow = 3;
        foreach ($label as $key => $vo) {
            $worksheet->setCellValueExplicitByColumnAndRow($key + 1, $headRow, $vo['name'], DataType::TYPE_STRING);
            $worksheet->getStyleByColumnAndRow($key + 1, $headRow)->applyFromArray($styleArray);
        }
        foreach ($data as $list) {
            $headRow++;
            foreach ($list as $k => $vo) {
                if (is_array($vo)) {
                    $callback = $vo['callback'];
                    $content = $vo['content'];
                } else {
                    $content = $vo;
                    $callback = '';
                }
                $worksheet->setCellValueExplicitByColumnAndRow($k + 1, $headRow, $content, DataType::TYPE_STRING);
                $item = $worksheet->getStyleByColumnAndRow($k + 1, $headRow)->applyFromArray($styleArray);
                if (is_callable($callback)) {
                    $callback($item);
                }
            }
        }

        unset($worksheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . rawurlencode($title . '-' . date('YmdHis')) . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        $spreadsheet->disconnectWorksheets();
    }

}
