<?php

namespace App\DataTables\InvoiceProduct;

use Carbon\Carbon;
use App\Models\CoreProduct;
use App\Helpers\Configuration;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ProductDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', 'content.InvoiceProduct.add._action-menu')
            ->editColumn('client.client_id',fn($query)=>"{$query->client->name}")
            ->editColumn('type.product_type_id',fn($query)=>"{$query->type->name}")
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(CoreProduct $model): QueryBuilder
    {
        return $model->newQuery()->with(['invoice'=>function($query){
            $query->where('invoice_type', 3);
            $query->whereYear('invoice_date', Carbon::now()->parse('Y'));
            $query->whereMonth('invoice_date', Carbon::now()->parse('m'));
        },'client','type','addons'=>function($query){$query->where('payment_status', 0);},'termin'=>function($query){$query->where('payment_status', 0);
        }])->whereHas('addons', function (QueryBuilder $query) {
            $query->where('payment_status', 0);
        })->whereHas('termin', function (QueryBuilder $query) {
            $query->where('payment_status', 0);
        })->orWhereDoesntHave('invoice', function (QueryBuilder $query) {
            $query->where('invoice_type', 3);
            $query->whereYear('invoice_date', Carbon::now()->parse('Y'));
            $query->whereMonth('invoice_date', Carbon::now()->parse('m'));
        });
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('product-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->stateSave(true)
                    ->orderBy(0, 'asc')
                    ->dom('frtip')
                    ->responsive()
                    ->autoWidth(true)
                    ->addTableClass('align-middle table table-row-dashed fs-4 gy-4')
                    ;
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('product_id')->title(__('No'))->data('DT_RowIndex') ->addClass('text-center')->width(5),
            Column::make('name')->title("Nama")->width(10),
            Column::make('client.client_id')->title("Client")->width(10),
            Column::make('type.product_type_id')->title("Tipe")->width(10),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(50)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Product_' . date('YmdHis');
    }
}
