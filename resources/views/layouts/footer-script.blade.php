<!-- JAVASCRIPT -->
<script src="{{ URL::asset('/assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/metismenu/metismenu.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/node-waves/node-waves.min.js') }}"></script>

@yield('script')

<!-- App js -->
<script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $(document).on("click", ".copyButton", function(){
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(this).html()).select();
            document.execCommand("copy");
            $temp.remove();
            toastr['success']($(this).html(), 'Success!');
        })
        $(document).on("click", ".copyButtonName", function(){
            var $temp = $("<input>");
            var dataName = $(this).attr("data-name");
            $("body").append($temp);
            $temp.val(dataName).select();
            document.execCommand("copy");
            $temp.remove();
            toastr['success'](dataName, 'Success!');
        });
    });
</script>

@yield('script-bottom')
