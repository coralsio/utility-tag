<?php

namespace Corals\Utility\Tag\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Utility\Tag\Models\Tag;
use Corals\Utility\Tag\Transformers\TagTransformer;
use Yajra\DataTables\EloquentDataTable;

class TagsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('utility-tag.models.tag.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new TagTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Tag $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Tag $model)
    {
        return $model->newQuery();
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id' => ['visible' => false],
            'name' => ['title' => trans('utility-tag::attributes.tag.name')],
            'slug' => ['title' => trans('utility-tag::attributes.tag.slug')],
            'module' => ['title' => trans('utility-tag::attributes.tag.module')],
            'status' => ['title' => trans('Corals::attributes.status')],
            'created_at' => ['title' => trans('Corals::attributes.created_at')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }

    protected function getBulkActions()
    {
        return [
            'delete' => ['title' => trans('Corals::labels.delete'), 'permission' => 'Utility::tag.delete', 'confirmation' => trans('Corals::labels.confirmation.title')],
            'active' => ['title' => '<i class="fa fa-check-circle"></i> ' . trans('Corals::attributes.status_options.active'), 'permission' => 'Utility::tag.update', 'confirmation' => trans('Corals::labels.confirmation.title')],
            'inActive' => ['title' => '<i class="fa fa-check-circle-o"></i> ' . trans('Corals::attributes.status_options.inactive'), 'permission' => 'Utility::tag.update', 'confirmation' => trans('Corals::labels.confirmation.title')],
        ];
    }

    protected function getOptions()
    {
        $url = url(config('utility-tag.models.tag.resource_url'));

        return ['resource_url' => $url];
    }
}
