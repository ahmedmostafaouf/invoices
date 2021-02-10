
@if (session()->has('error'))
    <script>
        window.onload = function() {
            notif({
                msg: "{{session()->get('error')}}",
                type: "danger"
            })
        }
    </script>
@endif

