<?php

namespace Duxravel\Core\UI\Form;

/**
 * Class Tree
 * @package Duxravel\Core\UI\Table
 */
class Tree extends Element implements Component
{

    protected $data;

    /**
     * Tree constructor.
     * @param string $name
     * @param string $field
     * @param null $data
     * @param string $has
     */
    public function __construct(string $name, string $field, $data = null, string $has = '')
    {
        $this->name = $name;
        $this->field = $field;
        $this->data = $data;
        $this->has = $has;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        $data = $this->data;
        if ($this->data instanceof \Closure) {
            $data = call_user_func($this->data);
        }
        if ($data instanceof \Illuminate\Database\Eloquent\Collection) {
            $data = $data->toArray();
        }

        $data = $this->loop($data);
        $data = [
            'nodeName' => 'div',
            'class' => 'bg-gray-100 dark:bg-blackgray-2 p-2 rounded w-full h-56  overflow-y-auto app-scrollbar',
            'child' => [
                'nodeName' => 'a-tree',
                'blockNode' => true,
                'checkable' => true,
                'showLine' => true,
                'vModel:checked-keys' => $this->getModelField(),
                'data' => $data,
            ]
        ];
        return $data;
    }

    /**
     * @param $value
     * @return array|null
     */
    public function dataValue($value): ?array
    {
        return $this->getValueArray($value);
    }

    /**
     * @param $data
     * @return string
     */
    public function dataInput($data): ?string
    {
        return is_array($data) ? implode(',', $data) : '';
    }

    /**
     * @param $data
     * @return array
     */
    protected function loop($data): array
    {
        $newData = [];
        foreach ($data as $item) {
            $tmpData = [
                'title' => $item['name'],
                'key' => $item['id'],
            ];
            if ($item['children']) {
                $tmpData['children'] = $this->loop($item['children']);
            }
            $newData[] = $tmpData;
        }
        return $newData;
    }

}
