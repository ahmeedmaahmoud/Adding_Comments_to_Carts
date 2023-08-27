<?php
namespace Myvendor\Comments\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class Comment extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $comment = $item['comment'];
                $item['comment'] = $comment;
            }
        }
        return $dataSource;
    }
}
