<?php

namespace Duxravel\Core\Util;

use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Excel
{

    public static function import($url, $start = 1): ?array
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
            $objRead->setReadDataOnly(true);
            $obj = $objRead->load($tmpFile);
            $currSheet = $obj->getSheet(0);
            $columnH = $currSheet->getHighestColumn();
            $columnCnt = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($columnH);
            $rowCnt = $currSheet->getHighestRow();
            $data = [];
            for ($_row = $start; $_row <= $rowCnt; $_row++) {
                $isNull = true;
                for ($_column = 1; $_column <= $columnCnt; $_column++) {
                    $cellName = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($_column);
                    $cellId = $cellName . $_row;
                    $data[$_row][$cellName] = trim($currSheet->getCell($cellId)->getFormattedValue());
                    if (!empty($data[$_row][$cellName])) {
                        $isNull = false;
                    }
                }
                if ($isNull) {
                    unset($data[$_row]);
                }
            }
            $table = [];
            foreach ($data as $vo) {
                $table[] = array_values($vo);
            }
            @unlink($tmpFile);
            return $table;
        } catch (\Exception $e) {
            @unlink($tmpFile);
            app_error($e->getMessage());
        }
    }

    public static function export($title, $subtitle, $label, $data)
    {
        $columns = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U',
            'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN',
            'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
        ];

        $count = count($label);
        $column = $columns[$count - 1];
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $worksheet = $spreadsheet->getSheet(0);
        //标题
        $worksheet->setCellValue('A1', $title)->mergeCells("A1:".$column.'1');
        $styleCenter = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'font'      => [
                'size' => 16,
            ],
        ];
        $worksheet->getStyle('A1')->applyFromArray($styleCenter);
        foreach ($label as $key => $vo) {
            $worksheet->getColumnDimension($columns[$key])->setWidth($vo['width']);
        }

        $worksheet->setCellValue('A2', $subtitle)->mergeCells("A2:".$column.'2');
        $worksheet->getStyle('A2')->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);
        //表头
        $styleArray = [
            'borders'   => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'       => ['argb' => '000000'],
                ],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'font'      => [
                'size' => 12,
            ],
        ];
        $headRow = 3;
        foreach ($label as $key => $vo) {
            $worksheet->setCellValueExplicit($columns[$key].$headRow, $vo['name'],
                's')->getStyle($columns[$key].$headRow)->applyFromArray($styleArray);
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
                $item = $worksheet->setCellValueExplicit($columns[$k].$headRow, $content,
                    's')->getStyle($columns[$k].$headRow)->applyFromArray($styleArray);
                if ($callback) {
                    $callback($item);
                }
            }
        }

        unset($worksheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$title.'-'.date('YmdHis').'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        $spreadsheet->disconnectWorksheets();
    }

}
