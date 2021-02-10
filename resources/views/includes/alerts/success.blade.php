@if (session()->has('success'))
    <script>
        window.onload = function() {
            notif({
                msg: "{{session()->get('success')}}",
                type: "success"
            })
        }
    </script>
@endif

