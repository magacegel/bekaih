<script src="{{ URL::asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ URL::asset('build/js/plugins.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>






  <!-- General JS Scripts -->
  <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/modules/popper.js') }}"></script>
  <script src="{{ asset('assets/modules/tooltip.js') }}"></script>
  <script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
  <script src="{{ asset('assets/modules/moment.min.js') }}"></script>
  <script src="{{ asset('assets/js/stisla.js') }}"></script>
  <script src="{{ asset('assets/js/fslightbox.js') }}"></script>

  <!-- JS Libraies -->
  <script src="{{ asset('assets/modules/prism/prism.js') }}"></script>


  <!-- JS Datatables -->
  <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>


  <!-- JS Libraies -->
  <script src="{{ asset('assets/modules/jquery.sparkline.min.js') }}"></script>
  <script src="{{ asset('assets/modules/chart.min.js') }}"></script>
  <script src="{{ asset('assets/modules/owlcarousel2/dist/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('assets/modules/summernote/summernote-bs4.js') }}"></script>
  <!-- <script src="{{ asset('assets/modules/chocolat/dist/js/jquery.chocolat.min.js') }}"></script> -->
  <script src="https://raw.githubusercontent.com/nicolas-t/Chocolat/master/dist/js/chocolat.js"></script>

  <!-- Page Specific JS File -->
  <!-- <script src="{{ asset('assets/js/page/index.js') }}"></script> -->

  <!-- Page Specific JS File -->
  <!-- <script src="{{ asset('assets/js/page/bootstrap-modal.js') }}"></script> -->

  <!-- JS Libraies -->
  <script src="{{ asset('assets/modules/izitoast/js/iziToast.min.js') }}"></script>
  
  <script
    src="https://sentry.bki.co.id/js-sdk-loader/712c5cb803364ff27febcc538ce083c4.min.js"
    crossorigin="anonymous"
  ></script>



  <!-- Template JS File -->
  <script src="{{ asset('assets/js/scripts.js') }}"></script>
  <script src="{{ asset('assets/js/custom.js') }}"></script>

  <script>
    fsLightbox.props.loadOnlyCurrentSource = true;
  </script>

  <script>
    $(document).ready(function() {
      // Membaca pesan success dari session
      @if(session('success'))
        iziToast.success({
          title: 'Sukses',
          message: '{{ session('success') }}',
          position: 'topRight'
        });
      @endif

      // Membaca pesan error dari session
      @if(session('error'))
        iziToast.error({
          title: 'Error',
          message: '{{ session('error') }}',
          position: 'topRight'
        });
      @endif
    });
  </script>

@yield('js')
@yield('script-bottom')
