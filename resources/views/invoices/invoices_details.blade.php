@extends('layouts.master')
@section('title')
 تفاصيل الفاتورة
@stop
@section('css')
    <!---Internal  Prism css-->
    <link href="{{ URL::asset('assets/plugins/prism/prism.css') }}" rel="stylesheet">
    <!---Internal Input tags css-->
    <link href="{{ URL::asset('assets/plugins/inputtags/inputtags.css') }}" rel="stylesheet">
    <!--- Custom-scroll -->
    <link href="{{ URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.css') }}" rel="stylesheet">
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">قائمة الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تفاصيل الفاتوره</span>
						</div>
					</div>

				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @include('includes.alerts.errors')
    @include('includes.alerts.success')
				<!-- row -->
				<div class="row">
                    <div class="col-xl-12">
                        <!-- div -->
                        <div class="card mg-b-20" id="tabs-style3">
                            <div class="card-body">
                                <div class="main-content-label mg-b-5">
                                    تفاصيل الفاتورة
                                </div>
                                <div class="text-wrap">
                                    <div class="example">
                                        <div class="panel panel-primary tabs-style-3">
                                            <div class="tab-menu-heading">
                                                <div class="tabs-menu ">
                                                    <!-- Tabs -->
                                                    <ul class="nav panel-tabs">
                                                        <li class=""><a href="#tab11" class="active" data-toggle="tab"><i class="fa fa-laptop"></i> معلومات الفاتورة</a></li>
                                                        <li><a href="#tab12" data-toggle="tab"><i class="fa fa-cube"></i> حالات الدفع</a></li>
                                                        <li><a href="#tab13" data-toggle="tab"><i class="fa fa-cogs"></i> المرفقات</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="panel-body tabs-menu-body">
                                                <div class="tab-content">
                                                    <div class="tab-pane active" id="tab11">
                                                        <div class="table-responsive mt-15">

                                                            <table class="table table-striped" style="text-align:center">
                                                                <tbody>
                                                                <tr>
                                                                    <th scope="row">رقم الفاتورة</th>
                                                                    <td>{{ $invoices->invoices_num }}</td>
                                                                    <th scope="row">تاريخ الاصدار</th>
                                                                    <td>{{ $invoices->invoices_date }}</td>
                                                                    <th scope="row">تاريخ الاستحقاق</th>
                                                                    <td>{{ $invoices->due_date }}</td>
                                                                    <th scope="row">القسم</th>
                                                                    <td>{{ $invoices->category->category_name }}</td>
                                                                </tr>

                                                                <tr>
                                                                    <th scope="row">المنتج</th>
                                                                    <td>{{ $invoices->product }}</td>
                                                                    <th scope="row">مبلغ التحصيل</th>
                                                                    <td>{{ $invoices->amount_collection }}</td>
                                                                    <th scope="row">مبلغ العمولة</th>
                                                                    <td>{{ $invoices->amount_commission }}</td>
                                                                    <th scope="row">الخصم</th>
                                                                    <td>{{ $invoices->discount }}</td>
                                                                </tr>


                                                                <tr>
                                                                    <th scope="row">نسبة الضريبة</th>
                                                                    <td>{{ $invoices->rate_vat }}</td>
                                                                    <th scope="row">قيمة الضريبة</th>
                                                                    <td>{{ $invoices->value_vat }}</td>
                                                                    <th scope="row">الاجمالي مع الضريبة</th>
                                                                    <td>{{ $invoices->total }}</td>
                                                                    <th scope="row">الحالة الحالية</th>
                                                                    @if ($invoices->status == 1)
                                                                        <td><span
                                                                                class="badge badge-pill badge-success">{{ $invoices->getStatus() }}</span>
                                                                        </td>
                                                                    @elseif($invoices->status ==2)
                                                                        <td><span
                                                                                class="badge badge-pill badge-warning">{{ $invoices->getStatus() }}</span>
                                                                        </td>
                                                                    @else
                                                                        <td><span
                                                                                class="badge badge-pill badge-danger">{{ $invoices->getStatus() }}</span>
                                                                        </td>
                                                                    @endif
                                                                </tr>

                                                                <tr>
                                                                    <th scope="row">المستخدم</th>
                                                                    <td>{{ $invoices->user }}</td>
                                                                    <th scope="row">ملاحظات</th>
                                                                    <td>{{ $invoices->note }}</td>
                                                                </tr>
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="tab12">
                                                        <div class="table-responsive mt-15">
                                                            <table class="table center-aligned-table mb-0 table-hover"
                                                                   style="text-align:center">
                                                                <thead>
                                                                <tr class="text-dark">
                                                                    <th>#</th>
                                                                    <th>رقم الفاتورة</th>
                                                                    <th>نوع المنتج</th>
                                                                    <th>القسم</th>
                                                                    <th>حالة الدفع</th>
                                                                    <th>تاريخ الدفع </th>
                                                                    <th>ملاحظات</th>
                                                                    <th>تاريخ الاضافة </th>
                                                                    <th>المستخدم</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                @foreach ($invoices_details as $index => $details)

                                                                    <tr>
                                                                        <td>{{ $index+1 }}</td>
                                                                        <td>{{ $details->invoice_number }}</td>
                                                                        <td>{{ $details->product }}</td>
                                                                        <td>{{ $invoices->category->category_name }}</td>
                                                                        @if ($details->status == 1)
                                                                            <td><span
                                                                                    class="badge badge-pill badge-success">{{ $details->getStatus() }}</span>
                                                                            </td>
                                                                        @elseif($details->status ==2)
                                                                            <td><span
                                                                                    class="badge badge-pill badge-warning">{{ $details->getStatus() }}</span>
                                                                            </td>
                                                                        @else
                                                                            <td><span
                                                                                    class="badge badge-pill badge-danger">{{ $details->getStatus() }}</span>
                                                                            </td>
                                                                        @endif
                                                                        <td>{{ $details->Payment_Date }}</td>
                                                                        <td>{{ $details->note }}</td>
                                                                        <td>{{ $details->created_at }}</td>
                                                                        <td>{{ $details->user }}</td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>


                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="tab13">
                                                        <!--المرفقات-->
                                                        <div class="card card-statistics">
                                                            @can('اضافة مرفق')
                                                            <div class="card-body">
                                                                    <p class="text-danger">* صيغة المرفق pdf, jpeg ,.jpg , png </p>
                                                                    <h5 class="card-title">اضافة مرفقات</h5>
                                                                    <form method="post" action="{{ url('/invoice_attachments') }}"
                                                                          enctype="multipart/form-data">
                                                                        @csrf
                                                                        <div class="custom-file">
                                                                            <input type="file" class="custom-file-input" id="customFile"
                                                                                   name="file_name" required>
                                                                            <input type="hidden" id="customFile" name="invoice_number"
                                                                                   value="{{ $invoices->invoices_num }}">
                                                                            <input type="hidden" id="invoice_id" name="invoice_id"
                                                                                   value="{{ $invoices->id }}">
                                                                            <label class="custom-file-label" for="customFile">حدد
                                                                                المرفق</label>
                                                                        </div><br><br>
                                                                        <button type="submit" class="btn btn-primary btn-sm "
                                                                                name="uploadedFile">تاكيد</button>
                                                                    </form>
                                                                </div>
                                                            @endcan
                                                        <div class="table-responsive mt-15">
                                                            <table class="table center-aligned-table mb-0 table table-hover"
                                                                   style="text-align:center">
                                                                <thead>
                                                                <tr class="text-dark">
                                                                    <th scope="col">#</th>
                                                                    <th scope="col">اسم الملف</th>
                                                                    <th scope="col">قام بالاضافة</th>
                                                                    <th scope="col">تاريخ الاضافة</th>
                                                                    <th scope="col">العمليات</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach ($attachments as $index => $attachment)
                                                                    <tr>
                                                                        <td>{{ $index+1 }}</td>
                                                                        <td>{{ $attachment->file_name }}</td>
                                                                        <td>{{ $attachment->created_by }}</td>
                                                                        <td>{{ $attachment->created_at }}</td>
                                                                        <td colspan="2">

                                                                            <a class="btn btn-outline-success btn-sm"
                                                                               href="{{ url('View_file') }}/{{ $attachment->invoice_number }}/{{ $attachment->file_name }}"
                                                                               role="button"><i class="fas fa-eye"></i>&nbsp;
                                                                                عرض</a>

                                                                            <a class="btn btn-outline-info btn-sm"
                                                                               href="{{ url('download_file') }}/{{ $attachment->invoice_number }}/{{ $attachment->file_name }}"
                                                                               role="button"><i
                                                                                    class="fas fa-download"></i>&nbsp;
                                                                                تحميل</a>

                                                                            @can('حذف المرفق')
                                                                                <button class="btn btn-outline-danger btn-sm"
                                                                                        data-toggle="modal"
                                                                                        data-file_name="{{ $attachment->file_name }}"
                                                                                        data-invoice_number="{{ $attachment->invoice_number }}"
                                                                                        data-file_id="{{ $attachment->id }}"
                                                                                        data-target="#delete_file">حذف</button>
                                                                            @else
                                                                                <a class="btn btn-outline-danger btn-sm">حذف</a>

                                                                            @endcan
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- delete -->
                    <div class="modal" id="delete_file">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-header">
                                    <h6 class="modal-title">اضافة قسم</h6>
                                </div>

                                <div class="modal-body">
                                    <form action="{{route('delete_file')}}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <p>هل انت متاكد من عملية الحذف ؟</p><br>
                                            <input type="hidden" name="file_id" id="file_id" value="">
                                            <input type="hidden" class="form-control"  name="file_name" id="file_name" value="">
                                            <input type="hidden" name="invoice_number" id="invoice_number" value="">
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
				</div>
				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!-- Internal Jquery.mCustomScrollbar js-->
    <script src="{{ URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <!-- Internal Input tags js-->
    <script src="{{ URL::asset('assets/plugins/inputtags/inputtags.js') }}"></script>
    <!--- Tabs JS-->
    <script src="{{ URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js') }}"></script>
    <script src="{{ URL::asset('assets/js/tabs.js') }}"></script>
    <!--Internal  Clipboard js-->
    <script src="{{ URL::asset('assets/plugins/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/clipboard/clipboard.js') }}"></script>
    <!-- Internal Prism js-->
    <script src="{{ URL::asset('assets/plugins/prism/prism.js') }}"></script>
    <script>
        $('#delete_file').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var file_id = button.data('file_id')
            var invoice_number = button.data('invoice_number')
            var file_name = button.data('file_name')
            var modal = $(this)
            modal.find('.modal-body #file_id').val(file_id);
            modal.find('.modal-body #file_name').val(file_name);
            modal.find('.modal-body #invoice_number').val(invoice_number);
        })
    </script>
@endsection
