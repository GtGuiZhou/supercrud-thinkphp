<?php
declare (strict_types=1);

namespace app;

use app\exceptions\CheckException;
use app\exceptions\InternalException;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;
use think\db\exception\PDOException;
use think\db\Query;
use think\facade\Db;

/**
 * 管理员控制器基础类
 */
class AdminController extends BaseController
{

    /**
     * @var string 搜索字段
     */
    protected $searchField = '';
    protected $updateValidate = [];
    protected $updatePolicy = []; // 不允许更新的字段
    protected $insertValidate = [];
    protected $indexHiddenField = []; // 输出要隐藏哪些字段
    protected $exportField = [];
    protected $importField = [];

    protected $indexWith = '';

    protected function indexQuery(Query $query)
    {
    }


    public function index()
    {
        $page = $this->request->get('page', 1);
        $size = $this->request->get('size', 'all');
        $search = $this->request->get('search');
        $order = $this->request->get('order');
        $where = $this->request->get('where');
        $query = function (Query $query) use ($search, $order, $where) {
            if ($search) {
                $query->whereLike($this->searchField, "%$search%");
            }
            if ($where) {
                $where = json_decode(urldecode($where), true);
                foreach ($where as $key => $val) {
                    if ($val != '') $query->where($key, $val);
                }
            }
            $this->indexQuery($query);
        };
        $total = $this->model
            ->where($query)->count();

        if ($size == 'all') $size = $total;
        $list = $this->model
            ->page($page, $size)
            ->where($query)
            ->with($this->indexWith)
            ->hidden($this->indexHiddenField)
            ->select();
        return json(['data' => $list, 'total' => $total]);
    }


    public function update($id)
    {

        $data = $this->validate($this->request->put(), $this->updateValidate);
        $model = $this->model->find($id);
        if (!$model) {
            throw new CheckException('更新数据不存在');
        }
        foreach ($this->updatePolicy as $field) {
            unset($data[$field]);
        }

        try {
            $model->save($data);
        } catch (PDOException $e) {
            $msg = $e->getMessage();
            if (preg_match("/.+Integrity constraint violation: 1062 Duplicate entry '(.+)' for key '(.+)'/is", $msg, $matches)) {
                throw new CheckException("修改失败，包含【{$matches[1]}】的记录已存在");
            } else {
                throw new InternalException($e);
            }
        }
        return json($model);
    }

    public function delete($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            throw new CheckException('删除数据不存在');
        }
        $model->delete();

    }

    public function insert()
    {
        $data = $this->validate($this->request->post(), $this->insertValidate);
        try {
            $model = $this->model->save($data);
        } catch (PDOException $e) {
            $msg = $e->getMessage();
            if (preg_match("/.+Integrity constraint violation: 1062 Duplicate entry '(.+)' for key '(.+)'/is", $msg, $matches)) {
                throw new CheckException("添加失败，包含【{$matches[1]}】的记录已存在");
            } else {
                throw new InternalException($e);
            }
        }
        return json($model);
    }


    public function import()
    {
        $file = $this->request->file('file');
        $ext = pathinfo($file->getOriginalName(), PATHINFO_EXTENSION);
        $filePath = $file->getPathname();
        switch ($ext) {
            case 'xlsx':
                $reader = new ReaderXlsx();
                break;
            case 'xls':
                $reader = new Xls();
                break;
            case 'csv':
                $reader = new Csv();
                break;
            default:
                throw new CheckException('仅支持xlsx,csv,xls三种文件格式');
        }
        // 建立一个comment -> field 映射表
        $table = $this->model->getTable();
        $fields = Db::query("show full columns from $table");
        $cf = [];
        foreach ($fields as $field) {
            if (in_array($field['Field'], $this->importField) && $field['Comment'])
                $cf[$field['Comment']] = $field['Field'];
        }
        // 读取数据
        $spread = $reader->load($filePath);
        $worksheet = $spread->getActiveSheet();
        $highestRow = $worksheet->getHighestRow(); // 总行数
        $highestColumn = $worksheet->getHighestColumn(); // 总列数
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5;
        if ($highestRow < 2) {
            throw new CheckException('文件中没有数据记录');
        }
        // 读取表头
        $header = [];
        for ($col = 1; $col < $highestColumnIndex; $col++) {
            $title = $worksheet->getCellByColumnAndRow($col, 1)->getValue();
            // 判断标题名是否是数据库字段,或者字段注释
            if (in_array($title, $this->importField)) {
                $header[] = ['col' => $col, 'field' => $title];
            } elseif (isset($cf[$title])) {
                $header[] = ['col' => $col, 'field' => $cf[$title]];
            }
        }
        // 读取数据
        $body = [];
        for ($row = 2; $row < $highestColumnIndex; $row++) {
            $dataItem = [];
            foreach ($header as $item) {
                $dataItem[$item['field']] = $worksheet->getCellByColumnAndRow($item['col'], $row)->getValue();
            }
            $v = validate($this->insertValidate);
            $v->batch(true)->failException(false);
            if (!$v->check($dataItem)) {
                $error = $v->getError();
                $keys = array_keys($error);
                $errorData = $dataItem[$keys[0]];
                throw new CheckException("异常数据【${errorData}】:" . $error[$keys[0]]);
            }
            $body[] = $dataItem;
        }

        if (count($body) < 1) {
            throw new CheckException('没有符合的数据记录');
        }

        try {
            $this->model->saveAll($body);
        } catch (PDOException $e) {
            $msg = $e->getMessage();
            if (preg_match("/.+Integrity constraint violation: 1062 Duplicate entry '(.+)' for key '(.+)'/is", $msg, $matches)) {
                throw new CheckException("导入失败，包含【{$matches[1]}】的记录已存在");
            } else {
                throw new InternalException($e);
            }
        }
    }

    public function export()
    {

        if (count($this->exportField) < 1) {
            throw new CheckException('没有设定导出字段');
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $table = $this->model->getTable();
        $fields = Db::query("show full columns from $table");
        // 导出标题
        $fieldNames = array_column($fields, 'Field');
        foreach ($this->exportField as $exportIndex => $exportFieldName) {
            // 检测导出字段是否存在
            $searchIndex = array_search($exportFieldName, $fieldNames);
            if ($searchIndex === false) {
                throw new CheckException("导出字段 $exportFieldName 不存在");
            }
            // 设置标题
            $title = $fields[$searchIndex]['Comment'] ? $fields[$searchIndex]['Comment'] : $fields[$searchIndex]['Field'];
            $titles[] = $title;
            $sheet->setCellValueByColumnAndRow($exportIndex + 1, 1, $title);
        }

        // 导出数据
        if ($this->request->post('size') == 'all')
            $data = $this->model->select();
        else
            $data = $this->index()->getData();
        for ($row = 0; $row < $data['total']; $row++) {
            foreach ($this->exportField as $exportIndex => $exportFieldName) {
                $content = $data['data'][$row][$exportFieldName];
//                var_dump($content);
                $sheet->setCellValueByColumnAndRow($exportIndex + 1, $row + 2, $content);
            }
        }


        $writer = new WriterXlsx($spreadsheet);
        $writer->save('php://output');
        $xlsx = ob_get_contents(); // download中会自动clan
        $fileName = date("YmdHis") . '.xlsx';
        return download($xlsx, $fileName, true);
    }


}
