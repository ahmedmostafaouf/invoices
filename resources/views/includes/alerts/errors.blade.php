
@if (session()->has('error'))
    <script>
        window.onload = function() {
            notif({
                msg: "{{session()->get('error')}}",
                type: "error"
            })
        }
    </script>
@endif

