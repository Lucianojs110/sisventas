                                                                                                                                                                                                                                                         @extends('layouts.app')
@section('css')
    {{--<link rel="stylesheet" href="{{URL::to('/')}}/admin/css/jquery.dataTables.min.css">--}}
    <style>
        table.dataTable tfoot th, table.dataTable tfoot td {
            padding: 0px 0px 0px 0px;
            border-top: 1px solid #111;
        }

        .table-wrapper {
            max-height: 150px;
            overflow: auto;
        }
    </style>

@endsection
@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <h3>Listado de Artículos
                            <button data-toggle="modal" data-target="#modal-agregar-articulo" class="btn btn-xs btn-success"><i
                                        data-toggle="tooltip" title="Agregar Artículo" class="fa fa-plus-circle"></i>
                            </button>
                            <button class="btn btn-primary btn-xs" form="pdfcodigo" type="submit"><i
                                        data-toggle="tooltip" title="Imprimir Codígos" class="fa fa-fw fa-print"></i>
                            </button>
                        </h3>
                    </div>
                </div>
                {!! Form::open(['route' => 'pdf.codigo', 'id'=>'pdfcodigo', 'method'=>'POST', 'files' => 'true']) !!}
                {{Form::token()}}
                {!!Form::close()!!}

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="art" class="table table-striped table-bordered table-condensed table-hover">
                                <thead>
                                <th>Nombre</th>
                                <th>Código</th>
                                <th>Categoría</th>
                                <th>Stock</th>
                                <th>Imagen</th>
                                <th>Opciones</th>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="borrar_"></th>
                                    <th class="borrar_"></th>
                                    <th id="borrar_" class="borrar_"></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('almacen.articulo.modal-agregar')
    @foreach($articulos as $art)
        @include('almacen.articulo.modal-editar')
        @include('almacen.articulo.modal-borrar')
        @include('almacen.articulo.modal-show')
        {{--@include('almacen.articulo.modal-cambiarprecio')--}}
    @endforeach
@endsection
@section('js')
    {{--<script src="{{URL::to('/')}}/admin/js/jquery.dataTables.min.js"></script>--}}
    <script>

        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });


        function control(f) {
            var ext = ['gif', 'jpg', 'jpeg', 'png'];
            var v = f.value.split('.').pop().toLowerCase();
            for (var i = 0, n; n = ext[i]; i++) {
                if (n.toLowerCase() == v)
                    return
            }
            var t = f.cloneNode(true);
            t.value = '';
            f.parentNode.replaceChild(t, f);
            alert('Debe ser de tipo imagen');
        }


        $('#art').DataTable({
            processing: true,
            serverSide: true,
            iDisplayLength: 10,
            order: [[0, "desc"]],
            ajax: "{{route('articulo.tabla')}}",
            columns: [
                {data: 'nombre', name: 'nombre'},
                {data: 'codigo', name: 'codigo'},
                {data: 'idcategoria', name: 'idcategoria'},
                {data: 'stock', name: 'stock'},
                {data: 'imagen', name: 'imagen'},
                {data: 'opcion', name: 'opcion', orderable: false, searchable: false}
            ],
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    $('.borrar_').empty();
                    $('.borrar').empty();
                    var columns = column.columns([0, 1]);
                    var input = document.createElement("input");
                    input.setAttribute("class", "form-control input-sm");
                    // input.setAttribute("style", "border-color: #2c1ea5;height: 29px;padding: 5px 0px;");
                    var select = $('<select class="form-control input-sm"><option value="">Por Defecto</option></select>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });
                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                    $(input).appendTo($(columns.footer()).empty())
                        .on('change', function () {
                            columns.search($(this).val()).draw();
                        });
                    $('#borrar_').empty();
                    $('.borrar_').empty();
                });
            },
            "language": {
                "url": "{{URL::to('/')}}/admin/Spanish.json"
            }
        });
    </script>
@endsection
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
