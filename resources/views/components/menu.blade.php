<div class="menu-btn ms-2">
  <div class="bar1"></div>
  <div class="bar2"></div>
  <div class="bar3"></div>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            $('.menu-btn').on('click', function() {
                animateMenu($(this));
            });

            function animateMenu(menuBtn) {
                menuBtn.toggleClass('change');
                $('.sidebar').toggleClass('sidebar-close');
            }
        });
    </script>
@endpush