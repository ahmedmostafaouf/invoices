@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('title')
    المنتجات
@stop
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الاعدادات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ المنتجات</span>
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
                        <div class="card mg-b-20">
                            <div class="card-header pb-0">
                                <div class="d-flex justify-content-between">
                                    @can('اضافة منتج')
                                        <a class="modal-effect btn btn-outline-primary btn-block" data-effect="effect-scale"
                                           data-toggle="modal" href="#modaldemo2">اضافة منتج</a>
                                    @else
                                        <a class="modal-effect btn btn-outline-primary btn-block disabled">اضافة منتج</a>
                                    @endcan

                                </div>
                                <!-- End add btn -->
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example1" class="table key-buttons text-md-nowrap" data-page-length='50' style="text-align: center">
                                        <thead>
                                        <tr>
                                            <th class="border-bottom-0">#</th>
                                            <th class="border-bottom-0">اسم المنتج</th>
                                            <th class="border-bottom-0">اسم القسم</th>
                                            <th class="border-bottom-0">تم بواسطة</th>
                                            <th class="border-bottom-0">الملاحظات</th>
                                            <th class="border-bottom-0">العمليات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                      @foreach($products as $index => $product)
                                        <tr>
                                            <td>{{$index+1}}</td>
                                            <td>{{$product->product_name}}</td>
                                            <td>{{$product->category->category_name}}</td>
                                            <td>{{$product->created_by}}</td>
                                            <td>{{$product->description}}</td>
                                            <td>
                                                @can('تعديل منتج')
                                                    <a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"
                                                       data-id="{{$product->id }}" data-product_name="{{$product->product_name }}"
                                                       data-category_name="{{$product->category->category_name}}"
                                                       data-description="{{$product->description }}" data-toggle="modal"
                                                       href="#exampleModal2" title="تعديل"><i class="las la-pen"></i></a>
                                                @else
                                                    <a class="modal-effect btn btn-sm btn-info disabled" title="تعديل"><i class="las la-pen"></i></a>
                                                @endcan

                                                    @can('حذف منتج')
                                                        <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                                           data-id="{{ $product->id }}" data-product_name="{{ $product->product_name }}"
                                                           data-toggle="modal" href="#delete_product" title="حذف"><i
                                                                class="las la-trash"></i></a>
                                                    @else
                                                        <a class="modal-effect btn btn-sm btn-danger disabled" title="حذف"><i
                                                                class="las la-trash"></i></a>
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
                    <!-- start add product-->
                    <div class="modal" id="modaldemo2">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-header">
                                    <h6 class="modal-title">اضافة منتج</h6>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('product.store') }}" method="post">
                                        @csrf

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">اسم المنتج</label>
                                            <input type="text" class="form-control" id="product_name" name="product_name">
                                            @error("product_name")
                                            <span class="text-danger">{{$message}} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="inlineFormCustomSelectPref">القسم</label>
                                            <select name="category_id" id="category_id" class="form-control" required>
                                                <option value="" selected disabled> --حدد القسم--</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{$category->category_name}}</option>
                                                @endforeach
                                            </select>
                                            @error("category_id")
                                            <span class="text-danger">{{$message}} </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlTextarea1">ملاحظات</label>
                                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                            @error("description")
                                            <span class="text-danger">{{$message}} </span>
                                            @enderror
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">تاكيد</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End add category -->

                    <!-- edit -->
                    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">تعديل المنتج</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <form action="{{ url('product/update') }}" method="post">
                                        @method("patch")
                                        @csrf

                                        <div class="form-group">
                                            <input type="hidden" name="id" id="id" value="">
                                            <label for="exampleInputEmail1">اسم المنتج</label>
                                            <input type="text" class="form-control" id="product_name" name="product_name">
                                            @error("product_name")
                                            <span class="text-danger">{{$message}} </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="inlineFormCustomSelectPref">القسم</label>
                                            <select name="category_name" id="category_name" class="custom-select my-1 mr-sm-2" required>
                                                @foreach ($categories as $category)
                                                    <option>{{ $category->category_name }}</option>
                                                @endforeach
                                            </select>
                                            @error("category_name")
                                            <span class="text-danger">{{$message}} </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlTextarea1">ملاحظات</label>
                                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                            @error("description")
                                            <span class="text-danger">{{$message}} </span>
                                            @enderror
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">تاكيد</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- edit closed-->

                    <!-- delete -->
                    <div class="modal" id="delete_product">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-header">
                                    <h6 class="modal-title">اضافة قسم</h6>
                                </div>

                                <div class="modal-body">
                                    <form action="{{url('category/destroy')}}" method="post">
                                        @method('delete')
                                        @csrf

                                        <div class="form-group">
                                            <p>هل انت متاكد من عملية الحذف ؟</p><br>
                                            <input type="hidden" name="id" id="id" value="">
                                            <input class="form-control" disabled name="product_name" id="product_name" type="text" readonly>
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
        $('#exampleModal2').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var product_name = button.data('product_name')
            var category_name = button.data('category_name')
            var description = button.data('description')
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #product_name').val(product_name);
            modal.find('.modal-body #category_name').val(category_name);
            modal.find('.modal-body #description').val(description);
        })
    </script>
    <script>
        $('#delete_product').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var product_name = button.data('product_name')
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #product_name').val(product_name);
        })
    </script>
@endsection
