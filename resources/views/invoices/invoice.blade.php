@extends('layouts.master')
@section('title')
    قائمة الفواتير
@stop
@section('css')

    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!--Internal   Notify -->
    <link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('title')
    الفواتير
@stop
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">قائمة الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ الفواتير</span>
						</div>
					</div>

				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
    @include('includes.alerts.errors')
    @include('includes.alerts.success')
    @if (session()->has('delete_invoice'))
        <script>
            window.onload = function() {
                notif({
                    msg: "{{session()->get('delete_invoice')}}",
                    type: "success"
                })
            }
        </script>
    @endif
    @if (session()->has('edit_status'))
        <script>
            window.onload = function() {
                notif({
                    msg: "{{session()->get('edit_status')}}",
                    type: "success"
                })
            }
        </script>
    @endif
    <div class="row">
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">

                    @can('اضافة فاتورة')
                        <a href="invoices/create" class="modal-effect btn btn-sm btn-primary" style="color:white"><i
                                class="fas fa-plus"></i>&nbsp; اضافة فاتورة</a>
                    @else
                        <a class="modal-effect btn btn-sm btn-primary disabled" style="color:white"><i class="fas fa-plus"></i>&nbsp; اضافة فاتورة</a>
                    @endcan
                       @can('تصدير EXCEL')
                            <a class="modal-effect btn btn-sm btn-primary" href="{{ url('invoices_export') }}"
                               style="color:white"><i class="fas fa-file-download"></i>&nbsp;تصدير اكسيل</a>
                        @else
                            <a class="modal-effect btn btn-sm btn-primary disabled"
                               style="color:white"><i class="fas fa-file-download"></i>&nbsp;تصدير اكسيل</a>
                        @endcan
                        <button type="button" class="btn btn-sm btn-danger" id="btn_delete_all">
                           حذف الكل
                        </button>


                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table key-buttons text-md-nowrap"  data-page-length='50' >
                            <thead >
                            <tr>
                                <th class="border-bottom-0"><input name="select_all" id="example-select-all" value="0" type="checkbox" onclick="CheckAll('box1', this)" /></th>
                                <th class="border-bottom-0">#</th>
                                <th class="border-bottom-0">رقم الفاتورة</th>
                                <th class="border-bottom-0">تاريخ القاتورة</th>
                                <th class="border-bottom-0">تاريخ الاستحقاق</th>
                                <th class="border-bottom-0">المنتج</th>
                                <th class="border-bottom-0">القسم</th>
                                <th class="border-bottom-0">الخصم</th>
                                <th class="border-bottom-0">نسبة الضريبة</th>
                                <th class="border-bottom-0">قيمة الضريبة</th>
                                <th class="border-bottom-0">الاجمالي</th>
                                <th class="border-bottom-0">الحالة</th>
                                <th class="border-bottom-0">ملاحظات</th>
                                <th class="border-bottom-0">العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($invoices as $index =>$invoice)
                            <tr>
                                <td><input type="checkbox"  value="{{ $invoice->id }}" class="box1" ></td>
                                <td>{{$index+1}}</td>
                                <td>{{$invoice -> invoices_num}}</td>
                                <td>{{$invoice->invoices_date}}</td>
                                <td>{{$invoice->due_date}}</td>
                                <td>{{$invoice->product}}</td>
                                <td> <a href="{{route('invoices.details',$invoice->id)}}">{{$invoice->category->category_name}}</a> </td>
                                <td>{{$invoice->discount}}</td>
                                <td>{{$invoice->rate_vat}}</td>
                                <td>{{$invoice->value_vat}}</td>
                                <td>{{$invoice->total}}</td>
                                <td> @if($invoice->status == 1 ) <span class="text-success">{{$invoice->getStatus()}}</span> @elseif($invoice->status==2) <span class="text-warning">{{$invoice->getStatus()}}</span>@else <span class="text-danger">{{$invoice->getStatus()}}</span> @endif</td>
                                <td> @if(!empty($invoice->note)){{$invoice->note}} @else <span style="color: red"> لايوجد ملاحظات </span>  @endif </td>
                                <td>
                                    <div class="dropdown">
                                        <button aria-expanded="false" aria-haspopup="true"
                                                class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                type="button">العمليات<i class="fas fa-caret-down ml-1"></i></button>
                                        <div class="dropdown-menu tx-13">
                                            @can('تعديل الفاتورة')
                                                <a class="dropdown-item"
                                                   href=" {{route('invoices.edit',$invoice->id)}}">تعديل
                                                    الفاتورة</a>
                                            @else
                                                <a class="dropdown-item disabled">تعديل الفاتورة</a>
                                            @endcan

                                                @can('حذف الفاتورة')
                                                    <a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}"
                                                       data-toggle="modal" data-target="#delete_invoice"><i
                                                            class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف
                                                        الفاتورة</a>
                                                @else
                                                    <a class="dropdown-item disabled"><i class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف
                                                        الفاتورة</a>
                                                @endcan

                                                @can('تغير حالة الدفع')
                                                    <a class="dropdown-item"
                                                       href="{{route('invoicesStatus.show',$invoice->id)}}"><i
                                                            class=" text-success fas fa-money-bill"></i>&nbsp;&nbsp;تغير
                                                        حالة
                                                        الدفع</a>
                                                @else
                                                    <a class="dropdown-item disabled" ><i class=" text-success fas fa-money-bill"></i>&nbsp;&nbsp;تغير حالة الدفع</a>
                                                @endcan

                                                @can('ارشفة الفاتورة')
                                                    <a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}"
                                                       data-toggle="modal" data-target="#Transfer_invoice"><i
                                                            class="text-warning fas fa-exchange-alt"></i>&nbsp;&nbsp;نقل الي
                                                        الارشيف</a>
                                                @else
                                                    <a class="dropdown-item disabled"><i class="text-warning fas fa-exchange-alt"></i>&nbsp;&nbsp;نقل الي
                                                        الارشيف</a>
                                                @endcan

                                                @can('طباعةالفاتورة')
                                                    <a class="dropdown-item" href="{{route('invoice.print',$invoice->id)}}"><i
                                                            class="text-success fas fa-print"></i>&nbsp;&nbsp;طباعة
                                                        الفاتورة
                                                    </a>
                                                @else
                                                    <a class="dropdown-item disabled"><i
                                                            class="text-success fas fa-print"></i>&nbsp;&nbsp;طباعة
                                                        الفاتورة
                                                    </a>
                                                @endcan


                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- delete -->
        <div class="modal" id="delete_invoice">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">حذف فاتورة</h6>
                    </div>

                    <div class="modal-body">
                        <form action="{{route('invoices.destroy','test')}}" method="post">
                            @method('delete')
                            @csrf

                            <div class="form-group">
                                <p>هل انت متاكد من عملية الحذف ؟</p><br>
                                <input type="hidden" name="invoice_id" id="invoice_id" value="">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">تاكيد</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                            </div>
                        </form>
                    </div>
                    <!-- modal body closed-->
                </div>
                <!-- modat-content closed-->
            </div>
            <!-- document closed-->
        </div>
        <!-- delete closed -->

        <!-- حذف مجموعة صفوف -->
        <div class="modal fade" id="delete_all" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="exampleModalLabel">
                            {{ trans('My_Classes_trans.delete_class') }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="{{route('delete.all')}}" method="POST">
                       @csrf
                        <div class="modal-body">
                            هل انت متاكد من عمليه الحذف ؟
                            <input class="text" type="hidden" id="page_id" name="page_id" value='2'>

                            <input class="text" type="hidden" id="delete_all_id" name="delete_all_id" value=''>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">اغلاق</button>
                            <button type="submit" class="btn btn-danger">حذف الكل</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- archive -->
        <div class="modal" id="Transfer_invoice">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">ارشفة فاتورة</h6>
                    </div>

                    <div class="modal-body">
                        <form action="{{route('invoices.destroy','test')}}" method="post">
                            @method('delete')
                            @csrf

                            <div class="form-group">
                                <p>هل انت متاكد من عملية الارشفة ؟</p><br>
                                <input type="hidden" name="invoice_id" id="invoice_id" value="">
                                <input type="hidden" name="page_id" id="page_id" value="2">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">تاكيد</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                            </div>
                        </form>
                    </div>
                    <!-- modal body closed-->
                </div>
                <!-- modat-content closed-->
            </div>
            <!-- document closed-->
        </div>
        <!-- archive closed -->
    </div>

			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>
    <script>
        $('#delete_invoice').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('invoice_id')
            var modal = $(this)
            modal.find('.modal-body #invoice_id').val(id);
        })
    </script>
    <script>
        $('#Transfer_invoice').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('invoice_id')
            var modal = $(this)
            modal.find('.modal-body #invoice_id').val(id);
        })
    </script>

    <script type="text/javascript">
        $(function() {
            $("#btn_delete_all").click(function() {
                var selected = new Array();
                $("#example1 input[type=checkbox]:checked").each(function() {
                    selected.push(this.value); // هات القيمه وحطهم في ارايي
                });
                if (selected.length > 0) {
                    $('#delete_all').modal('show')
                    $('input[id="delete_all_id"]').val(selected); // ضع الي في السيلكت دي ف الانبوت الي الاي دي بتاع ؟
                }
            });
        });
    </script>


@endsection
