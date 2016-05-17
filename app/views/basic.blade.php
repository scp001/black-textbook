<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>BlackBook Exchange</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="favicon.png">

    <!-- Bootstrap core CSS -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="{{asset('assets/js/html5shiv.js')}}"></script>
    <script src="{{asset('assets/js/respond.min.js')}}"></script>
    <![endif]-->
    <script src="{{asset('assets/js/jquery.js')}}"></script>
    <!-- Custom styles for this template -->
    <link href="{{asset('assets/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
</head>

<body class="home-1">



<!-- Navbar -->

@include('header')

@include('alerts')
@yield('content')

<!-- Footer Widgets -->
@include('footer')

<!-- Javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/js/jquery.autocomplete.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/jquery.backstretch.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/jquery.fitvids.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/application.js')}}"></script>
@if(isset($tailScripts) && count($tailScripts) > 0)
@foreach($tailScripts as $script)
<script type="text/javascript" src="{{asset('assets/js/'.$script)}}"></script>
@endforeach
@endif
<script>
            $(document).ready(function(){
                $('[data-url]').on('click',function(){
                    window.location.href= $(this).data('url');
                })
            });
        </script>
        <script>
        function receivedText() {
            var imageObj = new Image();
            imageObj.src=fr.result;
            $('#imgoldbox').find('img').eq(0).remove();
            $('#imgoldbox').append('<img src="'+fr.result+'" class="profilethunb">');
        }
    function handleFileSelect()
                {
                    if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
                        alert('The File APIs are not fully supported in this browser.');
                        return;
                    }

                    input = document.getElementById('fileinput');
                    if (!input) {
                        alert("Um, couldn't find the fileinput element.");
                    }
                    else if (!input.files) {
                        alert("This browser doesn't seem to support the `files` property of file inputs.");
                    }
                    else if (!input.files[0]) {
                        alert("Please select a file before clicking 'Load'");
                    }
                    else {
                        file = input.files[0];
                        fr = new FileReader();
                        fr.onload = receivedText;
                        //fr.readAsText(file);
                        fr.readAsDataURL(file);
                    }
                };
        $(document).ready(function(){
            $('input[type="file"]').on('change',function(){
                        handleFileSelect()
            });
            $('body').on('click','#removeoldpic',function(){
            that = $(this);
            $('input[type="file"]').val('');
            that.parent().find('img').remove();
            that.parent().append('<input type="hidden" name="removeoldimg" value="">');

            });
            $('body').on('click','.delhoppy',function(){
            var that = $(this);
                $.ajax({

                                url: "{{URL::to('hoppy/del?q=')}}"+encodeURI(that.data('hid'))
                                })
                        .done(function( data ) {
                        if (data == true){
                            that.parent().remove();
                        }else {
                        alert('Something went worng please try again laeter');
                        }
                        });
            });
        $('#addbtn').on('click',function(e){
            e.preventDefault();
            var that = $(this),
                hoppycont = $('#hoppycont'),
                hoppy = $('#hoppytxt');
            $.ajax({
                url: "{{URL::to('hoppy/add?q=')}}"+encodeURI(hoppy.val()),
                    beforeSend: function( xhr ) {
                    that.html('<img src="{{URL::asset('loading.gif')}}">').attr('disabled','disabled');
                }
                })
        .done(function( data ) {
        that.html('Add <i class="fa fa-fw fa-plus"></i>').removeAttr('disabled');
        hoppy.val('');
        if(data.id && data.title){
        hoppycont.append('<span class="hoppyitem">'+data.title+'<span class="delhoppy" data-hid="'+data.id+'"><i class="fa fa-fw fa-trash-o"></i></span></span>');
        }
        });

        });
        $('#addhoppies').on('click',function(){
        $('#hoppiesform').stop().slideToggle();
        })
        });
        </script>
        <script>
            jQuery(document).ready(function($){
            $(".hero-image").backstretch("{{URL::asset('assets/img/hero.jpg')}}");
                        // options being used
                        if($('#typecity .typeahead').size() > 0 )
                        {
                        var cities = new Bloodhound({
                            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                            queryTokenizer: Bloodhound.tokenizers.whitespace,
                            limit: 101,
                            prefetch: {
                                url: '<?= URL::to('citylist.json')?>',
                                filter: function(list) {
                                   return $.map(list, function(country) { return { name: country }; });
                                }
                            }
                        });
                        cities.initialize();
                            $('#typecity .typeahead').typeahead(null, {
                                name: 'city',
                                displayKey: 'name',
                                source: cities.ttAdapter()
                            });
                        }
                           if($('#typecity .typeahead').size() > 0 )
                            {
                            var uni = new Bloodhound({
                                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                                queryTokenizer: Bloodhound.tokenizers.whitespace,
                                limit: 101,
                                prefetch: {
                                    url: '<?= URL::to('unilist.json')?>',
                                    filter: function(list) {
                                       return $.map(list, function(uni) { return { name: uni }; });
                                    }
                                }
                            });

                            uni.initialize();
                                $('#typeschool .typeahead').typeahead(null, {
                                    name: 'uni',
                                    displayKey: 'name',
                                    source: uni.ttAdapter()
                                });
                            }
            });
        </script>

</body>
</html>
