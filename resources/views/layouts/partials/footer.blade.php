        <x-notify::notify />
        @notifyJs
        <script type="text/javascript" src="{{ asset('redtax-admin/js/main.js') }}"></script>
        <script type="text/javascript">        
            function formatPhoneNumber(event){
                let inputElement = event.target;
                let input = inputElement.value.replace(/\D/g, ''); // Remove all non-digit characters

                if (input.length <= 3) {
                    input = `(${input}`;
                } else if (input.length <= 6) {
                    input = `(${input.slice(0, 3)}) ${input.slice(3)}`;
                } else if (input.length <= 10) {
                    input = `(${input.slice(0, 3)}) ${input.slice(3, 6)}-${input.slice(6)}`;
                } else {
                    input = `(${input.slice(0, 3)}) ${input.slice(3, 6)}-${input.slice(6, 10)}`;
                }

                inputElement.value = input;
            }
        </script>
    </body>
</html>